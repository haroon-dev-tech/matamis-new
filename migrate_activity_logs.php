<?php
require_once __DIR__ . '/config/database.php';

header('Content-Type: text/plain');

try {
    $pdo = getDB();
    $sql = file_get_contents(__DIR__ . '/database/migrations/add_activity_logs.sql');
    $pdo->exec($sql);
    echo "Activity logs migration complete.\n";
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
    exit(1);
}

