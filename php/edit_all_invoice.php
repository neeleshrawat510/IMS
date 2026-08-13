<?php

require_once "../includes/api_auth.php";

//indian timezone
date_default_timezone_set('Asia/Kolkata');

include("../config/connection.php");

require_once("../vendor/autoload.php");
require_once "../includes/HubSpotService.php";
require_once "../controller/invoice_pdf.php";
require_once "../controller/send_email.php";


// POST DATA

$invoice_id   = $_POST['invoice_id'];

$contact_id   = $_POST['contact_id'];
$invoice_no   = $_POST['invoice_no'];
$invoice_date = $_POST['invoice_date'];
$due_date     = $_POST['due_date'];

$subtotal     = $_POST['subtotal'];
$tax_total    = $_POST['tax_total'];
$grand_total  = $_POST['grand_total'];

$product_id  = $_POST['product_id'];
$description = $_POST['description'];
$qty         = $_POST['qty'];
$price       = $_POST['price'];
$tax         = $_POST['tax'];
$amount      = $_POST['amount'];
$sendEmail = isset($_POST['send_email']) ? (int)$_POST['send_email'] : 0;
$dateToday = date('Y-m-d H:i:s');

$count = count($product_id);
// Get current invoice status
// Get current invoice status and public token
$invoiceQuery = mysqli_query($conn, "
    SELECT status, invoice_public_token
    FROM invoices
    WHERE id = '$invoice_id'
");

$invoice = mysqli_fetch_assoc($invoiceQuery);

$currentStatus = $invoice['status'];
$invoicePublicToken = $invoice['invoice_public_token'];

// If Draft, change to Sent. Otherwise keep existing status.
$newStatus = ($currentStatus == 'Draft') ? 'Sent' : $currentStatus;

if (!$invoice_id) {
    echo json_encode(["status" => "error", "msg" => "Invoice ID missing"]);
    exit;
}
// UPDATE invoice 
mysqli_query($conn, "
    UPDATE invoices SET
        contact_id = '$contact_id',
        invoice_no = '$invoice_no',
        invoice_date = '$invoice_date',
        due_date = '$due_date',
        subtotal = '$subtotal',
        tax_total = '$tax_total',
        grand_total = '$grand_total',
                status = '$newStatus'
    WHERE id = '$invoice_id'
");

// Get invoice ID 
$invoice_id = $_POST['invoice_id'];

// GET HUBSPOT ID
$hubspotQuery = mysqli_query($conn, "
    SELECT
        i.hubspot_deal_id,
        c.hubspot_contact_id
    FROM invoices i
    JOIN contacts c
        ON i.contact_id = c.id
    WHERE i.id = '$invoice_id'
");

$hubspotData = mysqli_fetch_assoc($hubspotQuery);

$hubspotDealId = $hubspotData['hubspot_deal_id'] ?? null;
$hubspotContactId = $hubspotData['hubspot_contact_id'] ?? null;

if (!empty($hubspotDealId)) {

    try {

        $hubspot = new HubSpotService();

        // Update HubSpot Deal
        $hubspot->updateDeal(
            $hubspotDealId,
            $invoice_no,
            $grand_total,
            $due_date,
            $invoice_id,
            $invoice_date,
            $subtotal,
            $tax_total,
            $newStatus
        );

        // Get existing HubSpot Line Items
        $oldLineItems = $hubspot->getDealLineItems($hubspotDealId);

        if (
            $oldLineItems['status'] >= 200 &&
            $oldLineItems['status'] < 300
        ) {

            $existingItems = $oldLineItems['response']['results'] ?? [];

            foreach ($existingItems as $oldItem) {

                $oldLineItemId = $oldItem['toObjectId'] ?? null;

                if (!empty($oldLineItemId)) {

                    $deleteResult = $hubspot->deleteLineItem(
                        $oldLineItemId
                    );

                    if (
                        $deleteResult['status'] >= 200 &&
                        $deleteResult['status'] < 300
                    ) {

                        error_log(
                            "Old HubSpot Line Item deleted: " .
                            $oldLineItemId
                        );

                    } else {

                        error_log(
                            "Failed to delete old HubSpot Line Item: " .
                            json_encode($deleteResult)
                        );
                    }
                }
            }
        }

    } catch (Exception $e) {

        error_log(
            "HubSpot Deal/Line Item sync error: " .
            $e->getMessage()
        );
    }
}

// Save each product (loop)
$count = count($product_id);

//fetch existing items
$oldItemsQuery = mysqli_query($conn, "
    SELECT
        id,
        product_id,
        qty,
        price,
        tax,
        amount,
        hubspot_line_item_id
    FROM invoice_items
    WHERE invoice_id = '$invoice_id'
");

$oldItems = [];

while ($row = mysqli_fetch_assoc($oldItemsQuery)) {
    $oldItems[$row['id']] = $row;
}

mysqli_query($conn, "
    DELETE FROM invoice_items
    WHERE invoice_id = '$invoice_id'
");

for ($i = 0; $i < $count; $i++) {

    $p_id = $product_id[$i];
    $desc = mysqli_real_escape_string($conn, $description[$i]);
    $q    = $qty[$i];
    $pr   = $price[$i];
    $tx   = $tax[$i];
    $amt  = $amount[$i];

    // Save item in IMS
    $itemInsert = mysqli_query($conn, "
        INSERT INTO `invoice_items`
        (`invoice_id`, `product_id`, `description`, `qty`, `price`, `tax`, `amount`)
        VALUES
        ('$invoice_id', '$p_id', '$desc', '$q', '$pr', '$tx', '$amt')
    ");

    // Create new HubSpot Line Item
    if ($itemInsert && !empty($hubspotDealId)) {

        try {

            // Get HubSpot Product ID
            $productQuery = mysqli_query($conn, "
                SELECT hubspot_product_id, product_name
                FROM products
                WHERE id='$p_id'
            ");

            $productData = mysqli_fetch_assoc($productQuery);

            $hubspotProductId =
                $productData['hubspot_product_id'] ?? null;

            $productName =
                $productData['product_name'] ?? $desc;

            // Only create if product exists in HubSpot
            if (!empty($hubspotProductId)) {

                $lineItem = $hubspot->createLineItem(
                    $hubspotProductId,
                    $productName,
                    $q,
                    $pr,
                    $tx
                );

                if (
                    $lineItem['status'] >= 200 &&
                    $lineItem['status'] < 300 &&
                    !empty($lineItem['response']['id'])
                ) {

                    $hubspotLineItemId =
                        $lineItem['response']['id'];

                    // Associate Line Item with Deal
                    $association =
                        $hubspot->associateLineItemWithDeal(
                            $hubspotLineItemId,
                            $hubspotDealId
                        );

                    if (
                        $association['status'] >= 200 &&
                        $association['status'] < 300
                    ) {

                        error_log(
                            "New HubSpot Line Item associated successfully: " .
                            $hubspotLineItemId
                        );

                    } else {

                        error_log(
                            "HubSpot Line Item association failed: " .
                            json_encode($association)
                        );
                    }

                } else {

                    error_log(
                        "HubSpot Line Item creation failed: " .
                        json_encode($lineItem)
                    );
                }

            } else {

                error_log(
                    "HubSpot Product ID not found for IMS Product ID: " .
                    $p_id
                );
            }

        } catch (Exception $e) {

            error_log(
                "HubSpot Line Item sync error: " .
                $e->getMessage()
            );
        }
    }
}

//GET Client's Info

$contactQuery = mysqli_query($conn, "
    SELECT name, company, gst, number, email
    FROM contacts
    WHERE id = '$contact_id'
");

$contact = mysqli_fetch_assoc($contactQuery);

$contact_name = $contact['name'];
$contact_company = $contact['company'];
$contact_gst = $contact['gst'];
$contact_number = $contact['number'];
$contact_email = $contact['email'];



$emailSent = false;

if ($sendEmail == 1) {

    $pdfOutput = generateInvoicePDF($conn, $invoice_id);

    $emailSent = sendInvoiceEmail(
        $contact_email,
        $contact_name,
        $invoice_no,
        $invoicePublicToken,
        $pdfOutput
    );
}

if ($sendEmail == 1) {

    if ($emailSent) {
        mysqli_query($conn, "
            UPDATE invoices
            SET email_status='Sent'
            WHERE id='$invoice_id'
        ");
    } else {
        mysqli_query($conn, "
            UPDATE invoices
            SET email_status='Failed'
            WHERE id='$invoice_id'
        ");
    }
}

echo json_encode([
    "status" => "success",
    "email_status" => $emailSent
]);

exit;

?>
