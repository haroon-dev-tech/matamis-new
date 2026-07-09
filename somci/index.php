<?php
$pageTitle = 'SOMCI — Statement of Monthly Comprehensive Income';
$activeNav = 'somci';
$requireAuth = true;
require __DIR__ . '/../includes/bootstrap.php';
require __DIR__ . '/../includes/linked_is.php';

function build_month_periods(int $yearFrom, int $monthFrom, int $yearTo, int $monthTo): array
{
    $periods = [];
    $currentYear = $yearFrom;
    $currentMonth = $monthFrom;

    while (($currentYear < $yearTo) || ($currentYear === $yearTo && $currentMonth <= $monthTo)) {
        $periods[] = [
            'year' => $currentYear,
            'month' => $currentMonth,
            'key' => period_key($currentYear, $currentMonth),
        ];

        $currentMonth++;
        if ($currentMonth > 12) {
            $currentMonth = 1;
            $currentYear++;
        }
    }

    return $periods;
}

$userId = current_user_id();
$companyId = (int) ($_GET['company_id'] ?? 0);
$branchId = (int) ($_GET['branch_id'] ?? 0); // 0 = all branches as separate columns
$yearFrom = (int) ($_GET['year_from'] ?? date('Y'));
$monthFrom = (int) ($_GET['month_from'] ?? 1);
$yearTo = (int) ($_GET['year_to'] ?? date('Y'));
$monthTo = (int) ($_GET['month_to'] ?? date('n'));

$months = array_keys(MONTHS);
if (!in_array($monthFrom, $months, true)) {
    $monthFrom = 1;
}
if (!in_array($monthTo, $months, true)) {
    $monthTo = (int) date('n');
}

$normalized = normalize_report_period_filters($yearFrom, $yearTo, $monthFrom, $monthTo);
$yearFrom = (int) ($normalized['year_from'] ?: date('Y'));
$yearTo = (int) ($normalized['year_to'] ?: date('Y'));
$monthFrom = (int) ($normalized['month_from'] ?: 1);
$monthTo = (int) ($normalized['month_to'] ?: 12);

$companies = get_accessible_companies($db, $userId, 'somci');
if (!$companyId && !empty($companies)) {
    $companyId = (int) $companies[0]['id'];
}

$company = null;
$branches = [];
$structure = ['heads' => []];
$selectedBranches = [];
$periods = [];
$matrixValues = []; // [periodKey][branchId][lineItemId] => amount

if ($companyId && can_access_company($db, $companyId, $userId, 'somci')) {
    $stmt = $db->prepare('SELECT * FROM companies WHERE id = ? AND ' . not_deleted());
    $stmt->execute([$companyId]);
    $company = $stmt->fetch();
    $branches = get_company_branches($db, $companyId);
    $structure = get_linked_is_structure($db, $companyId);

    if (!empty($branches)) {
        if ($branchId > 0) {
            foreach ($branches as $branch) {
                if ((int) $branch['id'] === $branchId) {
                    $selectedBranches[] = $branch;
                    break;
                }
            }
            if (empty($selectedBranches)) {
                $branchId = 0;
            }
        }
        if ($branchId === 0) {
            $selectedBranches = $branches;
        }

        $periods = build_month_periods($yearFrom, $monthFrom, $yearTo, $monthTo);

        if (!empty($periods) && !empty($selectedBranches)) {
            $branchIds = array_map(static function ($b) {
                return (int) $b['id'];
            }, $selectedBranches);

            $minPeriod = $periods[0];
            $maxPeriod = $periods[count($periods) - 1];

            $placeholders = implode(',', array_fill(0, count($branchIds), '?'));
            $sql = "SELECT se.period_year, se.period_month, se.branch_id, se.line_item_id, SUM(se.amount) AS total_amount
                    FROM linked_is_entries se
                    JOIN branches b ON b.id = se.branch_id
                    WHERE b.company_id = ?
                      AND b.deleted_at IS NULL
                      AND se.deleted_at IS NULL
                      AND se.branch_id IN ({$placeholders})
                      AND (se.period_year * 100 + se.period_month) BETWEEN ? AND ?
                    GROUP BY se.period_year, se.period_month, se.branch_id, se.line_item_id";

            $params = array_merge(
                [$companyId],
                $branchIds,
                [period_key((int) $minPeriod['year'], (int) $minPeriod['month']), period_key((int) $maxPeriod['year'], (int) $maxPeriod['month'])]
            );

            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            foreach ($stmt->fetchAll() as $row) {
                $periodKey = period_key((int) $row['period_year'], (int) $row['period_month']);
                $bId = (int) $row['branch_id'];
                $itemId = (int) $row['line_item_id'];
                $matrixValues[$periodKey][$bId][$itemId] = (float) $row['total_amount'];
            }
        }
    }
}

