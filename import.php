<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$host = hayabusa.proxy.rlwy.net;
$port = 59389;
$user = root;
$password = vlGfzDyXIncakRtZQhQlDbQKFVncJwAD;
$database = railway;

$conn = new mysqli($host, $user, $password, $database, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

$sql = file_get_contents(__DIR__ . '/config/dump.sql');

if (!$sql) {
    die("SQL file not found or empty");
}

$conn->query("SET FOREIGN_KEY_CHECKS=0");
$conn->query("SET SQL_MODE=''");

$queries = explode(';', $sql);

foreach ($queries as $query) {
    $query = trim($query);

    if ($query === '') continue;

    if (!$conn->query($query)) {
        echo "<h3>❌ SQL ERROR:</h3>";
        echo "<b>" . $conn->error . "</b><br><br>";
        echo "<pre>" . htmlspecialchars($query) . "</pre>";
        exit;
    }
}

$conn->query("SET FOREIGN_KEY_CHECKS=1");

echo "✅ Import completed successfully!";