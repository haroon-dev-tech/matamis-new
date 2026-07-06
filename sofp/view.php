<?php
$pageTitle = 'SOFP Details';
$activeNav = 'sofp';
$requireAuth = true;
require __DIR__ . '/../includes/bootstrap.php';

$userId = current_user_id();
$companyId = (int) ($_GET['company_id'] ?? 0);
$year = (int) ($_GET['year'] ?? 0);
$month = (int) ($_GET['month'] ?? 0);
$branchId = (int) ($_GET['branch_id'] ?? 0);

if (!$companyId || !$year || !$month || !user_owns_company($db, $companyId, $userId)) {
    flash('error', 'Invalid period or company selected.');
    redirect('/sofp/index.php');
}

$stmt = $db->prepare('SELECT * FROM companies WHERE id = ? AND ' . not_deleted());
$stmt->execute([$companyId]);
$company = $stmt->fetch();
$branches = get_company_branches($db, $companyId);
$somfpConfig = require __DIR__ . '/../config/somfp.php';

if ($branchId) {
    $branch = null;
    foreach ($branches as $b) {
        if ((int) $b['id'] === $branchId) {
            $branch = $b;
            break;
        }
    }
    if (!$branch) {
        flash('error', 'Branch not found.');
        redirect('/sofp/index.php?company_id=' . $companyId);
    }

    $values = get_branch_values($db, $branchId, $year, $month);
    $totals = calculate_somfp_totals($values);
    $branchData = [[
        'branch' => $branch,
        'values' => $values,
        'totals' => $totals,
    ]];
    $consolidated = $values;
} else {
    $branchData = [];
    $consolidated = [];
    foreach ($branches as $branch) {
        $values = get_branch_values($db, (int) $branch['id'], $year, $month);
        if (empty($values)) {
            continue;
        }
        $totals = calculate_somfp_totals($values);
        $branchData[] = [
            'branch' => $branch,
            'values' => $values,
            'totals' => $totals,
        ];
        foreach ($values as $key => $amount) {
            $consolidated[$key] = ($consolidated[$key] ?? 0) + $amount;
        }
    }
}

$consolidatedTotals = calculate_somfp_totals($consolidated);
$pageTitle = 'SOFP — ' . $company['name'] . ' — ' . MONTHS[$month] . ' ' . $year;

require __DIR__ . '/../includes/header.php';
?>

<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <a href="<?= BASE_URL ?>/sofp/index.php?company_id=<?= $companyId ?><?= $branchId ? '&branch_id=' . $branchId : '' ?>" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 dark:hover:text-slate-300">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/></svg>
        Back to SOFP
    </a>
    <div class="flex gap-2">
        <a href="<?= BASE_URL ?>/somfp/view.php?company_id=<?= $companyId ?>&year=<?= $year ?>&month=<?= $month ?>" class="btn-secondary text-sm">SOMFP View</a>
        <a href="<?= BASE_URL ?>/somfp/entry.php?company_id=<?= $companyId ?>&year=<?= $year ?>&month=<?= $month ?>" class="btn-primary text-sm">Edit Entry</a>
    </div>
</div>

<div class="mb-6">
    <h2 class="text-lg font-semibold"><?= e($company['name']) ?></h2>
    <p class="text-sm text-slate-500">
        <?= e(MONTHS[$month]) ?> <?= $year ?> · Statement of Financial Position
        <?php if ($branchId): ?> — <?= e($branch['name']) ?><?php else: ?> — Consolidated<?php endif; ?>
    </p>
</div>

<div class="mb-6 grid gap-4 sm:grid-cols-3">
    <div class="card p-5">
        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Total Assets (X)</p>
        <p class="mt-1 text-2xl font-bold font-mono"><?= format_money($consolidatedTotals['total_assets']) ?></p>
    </div>
    <div class="card p-5">
        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Total Equity & Liabilities (Y)</p>
        <p class="mt-1 text-2xl font-bold font-mono"><?= format_money($consolidatedTotals['total_equity_liabilities']) ?></p>
    </div>
    <div class="card p-5 <?= abs($consolidatedTotals['error']) < 0.01 ? 'border-emerald-300 dark:border-emerald-800' : 'border-amber-300 dark:border-amber-800' ?>">
        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Error (X − Y)</p>
        <p class="mt-1 text-2xl font-bold font-mono <?= abs($consolidatedTotals['error']) < 0.01 ? 'text-emerald-600' : 'text-amber-600' ?>">
            <?= format_money($consolidatedTotals['error']) ?>
        </p>
    </div>
</div>

<?php if (empty($branchData)): ?>
<div class="card p-12 text-center">
    <p class="text-slate-500">No data found for this period.</p>
</div>
<?php else: ?>

<?php if (!$branchId): ?>
<div class="card mb-8 overflow-hidden">
    <div class="border-b border-slate-200 px-6 py-4 dark:border-slate-800">
        <h3 class="font-semibold">Consolidated (All Branches)</h3>
    </div>
    <?php
    $values = $consolidated;
    $editable = false;
    require __DIR__ . '/../somfp/partials/table.php';
    ?>
</div>
<?php endif; ?>

<?php foreach ($branchData as $bd): ?>
<div class="card mb-6 overflow-hidden">
    <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4 dark:border-slate-800">
        <h3 class="font-semibold">
            <?= e($bd['branch']['name']) ?>
            <?php if ($bd['branch']['is_head_office']): ?>
            <span class="ml-2 text-xs font-normal text-brand-600">(Head Office)</span>
            <?php endif; ?>
        </h3>
        <div class="flex items-center gap-4 text-sm">
            <span class="<?= abs($bd['totals']['error']) < 0.01 ? 'text-emerald-600' : 'text-amber-600' ?>">
                Error: <?= format_money($bd['totals']['error']) ?>
            </span>
            <div class="table-actions">
                <a href="<?= BASE_URL ?>/sofp/view.php?company_id=<?= $companyId ?>&year=<?= $year ?>&month=<?= $month ?>&branch_id=<?= $bd['branch']['id'] ?>" class="btn-action btn-action-view">View</a>
                <span class="table-action-sep">|</span>
                <a href="<?= BASE_URL ?>/somfp/entry.php?company_id=<?= $companyId ?>&branch_id=<?= $bd['branch']['id'] ?>&year=<?= $year ?>&month=<?= $month ?>" class="btn-action btn-action-edit">Edit</a>
            </div>
        </div>
    </div>
    <?php
    $values = $bd['values'];
    $editable = false;
    require __DIR__ . '/../somfp/partials/table.php';
    ?>
</div>
<?php endforeach; ?>

<?php endif; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>
