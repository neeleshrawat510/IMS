<?php
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$conn = mysqli_connect($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

if (!$conn) {
    error_log("DB Connection failed: " . mysqli_connect_error()); // goes to server log, not to visitors
    http_response_code(500);
    die(json_encode(["status" => "error", "message" => "Something went wrong. Please try again later."]));
}
?>