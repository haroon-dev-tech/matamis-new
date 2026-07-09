<?php
$pageTitle = 'SOCI — Statement of Comprehensive Income';
$activeNav = 'soci';
$requireAuth = true;
require __DIR__ . '/../includes/bootstrap.php';
require __DIR__ . '/../includes/linked_is.php';

$userId = current_user_id();
$companyId = (int) ($_GET['company_id'] ?? 0);
$branchId = (int) ($_GET['branch_id'] ?? 0);
$year = (int) ($_GET['year'] ?? date('Y'));
$month = (int) ($_GET['month'] ?? date('n'));

$companies = get_accessible_companies($db, $userId, 'soci');

if (!$companyId && !empty($companies)) {
    $companyId = (int) $companies[0]['id'];
}

$company = null;
$branches = [];
$structure = ['heads' => []];
$previousPeriod = get_previous_period($year, $month);
$previousValues = [];
$currentValues = [];
$previousTotals = ['head_totals' => [], 'net_profit_loss' => 0];
$currentTotals = ['head_totals' => [], 'net_profit_loss' => 0];
$branchLabel = 'All Branches (Consolidated)';

if ($companyId && can_access_company($db, $companyId, $userId, 'soci')) {
    $stmt = $db->prepare('SELECT * FROM companies WHERE id = ? AND ' . not_deleted());
    $stmt->execute([$companyId]);
    $company = $stmt->fetch();
    $branches = get_company_branches($db, $companyId);

    if ($branchId) {
        foreach ($branches as $b) {
            if ((int) $b['id'] === $branchId) {
                $branchLabel = $b['name'];
                break;
            }
        }
    }

    $structure = get_linked_is_structure($db, $companyId);

    $previousEntry = get_consolidated_linked_is_entry_values(
        $db,
        $companyId,
        $previousPeriod['year'],
        $previousPeriod['month'],
        $branchId ?: null
    );
    $currentEntry = get_consolidated_linked_is_entry_values(
        $db,
        $companyId,
        $year,
        $month,
        $branchId ?: null
    );

    $previousValues = $previousEntry['values'];
    $currentValues = $currentEntry['values'];
    $previousTotals = calculate_linked_is_totals($structure, $previousValues);
    $currentTotals = calculate_linked_is_totals($structure, $currentValues);
}

$hasData = !empty($previousValues) || !empty($currentValues);

require __DIR__ . '/../includes/header.php';
?>

<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <p class="text-sm text-slate-500 dark:text-slate-400">
        Statement of Comprehensive Income (SOCI) — read-only comparison of Linked IS data
    </p>
</div>

<?php if (empty($companies)): ?>
<div class="card p-12 text-center">
    <p class="text-slate-500">Register a company first to view SOCI data.</p>
    <a href="<?= BASE_URL ?>/companies/create.php" class="btn-primary mt-4">Register Company</a>
</div>
<?php else: ?>

<div class="card mb-6 p-6">
    <form method="GET" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
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
                <option value="0" <?= $branchId === 0 ? 'selected' : '' ?>>All Branches (Consolidated)</option>
                <?php foreach ($branches as $b): ?>
                <option value="<?= $b['id'] ?>" <?= $b['id'] == $branchId ? 'selected' : '' ?>><?= e($b['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium">Year</label>
            <select name="year" class="input-field">
                <?php for ($y = (int) date('Y'); $y >= (int) date('Y') - 5; $y--): ?>
                <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium">Month</label>
            <select name="month" class="input-field">
                <?php foreach (MONTHS as $m => $label): ?>
                <option value="<?= $m ?>" <?= $m == $month ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="btn-primary w-full">Filter</button>
            <a href="<?= BASE_URL ?>/soci/index.php<?= $companyId ? '?company_id=' . $companyId : '' ?>" class="btn-secondary whitespace-nowrap">Reset</a>
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
<?php elseif (!$hasData): ?>
<div class="card p-12 text-center">
    <p class="text-slate-500">No Linked IS data found for the selected periods.</p>
    <?php if (user_can($db, $userId, 'linked_is', 'write')): ?>
    <a href="<?= BASE_URL ?>/linked-is/entry.php?company_id=<?= $companyId ?>&year=<?= $year ?>&month=<?= $month ?><?= $branchId ? '&branch_id=' . $branchId : '' ?>" class="btn-primary mt-4">Enter Linked IS Data</a>
    <?php endif; ?>
</div>
<?php else: ?>

<div class="mb-4">
    <h2 class="text-lg font-semibold"><?= e($company['name']) ?></h2>
    <p class="text-sm text-slate-500">
        <?= e($branchLabel) ?> · Comparing <?= e(MONTHS[$previousPeriod['month']]) ?> <?= $previousPeriod['year'] ?> and <?= e(MONTHS[$month]) ?> <?= $year ?>
    </p>
</div>

<div class="card overflow-hidden">
    <?php
    $previousYear = $previousPeriod['year'];
    $previousMonth = $previousPeriod['month'];
    $currentYear = $year;
    $currentMonth = $month;
    require __DIR__ . '/../linked-is/partials/compare_entry_table.php';
    ?>
</div>

<?php endif; ?>
<?php endif; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>
