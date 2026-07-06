<?php
require_once __DIR__ . '/config/database.php';

header('Content-Type: text/plain');

try {
    $pdo = getDB();
    $tables = ['users', 'companies', 'branches', 'somfp_entries'];

    foreach ($tables as $table) {
        try {
            $pdo->exec("ALTER TABLE {$table} ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL AFTER updated_at");
            echo "{$table}: deleted_at column added\n";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate column') !== false) {
                echo "{$table}: already migrated\n";
            } else {
                throw $e;
            }
        }
    }

    echo "Soft delete migration complete.\n";
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
    exit(1);
}
