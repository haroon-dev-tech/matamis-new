<?php
/**
 * One-time database setup script.
 * Visit: http://localhost/matamis/install.php
 * Delete this file after successful installation.
 */
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$messages = [];
$success = false;

try {
    $pdo = getDBConnectionWithoutDatabase();

    $sql = file_get_contents(__DIR__ . '/database/schema.sql');
    $pdo->exec($sql);
    $messages[] = 'Database and tables created successfully.';
    $success = true;
} catch (PDOException $e) {
    $messages[] = 'Error: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install — Mata MIS</title>
    <link rel="icon" type="image/png" href="<?= e(versioned_asset(APP_LOGO)) ?>">
    <link rel="apple-touch-icon" href="<?= e(versioned_asset(APP_LOGO)) ?>">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex min-h-screen items-center justify-center bg-slate-100 p-4">
    <div class="w-full max-w-md rounded-xl bg-white p-8 shadow-lg">
        <div class="mb-6 text-center">
            <img src="<?= e(versioned_asset(APP_LOGO)) ?>" alt="Mata Consultancy logo" class="mx-auto mb-4 h-20 w-20 object-contain">
            <h1 class="text-xl font-bold">Mata MIS Installation</h1>
        </div>
        <?php foreach ($messages as $msg): ?>
        <p class="mb-2 text-sm <?= $success ? 'text-emerald-600' : 'text-red-600' ?>"><?= htmlspecialchars($msg) ?></p>
        <?php endforeach; ?>
        <?php if ($success): ?>
        <div class="mt-4 rounded-lg bg-slate-50 p-4 text-sm">
            <p class="font-medium">Default login:</p>
            <p>Email: <code>admin@mata.ae</code></p>
            <p>Password: <code>admin123</code></p>
        </div>
        <a href="<?= BASE_URL ?>/login.php" class="mt-6 inline-block rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Go to Login</a>
        <p class="mt-4 text-xs text-slate-500">Delete install.php after setup for security.</p>
        <?php endif; ?>
    </div>
</body>
</html>
