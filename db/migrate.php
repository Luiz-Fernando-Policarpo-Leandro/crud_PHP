<?php

$pdo = require __DIR__ . '/../config/database.php';

$migrationsPath = __DIR__ . '/migrations';

$pdo->exec("
CREATE TABLE IF NOT EXISTS migrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    migration VARCHAR(255) NOT NULL UNIQUE,
    executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
");

$executed = $pdo->query("SELECT migration FROM migrations")
                ->fetchAll(PDO::FETCH_COLUMN);

$files = glob("$migrationsPath/*.php");

sort($files);

$command = $argv[1] ?? "up";

if (in_array($command, ["reset", "migrate"])) {
    $command = $argv[2] ?? "up";
}


if ($command === 'up') {

    foreach ($files as $file) {
        

        $name = basename($file);

        if (!in_array($name, $executed)) {

            echo "Running: $name\n";

            $migration = require $file;

            $pdo->beginTransaction();

            try {

                $pdo->exec($migration['up']);

                $stmt = $pdo->prepare(
                    "INSERT INTO migrations (migration) VALUES (?)"
                );

                $stmt->execute([$name]);

                if ($pdo->inTransaction()) {
                    $pdo->commit();
                }

            } catch (Exception $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                throw $e;

            }
        }
    }

    echo "Migrations complete\n";
}

elseif ($command === 'down') {

    $last = $pdo->query("
        SELECT migration 
        FROM migrations 
        ORDER BY id DESC 
        LIMIT 1
    ")->fetchColumn();

    if (!$last) {
        echo "No migrations to rollback\n";
        exit;
    }

    $file = $migrationsPath . '/' . $last;

    if (!file_exists($file)) {
        throw new Exception("Migration file missing: $last");
    }

    $migration = require $file;

    echo "Rolling back: $last\n";

    $pdo->beginTransaction();

    try {

        $pdo->exec($migration['down']);

        $stmt = $pdo->prepare("
            DELETE FROM migrations
            WHERE migration = ?
        ");

        $stmt->execute([$last]);
        if ($pdo->inTransaction()) {
            $pdo->commit();
        }

    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;

    }

}