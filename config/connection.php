<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Load .env only if it exists (local development)
$envPath = __DIR__ . '/../.env';

if (file_exists($envPath)) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
}

// Read values from either .env (local) or Railway Variables
$dbHost = $_ENV['DB_HOST'] ?? getenv('DB_HOST');
$dbUser = $_ENV['DB_USER'] ?? getenv('DB_USER');
$dbPass = $_ENV['DB_PASS'] ?? getenv('DB_PASS');
$dbName = $_ENV['DB_NAME'] ?? getenv('DB_NAME');

$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);

if (!$conn) {
    error_log("DB Connection failed: " . mysqli_connect_error());

    http_response_code(500);

    die(json_encode([
        "status" => "error",
        "message" => "Database connection failed"
    ]));
}