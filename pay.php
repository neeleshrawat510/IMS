<?php

require_once 'config/database.php';

$token = $_GET['token'] ?? '';

if (empty($token)) {
    die("Invalid payment link.");
}

$stmt = $conn->prepare("
    SELECT *
    FROM invoices
    WHERE invoice_public_token = ?
    LIMIT 1
");

$stmt->bind_param("s", $token);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Invalid payment link.");
}

$invoice = $result->fetch_assoc();

echo "<pre>";
print_r($invoice);