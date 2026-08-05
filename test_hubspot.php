<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

// Load .env only if it exists (for local development)
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

$accessToken = $_ENV['HUBSPOT_ACCESS_TOKEN'] ?? getenv('HUBSPOT_ACCESS_TOKEN');

if (!$accessToken) {
    die("HUBSPOT_ACCESS_TOKEN not found.");
}


$url = "https://api.hubapi.com/crm/v3/objects/contacts";

$data = [
    "properties" => [
        "firstname" => "Neelesh",
        "lastname"  => "Rawat",
        "email"     => "neelesh." . time() . "@example.com",
        "phone"     => "9876543210"
    ]
];

$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer " . $accessToken,
        "Content-Type: application/json"
    ],
    CURLOPT_POSTFIELDS => json_encode($data)
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    die("cURL Error: " . curl_error($ch));
}

curl_close($ch);

echo "<h2>HTTP Status: {$httpCode}</h2>";

echo "<pre>";
print_r(json_decode($response, true));
echo "</pre>";