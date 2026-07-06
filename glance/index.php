<?php
$pageTitle = 'Glance Picture';
$activeNav = 'glance';
$requireAuth = true;
require __DIR__ . '/../includes/bootstrap.php';

$userId = current_user_id();

$stmt = $db->prepare('SELECT id, name FROM companies WHERE user_id = ? AND ' . not_deleted() . ' ORDER BY name ASC');
$stmt->execute([$userId]);
$companies = $stmt->fetchAll();

$filtersSubmitted = isset($_GET['applied']);
$selectedCompanyId = (int) ($_GET['company_id'] ?? ($companies[0]['id'] ?? 0));
$yearFrom = (int) ($_GET['year_from'] ?? 0);
$yearTo = (int) ($_GET['year_to'] ?? 0);
$monthFrom = (int) ($_GET['month_from'] ?? 0);
$monthTo = (int) ($_GET['month_to'] ?? 0);
$branchId = (int) ($_GET['branch_id'] ?? 0);
$activeTab = $_GET['tab'] ?? 'financial-position';
if (!in_array($activeTab, ['financial-position', 'comprehensive-income'], true)) {
    $activeTab = 'financial-position';
}

$branches = [];
$availableYears = [];
$yearOptions = [];
$periodRows = [];
$periodRowCount = 0;
$chartLabels = [];
$chartAssets = [];
$chartLiabilities = [];
$ciPeriodRows = [];
$ciChartLabels = [];
$ciChartRevenue = [];
$ciChartDirectExpenses = [];
$ciChartGrossProfit = [];
$ciChartIndirectExpenses = [];
$ciChartProfitLoss = [];

if ($selectedCompanyId && user_owns_company($db, $selectedCompanyId, $userId)) {
    $branches = get_company_branches($db, $selectedCompanyId);
    $availableYears = $activeTab === 'comprehensive-income'
        ? get_available_somci_years($db, $selectedCompanyId, $userId)
        : get_available_somfp_years($db, $selectedCompanyId, $userId);

    if (!$filtersSubmitted) {
        if (empty($yearFrom) && !empty($availableYears)) {
            $yearFrom = (int) min($availableYears);
        }
        if (empty($yearTo) && !empty($availableYears)) {
            $yearTo = (int) max($availableYears);
        } elseif (empty($yearTo)) {
            $yearTo = (int) date('Y');
        }
        if (empty($yearFrom)) {
            $yearFrom = $yearTo;
        }
    }

    $normalized = normalize_report_period_filters($yearFrom, $yearTo, $monthFrom, $monthTo);
    $yearFrom = $normalized['year_from'];
    $yearTo = $normalized['year_to'];
    $monthFrom = $normalized['month_from'];
    $monthTo = $normalized['month_to'];

    $yearOptions = build_report_year_options($availableYears, $yearFrom, $yearTo);

    $filters = [
        'year_from'  => $yearFrom,
        'year_to'    => $yearTo,
        'month_from' => $monthFrom ?: null,
        'month_to'   => $monthTo ?: null,
        'branch_id'  => $branchId ?: null,
    ];

    if ($activeTab === 'financial-position') {
        $periods = get_somfp_periods($db, $selectedCompanyId, $userId, $filters);

        foreach ($periods as $period) {
            $values = get_consolidated_period_values(
                $db,
                $selectedCompanyId,
                (int) $period['period_year'],
                (int) $period['period_month'],
                $branchId ?: null
            );
            $totals = calculate_somfp_totals($values);
            $periodRows[] = array_merge($period, ['totals' => $totals]);
        }

        $periodRowCount = count($periodRows);
        foreach ($periodRows as $row) {
            $month = (int) $row['period_month'];
            $year = (int) $row['period_year'];
            $chartLabels[] = substr(MONTHS[$month], 0, 3) . '-' . substr((string) $year, -2);
            $chartAssets[] = round($row['totals']['total_assets'], 2);
            $chartLiabilities[] = round($row['totals']['total_equity_liabilities'], 2);
        }
    } else {
        $periods = get_somci_periods($db, $selectedCompanyId, $userId, $filters);

        foreach ($periods as $period) {
            $values = get_consolidated_somci_period_values(
                $db,
                $selectedCompanyId,
                (int) $period['period_year'],
                (int) $period['period_month'],
                $branchId ?: null
            );
            $totals = calculate_somci_totals($values);
            $ciPeriodRows[] = array_merge($period, ['totals' => $totals]);
        }

        $periodRowCount = count($ciPeriodRows);
        foreach ($ciPeriodRows as $row) {
            $month = (int) $row['period_month'];
            $year = (int) $row['period_year'];
            $ciChartLabels[] = substr(MONTHS[$month], 0, 3) . '-' . substr((string) $year, -2);
            $ciChartRevenue[] = round($row['totals']['total_revenue'], 2);
            $ciChartDirectExpenses[] = round($row['totals']['total_direct_expenses'], 2);
            $ciChartGrossProfit[] = round($row['totals']['gross_profit_loss'], 2);
            $ciChartIndirectExpenses[] = round($row['totals']['indirect_expenses'], 2);
            $ciChartProfitLoss[] = round($row['totals']['profit_loss'], 2);
        }
    }
}

