<?php
require_once __DIR__ . '/config/database.php';

header('Content-Type: text/plain');

try {
    $pdo = getDB();
    $sql = file_get_contents(__DIR__ . '/database/migrations/add_rbac.sql');
    $pdo->exec($sql);
    echo "RBAC migration complete.\n";
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
    exit(1);
}

