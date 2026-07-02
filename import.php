<?php

$host = getenv('MYSQLHOST');
$port = getenv('MYSQLPORT');
$user = getenv('MYSQLUSER');
$password = getenv('MYSQLPASSWORD');
$database = getenv('MYSQLDATABASE');

$sqlFile = __DIR__ . '/config/dump.sql';

if (!file_exists($sqlFile)) {
    die("SQL file not found.");
}

$command = sprintf(
    'mysql -h %s -P %s -u %s -p%s %s < %s',
    escapeshellarg($host),
    escapeshellarg($port),
    escapeshellarg($user),
    $password,
    escapeshellarg($database),
    escapeshellarg($sqlFile)
);

system($command, $result);

if ($result === 0) {
    echo "Database imported successfully.";
} else {
    echo "Import failed.";
}