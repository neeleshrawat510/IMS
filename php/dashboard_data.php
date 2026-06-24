<?php
//session start
session_start();

if(!isset($_SESSION['user_id'])){
    header("location: index.php");
    exit;
}
//connection
include("../config/connection.php");

//DATA METRICS FOR CARD
$counts = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT
        COUNT(*) AS invoices,
        SUM(status = 'Paid') AS paidInvoices,
        SUM(status = 'Unpaid') AS unpaidInvoices,
        SUM(status = 'Overdue') AS overdueInvoices,
        COALESCE(SUM(grand_total),0) AS totalRevenue
    FROM invoices
"));

$metrics = [
    "invoices" => (int)$counts['invoices'],
    "paidInvoices" => (int)$counts['paidInvoices'],
    "unpaidInvoices" => (int)$counts['unpaidInvoices'],
    "overdueInvoices" => (int)$counts['overdueInvoices'],
    "totalRevenue" => (float)$counts['totalRevenue']
];

//RECENT INVOICES
$invoices = [];

$sql = mysqli_query($conn, "SELECT * FROM `invoices` ORDER BY id DESC LIMIT 10");

while ($row = mysqli_fetch_array($sql)) {
    $invoices[] = $row;
}


//REVENUE CHART

$months = [];
$revenue = [];

$sql2 = mysqli_query($conn, "
    SELECT 
        DATE_FORMAT(STR_TO_DATE(invoice_date, '%Y-%m-%d'), '%b') as month,
        SUM(grand_total) as total
    FROM invoices
    GROUP BY MONTH(STR_TO_DATE(invoice_date, '%Y-%m-%d'))
    ORDER BY MONTH(STR_TO_DATE(invoice_date, '%Y-%m-%d'))
");

while ($row = mysqli_fetch_assoc($sql2)) {
    $months[] = $row['month'];
    $revenue[] = (float)$row['total'];
}


//pie chart -Invoice
$status = [
    "Paid" => 0,
    "Unpaid" => 0,
    "Overdue" => 0
];

$sql3 = mysqli_query($conn, "
    SELECT status, COUNT(*) as total
    FROM invoices
    GROUP BY status
");

while ($row = mysqli_fetch_assoc($sql3)) {
    $status[$row['status']] = (int)$row['total'];
}

echo json_encode([
    "metrics" => $metrics,
    "invoices" => $invoices,
    "charts" => [
        "months" => $months,
        "revenue" => $revenue,
        "status" => $status
    ]
]);