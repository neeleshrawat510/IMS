<?php

require_once "../includes/api_auth.php";
include("../config/connection.php");

//DATA METRICS FOR CARD
$counts = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT
        COUNT(*) AS invoices,
        SUM(payment_status = 'Paid') AS paidInvoices,
        SUM(payment_status = 'Unpaid') AS unpaidInvoices,
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
        DATE_FORMAT(STR_TO_DATE(invoice_date, '%Y-%m-%d'), '%b %Y') AS month,
        YEAR(STR_TO_DATE(invoice_date, '%Y-%m-%d')) AS yr,
        MONTH(STR_TO_DATE(invoice_date, '%Y-%m-%d')) AS mo,
        SUM(grand_total) AS total
    FROM invoices
    GROUP BY 
        month, yr, mo
    ORDER BY 
        yr, mo
");

while ($row = mysqli_fetch_assoc($sql2)) {
    $months[] = $row['month'];
    $revenue[] = (float)$row['total'];
}


//pie chart -Invoice
$status = [
    "Sent" => 0,
    "Draft" => 0,
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