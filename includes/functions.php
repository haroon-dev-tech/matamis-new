<?php

require_once __DIR__ . '/../config/app.php';

function using_https(): bool
{
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        return true;
    }
    if (!empty($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443) {
        return true;
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower((string) $_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https') {
        return true;
    }
    return false;
}

function bootstrap_secure_session(): void
{
    if (session_status() !== PHP_SESSION_NONE) {
        return;
    }

    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Lax');

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => using_https(),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();
}

if (session_status() === PHP_SESSION_NONE) {
    bootstrap_secure_session();
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

function apply_security_headers(): void
{
    if (headers_sent()) {
        return;
    }

    header('X-Frame-Options: DENY');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
    header('Cross-Origin-Opener-Policy: same-origin');
    header('Cross-Origin-Resource-Policy: same-origin');
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

function can_access_company(PDO $db, int $companyId, int $userId, ?string $contextPermission = null): bool
{
    if ($companyId <= 0 || $userId <= 0) {
        return false;
    }

    if (user_owns_company($db, $companyId, $userId)) {
        return true;
    }

    if ($contextPermission && (user_can($db, $userId, $contextPermission, 'read') || user_can($db, $userId, $contextPermission, 'write'))) {
        $stmt = $db->prepare('SELECT id FROM companies WHERE id = ? AND ' . not_deleted());
        $stmt->execute([$companyId]);
        return (bool) $stmt->fetch();
    }

    return false;
}

function get_accessible_companies(PDO $db, int $userId, ?string $contextPermission = null): array
{
    if (
        $contextPermission &&
        (user_can($db, $userId, $contextPermission, 'read') || user_can($db, $userId, $contextPermission, 'write'))
    ) {
        $stmt = $db->prepare('SELECT id, name FROM companies WHERE ' . not_deleted() . ' ORDER BY name ASC');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    $stmt = $db->prepare('SELECT id, name FROM companies WHERE user_id = ? AND ' . not_deleted() . ' ORDER BY name ASC');
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
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

function ensure_user_profile_columns(PDO $db): void
{
    static $checked = false;
    if ($checked) {
        return;
    }
    $checked = true;

    $columns = [
        'phone' => 'ADD COLUMN phone VARCHAR(30) NULL AFTER email',
        'designation' => 'ADD COLUMN designation VARCHAR(150) NULL AFTER phone',
    ];

    try {
        foreach ($columns as $column => $alterSql) {
            $stmt = $db->query("SHOW COLUMNS FROM users LIKE " . $db->quote($column));
            if (!$stmt->fetch()) {
                $db->exec('ALTER TABLE users ' . $alterSql);
            }
        }
    } catch (Exception $e) {
        // Do not break app boot if migration cannot run.
    }
}

function ensure_rbac_seeded(PDO $db): void
{
    if (!empty($_SESSION['rbac_seeded'])) {
        try {
            // If new permissions were added after a user session started,
            // run seeding once again to backfill them.
            $check = $db->prepare('SELECT id FROM permissions WHERE perm_key = ? LIMIT 1');
            $check->execute(['settings_logs']);
            if ($check->fetch()) {
                return;
            }
        } catch (Exception $e) {
            // Fall through and retry seeding.
        }
    }

    try {
        $db->query('SELECT 1 FROM roles LIMIT 1');
        $db->query('SELECT 1 FROM permissions LIMIT 1');
        $db->query('SELECT 1 FROM role_permissions LIMIT 1');
        $db->query('SELECT 1 FROM user_roles LIMIT 1');
    } catch (PDOException $e) {
        // RBAC not migrated yet; keep app usable until migration is applied.
        $_SESSION['rbac_disabled'] = true;
        $_SESSION['rbac_seeded'] = true;
        return;
    }

    $perms = [
        ['dashboard', 'Dashboard', 'Access dashboard'],
        ['companies', 'Companies', 'Manage companies and branches'],
        ['observations', 'Observations', 'Manage observations & recommendations'],
        ['linked_is', 'Linked IS', 'Manage linked income statement entries'],
        ['linked_bs', 'Linked BS', 'Manage linked balance sheet entries'],
        ['somfp', 'SOMFP', 'Manage SOMFP entries and reports'],
        ['somci', 'SOMCI', 'Manage SOMCI entries and reports'],
        ['sofp', 'SOFP', 'Access overall statement of financial position'],
        ['soci', 'SOCI', 'Access overall statement of comprehensive income'],
        ['glance', 'Glance', 'Access glance picture insights'],
        ['settings_users', 'Settings: Users', 'Manage users and role assignment'],
        ['settings_roles', 'Settings: Roles', 'Manage roles and permissions'],
        ['settings_logs', 'Settings: Logs', 'View activity and audit logs'],
    ];

    $db->beginTransaction();
    try {
        $stmt = $db->prepare('INSERT INTO permissions (perm_key, label, description) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE label = VALUES(label), description = VALUES(description)');
        foreach ($perms as [$key, $label, $desc]) {
            $stmt->execute([$key, $label, $desc]);
        }

        // Ensure Admin role automatically gets access to any newly seeded permissions.
        $adminRoleStmt = $db->prepare('SELECT id FROM roles WHERE name = ? AND deleted_at IS NULL LIMIT 1');
        $adminRoleStmt->execute(['Admin']);
        $adminRoleId = (int) $adminRoleStmt->fetchColumn();
        if ($adminRoleId > 0) {
            $db->exec(
                'INSERT IGNORE INTO role_permissions (role_id, permission_id, can_read, can_write)
                 SELECT ' . $adminRoleId . ', p.id, 1, 1
                 FROM permissions p
                 WHERE p.perm_key IS NOT NULL'
            );
        }

        $db->commit();
        unset($_SESSION['rbac_disabled']);
        unset($_SESSION['rbac_perm_cache']);
        $_SESSION['rbac_seeded'] = true;
    } catch (Exception $e) {
        $db->rollBack();
        $_SESSION['rbac_seeded'] = true;
    }
}

function get_user_permissions(PDO $db, int $userId): array
{
    if (isset($_SESSION['rbac_perm_cache']) && is_array($_SESSION['rbac_perm_cache'])) {
        return $_SESSION['rbac_perm_cache'];
    }

    try {
        $sql = 'SELECT p.perm_key,
                       MAX(rp.can_read) AS can_read,
                       MAX(rp.can_write) AS can_write
                FROM user_roles ur
                INNER JOIN roles r ON r.id = ur.role_id AND r.deleted_at IS NULL
                INNER JOIN role_permissions rp ON rp.role_id = r.id
                INNER JOIN permissions p ON p.id = rp.permission_id
                WHERE ur.user_id = ?
                GROUP BY p.perm_key';
        $stmt = $db->prepare($sql);
        $stmt->execute([$userId]);
        $rows = $stmt->fetchAll();
    } catch (PDOException $e) {
        $_SESSION['rbac_perm_cache'] = [];
        return [];
    }

    $map = [];
    foreach ($rows as $row) {
        $map[$row['perm_key']] = [
            'read' => (bool) $row['can_read'],
            'write' => (bool) $row['can_write'],
        ];
    }

    $_SESSION['rbac_perm_cache'] = $map;
    return $map;
}

function user_can(PDO $db, ?int $userId, string $permKey, string $mode = 'read'): bool
{
    if (!empty($_SESSION['rbac_disabled'])) {
        return true;
    }
    if (!$userId) {
        return false;
    }
    $mode = $mode === 'write' ? 'write' : 'read';
    $perms = get_user_permissions($db, $userId);
    return !empty($perms[$permKey][$mode]);
}

function infer_permission_for_request(string $scriptName, string $method): ?array
{
    $path = strtolower($scriptName);
    $method = strtoupper($method);

    // Settings
    if (strpos($path, '/settings/users') !== false || strpos($path, '/settings/user_') !== false) {
        return ['settings_users', $method === 'POST' ? 'write' : 'read'];
    }
    if (strpos($path, '/settings/roles') !== false || strpos($path, '/settings/role_') !== false) {
        return ['settings_roles', $method === 'POST' ? 'write' : 'read'];
    }
    if (strpos($path, '/settings/logs') !== false) {
        return ['settings_logs', 'read'];
    }

    $moduleMap = [
        '/companies/' => 'companies',
        '/observations/' => 'observations',
        '/linked-is/' => 'linked_is',
        '/linked-bs/' => 'linked_bs',
        '/somfp/' => 'somfp',
        '/somci/' => 'somci',
        '/sofp/' => 'sofp',
        '/soci/' => 'soci',
        '/glance/' => 'glance',
    ];

    foreach ($moduleMap as $prefix => $permKey) {
        if (strpos($path, $prefix) !== false) {
            $isWriteByName = (
                strpos($path, 'create.php') !== false ||
                strpos($path, 'edit.php') !== false ||
                strpos($path, 'entry.php') !== false
            );
            $mode = ($method === 'POST' || $isWriteByName) ? 'write' : 'read';
            return [$permKey, $mode];
        }
    }

    if (strpos($path, '/dashboard.php') !== false) {
        return ['dashboard', 'read'];
    }

    return null;
}

function require_permission(PDO $db, ?int $userId, string $permKey, string $mode): void
{
    if (user_can($db, $userId, $permKey, $mode)) {
        return;
    }
    log_activity($db, [
        'user_id' => $userId,
        'event_type' => 'access_denied',
        'action' => 'deny',
        'module' => $permKey,
        'description' => 'Permission denied for requested resource.',
        'metadata' => [
            'required_permission' => $permKey,
            'required_mode' => $mode,
        ],
    ]);

    if ($permKey === 'dashboard' && $mode === 'read') {
        flash('error', 'Dashboard is not available for your role. Redirected to your landing page.');
        redirect(get_default_landing_path($db, $userId));
    }

    flash('error', 'You are not authorized to access this page.');
    redirect('/forbidden.php');
}

function get_default_landing_path(PDO $db, ?int $userId): string
{
    if (!$userId) {
        return '/login.php';
    }
    if (!empty($_SESSION['rbac_disabled'])) {
        return '/dashboard.php';
    }

    $routes = [
        'dashboard' => '/dashboard.php',
        'companies' => '/companies/index.php',
        'observations' => '/observations/index.php',
        'linked_is' => '/linked-is/index.php',
        'linked_bs' => '/linked-bs/index.php',
        'somfp' => '/somfp/index.php',
        'somci' => '/somci/index.php',
        'sofp' => '/sofp/index.php',
        'soci' => '/soci/index.php',
        'glance' => '/glance/index.php',
        'settings_users' => '/settings/users.php',
        'settings_roles' => '/settings/roles.php',
        'settings_logs' => '/settings/logs.php',
    ];

    foreach ($routes as $perm => $path) {
        if (user_can($db, $userId, $perm, 'read')) {
            return $path;
        }
    }

    return '/landing.php';
}

function ensure_audit_logs_table(PDO $db): void
{
    if (!empty($_SESSION['audit_logs_checked'])) {
        return;
    }

    try {
        $db->query('SELECT 1 FROM activity_logs LIMIT 1');
        $_SESSION['audit_logs_checked'] = true;
        return;
    } catch (PDOException $e) {
        // Continue and create it below.
    }

    $sql = "CREATE TABLE IF NOT EXISTS activity_logs (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT UNSIGNED NULL,
        user_name VARCHAR(150) NULL,
        user_email VARCHAR(150) NULL,
        event_type VARCHAR(50) NOT NULL,
        action VARCHAR(50) NOT NULL,
        module_key VARCHAR(100) NULL,
        route_path VARCHAR(255) NOT NULL,
        request_method VARCHAR(10) NOT NULL,
        ip_address VARCHAR(64) NULL,
        user_agent VARCHAR(255) NULL,
        description VARCHAR(255) NULL,
        metadata_json JSON NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_activity_logs_user (user_id),
        INDEX idx_activity_logs_event (event_type),
        INDEX idx_activity_logs_created (created_at),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
    ) ENGINE=InnoDB;";

    try {
        $db->exec($sql);
        $_SESSION['audit_logs_checked'] = true;
    } catch (PDOException $e) {
        $_SESSION['audit_logs_checked'] = true;
    }
}

function request_ip_address(): string
{
    $keys = ['HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
    foreach ($keys as $key) {
        if (!empty($_SERVER[$key])) {
            $value = trim((string) $_SERVER[$key]);
            if ($key === 'HTTP_X_FORWARDED_FOR') {
                $parts = explode(',', $value);
                return trim($parts[0]);
            }
            return $value;
        }
    }
    return 'unknown';
}

function is_login_rate_limited(PDO $db, string $email, string $ip): bool
{
    // 8 failed attempts in 15 minutes from same IP/email pair will be blocked.
    try {
        ensure_audit_logs_table($db);
        $stmt = $db->prepare(
            'SELECT COUNT(*) FROM activity_logs
             WHERE event_type = ? AND action = ? AND created_at >= (NOW() - INTERVAL 15 MINUTE)
             AND (ip_address = ? OR (user_email IS NOT NULL AND user_email = ?))'
        );
        $stmt->execute(['auth', 'login_failed', $ip, $email]);
        $count = (int) $stmt->fetchColumn();
        return $count >= 8;
    } catch (PDOException $e) {
        // If logs table is unavailable, do not break login flow.
        return false;
    }
}

function infer_activity_action(string $scriptName, string $method, array $postData = []): array
{
    $path = strtolower($scriptName);
    $method = strtoupper($method);
    $eventType = 'view';
    $action = 'view_page';

    if ($method === 'POST') {
        $eventType = 'write';
        $action = 'update';
        foreach (array_keys($postData) as $key) {
            $key = strtolower((string) $key);
            if (strpos($key, 'delete') !== false || strpos($key, 'remove') !== false) {
                return ['delete', 'delete_record'];
            }
        }
        if (strpos($path, 'create.php') !== false || strpos($path, 'register.php') !== false || strpos($path, '/entry.php') !== false) {
            return ['create', 'create_record'];
        }
        if (strpos($path, 'edit.php') !== false) {
            return ['update', 'edit_record'];
        }
        return [$eventType, $action];
    }

    if (strpos($path, 'view.php') !== false) {
        return ['view', 'view_record'];
    }
    if (strpos($path, 'index.php') !== false) {
        return ['view', 'list_page'];
    }
    if (strpos($path, 'create.php') !== false || strpos($path, 'edit.php') !== false || strpos($path, '/entry.php') !== false) {
        return ['view', 'form_page'];
    }

    return [$eventType, $action];
}

function log_activity(PDO $db, array $data): void
{
    ensure_audit_logs_table($db);

    $routePath = (string) ($_SERVER['SCRIPT_NAME'] ?? '');
    $requestMethod = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
    $ip = request_ip_address();
    $agent = substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255);

    $userId = isset($data['user_id']) ? (int) $data['user_id'] : null;
    $userName = $data['user_name'] ?? ($_SESSION['user_name'] ?? null);
    $userEmail = $data['user_email'] ?? null;
    $module = $data['module'] ?? null;

    if ($userId && $userEmail === null) {
        try {
            $stmt = $db->prepare('SELECT full_name, email FROM users WHERE id = ?');
            $stmt->execute([$userId]);
            $row = $stmt->fetch();
            if ($row) {
                $userName = $row['full_name'] ?? $userName;
                $userEmail = $row['email'] ?? null;
            }
        } catch (PDOException $e) {
            // Ignore user lookup failure and continue logging.
        }
    }

    $stmt = $db->prepare(
        'INSERT INTO activity_logs (
            user_id, user_name, user_email, event_type, action, module_key,
            route_path, request_method, ip_address, user_agent, description, metadata_json
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );

    try {
        $stmt->execute([
            $userId,
            $userName,
            $userEmail,
            (string) ($data['event_type'] ?? 'view'),
            (string) ($data['action'] ?? 'view_page'),
            $module ? (string) $module : null,
            $routePath,
            $requestMethod,
            $ip,
            $agent,
            isset($data['description']) ? (string) $data['description'] : null,
            !empty($data['metadata']) ? json_encode($data['metadata']) : null,
        ]);
    } catch (PDOException $e) {
        // Logging failures should never break app flow.
    }
}

function log_current_request(PDO $db, ?array $currentUser): void
{
    static $alreadyLogged = false;

    if (!empty($_SERVER['REQUEST_URI']) && strpos((string) $_SERVER['REQUEST_URI'], '/assets/') !== false) {
        return;
    }
    if ($alreadyLogged) {
        return;
    }

    [$eventType, $action] = infer_activity_action(
        (string) ($_SERVER['SCRIPT_NAME'] ?? ''),
        (string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'),
        $_POST ?? []
    );

    $required = infer_permission_for_request(
        (string) ($_SERVER['SCRIPT_NAME'] ?? ''),
        (string) ($_SERVER['REQUEST_METHOD'] ?? 'GET')
    );
    $module = $required ? $required[0] : null;

    log_activity($db, [
        'user_id' => $currentUser['id'] ?? null,
        'user_name' => $currentUser['full_name'] ?? null,
        'user_email' => $currentUser['email'] ?? null,
        'event_type' => $eventType,
        'action' => $action,
        'module' => $module,
        'description' => 'HTTP request activity',
        'metadata' => [
            'query_string' => $_SERVER['QUERY_STRING'] ?? '',
        ],
    ]);

    $alreadyLogged = true;
}
