<?php
$pageTitle = 'Glance Picture';
$activeNav = 'glance';
$requireAuth = true;
require __DIR__ . '/../includes/bootstrap.php';
require __DIR__ . '/../includes/linked_bs.php';
require __DIR__ . '/../includes/linked_is.php';

$userId = current_user_id();

$companies = get_accessible_companies($db, $userId, 'glance');

$filtersSubmitted = isset($_GET['applied']);
$selectedCompanyId = (int) ($_GET['company_id'] ?? ($companies[0]['id'] ?? 0));
$yearFrom = (int) ($_GET['year_from'] ?? 0);
$yearTo = (int) ($_GET['year_to'] ?? 0);
$monthFrom = (int) ($_GET['month_from'] ?? 0);
$monthTo = (int) ($_GET['month_to'] ?? 0);
$branchId = (int) ($_GET['branch_id'] ?? 0);
$activeTab = $_GET['tab'] ?? 'linked-bs';
if (!in_array($activeTab, ['linked-bs', 'linked-is'], true)) {
    $activeTab = 'linked-bs';
}

$branches = [];
$availableYears = [];
$yearOptions = [];
$periodRows = [];
$periodRowCount = 0;
$chartLabels = [];
$chartAssets = [];
$chartLiabilities = [];
$chartNetWorth = [];
$ciPeriodRows = [];
$ciHeadSeries = [];
$ciChartLabels = [];
$ciSeriesForChart = [];

