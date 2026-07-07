<?php
require_once __DIR__ . '/config/database.php';
header('Content-Type: text/plain');
try {
    $pdo = getDB();
    $sql = file_get_contents(__DIR__ . '/database/migrations/add_linked_bs.sql');
    $pdo->exec($sql);
    echo "Linked BS migration complete.\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'already exists') !== false) {
        echo "Linked BS tables already exist.\n";
    } else {
        echo 'Error: ' . $e->getMessage() . "\n";
        exit(1);
    }
}
