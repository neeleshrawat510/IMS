<?php

date_default_timezone_set("Asia/Kolkata");

require_once __DIR__ . "/config/connection.php";
require_once __DIR__ . "/includes/HubSpotService.php";


// Find invoices that need to become Overdue
$result = mysqli_query(
    $conn,
    "SELECT id, hubspot_deal_id
     FROM invoices
     WHERE due_date < CURDATE()
     AND status='Sent'
     AND payment_status='Unpaid'"
);


if (!$result) {
    error_log("Overdue invoice query failed: " . mysqli_error($conn));
    exit;
}


$hubspot = new HubSpotService();


while ($invoice = mysqli_fetch_assoc($result)) {

    $invoiceId = $invoice['id'];
    $hubspotDealId = $invoice['hubspot_deal_id'];


    // 1. Update IMS invoice status
    $updated = mysqli_query(
        $conn,
        "UPDATE invoices
         SET status='Overdue'
         WHERE id='$invoiceId'"
    );


    if (!$updated) {
        error_log(
            "Failed to update invoice $invoiceId to Overdue: " .
            mysqli_error($conn)
        );

        continue;
    }


    // 2. Update HubSpot
    if (!empty($hubspotDealId)) {

        try {

            // Update HubSpot invoice status
            $statusResult = $hubspot->updateDealInvoiceStatus(
                $hubspotDealId,
                'Overdue'
            );

            error_log(
                "HubSpot invoice status Overdue - Deal $hubspotDealId: " .
                json_encode($statusResult)
            );


            // Update HubSpot Deal stage
            $stageResult = $hubspot->updateDealStage(
                $hubspotDealId,
                'Overdue'
            );

            error_log(
                "HubSpot Deal stage Overdue - Deal $hubspotDealId: " .
                json_encode($stageResult)
            );

        } catch (Exception $e) {

            error_log(
                "HubSpot overdue sync failed for invoice $invoiceId: " .
                $e->getMessage()
            );
        }
    }
}

?>