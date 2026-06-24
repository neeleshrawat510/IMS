<?php

include("../config/connection.php");

if (!isset($_POST['ids']) || empty($_POST['ids'])) {
    die("No invoices selected");
}

$ids = array_map('intval', $_POST['ids']);

$idList = implode(",", $ids);

$query = "SELECT pdf_path FROM invoices WHERE id IN ($idList)";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("No PDFs found");
}

// Create ZIP
$zip = new ZipArchive();

$zipFileName = "invoices_" . time() . ".zip";
$tempZip = __DIR__ . "/temp/" . $zipFileName;

if ($zip->open($tempZip, ZipArchive::CREATE) !== TRUE) {
    die("Cannot create ZIP file");
}

while ($row = mysqli_fetch_assoc($result)) {

    $filePath = "../" . $row['pdf_path'];

    if (file_exists($filePath)) {

        // Add file to zip
        $zip->addFile($filePath, basename($filePath));
    }
}

$zip->close();

// Download ZIP
header('Content-Type: application/zip');
header('Content-disposition: attachment; filename=' . $zipFileName);
header('Content-Length: ' . filesize($tempZip));

readfile($tempZip);

// Delete temp zip after download
unlink($tempZip);

exit;
?>