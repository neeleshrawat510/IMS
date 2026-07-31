<?php

require_once __DIR__ . '/../vendor/autoload.php';
date_default_timezone_set('Asia/Kolkata');
// Load .env only for local development
$envFile = __DIR__ . '/../.env';

if (file_exists($envFile)) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
}

$dbHost = getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? null);
$dbUser = getenv('DB_USER') ?: ($_ENV['DB_USER'] ?? null);
$dbPass = getenv('DB_PASS') ?: ($_ENV['DB_PASS'] ?? null);
$dbName = getenv('DB_NAME') ?: ($_ENV['DB_NAME'] ?? null);
$dbPort = getenv('DB_PORT') ?: ($_ENV['DB_PORT'] ?? 3306);

$conn = mysqli_connect(
    $dbHost,
    $dbUser,
    $dbPass,
    $dbName,
    (int)$dbPort
);

echo "<pre>";

echo "DB_HOST: ";
var_dump($dbHost);

echo "DB_USER: ";
var_dump($dbUser);

echo "DB_NAME: ";
var_dump($dbName);

echo "DB_PORT: ";
var_dump($dbPort);

exit;