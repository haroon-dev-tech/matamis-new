<?php
$pageTitle = 'Linked BS — Data Entry';
$activeNav = 'linked-bs';
$requireAuth = true;
require __DIR__ . '/../includes/bootstrap.php';
require __DIR__ . '/../includes/linked_bs.php';

$userId = current_user_id();
$companyId = (int) ($_GET['company_id'] ?? $_POST['company_id'] ?? 0);
$branchId = (int) ($_GET['branch_id'] ?? $_POST['branch_id'] ?? 0);
$year = (int) ($_GET['year'] ?? $_POST['year'] ?? date('Y'));
$month = (int) ($_GET['month'] ?? $_POST['month'] ?? date('n'));
$entryDate = $_GET['entry_date'] ?? $_POST['entry_date'] ?? date('Y-m-d');

$companies = get_accessible_companies($db, $userId, 'linked_bs');

if (!$companyId && !empty($companies)) {
    $companyId = (int) $companies[0]['id'];
}

if (!$companyId || !can_access_company($db, $companyId, $userId, 'linked_bs')) {
    flash('error', 'Please select a valid company.');
    redirect('/linked-bs/index.php');
}

$stmt = $db->prepare('SELECT * FROM companies WHERE id = ? AND ' . not_deleted());
$stmt->execute([$companyId]);
$company = $stmt->fetch();
$branches = get_company_branches($db, $companyId);

if (empty($branches)) {
    flash('error', 'This company has no branches.');
    redirect('/companies/view.php?id=' . $companyId);
}

if (!$branchId) {
    $branchId = (int) $branches[0]['id'];
}

$currentBranch = null;
foreach ($branches as $b) {
    if ((int) $b['id'] === $branchId) {
        $currentBranch = $b;
        break;
    }
}
if (!$currentBranch) {
    $currentBranch = $branches[0];
    $branchId = (int) $currentBranch['id'];
}

$structure = get_linked_bs_structure($db, $companyId);
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $error = 'Invalid security token.';
    } elseif (empty($structure['heads'])) {
        $error = 'Configure Linked BS structure first.';
    } else {
        try {
            $db->beginTransaction();
            save_linked_bs_entries(
                $db,
                $branchId,
                $year,
                $month,
                $entryDate ?: null,
                $_POST['items'] ?? [],
                $structure
            );
            $db->commit();
            flash('success', 'Linked BS data saved for ' . $currentBranch['name'] . ' — ' . MONTHS[$month] . ' ' . $year);
            redirect('/linked-bs/entry.php?company_id=' . $companyId . '&branch_id=' . $branchId . '&year=' . $year . '&month=' . $month . '&entry_date=' . urlencode($entryDate));
        } catch (Exception $e) {
            $db->rollBack();
            $error = 'Failed to save data. Please try again.';
        }
    }
}

$entryData = get_linked_bs_entry_values($db, $branchId, $year, $month);
$values = $entryData['values'];
if ($entryData['entry_date']) {
    $entryDate = $entryData['entry_date'];
}
$totals = calculate_linked_bs_totals($structure, $values);

$formulaMeta = [];
foreach ($structure['heads'] as $head) {
    $formulaMeta[$head['id']] = $head['formula_operation'] ?? '';
}

$pageTitle = 'Linked BS — ' . $company['name'];
require __DIR__ . '/../includes/header.php';
?>

<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <a href="<?= BASE_URL ?>/linked-bs/index.php" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 dark:hover:text-slate-300">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/></svg>
        Back to Linked BS
    </a>
    <a href="<?= BASE_URL ?>/linked-bs/structure.php?company_id=<?= $companyId ?>" class="btn-secondary text-sm">Configure Structure</a>
</div>

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
        <div>
            <label class="mb-1.5 block text-sm font-medium">Entry Date</label>
            <input type="date" name="entry_date" class="input-field" value="<?= e($entryDate) ?>">
        </div>
        <div class="flex items-end">
            <button type="submit" class="btn-secondary w-full">Load</button>
        </div>
    </form>
</div>

<div class="mb-4">
    <h2 class="text-lg font-semibold"><?= e($company['name']) ?> — <?= e($currentBranch['name']) ?></h2>
    <p class="text-sm text-slate-500"><?= e(MONTHS[$month]) ?> <?= $year ?> · Linked Balance Sheet</p>
</div>

<?php if ($error): ?>
<div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-950 dark:text-red-200"><?= e($error) ?></div>
<?php endif; ?>

<?php if (empty($structure['heads'])): ?>
<div class="card p-12 text-center">
    <p class="text-slate-500">No structure configured for this company.</p>
    <a href="<?= BASE_URL ?>/linked-bs/structure.php?company_id=<?= $companyId ?>" class="btn-primary mt-4">Configure Structure</a>
</div>
<?php else: ?>

<form method="POST" id="linked-bs-form" class="card overflow-hidden"
      data-formula='<?= e(json_encode($formulaMeta)) ?>'>
    <?= csrf_field() ?>
    <input type="hidden" name="company_id" value="<?= $companyId ?>">
    <input type="hidden" name="branch_id" value="<?= $branchId ?>">
    <input type="hidden" name="year" value="<?= $year ?>">
    <input type="hidden" name="month" value="<?= $month ?>">
    <input type="hidden" name="entry_date" value="<?= e($entryDate) ?>">

    <?php
    $editable = true;
    require __DIR__ . '/partials/entry_table.php';
    ?>

    <div class="border-t border-slate-200 px-6 py-4 dark:border-slate-800">
        <div class="flex justify-end gap-3">
            <a href="<?= BASE_URL ?>/linked-bs/index.php" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Save Linked BS Data</button>
        </div>
    </div>
</form>

<script src="<?= e(versioned_asset('/assets/js/linked-bs.js')) ?>"></script>

<?php endif; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>
