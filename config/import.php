<?php
$host = getenv("MYSQLHOST");
$db   = getenv("MYSQLDATABASE");
$user = getenv("MYSQLUSER");
$pass = getenv("MYSQLPASSWORD");
$port = getenv("MYSQLPORT");

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
    $sql = file_get_contents("dump.sql");

    $pdo->exec($sql);

    echo "Imported successfully";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>