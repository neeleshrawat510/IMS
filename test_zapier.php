<?php

$webhookUrl = $_ENV['ZAPIER_WEBHOOK_URL'] ?? getenv('ZAPIER_WEBHOOK_URL');
;

$data = [
    "name" => "Akash Test ",
    "email" => "akashverma9780@gmail.com",
    "phone" => "9876543210"
];

$ch = curl_init($webhookUrl);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

echo $response;

curl_close($ch);