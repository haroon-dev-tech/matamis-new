<?php
$pageTitle = 'Access Denied';
$activeNav = '';
$requireAuth = true;
require __DIR__ . '/includes/bootstrap.php';

require __DIR__ . '/includes/header.php';
?>

<div class="card p-10 text-center">
    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-red-50 text-red-600 dark:bg-red-950 dark:text-red-300">
        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v4m0 4h.01M10.29 3.86l-7.4 12.83A1.5 1.5 0 004.19 19h15.62a1.5 1.5 0 001.3-2.31l-7.4-12.83a1.5 1.5 0 00-2.6 0z"/></svg>
    </div>
    <h2 class="text-xl font-semibold">You don't have access</h2>
    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Your role doesn't permit opening this page or performing this action.</p>
    <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
        <a href="<?= BASE_URL ?>/dashboard.php" class="btn-primary">Go to Dashboard</a>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>

