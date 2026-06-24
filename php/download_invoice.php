<?php
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

if (!file_exists($file)) {
    die("File missing on server");
}

header("Content-Type: application/pdf");
header("Content-Disposition: attachment; filename=\"invoice.pdf\"");
header("Content-Length: " . filesize($file));

readfile($file);
exit;
?>