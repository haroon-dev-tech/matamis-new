<?php
$pageTitle = 'Welcome';
$activeNav = '';
$requireAuth = true;
require __DIR__ . '/includes/bootstrap.php';

$target = get_default_landing_path($db, current_user_id());
if ($target !== '/landing.php') {
    redirect($target);
}

require __DIR__ . '/includes/header.php';
?>

<div class="card p-10 text-center">
    <h2 class="text-xl font-semibold">Welcome</h2>
    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
        Your account is active, but no module access has been assigned yet.
        Please contact an administrator to assign at least one role permission.
    </p>
    <div class="mt-6">
        <a href="<?= BASE_URL ?>/logout.php" class="btn-secondary">Sign Out</a>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>

