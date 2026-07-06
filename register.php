<?php
require_once __DIR__ . '/includes/auth.php';

if (is_logged_in()) {
    redirect('/dashboard.php');
}

$error = handle_register();
$pageTitle = 'Create Account';
require __DIR__ . '/includes/header.php';
?>

<div class="auth-shell flex min-h-screen items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="mb-8 text-center">
            <img src="<?= e(asset_url(APP_LOGO_FULL)) ?>" alt="<?= e(APP_SHORT) ?> logo" class="auth-logo-img mx-auto mb-4 h-14 w-14 object-contain">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Create Account</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Join Mata Consultancy MIS</p>
        </div>

        <div class="card p-8">
            <?php if ($error): ?>
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-950 dark:text-red-200">
                <?= e($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label class="mb-1.5 block text-sm font-medium">Full Name</label>
                    <input type="text" name="full_name" class="input-field" placeholder="Your full name" value="<?= e($_POST['full_name'] ?? '') ?>" required>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium">Email Address</label>
                    <input type="email" name="email" class="input-field" placeholder="you@company.ae" value="<?= e($_POST['email'] ?? '') ?>" required>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium">Password</label>
                    <input type="password" name="password" class="input-field" placeholder="Min. 6 characters" required>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium">Confirm Password</label>
                    <input type="password" name="password_confirm" class="input-field" placeholder="Repeat password" required>
                </div>
                <button type="submit" class="btn-primary w-full">Create Account</button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">
                Already have an account?
                <a href="<?= BASE_URL ?>/login.php" class="font-medium text-brand-600 hover:text-brand-700 dark:text-brand-400">Sign in</a>
            </p>
        </div>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
