<?php

require_once __DIR__ . '/../config/app.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function asset_url(string $path): string
{
    $path = '/' . ltrim($path, '/');
    $base = rtrim(BASE_URL, '/');
    return ($base !== '' ? $base : '') . $path;
}

function versioned_asset(string $path): string
{
    return asset_url($path) . '?v=' . APP_ASSET_VERSION;
}

function redirect(string $path): void
{
    header('Location: ' . BASE_URL . $path);
    exit;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): bool
{
    $token = $_POST['csrf_token'] ?? '';
    return hash_equals($_SESSION['csrf_token'] ?? '', $token);
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array
{
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function format_money(float $amount): string
{
    return number_format($amount, 2, '.', ',');
}

function not_deleted(?string $alias = null): string
{
    $prefix = $alias ? $alias . '.' : '';
    return $prefix . 'deleted_at IS NULL';
}

function soft_delete_rows(PDO $db, string $table, string $where, array $params): int
{
    $allowed = [
        'users', 'companies', 'branches', 'somfp_entries', 'somci_entries', 'company_observations',
        'linked_is_heads', 'linked_is_line_items', 'linked_is_formula_terms', 'linked_is_entries',
        'linked_bs_heads', 'linked_bs_line_items', 'linked_bs_formula_terms', 'linked_bs_entries',
    ];
    if (!in_array($table, $allowed, true)) {
        throw new InvalidArgumentException('Invalid table for soft delete.');
    }

    $sql = "UPDATE {$table} SET deleted_at = CURRENT_TIMESTAMP, updated_at = CURRENT_TIMESTAMP
            WHERE {$where} AND deleted_at IS NULL";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->rowCount();
}

function soft_delete_branches(PDO $db, array $branchIds, int $companyId): void
{
    if (empty($branchIds)) {
        return;
    }

    $placeholders = implode(',', array_fill(0, count($branchIds), '?'));
    soft_delete_rows(
        $db,
        'somfp_entries',
        "branch_id IN ($placeholders)",
        $branchIds
    );
    soft_delete_rows(
        $db,
        'somci_entries',
        "branch_id IN ($placeholders)",
        $branchIds
    );
    soft_delete_rows(
        $db,
        'linked_is_entries',
        "branch_id IN ($placeholders)",
        $branchIds
    );
    soft_delete_rows(
        $db,
        'linked_bs_entries',
        "branch_id IN ($placeholders)",
        $branchIds
    );
    soft_delete_rows(
        $db,
        'branches',
        "id IN ($placeholders) AND company_id = ?",
        [...$branchIds, $companyId]
    );
}

function soft_delete_company(PDO $db, int $companyId): void
{
    $stmt = $db->prepare('SELECT id FROM branches WHERE company_id = ? AND deleted_at IS NULL');
    $stmt->execute([$companyId]);
    $branchIds = array_column($stmt->fetchAll(), 'id');

    if (!empty($branchIds)) {
        soft_delete_branches($db, $branchIds, $companyId);
    }

    soft_delete_rows($db, 'company_observations', 'company_id = ?', [$companyId]);
    soft_delete_rows($db, 'companies', 'id = ?', [$companyId]);
}

function is_logged_in(): bool
{
    return !empty($_SESSION['user_id']);
}

function current_user_id(): ?int
{
    return $_SESSION['user_id'] ?? null;
}

function require_login(): void
{
    if (!is_logged_in()) {
        flash('error', 'Please log in to continue.');
        redirect('/login.php');
    }
}

function get_auth_user(PDO $db): ?array
{
    if (!is_logged_in()) {
        return null;
    }
    $stmt = $db->prepare('SELECT id, full_name, email FROM users WHERE id = ? AND ' . not_deleted());
    $stmt->execute([current_user_id()]);
    return $stmt->fetch() ?: null;
}

function get_all_line_item_keys(): array
{
    $config = require __DIR__ . '/../config/somfp.php';
    $keys = [];
    foreach ($config['sections'] as $section) {
        foreach ($section['groups'] as $group) {
            foreach ($group['items'] as $key => $label) {
                $keys[] = $key;
            }
        }
    }
    return $keys;
}

function get_line_item_label(string $key): string
{
    $config = require __DIR__ . '/../config/somfp.php';
    foreach ($config['sections'] as $section) {
        foreach ($section['groups'] as $group) {
            if (isset($group['items'][$key])) {
                return $group['items'][$key];
            }
        }
    }
    return $key;
}

function calculate_somfp_totals(array $values): array
{
    $config = require __DIR__ . '/../config/somfp.php';
    $totals = [
        'groups' => [],
        'total_assets' => 0,
        'total_equity_liabilities' => 0,
        'error' => 0,
    ];

    foreach ($config['sections'] as $sectionKey => $section) {
        $sectionTotal = 0;
        foreach ($section['groups'] as $groupKey => $group) {
            $groupTotal = 0;
            foreach ($group['items'] as $itemKey => $label) {
                $groupTotal += (float) ($values[$itemKey] ?? 0);
            }
            $totals['groups'][$groupKey] = $groupTotal;
            $sectionTotal += $groupTotal;
        }
        if ($sectionKey === 'assets') {
            $totals['total_assets'] = $sectionTotal;
        } else {
            $totals['total_equity_liabilities'] = $sectionTotal;
        }
    }

    $totals['error'] = $totals['total_assets'] - $totals['total_equity_liabilities'];
    return $totals;
}

function get_branch_values(PDO $db, int $branchId, int $year, int $month): array
{
    $stmt = $db->prepare(
        'SELECT line_item_key, amount FROM somfp_entries
         WHERE branch_id = ? AND period_year = ? AND period_month = ? AND ' . not_deleted()
    );
    $stmt->execute([$branchId, $year, $month]);
    $values = [];
    foreach ($stmt->fetchAll() as $row) {
        $values[$row['line_item_key']] = (float) $row['amount'];
    }
    return $values;
}

function get_company_branches(PDO $db, int $companyId): array
{
    $stmt = $db->prepare('SELECT * FROM branches WHERE company_id = ? AND ' . not_deleted() . ' ORDER BY is_head_office DESC, name ASC');
    $stmt->execute([$companyId]);
    return $stmt->fetchAll();
}

function user_owns_company(PDO $db, int $companyId, int $userId): bool
{
    $stmt = $db->prepare('SELECT id FROM companies WHERE id = ? AND user_id = ? AND ' . not_deleted());
    $stmt->execute([$companyId, $userId]);
    return (bool) $stmt->fetch();
}

function period_key(int $year, int $month): int
{
    return $year * 100 + $month;
}

function normalize_report_period_filters(int $yearFrom, int $yearTo, int $monthFrom, int $monthTo): array
{
    if ($yearFrom > 0 && $yearTo > 0 && $yearFrom > $yearTo) {
        [$yearFrom, $yearTo] = [$yearTo, $yearFrom];
        if ($monthFrom > 0 && $monthTo > 0) {
            [$monthFrom, $monthTo] = [$monthTo, $monthFrom];
        }
    }

    return [
        'year_from' => $yearFrom,
        'year_to' => $yearTo,
        'month_from' => $monthFrom,
        'month_to' => $monthTo,
    ];
}

function apply_period_range_sql(string $alias, int $yearFrom, int $yearTo, int $monthFrom, int $monthTo): array
{
    $sql = '';
    $params = [];
    $periodExpr = "({$alias}.period_year * 100 + {$alias}.period_month)";

    if ($yearFrom > 0) {
        $startMonth = ($monthFrom >= 1 && $monthFrom <= 12) ? $monthFrom : 1;
        $sql .= " AND {$periodExpr} >= ?";
        $params[] = period_key($yearFrom, $startMonth);
    }

    if ($yearTo > 0) {
        $endMonth = ($monthTo >= 1 && $monthTo <= 12) ? $monthTo : 12;
        $sql .= " AND {$periodExpr} <= ?";
        $params[] = period_key($yearTo, $endMonth);
    }

    return [$sql, $params];
}

function build_report_year_options(array $availableYears, int $yearFrom, int $yearTo): array
{
    $years = $availableYears;
    foreach ([$yearFrom, $yearTo, (int) date('Y')] as $year) {
        if ($year > 0 && !in_array($year, $years, true)) {
            $years[] = $year;
        }
    }

    if (empty($years)) {
        $years = range((int) date('Y'), (int) date('Y') - 5);
    }

    rsort($years);
    return $years;
}

function report_filter_query(array $params): string
{
    $query = [];
    foreach ($params as $key => $value) {
        if ($key === 'branch_id' && empty($value)) {
            continue;
        }
        if ($value === null || $value === '') {
            continue;
        }
        $query[$key] = $value;
    }

    return '?' . http_build_query($query);
}

function get_somfp_periods(PDO $db, int $companyId, int $userId, array $filters = []): array
{
    $yearFrom = !empty($filters['year_from']) ? (int) $filters['year_from'] : 0;
    $yearTo = !empty($filters['year_to']) ? (int) $filters['year_to'] : 0;
    $monthFrom = !empty($filters['month_from']) ? (int) $filters['month_from'] : 0;
    $monthTo = !empty($filters['month_to']) ? (int) $filters['month_to'] : 0;
    $branchId = !empty($filters['branch_id']) ? (int) $filters['branch_id'] : null;

    $normalized = normalize_report_period_filters($yearFrom, $yearTo, $monthFrom, $monthTo);
    $yearFrom = $normalized['year_from'];
    $yearTo = $normalized['year_to'];
    $monthFrom = $normalized['month_from'];
    $monthTo = $normalized['month_to'];

    $sql = 'SELECT se.period_year, se.period_month,
                   COUNT(DISTINCT se.branch_id) AS branch_count,
                   MAX(se.updated_at) AS last_updated
            FROM somfp_entries se
            INNER JOIN branches b ON b.id = se.branch_id
            INNER JOIN companies c ON c.id = b.company_id
            WHERE c.id = ? AND c.user_id = ? AND c.deleted_at IS NULL AND b.deleted_at IS NULL AND se.deleted_at IS NULL';
    $params = [$companyId, $userId];

    [$rangeSql, $rangeParams] = apply_period_range_sql('se', $yearFrom, $yearTo, $monthFrom, $monthTo);
    $sql .= $rangeSql;
    $params = array_merge($params, $rangeParams);

    if ($branchId) {
        $sql .= ' AND se.branch_id = ?';
        $params[] = $branchId;
    }

    $sql .= ' GROUP BY se.period_year, se.period_month
              ORDER BY se.period_year DESC, se.period_month DESC';

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function get_consolidated_period_values(PDO $db, int $companyId, int $year, int $month, ?int $branchId = null): array
{
    $sql = 'SELECT se.line_item_key, SUM(se.amount) AS amount
            FROM somfp_entries se
            INNER JOIN branches b ON b.id = se.branch_id
            WHERE b.company_id = ? AND se.period_year = ? AND se.period_month = ?
              AND b.deleted_at IS NULL AND se.deleted_at IS NULL';
    $params = [$companyId, $year, $month];

    if ($branchId) {
        $sql .= ' AND se.branch_id = ?';
        $params[] = $branchId;
    }

    $sql .= ' GROUP BY se.line_item_key';

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $values = [];
    foreach ($stmt->fetchAll() as $row) {
        $values[$row['line_item_key']] = (float) $row['amount'];
    }
    return $values;
}

function get_available_somfp_years(PDO $db, int $companyId, int $userId): array
{
    $stmt = $db->prepare(
        'SELECT DISTINCT se.period_year
         FROM somfp_entries se
         INNER JOIN branches b ON b.id = se.branch_id
         INNER JOIN companies c ON c.id = b.company_id
         WHERE c.id = ? AND c.user_id = ? AND c.deleted_at IS NULL AND b.deleted_at IS NULL AND se.deleted_at IS NULL
         ORDER BY se.period_year DESC'
    );
    $stmt->execute([$companyId, $userId]);
    return array_column($stmt->fetchAll(), 'period_year');
}

function get_all_somci_line_item_keys(): array
{
    $config = require __DIR__ . '/../config/somci.php';
    return $config['input_keys'];
}

function calculate_somci_totals(array $values): array
{
    $sum = static function (array $keys) use ($values): float {
        $total = 0;
        foreach ($keys as $key) {
            $total += (float) ($values[$key] ?? 0);
        }
        return $total;
    };

    $totalRevenue = $sum(['sales', 'e_commerce_online_sale', 'sales_discounts']);
    $totalDirectExpenses = $sum(['cost_of_sales']);
    $totalOperatingAdmin = $sum([
        'salary_wages', 'admin_expenses', 'legal_professional_consultancy',
        'office_misc_expenses', 'trade_license_legal_expenses', 'office_rent_expenses',
        'utility_expenses', 'printing_stationery', 'meals_refreshments_general',
        'staff_medical_expenses', 'travel_transportation_expenses', 'employees_visa_expenses',
        'advertisement_marketing_expenses', 'repair_maintenance_expenses', 'delivery_charges_expenses',
    ]);
    $totalOtherExpenses = $sum([
        'directors_remuneration', 'bank_charges', 'wps_charges', 'fines_mukhalfa',
    ]);

    $interestOnLoans = (float) ($values['interest_on_loans'] ?? 0);
    $depreciation = (float) ($values['depreciation'] ?? 0);
    $otherIncome = (float) ($values['other_income'] ?? 0);
    $corporateTax = (float) ($values['corporate_tax'] ?? 0);

    $grossProfitLoss = $totalRevenue - $totalDirectExpenses;
    $indirectExpenses = $totalOperatingAdmin + $totalOtherExpenses;
    $profitBeforeInterest = $totalRevenue - $totalDirectExpenses - $totalOperatingAdmin - $totalOtherExpenses;
    $profitAfterInterest = $profitBeforeInterest - $interestOnLoans;
    $profitAfterDep = $profitAfterInterest - $depreciation;
    $profitAfterOtherIncome = $profitAfterDep - $otherIncome;
    $profitLoss = $profitAfterOtherIncome + $corporateTax;

    return [
        'total_revenue' => $totalRevenue,
        'total_direct_expenses' => $totalDirectExpenses,
        'gross_profit_loss' => $grossProfitLoss,
        'total_operating_admin' => $totalOperatingAdmin,
        'total_other_expenses' => $totalOtherExpenses,
        'indirect_expenses' => $indirectExpenses,
        'profit_before_interest' => $profitBeforeInterest,
        'profit_after_interest' => $profitAfterInterest,
        'profit_after_dep' => $profitAfterDep,
        'profit_after_other_income' => $profitAfterOtherIncome,
        'profit_loss' => $profitLoss,
    ];
}

function get_somci_branch_values(PDO $db, int $branchId, int $year, int $month): array
{
    $stmt = $db->prepare(
        'SELECT line_item_key, amount FROM somci_entries
         WHERE branch_id = ? AND period_year = ? AND period_month = ? AND ' . not_deleted()
    );
    $stmt->execute([$branchId, $year, $month]);
    $values = [];
    foreach ($stmt->fetchAll() as $row) {
        $values[$row['line_item_key']] = (float) $row['amount'];
    }
    return $values;
}

function get_consolidated_somci_period_values(PDO $db, int $companyId, int $year, int $month, ?int $branchId = null): array
{
    $sql = 'SELECT se.line_item_key, SUM(se.amount) AS amount
            FROM somci_entries se
            INNER JOIN branches b ON b.id = se.branch_id
            WHERE b.company_id = ? AND se.period_year = ? AND se.period_month = ?
              AND b.deleted_at IS NULL AND se.deleted_at IS NULL';
    $params = [$companyId, $year, $month];

    if ($branchId) {
        $sql .= ' AND se.branch_id = ?';
        $params[] = $branchId;
    }

    $sql .= ' GROUP BY se.line_item_key';

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $values = [];
    foreach ($stmt->fetchAll() as $row) {
        $values[$row['line_item_key']] = (float) $row['amount'];
    }
    return $values;
}

function get_somci_periods(PDO $db, int $companyId, int $userId, array $filters = []): array
{
    $yearFrom = !empty($filters['year_from']) ? (int) $filters['year_from'] : 0;
    $yearTo = !empty($filters['year_to']) ? (int) $filters['year_to'] : 0;
    $monthFrom = !empty($filters['month_from']) ? (int) $filters['month_from'] : 0;
    $monthTo = !empty($filters['month_to']) ? (int) $filters['month_to'] : 0;
    $branchId = !empty($filters['branch_id']) ? (int) $filters['branch_id'] : null;

    $normalized = normalize_report_period_filters($yearFrom, $yearTo, $monthFrom, $monthTo);
    $yearFrom = $normalized['year_from'];
    $yearTo = $normalized['year_to'];
    $monthFrom = $normalized['month_from'];
    $monthTo = $normalized['month_to'];

    $sql = 'SELECT se.period_year, se.period_month,
                   COUNT(DISTINCT se.branch_id) AS branch_count,
                   MAX(se.updated_at) AS last_updated
            FROM somci_entries se
            INNER JOIN branches b ON b.id = se.branch_id
            INNER JOIN companies c ON c.id = b.company_id
            WHERE c.id = ? AND c.user_id = ? AND c.deleted_at IS NULL AND b.deleted_at IS NULL AND se.deleted_at IS NULL';
    $params = [$companyId, $userId];

    [$rangeSql, $rangeParams] = apply_period_range_sql('se', $yearFrom, $yearTo, $monthFrom, $monthTo);
    $sql .= $rangeSql;
    $params = array_merge($params, $rangeParams);

    if ($branchId) {
        $sql .= ' AND se.branch_id = ?';
        $params[] = $branchId;
    }

    $sql .= ' GROUP BY se.period_year, se.period_month
              ORDER BY se.period_year DESC, se.period_month DESC';

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function get_available_somci_years(PDO $db, int $companyId, int $userId): array
{
    $stmt = $db->prepare(
        'SELECT DISTINCT se.period_year
         FROM somci_entries se
         INNER JOIN branches b ON b.id = se.branch_id
         INNER JOIN companies c ON c.id = b.company_id
         WHERE c.id = ? AND c.user_id = ? AND c.deleted_at IS NULL AND b.deleted_at IS NULL AND se.deleted_at IS NULL
         ORDER BY se.period_year DESC'
    );
    $stmt->execute([$companyId, $userId]);
    return array_column($stmt->fetchAll(), 'period_year');
}

function ensure_company_observations_table(PDO $db): void
{
    $hasTableStmt = $db->query("SHOW TABLES LIKE 'company_observations'");
    if (!$hasTableStmt->fetch()) {
        $sql = file_get_contents(__DIR__ . '/../database/migrations/add_company_observations.sql');
        $db->exec($sql);
    }
}

function get_company_observations(PDO $db, int $companyId): array
{
    $stmt = $db->prepare(
        'SELECT * FROM company_observations
         WHERE company_id = ? AND ' . not_deleted() . '
         ORDER BY updated_at DESC, id DESC'
    );
    $stmt->execute([$companyId]);
    return $stmt->fetchAll();
}

function get_company_observation(PDO $db, int $observationId, int $companyId): ?array
{
    $stmt = $db->prepare(
        'SELECT * FROM company_observations
         WHERE id = ? AND company_id = ? AND ' . not_deleted()
    );
    $stmt->execute([$observationId, $companyId]);
    $row = $stmt->fetch();
    return $row ?: null;
}
