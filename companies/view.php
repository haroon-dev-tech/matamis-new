<?php
$pageTitle = 'Company Details';
$activeNav = 'companies';
$requireAuth = true;
require __DIR__ . '/../includes/bootstrap.php';

$companyId = (int) ($_GET['id'] ?? 0);
if (!$companyId || !user_owns_company($db, $companyId, current_user_id())) {
    flash('error', 'Company not found.');
    redirect('/companies/index.php');
}

$stmt = $db->prepare('SELECT * FROM companies WHERE id = ? AND ' . not_deleted());
$stmt->execute([$companyId]);
$company = $stmt->fetch();
$branches = get_company_branches($db, $companyId);
$pageTitle = $company['name'];
$logoPath = $company['logo_path'] ?? null;

require __DIR__ . '/../includes/header.php';
?>

<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <a href="<?= BASE_URL ?>/companies/index.php" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 dark:hover:text-slate-300">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/></svg>
        All Companies
    </a>
    <div class="flex gap-2">
        <a href="<?= BASE_URL ?>/observations/index.php?company_id=<?= $companyId ?>" class="btn-secondary">Observations</a>
        <a href="<?= BASE_URL ?>/companies/edit.php?id=<?= $companyId ?>" class="btn-secondary">Edit Company</a>
        <a href="<?= BASE_URL ?>/somfp/entry.php?company_id=<?= $companyId ?>" class="btn-secondary">New SOMFP Entry</a>
        <a href="<?= BASE_URL ?>/somci/entry.php?company_id=<?= $companyId ?>" class="btn-primary">New SOMCI Entry</a>
    </div>
</div>

<div class="grid gap-6 lg:grid-cols-3">
    <div class="card p-6 lg:col-span-1">
        <h2 class="mb-4 font-semibold">Company Information</h2>
        <?php if (!empty($logoPath)): ?>
        <div class="mb-4">
            <img src="<?= e(BASE_URL . $logoPath) ?>" alt="<?= e($company['name']) ?> logo" class="h-24 w-24 rounded-lg border border-slate-200 object-contain p-1 dark:border-slate-700">
        </div>
        <?php endif; ?>
        <dl class="space-y-3 text-sm">
            <div>
                <dt class="text-slate-500">Company Name</dt>
                <dd class="font-medium"><?= e($company['name']) ?></dd>
            </div>
            <?php if ($company['trade_license']): ?>
            <div>
                <dt class="text-slate-500">Trade License</dt>
                <dd class="font-medium"><?= e($company['trade_license']) ?></dd>
            </div>
            <?php endif; ?>
            <?php if ($company['address']): ?>
            <div>
                <dt class="text-slate-500">Address</dt>
                <dd><?= e($company['address']) ?></dd>
            </div>
            <?php endif; ?>
            <div>
                <dt class="text-slate-500">Registered On</dt>
                <dd><?= date('d M Y', strtotime($company['created_at'])) ?></dd>
            </div>
        </dl>
    </div>

    <div class="card lg:col-span-2">
        <div class="border-b border-slate-200 px-6 py-4 dark:border-slate-800">
            <h2 class="font-semibold">Branches (<?= count($branches) ?>)</h2>
        </div>
        <?php if (empty($branches)): ?>
        <div class="p-6 text-center text-sm text-slate-500">No branches registered.</div>
        <?php else: ?>
        <div class="divide-y divide-slate-200 dark:divide-slate-800">
            <?php foreach ($branches as $branch): ?>
            <div class="flex items-center justify-between px-6 py-4">
                <div>
                    <p class="font-medium">
                        <?= e($branch['name']) ?>
                        <?php if ($branch['is_head_office']): ?>
                        <span class="ml-2 inline-flex rounded-full bg-brand-100 px-2 py-0.5 text-xs font-medium text-brand-700 dark:bg-brand-950 dark:text-brand-300">Head Office</span>
                        <?php endif; ?>
                    </p>
                    <?php if ($branch['location']): ?>
                    <p class="text-sm text-slate-500"><?= e($branch['location']) ?></p>
                    <?php endif; ?>
                </div>
                <div class="flex flex-col items-end gap-2">
                    <div class="table-actions">
                        <span class="mr-1 text-xs font-medium text-slate-400">SOMFP</span>
                        <a href="<?= BASE_URL ?>/somfp/view.php?company_id=<?= $companyId ?>&branch_id=<?= $branch['id'] ?>" class="btn-action btn-action-view">View</a>
                        <span class="table-action-sep">|</span>
                        <a href="<?= BASE_URL ?>/somfp/entry.php?company_id=<?= $companyId ?>&branch_id=<?= $branch['id'] ?>" class="btn-action btn-action-edit">Edit</a>
                    </div>
                    <div class="table-actions">
                        <span class="mr-1 text-xs font-medium text-slate-400">SOMCI</span>
                        <a href="<?= BASE_URL ?>/somci/view.php?company_id=<?= $companyId ?>&branch_id=<?= $branch['id'] ?>" class="btn-action btn-action-view">View</a>
                        <span class="table-action-sep">|</span>
                        <a href="<?= BASE_URL ?>/somci/entry.php?company_id=<?= $companyId ?>&branch_id=<?= $branch['id'] ?>" class="btn-action btn-action-edit">Edit</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
