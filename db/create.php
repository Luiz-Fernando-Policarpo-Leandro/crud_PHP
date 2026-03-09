<?php

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$host = $_ENV['DB_HOST'] ?? 'localhost';
$user = $_ENV['DB_USER'] ?? 'root';
$pass = $_ENV['DB_PASSWORD'] ?? '';
$db   = $_ENV['DB_NAME'] ?? 'development';

try {

    $pdo = new PDO(
        "mysql:host=$host;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );

    $stmt = $pdo->prepare("
        SELECT SCHEMA_NAME 
        FROM INFORMATION_SCHEMA.SCHEMATA 
        WHERE SCHEMA_NAME = ?
    ");

    $stmt->execute([$db]);

    $exists = $stmt->fetchColumn();

    if ($exists) {

        echo "Database already exists: $db\n";
        exit;

    }

    $pdo->exec("
        CREATE DATABASE `$db`
        CHARACTER SET utf8mb4
        COLLATE utf8mb4_0900_ai_ci;
    ");

    echo "Database created: $db\n";

} catch (PDOException $e) {

    echo "Error: " . $e->getMessage() . "\n";
    exit(1);

}