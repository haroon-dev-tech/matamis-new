<div class="app-shell flex h-full min-h-screen">
    <aside id="sidebar" class="sidebar fixed inset-y-0 left-0 z-40 flex w-[17.5rem] -translate-x-full flex-col transition-transform duration-300 ease-out lg:static lg:translate-x-0">
        <div class="sidebar-brand">
            <div class="sidebar-brand-mark">
                <img src="<?= e(asset_url(APP_LOGO_MARK)) ?>" alt="<?= e(APP_SHORT) ?> logo" class="sidebar-brand-logo">
            </div>
            <div class="min-w-0">
                <p class="truncate text-sm font-semibold tracking-tight text-white">Mata Consultancy</p>
                <p class="truncate text-[11px] font-medium uppercase tracking-wider text-slate-400">Management System</p>
            </div>
        </div>

        <?php $uid = current_user_id(); ?>
        <nav class="sidebar-nav flex-1 overflow-y-auto px-3 py-5">
            <p class="nav-section-label">Overview</p>
            <?php if (user_can($db, $uid, 'dashboard', 'read')): ?>
            <a href="<?= BASE_URL ?>/dashboard.php" class="nav-link <?= $activeNav === 'dashboard' ? 'active' : '' ?>">
                <span class="nav-link-icon">
                    <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </span>
                <span>Dashboard</span>
            </a>
            <?php endif; ?>

            <p class="nav-section-label mt-6">Management</p>
            <?php if (user_can($db, $uid, 'companies', 'read')): ?>
            <a href="<?= BASE_URL ?>/companies/index.php" class="nav-link <?= $activeNav === 'companies' ? 'active' : '' ?>">
                <span class="nav-link-icon">
                    <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </span>
                <span>Companies</span>
            </a>
            <?php endif; ?>
            <?php if (user_can($db, $uid, 'observations', 'read')): ?>
            <a href="<?= BASE_URL ?>/observations/index.php" class="nav-link <?= $activeNav === 'observations' ? 'active' : '' ?>">
                <span class="nav-link-icon">
                    <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </span>
                <span>Observations &amp; Recommendations</span>
            </a>
            <?php endif; ?>

            <p class="nav-section-label mt-6">Data Entry</p>
            <?php if (user_can($db, $uid, 'linked_is', 'read')): ?>
            <a href="<?= BASE_URL ?>/linked-is/index.php" class="nav-link <?= $activeNav === 'linked-is' ? 'active' : '' ?>">
                <span class="nav-link-icon">
                    <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </span>
                <span>Linked IS</span>
                <span class="nav-link-badge">Income</span>
            </a>
            <?php endif; ?>
            <?php if (user_can($db, $uid, 'linked_bs', 'read')): ?>
            <a href="<?= BASE_URL ?>/linked-bs/index.php" class="nav-link <?= $activeNav === 'linked-bs' ? 'active' : '' ?>">
                <span class="nav-link-icon">
                    <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z"/></svg>
                </span>
                <span>Linked BS</span>
                <span class="nav-link-badge">Balance</span>
            </a>
            <?php endif; ?>

            <p class="nav-section-label mt-6">Financial Reports</p>
            <?php if (user_can($db, $uid, 'somfp', 'read')): ?>
            <a href="<?= BASE_URL ?>/somfp/index.php" class="nav-link <?= $activeNav === 'somfp' ? 'active' : '' ?>">
                <span class="nav-link-icon">
                    <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </span>
                <span>SOMFP</span>
                <span class="nav-link-badge">Monthly</span>
            </a>
            <?php endif; ?>
            <?php if (user_can($db, $uid, 'somci', 'read')): ?>
            <a href="<?= BASE_URL ?>/somci/index.php" class="nav-link <?= $activeNav === 'somci' ? 'active' : '' ?>">
                <span class="nav-link-icon">
                    <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </span>
                <span>SOMCI</span>
                <span class="nav-link-badge">Monthly</span>
            </a>
            <?php endif; ?>
            <?php if (user_can($db, $uid, 'sofp', 'read')): ?>
            <a href="<?= BASE_URL ?>/sofp/index.php" class="nav-link <?= $activeNav === 'sofp' ? 'active' : '' ?>">
                <span class="nav-link-icon">
                    <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z"/></svg>
                </span>
                <span>SOFP</span>
                <span class="nav-link-badge">Overall</span>
            </a>
            <?php endif; ?>
            <?php if (user_can($db, $uid, 'soci', 'read')): ?>
            <a href="<?= BASE_URL ?>/soci/index.php" class="nav-link <?= $activeNav === 'soci' ? 'active' : '' ?>">
                <span class="nav-link-icon">
                    <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                </span>
                <span>SOCI</span>
                <span class="nav-link-badge">Overall</span>
            </a>
            <?php endif; ?>

            <p class="nav-section-label mt-6">Insights</p>
            <?php if (user_can($db, $uid, 'glance', 'read')): ?>
            <a href="<?= BASE_URL ?>/glance/index.php" class="nav-link <?= $activeNav === 'glance' ? 'active' : '' ?>">
                <span class="nav-link-icon">
                    <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                </span>
                <span>Glance Picture</span>
            </a>
            <?php endif; ?>

            <?php if (user_can($db, $uid, 'settings_users', 'read') || user_can($db, $uid, 'settings_roles', 'read') || user_can($db, $uid, 'settings_logs', 'read')): ?>
            <p class="nav-section-label mt-6">Settings</p>
            <?php if (user_can($db, $uid, 'settings_users', 'read')): ?>
            <a href="<?= BASE_URL ?>/settings/users.php" class="nav-link <?= $activeNav === 'settings-users' ? 'active' : '' ?>">
                <span class="nav-link-icon">
                    <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1m-4 6H2v-2a4 4 0 014-4h6a4 4 0 014 4v2zM9 10a4 4 0 100-8 4 4 0 000 8zm9-1a3 3 0 10-6 0 3 3 0 006 0z"/></svg>
                </span>
                <span>Users</span>
            </a>
            <?php endif; ?>
            <?php if (user_can($db, $uid, 'settings_roles', 'read')): ?>
            <a href="<?= BASE_URL ?>/settings/roles.php" class="nav-link <?= $activeNav === 'settings-roles' ? 'active' : '' ?>">
                <span class="nav-link-icon">
                    <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a4 4 0 00-4 4v1a3 3 0 003 3h2a3 3 0 003-3v-1a4 4 0 00-4-4zm-7 9h14M7 20h10"/></svg>
                </span>
                <span>Roles</span>
            </a>
            <?php endif; ?>
            <?php if (user_can($db, $uid, 'settings_logs', 'read')): ?>
            <a href="<?= BASE_URL ?>/settings/logs.php" class="nav-link <?= $activeNav === 'settings-logs' ? 'active' : '' ?>">
                <span class="nav-link-icon">
                    <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6m3 6V7m3 10v-3m2 7H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z"/></svg>
                </span>
                <span>Logs</span>
            </a>
            <?php endif; ?>
            <?php endif; ?>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user-card">
                <div class="sidebar-avatar">
                    <?= e(strtoupper(substr($currentUser['full_name'], 0, 1))) ?>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold text-white"><?= e($currentUser['full_name']) ?></p>
                    <p class="truncate text-xs text-slate-400"><?= e($currentUser['email']) ?></p>
                </div>
            </div>
            <a href="<?= BASE_URL ?>/logout.php" class="sidebar-signout">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Sign Out
            </a>
        </div>
    </aside>

    <div id="sidebar-overlay" class="sidebar-overlay fixed inset-0 z-30 hidden lg:hidden"></div>

    <div class="flex min-w-0 flex-1 flex-col">
        <header class="app-header sticky top-0 z-20">
            <div class="flex items-center gap-3">
                <button id="sidebar-toggle" type="button" class="icon-btn lg:hidden" aria-label="Open menu">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400"><?= e(APP_SHORT) ?></p>
                    <h1 class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white"><?= e($pageTitle) ?></h1>
                </div>
            </div>
            <button id="theme-toggle" type="button" class="icon-btn" title="Toggle theme" aria-label="Toggle theme">
                <svg class="h-5 w-5 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <svg class="h-5 w-5 block dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
            </button>
        </header>

        <main class="app-main flex-1 overflow-y-auto">
            <?php if ($flash): ?>
            <div class="flash-message <?= $flash['type'] === 'success' ? 'flash-success' : 'flash-error' ?>">
                <?php if ($flash['type'] === 'success'): ?>
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <?php else: ?>
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <?php endif; ?>
                <?= e($flash['message']) ?>
            </div>
            <?php endif; ?>
