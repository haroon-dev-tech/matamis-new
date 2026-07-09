<?php
$pageTitle = 'SOFP — Statement of Financial Position';
$activeNav = 'sofp';
$requireAuth = true;
require __DIR__ . '/../includes/bootstrap.php';
require __DIR__ . '/../includes/linked_bs.php';

$userId = current_user_id();
$companyId = (int) ($_GET['company_id'] ?? 0);
$branchId = (int) ($_GET['branch_id'] ?? 0);
$year = (int) ($_GET['year'] ?? date('Y'));
$month = (int) ($_GET['month'] ?? date('n'));

$companies = get_accessible_companies($db, $userId, 'sofp');
if (!$companyId && !empty($companies)) {
    $companyId = (int) $companies[0]['id'];
}

$company = null;
$branches = [];
$structure = ['heads' => []];

$previousYear = $year;
$previousMonth = $month - 1;
if ($previousMonth <= 0) {
    $previousMonth = 12;
    $previousYear--;
}

$previousValues = [];
$currentValues = [];
$previousTotals = ['head_totals' => [], 'calculated_total' => 0];
$currentTotals = ['head_totals' => [], 'calculated_total' => 0];
$branchLabel = 'All Branches (Consolidated)';

if ($companyId && can_access_company($db, $companyId, $userId, 'sofp')) {
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

    $structure = get_linked_bs_structure($db, $companyId);

    $previousEntry = get_consolidated_linked_bs_entry_values($db, $companyId, $previousYear, $previousMonth, $branchId ?: null);
    $currentEntry = get_consolidated_linked_bs_entry_values($db, $companyId, $year, $month, $branchId ?: null);

    $previousValues = $previousEntry['values'];
    $currentValues = $currentEntry['values'];
    $previousTotals = calculate_linked_bs_totals($structure, $previousValues);
    $currentTotals = calculate_linked_bs_totals($structure, $currentValues);
}

$hasData = !empty($previousValues) || !empty($currentValues);

require __DIR__ . '/../includes/header.php';
?>

<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <p class="text-sm text-slate-500 dark:text-slate-400">
        Statement of Financial Position (SOFP) — read-only comparison of Linked BS data
    </p>
</div>

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
            <a href="<?= BASE_URL ?>/sofp/index.php<?= $companyId ? '?company_id=' . $companyId : '' ?>" class="btn-secondary whitespace-nowrap">Reset</a>
        </div>
    </form>
</div>

<?php if (empty($companies)): ?>
<div class="card p-12 text-center">
    <p class="text-slate-500">Register a company first to view SOFP data.</p>
    <a href="<?= BASE_URL ?>/companies/create.php" class="btn-primary mt-4">Register Company</a>
</div>
<?php elseif (empty($branches)): ?>
<div class="card p-12 text-center">
    <p class="text-slate-500">This company has no branches.</p>
    <a href="<?= BASE_URL ?>/companies/view.php?id=<?= $companyId ?>" class="btn-primary mt-4">Manage Company</a>
</div>
<?php elseif (empty($structure['heads'])): ?>
<div class="card p-12 text-center">
    <p class="text-slate-500">No Linked BS structure configured for this company.</p>
    <?php if (user_can($db, $userId, 'linked_bs', 'write')): ?>
    <a href="<?= BASE_URL ?>/linked-bs/structure.php?company_id=<?= $companyId ?>" class="btn-primary mt-4">Configure Structure</a>
    <?php endif; ?>
</div>
<?php elseif (!$hasData): ?>
<div class="card p-12 text-center">
    <p class="text-slate-500">No Linked BS data found for the selected periods.</p>
    <?php if (user_can($db, $userId, 'linked_bs', 'write')): ?>
    <a href="<?= BASE_URL ?>/linked-bs/entry.php?company_id=<?= $companyId ?>&year=<?= $year ?>&month=<?= $month ?><?= $branchId ? '&branch_id=' . $branchId : '' ?>" class="btn-primary mt-4">Enter Linked BS Data</a>
    <?php endif; ?>
</div>
<?php else: ?>
<div class="mb-4">
    <h2 class="text-lg font-semibold"><?= e($company['name']) ?></h2>
    <p class="text-sm text-slate-500">
        <?= e($branchLabel) ?> · Comparing <?= e(MONTHS[$previousMonth]) ?> <?= $previousYear ?> and <?= e(MONTHS[$month]) ?> <?= $year ?>
    </p>
</div>

<div class="grid gap-6 lg:grid-cols-2">
    <div class="card overflow-hidden">
        <div class="border-b border-slate-200 px-6 py-4 dark:border-slate-800">
            <h3 class="font-semibold"><?= e(MONTHS[$previousMonth]) ?> <?= $previousYear ?> — Previous Month</h3>
        </div>
        <?php
        $values = $previousValues;
        $totals = $previousTotals;
        $editable = false;
        require __DIR__ . '/../linked-bs/partials/entry_table.php';
        ?>
    </div>
    <div class="card overflow-hidden">
        <div class="border-b border-slate-200 px-6 py-4 dark:border-slate-800">
            <h3 class="font-semibold"><?= e(MONTHS[$month]) ?> <?= $year ?> — Last Month</h3>
        </div>
        <?php
        $values = $currentValues;
        $totals = $currentTotals;
        $editable = false;
        require __DIR__ . '/../linked-bs/partials/entry_table.php';
        ?>
    </div>
</div>
<?php endif; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>
