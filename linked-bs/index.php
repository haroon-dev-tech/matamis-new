<?php
$pageTitle = 'Linked BS';
$activeNav = 'linked-bs';
$requireAuth = true;
require __DIR__ . '/../includes/bootstrap.php';
require __DIR__ . '/../includes/linked_bs.php';

$userId = current_user_id();
$companies = get_accessible_companies($db, $userId, 'linked_bs');

require __DIR__ . '/../includes/header.php';
?>

<div class="mb-6">
    <p class="text-sm text-slate-500 dark:text-slate-400">
        Linked Balance Sheet — source data entry for SOMFP &amp; SOFP (read-only reports)
    </p>
</div>

<?php if (empty($companies)): ?>
<div class="card p-12 text-center">
    <p class="text-slate-500">Register a company first to use Linked BS.</p>
    <a href="<?= BASE_URL ?>/companies/create.php" class="btn-primary mt-4">Register Company</a>
</div>
<?php else: ?>

<div class="grid gap-6 md:grid-cols-2">
    <a href="<?= BASE_URL ?>/linked-bs/entry.php" class="card group p-6 transition hover:border-brand-300 dark:hover:border-brand-700">
        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        </div>
        <h2 class="text-lg font-semibold group-hover:text-brand-600 dark:group-hover:text-brand-400">Data Entry</h2>
        <p class="mt-1 text-sm text-slate-500">Enter monthly values per branch with auto-calculated head totals and balance sheet total.</p>
    </a>

    <a href="<?= BASE_URL ?>/linked-bs/structure.php" class="card group p-6 transition hover:border-brand-300 dark:hover:border-brand-700">
        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 text-amber-600 dark:bg-amber-950 dark:text-amber-400">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
        </div>
        <h2 class="text-lg font-semibold group-hover:text-brand-600 dark:group-hover:text-brand-400">Structure Setup</h2>
        <p class="mt-1 text-sm text-slate-500">Configure dynamic heads, sub-headings, and calculated total formula (add/subtract per head).</p>
    </a>
</div>

<div class="card mt-6 overflow-hidden">
    <div class="border-b border-slate-200 px-6 py-4 dark:border-slate-800">
        <h2 class="font-semibold">Companies</h2>
    </div>
    <div class="divide-y divide-slate-200 dark:divide-slate-800">
        <?php foreach ($companies as $co): ?>
        <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-4">
            <p class="font-medium"><?= e($co['name']) ?></p>
            <div class="flex gap-2">
                <a href="<?= BASE_URL ?>/linked-bs/structure.php?company_id=<?= $co['id'] ?>" class="btn-secondary text-xs">Structure</a>
                <a href="<?= BASE_URL ?>/linked-bs/entry.php?company_id=<?= $co['id'] ?>" class="btn-primary text-xs">Enter Data</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php endif; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>
