<?php

include "config/connection.php";

if (!isset($_GET['token'])) {
    die("Invalid payment link.");
}

$token = $_GET['token'];

$sql = "SELECT * FROM invoices WHERE invoice_public_token = '$token'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    die("Invalid payment link.");
}

$invoice = mysqli_fetch_assoc($result);

echo "<pre>";
print_r($invoice);
echo "</pre>";