<?php
require_once __DIR__ . '/includes/functions.php';
http_response_code(404);

$homePath = is_logged_in() ? '/index.php' : '/login.php';
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found — <?= e(APP_SHORT) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0f7ff',
                            100: '#e0effe',
                            200: '#b9dffd',
                            300: '#7cc4fc',
                            400: '#36a6f8',
                            500: '#0c8ce9',
                            600: '#006fc7',
                            700: '#0159a1',
                            800: '#064b85',
                            900: '#0b3f6e',
                            950: '#072849'
                        }
                    }
                }
            }
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="<?= e(versioned_asset(APP_LOGO)) ?>">
    <link rel="apple-touch-icon" href="<?= e(versioned_asset(APP_LOGO)) ?>">
    <style>
        body { font-family: Inter, system-ui, sans-serif; }
    </style>
    <script>
        (function() {
            const theme = localStorage.getItem('theme') || 'light';
            if (theme === 'dark') document.documentElement.classList.add('dark');
        })();
    </script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased dark:bg-slate-950 dark:text-slate-100">
    <div class="relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-8">
        <div class="pointer-events-none absolute -top-24 -left-24 h-72 w-72 rounded-full bg-brand-300/30 blur-3xl dark:bg-brand-700/20"></div>
        <div class="pointer-events-none absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-violet-300/30 blur-3xl dark:bg-violet-700/20"></div>

        <div class="relative w-full max-w-2xl rounded-3xl border border-slate-200/80 bg-white/90 p-8 shadow-2xl backdrop-blur dark:border-slate-800 dark:bg-slate-900/80 sm:p-10">
            <div class="mb-6 inline-flex items-center gap-3 rounded-full bg-brand-50 px-4 py-2 text-sm font-semibold text-brand-700 dark:bg-brand-950/70 dark:text-brand-300">
                <img src="<?= e(versioned_asset(APP_LOGO)) ?>" alt="<?= e(APP_SHORT) ?> logo" class="h-8 w-8 object-contain">
                <?= e(APP_SHORT) ?>
            </div>

            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-brand-600 dark:text-brand-400">Error 404</p>
            <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-4xl">
                Page not found
            </h1>
            <p class="mt-4 max-w-xl text-sm leading-6 text-slate-600 dark:text-slate-300 sm:text-base">
                The URL you requested does not exist or may have been moved. Please return to a known page from below.
            </p>

            <div class="mt-8 flex flex-wrap items-center gap-3">
                <a href="<?= BASE_URL . $homePath ?>" class="inline-flex items-center justify-center rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-700">
                    Go to Home
                </a>
                <a href="<?= BASE_URL ?>/dashboard.php" class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                    Dashboard
                </a>
            </div>

            <p class="mt-6 text-xs text-slate-500 dark:text-slate-400">
                Requested path:
                <span class="font-mono"><?= e($_SERVER['REQUEST_URI'] ?? '') ?></span>
            </p>
        </div>
    </div>
</body>
</html>

