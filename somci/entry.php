<?php
$pageTitle = 'SOMCI Entry';
$activeNav = 'somci';
$requireAuth = true;
require __DIR__ . '/../includes/bootstrap.php';

$userId = current_user_id();
$companyId = (int) ($_GET['company_id'] ?? $_POST['company_id'] ?? 0);
$branchId = (int) ($_GET['branch_id'] ?? $_POST['branch_id'] ?? 0);
$year = (int) ($_GET['year'] ?? $_POST['year'] ?? date('Y'));
$month = (int) ($_GET['month'] ?? $_POST['month'] ?? date('n'));

if (!$companyId || !user_owns_company($db, $companyId, $userId)) {
    flash('error', 'Please select a valid company.');
    redirect('/somci/index.php');
}

$stmt = $db->prepare('SELECT * FROM companies WHERE id = ? AND ' . not_deleted());
$stmt->execute([$companyId]);
$company = $stmt->fetch();
$branches = get_company_branches($db, $companyId);

if (empty($branches)) {
    flash('error', 'This company has no branches. Add branches first.');
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

$lineItemKeys = get_all_somci_line_item_keys();
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $error = 'Invalid security token.';
    } else {
        $items = $_POST['items'] ?? [];
        try {
            $hasTableStmt = $db->query("SHOW TABLES LIKE 'somci_entries'");
            if (!$hasTableStmt->fetch()) {
                $sql = file_get_contents(__DIR__ . '/../database/migrations/add_somci.sql');
                $db->exec($sql);
            }

            $db->beginTransaction();
            $stmt = $db->prepare(
                'INSERT INTO somci_entries (branch_id, period_year, period_month, line_item_key, amount)
                 VALUES (?, ?, ?, ?, ?)
                 ON DUPLICATE KEY UPDATE amount = VALUES(amount), deleted_at = NULL, updated_at = CURRENT_TIMESTAMP'
            );
            foreach ($lineItemKeys as $key) {
                $raw = $items[$key] ?? '0';
                $amount = (float) str_replace(',', '', $raw);
                $stmt->execute([$branchId, $year, $month, $key, $amount]);
            }
            $db->commit();
            flash('success', 'SOMCI data saved for ' . $currentBranch['name'] . ' — ' . MONTHS[$month] . ' ' . $year);
            redirect('/somci/entry.php?company_id=' . $companyId . '&branch_id=' . $branchId . '&year=' . $year . '&month=' . $month);
        } catch (Exception $e) {
            $db->rollBack();
            $error = 'Failed to save data. Please try again.';
        }
    }
}

$values = get_somci_branch_values($db, $branchId, $year, $month);
$somciConfig = require __DIR__ . '/../config/somci.php';
$pageTitle = 'SOMCI — ' . $company['name'];

require __DIR__ . '/../includes/header.php';
?>

<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <a href="<?= BASE_URL ?>/somci/index.php?company_id=<?= $companyId ?>&year=<?= $year ?>&month=<?= $month ?>" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 dark:hover:text-slate-300">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/></svg>
        Back to SOMCI
    </a>
    <a href="<?= BASE_URL ?>/somci/view.php?company_id=<?= $companyId ?>&year=<?= $year ?>&month=<?= $month ?>" class="btn-secondary text-sm">Consolidated View</a>
</div>

<div class="card mb-6 p-6">
    <form method="GET" class="flex flex-wrap items-end gap-4">
        <input type="hidden" name="company_id" value="<?= $companyId ?>">
        <div class="min-w-[180px] flex-1">
            <label class="mb-1.5 block text-sm font-medium">Branch</label>
            <select name="branch_id" class="input-field" onchange="this.form.submit()">
                <?php foreach ($branches as $b): ?>
                <option value="<?= $b['id'] ?>" <?= $b['id'] == $branchId ? 'selected' : '' ?>><?= e($b['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="w-28">
            <label class="mb-1.5 block text-sm font-medium">Year</label>
            <select name="year" class="input-field">
                <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="w-36">
            <label class="mb-1.5 block text-sm font-medium">Month</label>
            <select name="month" class="input-field">
                <?php foreach (MONTHS as $m => $label): ?>
                <option value="<?= $m ?>" <?= $m == $month ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn-secondary">Load</button>
    </form>
</div>

<div class="mb-4">
    <h2 class="text-lg font-semibold"><?= e($company['name']) ?> — <?= e($currentBranch['name']) ?></h2>
    <p class="text-sm text-slate-500"><?= e(MONTHS[$month]) ?> <?= $year ?> · Statement of Monthly Comprehensive Income</p>
</div>

<?php if ($error): ?>
<div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-950 dark:text-red-200"><?= e($error) ?></div>
<?php endif; ?>

<form method="POST" id="somci-form" class="card overflow-hidden">
    <?= csrf_field() ?>
    <input type="hidden" name="company_id" value="<?= $companyId ?>">
    <input type="hidden" name="branch_id" value="<?= $branchId ?>">
    <input type="hidden" name="year" value="<?= $year ?>">
    <input type="hidden" name="month" value="<?= $month ?>">

    <?php
    $editable = true;
    $prefix = 'items';
    require __DIR__ . '/partials/table.php';
    ?>

    <div class="border-t border-slate-200 px-6 py-4 dark:border-slate-800">
        <div class="flex justify-end gap-3">
            <a href="<?= BASE_URL ?>/somci/index.php?company_id=<?= $companyId ?>&year=<?= $year ?>&month=<?= $month ?>" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Save SOMCI Data</button>
        </div>
    </div>
</form>

<?php require __DIR__ . '/../includes/footer.php'; ?>
