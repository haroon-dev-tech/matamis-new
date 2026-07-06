<?php
$pageTitle = 'SOFP — Statement of Financial Position';
$activeNav = 'sofp';
$requireAuth = true;
require __DIR__ . '/../includes/bootstrap.php';

$userId = current_user_id();

$stmt = $db->prepare('SELECT id, name FROM companies WHERE user_id = ? AND ' . not_deleted() . ' ORDER BY name ASC');
$stmt->execute([$userId]);
$companies = $stmt->fetchAll();

$selectedCompanyId = (int) ($_GET['company_id'] ?? ($companies[0]['id'] ?? 0));
$yearFrom = (int) ($_GET['year_from'] ?? 0);
$yearTo = (int) ($_GET['year_to'] ?? 0);
$monthFrom = (int) ($_GET['month_from'] ?? 0);
$monthTo = (int) ($_GET['month_to'] ?? 0);
$branchId = (int) ($_GET['branch_id'] ?? 0);

$branches = [];
$availableYears = [];
$periods = [];
$periodRows = [];

if ($selectedCompanyId && user_owns_company($db, $selectedCompanyId, $userId)) {
    $branches = get_company_branches($db, $selectedCompanyId);
    $availableYears = get_available_somfp_years($db, $selectedCompanyId, $userId);

    if (empty($yearFrom) && !empty($availableYears)) {
        $yearFrom = (int) min($availableYears);
    }
    if (empty($yearTo)) {
        $yearTo = (int) date('Y');
    }
    if (empty($yearFrom)) {
        $yearFrom = $yearTo;
    }

    $filters = [
        'year_from'  => $yearFrom,
        'year_to'    => $yearTo,
        'month_from' => $monthFrom ?: null,
        'month_to'   => $monthTo ?: null,
        'branch_id'  => $branchId ?: null,
    ];

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
}

require __DIR__ . '/../includes/header.php';
?>

<div class="mb-6">
    <p class="text-sm text-slate-500 dark:text-slate-400">
        Statement of Financial Position (Overall) — view all monthly SOMFP entries
    </p>
</div>

<div class="card mb-6 p-6">
    <form method="GET" class="space-y-4">
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
            <?php
            $years = !empty($availableYears) ? $availableYears : range((int) date('Y'), (int) date('Y') - 5);
            ?>
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
                    <?php foreach ($years as $y): ?>
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
                <label class="mb-1.5 block text-sm font-medium">Year From</label>
                <select name="year_from" class="input-field">
                    <?php foreach ($years as $y): ?>
                    <option value="<?= $y ?>" <?= $y == $yearFrom ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="flex flex-wrap gap-3">
            <button type="submit" class="btn-primary">Apply Filters</button>
            <a href="<?= BASE_URL ?>/sofp/index.php" class="btn-secondary">Reset</a>
        </div>
    </form>
</div>

<?php if (empty($companies)): ?>
<div class="card p-12 text-center">
    <p class="text-slate-500">Register a company and enter SOMFP data first.</p>
    <a href="<?= BASE_URL ?>/companies/create.php" class="btn-primary mt-4">Register Company</a>
</div>
<?php elseif (empty($periodRows)): ?>
<div class="card p-12 text-center">
    <p class="text-slate-500">No SOMFP entries found for the selected filters.</p>
    <a href="<?= BASE_URL ?>/somfp/index.php?company_id=<?= $selectedCompanyId ?>" class="btn-primary mt-4">Enter SOMFP Data</a>
</div>
<?php else: ?>
<div class="card overflow-hidden">
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-6 py-4 dark:border-slate-800">
        <h2 class="font-semibold">Monthly Entries (<?= count($periodRows) ?>)</h2>
        <?php if ($branchId): ?>
        <?php
        $filterBranchName = 'Branch';
        foreach ($branches as $b) {
            if ((int) $b['id'] === $branchId) {
                $filterBranchName = $b['name'];
                break;
            }
        }
        ?>
        <span class="text-sm text-slate-500">Showing: <?= e($filterBranchName) ?></span>
        <?php else: ?>
        <span class="text-sm text-slate-500">Consolidated across all branches</span>
        <?php endif; ?>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-800/50">
                    <th class="px-6 py-3 text-left font-semibold">Period</th>
                    <th class="px-6 py-3 text-center font-semibold">Branches</th>
                    <th class="px-6 py-3 text-right font-semibold">Total Assets (X)</th>
                    <th class="px-6 py-3 text-right font-semibold">Total E&amp;L (Y)</th>
                    <th class="px-6 py-3 text-right font-semibold">Error (X−Y)</th>
                    <th class="px-6 py-3 text-left font-semibold">Last Updated</th>
                    <th class="px-6 py-3 text-right font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                <?php foreach ($periodRows as $row): ?>
                <?php $balanced = abs($row['totals']['error']) < 0.01; ?>
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30">
                    <td class="px-6 py-4 font-medium"><?= e(MONTHS[(int)$row['period_month']]) ?> <?= (int)$row['period_year'] ?></td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex rounded-full bg-brand-100 px-2.5 py-0.5 text-xs font-medium text-brand-700 dark:bg-brand-950 dark:text-brand-300"><?= (int)$row['branch_count'] ?></span>
                    </td>
                    <td class="px-6 py-4 text-right font-mono tabular-nums"><?= format_money($row['totals']['total_assets']) ?></td>
                    <td class="px-6 py-4 text-right font-mono tabular-nums"><?= format_money($row['totals']['total_equity_liabilities']) ?></td>
                    <td class="px-6 py-4 text-right font-mono tabular-nums <?= $balanced ? 'text-emerald-600' : 'text-amber-600' ?>">
                        <?= format_money($row['totals']['error']) ?>
                    </td>
                    <td class="px-6 py-4 text-slate-500"><?= date('d M Y H:i', strtotime($row['last_updated'])) ?></td>
                    <td class="px-6 py-4 text-right">
                        <div class="table-actions">
                            <a href="<?= BASE_URL ?>/sofp/view.php?company_id=<?= $selectedCompanyId ?>&year=<?= (int)$row['period_year'] ?>&month=<?= (int)$row['period_month'] ?><?= $branchId ? '&branch_id=' . $branchId : '' ?>" class="btn-action btn-action-view">View</a>
                            <span class="table-action-sep">|</span>
                            <a href="<?= BASE_URL ?>/somfp/entry.php?company_id=<?= $selectedCompanyId ?>&year=<?= (int)$row['period_year'] ?>&month=<?= (int)$row['period_month'] ?><?= $branchId ? '&branch_id=' . $branchId : '' ?>" class="btn-action btn-action-edit">Edit</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>
