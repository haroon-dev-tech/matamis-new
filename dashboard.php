<?php
$pageTitle = 'Dashboard';
$activeNav = 'dashboard';
$requireAuth = true;
require __DIR__ . '/includes/bootstrap.php';

$userId = current_user_id();

$stmt = $db->prepare('SELECT COUNT(*) FROM companies WHERE user_id = ? AND ' . not_deleted());
$stmt->execute([$userId]);
$companyCount = (int) $stmt->fetchColumn();

$stmt = $db->prepare(
    'SELECT COUNT(*) FROM branches b
     INNER JOIN companies c ON c.id = b.company_id
     WHERE c.user_id = ? AND c.deleted_at IS NULL AND b.deleted_at IS NULL'
);
$stmt->execute([$userId]);
$branchCount = (int) $stmt->fetchColumn();

$stmt = $db->prepare(
    'SELECT COUNT(DISTINCT CONCAT(se.period_year, "-", se.period_month, "-", se.branch_id))
     FROM somfp_entries se
     INNER JOIN branches b ON b.id = se.branch_id
     INNER JOIN companies c ON c.id = b.company_id
     WHERE c.user_id = ? AND c.deleted_at IS NULL AND b.deleted_at IS NULL AND se.deleted_at IS NULL'
);
$stmt->execute([$userId]);
$somfpCount = (int) $stmt->fetchColumn();

$stmt = $db->prepare(
    'SELECT c.name, c.id, COUNT(b.id) as branch_count
     FROM companies c
     LEFT JOIN branches b ON b.company_id = c.id AND b.deleted_at IS NULL
     WHERE c.user_id = ? AND c.deleted_at IS NULL
     GROUP BY c.id
     ORDER BY c.created_at DESC
     LIMIT 5'
);
$stmt->execute([$userId]);
$recentCompanies = $stmt->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<div class="mb-8">
    <p class="text-slate-500 dark:text-slate-400">Welcome back, <span class="font-medium text-slate-900 dark:text-white"><?= e($currentUser['full_name']) ?></span></p>
</div>

<div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    <div class="card p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Companies</p>
                <p class="mt-1 text-3xl font-bold"><?= $companyCount ?></p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-100 text-brand-600 dark:bg-brand-950 dark:text-brand-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
            </div>
        </div>
    </div>
    <div class="card p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Branches</p>
                <p class="mt-1 text-3xl font-bold"><?= $branchCount ?></p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
        </div>
    </div>
    <div class="card p-6 sm:col-span-2 lg:col-span-1">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">SOMFP Entries</p>
                <p class="mt-1 text-3xl font-bold"><?= $somfpCount ?></p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-violet-100 text-violet-600 dark:bg-violet-950 dark:text-violet-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
        </div>
    </div>
</div>

<div class="grid gap-6 lg:grid-cols-2">
    <div class="card">
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4 dark:border-slate-800">
            <h2 class="font-semibold">Quick Actions</h2>
        </div>
        <div class="grid gap-3 p-6 sm:grid-cols-2">
            <a href="<?= BASE_URL ?>/companies/create.php" class="flex items-center gap-3 rounded-lg border border-slate-200 p-4 transition hover:border-brand-300 hover:bg-brand-50 dark:border-slate-700 dark:hover:border-brand-700 dark:hover:bg-brand-950">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-brand-100 text-brand-600 dark:bg-brand-900 dark:text-brand-300">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <div>
                    <p class="font-medium">Register Company</p>
                    <p class="text-xs text-slate-500">Add company & branches</p>
                </div>
            </a>
            <a href="<?= BASE_URL ?>/somfp/index.php" class="flex items-center gap-3 rounded-lg border border-slate-200 p-4 transition hover:border-brand-300 hover:bg-brand-50 dark:border-slate-700 dark:hover:border-brand-700 dark:hover:bg-brand-950">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-violet-100 text-violet-600 dark:bg-violet-900 dark:text-violet-300">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <p class="font-medium">SOMFP Entry</p>
                    <p class="text-xs text-slate-500">Monthly financial position</p>
                </div>
            </a>
            <a href="<?= BASE_URL ?>/somci/index.php" class="flex items-center gap-3 rounded-lg border border-slate-200 p-4 transition hover:border-brand-300 hover:bg-brand-50 dark:border-slate-700 dark:hover:border-brand-700 dark:hover:bg-brand-950">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 dark:bg-emerald-900 dark:text-emerald-300">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <div>
                    <p class="font-medium">SOMCI Entry</p>
                    <p class="text-xs text-slate-500">Monthly comprehensive income</p>
                </div>
            </a>
        </div>
    </div>

    <div class="card">
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4 dark:border-slate-800">
            <h2 class="font-semibold">Recent Companies</h2>
            <a href="<?= BASE_URL ?>/companies/index.php" class="text-sm text-brand-600 hover:text-brand-700 dark:text-brand-400">View all</a>
        </div>
        <div class="divide-y divide-slate-200 dark:divide-slate-800">
            <?php if (empty($recentCompanies)): ?>
            <div class="p-6 text-center text-sm text-slate-500">
                No companies registered yet.
                <a href="<?= BASE_URL ?>/companies/create.php" class="text-brand-600 hover:underline">Register your first company</a>
            </div>
            <?php else: ?>
            <?php foreach ($recentCompanies as $co): ?>
            <div class="flex items-center justify-between px-6 py-4 transition hover:bg-slate-50 dark:hover:bg-slate-800/50">
                <div>
                    <p class="font-medium"><?= e($co['name']) ?></p>
                    <p class="text-xs text-slate-500"><?= (int)$co['branch_count'] ?> branch<?= $co['branch_count'] != 1 ? 'es' : '' ?></p>
                </div>
                <div class="table-actions">
                    <a href="<?= BASE_URL ?>/companies/view.php?id=<?= $co['id'] ?>" class="btn-action btn-action-view">View</a>
                    <span class="table-action-sep">|</span>
                    <a href="<?= BASE_URL ?>/companies/edit.php?id=<?= $co['id'] ?>" class="btn-action btn-action-edit">Edit</a>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
