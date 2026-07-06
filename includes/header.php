<?php

if (!isset($db)) {
    require __DIR__ . '/bootstrap.php';
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> — <?= e(APP_SHORT) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50:  '#f0f7ff',
                            100: '#e0effe',
                            200: '#b9dffd',
                            300: '#7cc4fc',
                            400: '#36a6f8',
                            500: '#0c8ce9',
                            600: '#006fc7',
                            700: '#0159a1',
                            800: '#064b85',
                            900: '#0b3f6e',
                            950: '#072849',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="<?= e(asset_url(APP_LOGO_MARK)) ?>">
    <link rel="apple-touch-icon" href="<?= e(asset_url(APP_LOGO_MARK)) ?>">
    <link rel="stylesheet" href="<?= e(versioned_asset('/assets/css/app.css')) ?>">
    <script>
        (function() {
            const theme = localStorage.getItem('theme') || 'light';
            if (theme === 'dark') document.documentElement.classList.add('dark');
        })();
    </script>
</head>
<body class="h-full bg-slate-50 text-slate-900 antialiased dark:bg-slate-950 dark:text-slate-100 transition-colors duration-200">
<?php if (!empty($currentUser)) {
    require __DIR__ . '/app_start.php';
} ?>
