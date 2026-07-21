<?php

require_once "../includes/api_auth.php";
include("../config/connection.php");


if (!isset($_GET['id'])) {
    die("Invalid request");
}

$id = intval($_GET['id']);

$result = mysqli_query($conn, "SELECT pdf_path FROM invoices WHERE id = $id");
$row = mysqli_fetch_assoc($result);

if (!$row || empty($row['pdf_path'])) {
    die("PDF not found");
}

$file = "../" . $row['pdf_path'];

echo "DB Path: " . $row['pdf_path'] . "<br>";
echo "Current Directory: " . __DIR__ . "<br>";
echo "Checking File: " . $file . "<br>";
echo "Absolute Path: " . realpath(dirname($file)) . "<br>";

if (!file_exists($file)) {
    die("File missing on server");
}

header("Content-Type: application/pdf");
header("Content-Disposition: inline; filename=\"invoice.pdf\"");

readfile($file);
exit;
?>