$filterQueryBase = [
    'company_id' => $selectedCompanyId,
    'year_from'  => $yearFrom,
    'year_to'    => $yearTo,
    'month_from' => $monthFrom,
    'month_to'   => $monthTo,
    'branch_id'  => $branchId ?: null,
    'applied'    => 1,
];

require __DIR__ . '/../includes/header.php';
?>

<div class="mb-6">
    <p class="text-sm text-slate-500 dark:text-slate-400">
        Balance sheet glance — financial position and comprehensive income overview
    </p>
</div>

<?php if (empty($companies)): ?>
<div class="card p-12 text-center">
    <p class="text-slate-500">Register a company and enter SOMFP data first.</p>
    <a href="<?= BASE_URL ?>/companies/create.php" class="btn-primary mt-4">Register Company</a>
</div>
<?php else: ?>

<div class="card mb-6 p-6">
    <form method="GET" action="<?= BASE_URL ?>/glance/index.php" class="space-y-4">
        <input type="hidden" name="tab" value="<?= e($activeTab) ?>">
        <input type="hidden" name="applied" value="1">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div>
                <label class="mb-1.5 block text-sm font-medium">Company</label>
                <select name="company_id" class="input-field">
                    <?php foreach ($companies as $co): ?>
                    <option value="<?= $co['id'] ?>" <?= $co['id'] == $selectedCompanyId ? 'selected' : '' ?>><?= e($co['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium">Branch</label>
                <select name="branch_id" class="input-field">
                    <option value="0">All Branches (Consolidated)</option>
                    <?php foreach ($branches as $b): ?>
                    <option value="<?= $b['id'] ?>" <?= $b['id'] == $branchId ? 'selected' : '' ?>><?= e($b['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium">Year From</label>
                <select name="year_from" class="input-field">
                    <?php foreach ($yearOptions as $y): ?>
                    <option value="<?= $y ?>" <?= $y == $yearFrom ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium">Year To</label>
                <select name="year_to" class="input-field">
                    <?php foreach ($yearOptions as $y): ?>
                    <option value="<?= $y ?>" <?= $y == $yearTo ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium">Month From</label>
                <select name="month_from" class="input-field">
                    <option value="0">All Months</option>
                    <?php foreach (MONTHS as $m => $label): ?>
                    <option value="<?= $m ?>" <?= $m == $monthFrom ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium">Month To</label>
                <select name="month_to" class="input-field">
                    <option value="0">All Months</option>
                    <?php foreach (MONTHS as $m => $label): ?>
                    <option value="<?= $m ?>" <?= $m == $monthTo ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="flex flex-wrap gap-3">
            <button type="submit" class="btn-primary">Apply Filters</button>
            <a href="<?= BASE_URL ?>/glance/index.php" class="btn-secondary">Reset</a>
        </div>
    </form>
</div>

<div class="glance-tabs mb-6">
    <a href="<?= BASE_URL ?>/glance/index.php<?= report_filter_query(array_merge($filterQueryBase, ['tab' => 'financial-position'])) ?>"
       class="glance-tab <?= $activeTab === 'financial-position' ? 'active' : '' ?>">
        Financial Position
    </a>
    <a href="<?= BASE_URL ?>/glance/index.php<?= report_filter_query(array_merge($filterQueryBase, ['tab' => 'comprehensive-income'])) ?>"
       class="glance-tab <?= $activeTab === 'comprehensive-income' ? 'active' : '' ?>">
        Comprehensive Income
    </a>
</div>

<?php if ($filtersSubmitted && $yearFrom > 0 && $yearTo > 0): ?>
<div class="mb-4 text-sm text-slate-500 dark:text-slate-400">
    Showing <?= $periodRowCount ?> period<?= $periodRowCount === 1 ? '' : 's' ?>
    from <?= $monthFrom ? e(MONTHS[$monthFrom]) . ' ' : '' ?><?= $yearFrom ?>
    to <?= $monthTo ? e(MONTHS[$monthTo]) . ' ' : '' ?><?= $yearTo ?>
    <?php if ($branchId): ?> · Branch filter active<?php endif; ?>
</div>
<?php endif; ?>

<?php if ($activeTab === 'financial-position'): ?>
    <?php require __DIR__ . '/partials/financial_position.php'; ?>
<?php else: ?>
    <?php require __DIR__ . '/partials/comprehensive_income.php'; ?>
<?php endif; ?>

<?php endif; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>
