<?php
require_once __DIR__ . '/config/database.php';
header('Content-Type: text/plain');
try {
    $pdo = getDB();
    $sql = file_get_contents(__DIR__ . '/database/migrations/add_linked_is.sql');
    $pdo->exec($sql);
    echo "Linked IS migration complete.\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'already exists') !== false) {
        echo "Linked IS tables already exist.\n";
    } else {
        echo 'Error: ' . $e->getMessage() . "\n";
        exit(1);
    }
}
