<?php
require_once __DIR__ . '/includes/auth.php';

if (is_logged_in()) {
    redirect('/dashboard.php');
}

$error = handle_login();
$pageTitle = 'Sign In';
require __DIR__ . '/includes/header.php';
?>

<div class="auth-shell auth-shell-login flex min-h-screen items-center justify-center p-4">
    <div class="auth-bg-layer auth-bg-layer--mesh" aria-hidden="true"></div>
    <div class="auth-bg-layer auth-bg-layer--aurora auth-bg-layer--aurora-1" aria-hidden="true"></div>
    <div class="auth-bg-layer auth-bg-layer--aurora auth-bg-layer--aurora-2" aria-hidden="true"></div>
    <div class="auth-bg-layer auth-bg-layer--aurora auth-bg-layer--aurora-3" aria-hidden="true"></div>
    <div class="auth-bg-orb auth-bg-orb--1" aria-hidden="true"></div>
    <div class="auth-bg-orb auth-bg-orb--2" aria-hidden="true"></div>
    <div class="auth-bg-orb auth-bg-orb--3" aria-hidden="true"></div>
    <div class="auth-bg-orb auth-bg-orb--4" aria-hidden="true"></div>
    <div class="auth-bg-beam" aria-hidden="true"></div>
    <div class="auth-grid-overlay" aria-hidden="true"></div>
    <div class="auth-bg-spotlight" aria-hidden="true"></div>

    <div class="auth-login-wrap">
        <div class="auth-logo-wrap mb-8 text-center">
            <img
                src="<?= e(versioned_asset(APP_LOGO)) ?>"
                alt="<?= e(APP_SHORT) ?> logo"
                class="auth-logo-img mx-auto h-24 w-24 object-contain"
                width="96"
                height="96"
            >
            <h1 class="mt-5 text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Mata Consultancy</h1>
            <p class="mt-1 text-xs font-semibold uppercase tracking-[0.2em] text-brand-600 dark:text-brand-400">MIS Platform</p>
            <p class="mt-3 text-sm font-medium text-slate-600 dark:text-slate-300">Management Information System — UAE</p>
        </div>

        <div class="auth-login-card card rounded-2xl p-8">
            <h2 class="mb-1 text-lg font-semibold text-slate-900 dark:text-white">Welcome back</h2>
            <p class="mb-6 text-sm text-slate-500 dark:text-slate-400">Sign in to your account to continue</p>

            <?php if ($error): ?>
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-950 dark:text-red-200">
                <?= e($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label class="mb-1.5 block text-sm font-medium">Email Address</label>
                    <input type="email" name="email" class="input-field" placeholder="you@company.ae" value="<?= e($_POST['email'] ?? '') ?>" required autofocus>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium">Password</label>
                    <input type="password" name="password" class="input-field" placeholder="••••••••" required>
                </div>
                <button type="submit" class="auth-btn-signin btn-primary w-full">Sign In</button>
            </form>
        </div>

        <div class="mt-5 flex items-center justify-center">
            <button id="theme-toggle" type="button" class="auth-theme-btn rounded-xl p-2.5 text-slate-500 dark:text-slate-400" title="Toggle theme" aria-label="Toggle theme">
                <svg class="h-5 w-5 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <svg class="h-5 w-5 block dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
            </button>
        </div>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
