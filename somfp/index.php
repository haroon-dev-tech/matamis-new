<?php
$pageTitle = 'SOMFP — Statement of Financial Position';
$activeNav = 'somfp';
$requireAuth = true;
require __DIR__ . '/../includes/bootstrap.php';
require __DIR__ . '/../includes/linked_bs.php';

$userId = current_user_id();
$companyId = (int) ($_GET['company_id'] ?? 0);
$branchId = (int) ($_GET['branch_id'] ?? 0);
$year = (int) ($_GET['year'] ?? date('Y'));
$month = (int) ($_GET['month'] ?? date('n'));

$companies = get_accessible_companies($db, $userId, 'somfp');
if (!$companyId && !empty($companies)) {
    $companyId = (int) $companies[0]['id'];
}

$company = null;
$branches = [];
$currentBranch = null;
$structure = ['heads' => []];
$values = [];
$totals = ['head_totals' => [], 'calculated_total' => 0];
$entryDate = null;

if ($companyId && can_access_company($db, $companyId, $userId, 'somfp')) {
    $stmt = $db->prepare('SELECT * FROM companies WHERE id = ? AND ' . not_deleted());
    $stmt->execute([$companyId]);
    $company = $stmt->fetch();
    $branches = get_company_branches($db, $companyId);

    if (!empty($branches)) {
        if (!$branchId) {
            $branchId = (int) $branches[0]['id'];
        }

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
        $entryData = get_linked_bs_entry_values($db, $branchId, $year, $month);
        $values = $entryData['values'];
        $entryDate = $entryData['entry_date'];
        $totals = calculate_linked_bs_totals($structure, $values);
    }
}

require __DIR__ . '/../includes/header.php';
?>

<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <p class="text-sm text-slate-500 dark:text-slate-400">
        Statement of Financial Position (SOMFP) — read-only view of Linked BS data
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
        <div class="flex items-end">
            <button type="submit" class="btn-primary w-full">Filter</button>
        </div>
    </form>
</div>

<?php if (empty($companies)): ?>
<div class="card p-12 text-center">
    <p class="text-slate-500">Register a company first to view SOMFP data.</p>
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
<?php else: ?>
<div class="mb-4">
    <h2 class="text-lg font-semibold"><?= e($company['name']) ?> — <?= e($currentBranch['name']) ?></h2>
    <p class="text-sm text-slate-500">
        <?= e(MONTHS[$month]) ?> <?= $year ?> · Statement of Monthly Financial Position
        <?php if ($entryDate): ?> · Entry date: <?= e(date('d M Y', strtotime($entryDate))) ?><?php endif; ?>
    </p>
</div>

<div class="card overflow-hidden">
    <?php
    $editable = false;
    require __DIR__ . '/../linked-bs/partials/entry_table.php';
    ?>
</div>
<?php endif; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>