if ($selectedCompanyId && can_access_company($db, $selectedCompanyId, $userId, 'glance')) {
    $branches = get_company_branches($db, $selectedCompanyId);
    $linkedBsStructure = get_linked_bs_structure($db, $selectedCompanyId);
    $linkedIsStructure = get_linked_is_structure($db, $selectedCompanyId);

    $tableName = $activeTab === 'linked-is' ? 'linked_is_entries' : 'linked_bs_entries';

    $yearSql = "SELECT DISTINCT se.period_year
                FROM {$tableName} se
                INNER JOIN branches b ON b.id = se.branch_id
                WHERE b.company_id = ? AND b.deleted_at IS NULL AND se.deleted_at IS NULL";
    $yearParams = [$selectedCompanyId];
    if ($branchId > 0) {
        $yearSql .= ' AND se.branch_id = ?';
        $yearParams[] = $branchId;
    }
    $yearSql .= ' ORDER BY se.period_year DESC';
    $stmt = $db->prepare($yearSql);
    $stmt->execute($yearParams);
    $availableYears = array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN) ?: []);

    if (!$filtersSubmitted || (empty($yearFrom) && empty($yearTo))) {
        if (!empty($availableYears)) {
            $yearTo = (int) $availableYears[0];
            $yearFrom = (int) $availableYears[count($availableYears) - 1];
        } else {
            $yearTo = (int) date('Y');
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

    $periodSql = "SELECT se.period_year, se.period_month,
                         COUNT(DISTINCT se.branch_id) AS branch_count,
                         MAX(se.updated_at) AS last_updated
                  FROM {$tableName} se
                  INNER JOIN branches b ON b.id = se.branch_id
                  WHERE b.company_id = ? AND b.deleted_at IS NULL AND se.deleted_at IS NULL";
    $periodParams = [$selectedCompanyId];
    if ($branchId > 0) {
        $periodSql .= ' AND se.branch_id = ?';
        $periodParams[] = $branchId;
    }
    [$periodRangeSql, $periodRangeParams] = apply_period_range_sql('se', $yearFrom, $yearTo, $monthFrom, $monthTo);
    $periodSql .= $periodRangeSql;
    $periodParams = array_merge($periodParams, $periodRangeParams);
    $periodSql .= ' GROUP BY se.period_year, se.period_month ORDER BY se.period_year DESC, se.period_month DESC';
    $stmt = $db->prepare($periodSql);
    $stmt->execute($periodParams);
    $periods = $stmt->fetchAll();

    if ($activeTab === 'linked-bs') {
        foreach ($periods as $period) {
            $valuesData = get_consolidated_linked_bs_entry_values(
                $db,
                $selectedCompanyId,
                (int) $period['period_year'],
                (int) $period['period_month'],
                $branchId ?: null
            );
            $totals = calculate_linked_bs_totals($linkedBsStructure, $valuesData['values']);
            $assetTotal = 0.0;
            $liabilityTotal = 0.0;
            foreach ($linkedBsStructure['heads'] as $head) {
                $label = strtolower((string) ($head['label'] ?? ''));
                $headTotal = (float) ($totals['head_totals'][$head['id']] ?? 0);
                if (strpos($label, 'asset') !== false) {
                    $assetTotal += $headTotal;
                } else {
                    $liabilityTotal += $headTotal;
                }
            }
            $periodRows[] = array_merge($period, ['totals' => $totals]);
            $periodRows[count($periodRows) - 1]['totals']['total_assets'] = $assetTotal;
            $periodRows[count($periodRows) - 1]['totals']['total_equity_liabilities'] = $liabilityTotal;
            $periodRows[count($periodRows) - 1]['totals']['net_worth'] = $assetTotal - $liabilityTotal;
        }

        $periodRowCount = count($periodRows);
        foreach ($periodRows as $row) {
            $month = (int) $row['period_month'];
            $year = (int) $row['period_year'];
            $chartLabels[] = substr(MONTHS[$month], 0, 3) . '-' . substr((string) $year, -2);
            $chartAssets[] = round($row['totals']['total_assets'], 2);
            $chartLiabilities[] = round($row['totals']['total_equity_liabilities'], 2);
            $chartNetWorth[] = round($row['totals']['net_worth'], 2);
        }
    } else {
        $headColors = [
            'rgb(5, 150, 105)',
            'rgb(220, 38, 38)',
            'rgb(37, 99, 235)',
            'rgb(217, 119, 6)',
            'rgb(124, 58, 237)',
            'rgb(236, 72, 153)',
            'rgb(14, 165, 233)',
            'rgb(132, 204, 22)',
        ];

        foreach ($periods as $period) {
            $valuesData = get_consolidated_linked_is_entry_values(
                $db,
                $selectedCompanyId,
                (int) $period['period_year'],
                (int) $period['period_month'],
                $branchId ?: null
            );
            $totals = calculate_linked_is_totals($linkedIsStructure, $valuesData['values']);

            $derived = [
                'total_revenue' => 0.0,
                'total_direct_expenses' => 0.0,
                'gross_profit_loss' => 0.0,
                'indirect_expenses' => 0.0,
                'profit_loss' => (float) ($totals['net_profit_loss'] ?? 0),
            ];

            foreach ($linkedIsStructure['heads'] as $head) {
                $label = strtolower((string) ($head['label'] ?? ''));
                $headTotal = (float) ($totals['head_totals'][$head['id']] ?? 0);
                if (strpos($label, 'revenue') !== false) {
                    $derived['total_revenue'] += $headTotal;
                } elseif (strpos($label, 'direct') !== false) {
                    $derived['total_direct_expenses'] += $headTotal;
                } elseif (strpos($label, 'operating') !== false || strpos($label, 'administrative') !== false || strpos($label, 'other expenses') !== false) {
                    $derived['indirect_expenses'] += $headTotal;
                }
            }
            $derived['gross_profit_loss'] = $derived['total_revenue'] - $derived['total_direct_expenses'];

            $ciPeriodRows[] = array_merge($period, ['totals' => $totals]);
            $ciPeriodRows[count($ciPeriodRows) - 1]['totals'] = array_merge($ciPeriodRows[count($ciPeriodRows) - 1]['totals'], $derived);
        }

        foreach ($linkedIsStructure['heads'] as $index => $head) {
            $ciHeadSeries[] = [
                'head_id' => (int) $head['id'],
                'label' => $head['label'],
                'color' => $headColors[$index % count($headColors)],
                'values' => [],
            ];
        }

        $periodRowCount = count($ciPeriodRows);
        foreach ($ciPeriodRows as $rowIndex => $row) {
            $month = (int) $row['period_month'];
            $year = (int) $row['period_year'];
            $ciChartLabels[] = substr(MONTHS[$month], 0, 3) . '-' . substr((string) $year, -2);

            foreach ($ciHeadSeries as $seriesIndex => $series) {
                $value = round((float) ($row['totals']['head_totals'][$series['head_id']] ?? 0), 2);
                $ciHeadSeries[$seriesIndex]['values'][] = $value;
            }
        }

        foreach ($ciHeadSeries as $series) {
            $rgb = $series['color'];
            $ciSeriesForChart[] = [
                'label' => $series['label'],
                'data' => $series['values'],
                'border' => $rgb,
                'fill' => str_replace('rgb(', 'rgba(', str_replace(')', ', 0.12)', $rgb)),
                'bar' => str_replace('rgb(', 'rgba(', str_replace(')', ', 0.8)', $rgb)),
            ];
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
        Glance overview — linked balance sheet and linked income statement
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
        <div class="grid gap-4 sm:grid-cols-2">
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
        </div>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
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
                <label class="mb-1.5 block text-sm font-medium">Year From</label>
                <select name="year_from" class="input-field">
                    <?php foreach ($yearOptions as $y): ?>
                    <option value="<?= $y ?>" <?= $y == $yearFrom ? 'selected' : '' ?>><?= $y ?></option>
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
            <div>
                <label class="mb-1.5 block text-sm font-medium">Year To</label>
                <select name="year_to" class="input-field">
                    <?php foreach ($yearOptions as $y): ?>
                    <option value="<?= $y ?>" <?= $y == $yearTo ? 'selected' : '' ?>><?= $y ?></option>
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
    <a href="<?= BASE_URL ?>/glance/index.php<?= report_filter_query(array_merge($filterQueryBase, ['tab' => 'linked-bs'])) ?>"
       class="glance-tab <?= $activeTab === 'linked-bs' ? 'active' : '' ?>">
        Linked BS
    </a>
    <a href="<?= BASE_URL ?>/glance/index.php<?= report_filter_query(array_merge($filterQueryBase, ['tab' => 'linked-is'])) ?>"
       class="glance-tab <?= $activeTab === 'linked-is' ? 'active' : '' ?>">
        Linked IS
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

<?php if ($activeTab === 'linked-bs'): ?>
    <?php require __DIR__ . '/partials/financial_position.php'; ?>
<?php else: ?>
    <?php require __DIR__ . '/partials/comprehensive_income.php'; ?>
<?php endif; ?>

<?php endif; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>