require __DIR__ . '/../includes/header.php';
?>

<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <p class="text-sm text-slate-500 dark:text-slate-400">
        Statement of Monthly Comprehensive Income (SOMCI) — Linked IS read-only matrix by period and branch
    </p>
</div>

<?php if (empty($companies)): ?>
<div class="card p-12 text-center">
    <p class="text-slate-500">Register a company first to view SOMCI data.</p>
    <a href="<?= BASE_URL ?>/companies/create.php" class="btn-primary mt-4">Register Company</a>
</div>
<?php else: ?>

<div class="card mb-6 p-6">
    <form method="GET" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
        <div>
            <label class="mb-1.5 block text-sm font-medium">Company</label>
            <select name="company_id" class="input-field" onchange="this.form.submit()">
                <?php foreach ($companies as $co): ?>
                <option value="<?= $co['id'] ?>" <?= $co['id'] == $companyId ? 'selected' : '' ?>><?= e($co['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium">Branch</label>
            <select name="branch_id" class="input-field">
                <option value="0" <?= $branchId === 0 ? 'selected' : '' ?>>All Branches (Separate Columns)</option>
                <?php foreach ($branches as $b): ?>
                <option value="<?= $b['id'] ?>" <?= (int) $b['id'] === $branchId ? 'selected' : '' ?>><?= e($b['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium">Month From</label>
            <select name="month_from" class="input-field">
                <?php foreach (MONTHS as $m => $label): ?>
                <option value="<?= $m ?>" <?= $m == $monthFrom ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium">Year From</label>
            <select name="year_from" class="input-field">
                <?php for ($y = (int) date('Y'); $y >= (int) date('Y') - 5; $y--): ?>
                <option value="<?= $y ?>" <?= $y == $yearFrom ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium">Month To</label>
            <select name="month_to" class="input-field">
                <?php foreach (MONTHS as $m => $label): ?>
                <option value="<?= $m ?>" <?= $m == $monthTo ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium">Year To</label>
            <select name="year_to" class="input-field">
                <?php for ($y = (int) date('Y'); $y >= (int) date('Y') - 5; $y--): ?>
                <option value="<?= $y ?>" <?= $y == $yearTo ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="sm:col-span-2 lg:col-span-3 xl:col-span-6 flex gap-3">
            <button type="submit" class="btn-primary">Apply Filters</button>
            <a href="<?= BASE_URL ?>/somci/index.php?company_id=<?= $companyId ?>" class="btn-secondary">Reset</a>
        </div>
    </form>
</div>

<?php if (empty($branches)): ?>
<div class="card p-12 text-center">
    <p class="text-slate-500">This company has no branches.</p>
    <a href="<?= BASE_URL ?>/companies/view.php?id=<?= $companyId ?>" class="btn-primary mt-4">Manage Company</a>
</div>
<?php elseif (empty($structure['heads'])): ?>
<div class="card p-12 text-center">
    <p class="text-slate-500">No Linked IS structure configured for this company.</p>
    <?php if (user_can($db, $userId, 'linked_is', 'write')): ?>
    <a href="<?= BASE_URL ?>/linked-is/structure.php?company_id=<?= $companyId ?>" class="btn-primary mt-4">Configure Structure</a>
    <?php endif; ?>
</div>
<?php elseif (empty($periods) || empty($selectedBranches)): ?>
<div class="card p-12 text-center">
    <p class="text-slate-500">No period or branch selected for reporting.</p>
</div>
<?php else: ?>

<div class="mb-4">
    <h2 class="text-lg font-semibold"><?= e($company['name']) ?></h2>
    <p class="text-sm text-slate-500">
        Period: <?= e(MONTHS[$monthFrom]) ?> <?= $yearFrom ?> to <?= e(MONTHS[$monthTo]) ?> <?= $yearTo ?>
        <?php if ($branchId === 0): ?> · All branches in separate columns<?php endif; ?>
    </p>
</div>

<div class="card overflow-x-auto">
    <table class="min-w-full text-sm whitespace-nowrap">
        <thead>
            <tr class="border-b border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-800/50">
                <th rowspan="2" class="sticky left-0 z-20 border-r border-slate-200 bg-slate-50 px-4 py-3 text-left font-semibold w-16 dark:border-slate-800 dark:bg-slate-800/50">#</th>
                <th rowspan="2" class="sticky left-[64px] z-20 border-r border-slate-200 bg-slate-50 px-4 py-3 text-left font-semibold min-w-[240px] dark:border-slate-800 dark:bg-slate-800/50">Particulars</th>
                <?php foreach ($periods as $periodIndex => $period): ?>
                <th colspan="<?= count($selectedBranches) ?>" class="px-4 py-3 text-center font-semibold border-r border-slate-300 dark:border-slate-700 <?= $periodIndex % 2 === 0 ? 'bg-slate-100 dark:bg-slate-800/60' : 'bg-slate-50 dark:bg-slate-800/40' ?>">
                    <?= e(MONTHS[(int) $period['month']]) ?>-<?= (int) $period['year'] ?>
                </th>
                <?php endforeach; ?>
            </tr>
            <tr class="border-b border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-800/50">
                <?php foreach ($periods as $periodIndex => $period): ?>
                    <?php foreach ($selectedBranches as $branchIndex => $branch): ?>
                    <th class="px-3 py-2 text-center text-xs font-semibold text-slate-700 dark:text-slate-300 <?= $branchIndex === count($selectedBranches) - 1 ? 'border-r border-slate-300 dark:border-slate-700' : '' ?> <?= $periodIndex % 2 === 0 ? 'bg-slate-100 dark:bg-slate-800/60' : 'bg-slate-50 dark:bg-slate-800/40' ?>">
                        <?= e($branch['name']) ?><?php if (!empty($branch['is_head_office'])): ?> <span class="text-brand-600">(HO)</span><?php endif; ?>
                    </th>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            <?php foreach ($structure['heads'] as $head): ?>
            <tr class="somfp-row-section">
                <td class="sticky left-0 z-10 border-r border-slate-200 bg-white px-4 py-3 dark:border-slate-800 dark:bg-slate-900"><?= e($head['head_number'] ?: '') ?></td>
                <td class="sticky left-[64px] z-10 border-r border-slate-200 bg-white px-4 py-3 font-semibold dark:border-slate-800 dark:bg-slate-900"><?= e($head['label']) ?></td>
                <?php foreach ($periods as $period): ?>
                    <?php
                    $periodKey = (int) $period['key'];
                    foreach ($selectedBranches as $branchIndex => $branch):
                        $branchValues = $matrixValues[$periodKey][(int) $branch['id']] ?? [];
                        $headTotal = 0.0;
                        foreach ($head['items'] as $item) {
                            $headTotal += (float) ($branchValues[(int) $item['id']] ?? 0);
                        }
                    ?>
                    <td class="px-3 py-3 text-right font-mono tabular-nums font-semibold <?= $branchIndex === count($selectedBranches) - 1 ? 'border-r border-slate-300 dark:border-slate-700' : '' ?>"><?= format_money($headTotal) ?></td>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tr>

            <?php foreach ($head['items'] as $item): ?>
            <tr>
                <td class="sticky left-0 z-10 border-r border-slate-200 bg-white px-4 py-2 pl-6 text-slate-500 dark:border-slate-800 dark:bg-slate-900"><?= e($item['item_number'] ?: '') ?></td>
                <td class="sticky left-[64px] z-10 border-r border-slate-200 bg-white px-4 py-2 text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300"><?= e($item['label']) ?></td>
                <?php foreach ($periods as $period): ?>
                    <?php
                    $periodKey = (int) $period['key'];
                    foreach ($selectedBranches as $branchIndex => $branch):
                        $amount = (float) ($matrixValues[$periodKey][(int) $branch['id']][(int) $item['id']] ?? 0);
                    ?>
                    <td class="px-3 py-2 text-right font-mono tabular-nums <?= $branchIndex === count($selectedBranches) - 1 ? 'border-r border-slate-300 dark:border-slate-700' : '' ?>">
                        <?= abs($amount) < 0.00001 ? '—' : format_money($amount) ?>
                    </td>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
            <?php endforeach; ?>

            <tr class="somci-row-final">
                <td class="sticky left-0 z-10 border-r border-slate-200 bg-white px-4 py-3 dark:border-slate-800 dark:bg-slate-900"></td>
                <td class="sticky left-[64px] z-10 border-r border-slate-200 bg-white px-4 py-3 font-bold dark:border-slate-800 dark:bg-slate-900">Net Profit / Loss</td>
                <?php foreach ($periods as $period): ?>
                    <?php
                    $periodKey = (int) $period['key'];
                    foreach ($selectedBranches as $branchIndex => $branch):
                        $branchValues = $matrixValues[$periodKey][(int) $branch['id']] ?? [];
                        $branchTotals = calculate_linked_is_totals($structure, $branchValues);
                    ?>
                    <td class="px-3 py-3 text-right font-mono tabular-nums font-bold <?= $branchTotals['net_profit_loss'] >= 0 ? 'text-emerald-600' : 'text-red-600' ?> <?= $branchIndex === count($selectedBranches) - 1 ? 'border-r border-slate-300 dark:border-slate-700' : '' ?>">
                        <?= format_money($branchTotals['net_profit_loss']) ?>
                    </td>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tr>
        </tbody>
    </table>
</div>

<?php endif; ?>
<?php endif; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>
