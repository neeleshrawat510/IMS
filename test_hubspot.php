<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

// Load .env only for local development
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

// Load HubSpot Service
require_once __DIR__ . '/includes/HubSpotService.php';

$hubspot = new HubSpotService();

$result = $hubspot->createContact(
    "Abhi",
    "Rawat",
    "neelesh." . time() . "@example.com",
    "9876543210",
    "Baseline",
    "Mohali"

);

echo "<pre>";
print_r($result);
echo "</pre>";  