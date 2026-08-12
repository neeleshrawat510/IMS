<?php

require_once "../includes/api_auth.php";
include("../config/connection.php");
require_once("../vendor/autoload.php");
require_once "../includes/HubSpotService.php";
require_once "../controller/invoice_pdf.php";
require_once "../controller/send_email.php";

//Indian Timezone
date_default_timezone_set('Asia/Kolkata');

$contact_id = $_POST['contact_id'];
$invoice_no = $_POST['invoice_no'];
$invoice_date = $_POST['invoice_date'];
$due_date = $_POST['due_date'];

$subtotal = $_POST['subtotal'];
$tax_total = $_POST['tax_total'];
$grand_total = $_POST['grand_total'];

// Get items
$product_id = $_POST['product_id'];
$description = $_POST['description'];
$qty = $_POST['qty'];
$price = $_POST['price'];
$tax = $_POST['tax'];
$amount = $_POST['amount'];
$status = $_POST['status'];
$isDraft = ($status === 'Draft');
$dateToday = date('Y-m-d H:i:s');
$created_by = $_SESSION['user_name'];
$invoicePublicToken = bin2hex(random_bytes(32));

// Save invoice 
$insertInvoice = mysqli_query($conn, "
    INSERT INTO invoices 
    (`contact_id`, `invoice_no`, `invoice_date`, `due_date`, `subtotal`, `tax_total`, `grand_total`, `status`, `created_at`, `created_by`, `invoice_public_token`)
    VALUES 
    ('$contact_id', '$invoice_no', '$invoice_date', '$due_date', '$subtotal', '$tax_total', '$grand_total', '$status', '$dateToday', '$created_by', '$invoicePublicToken')
");

// Get invoice ID 
$invoice_id = mysqli_insert_id($conn);

// Get HubSpot Contact ID
$contactQuery = mysqli_query(
    $conn,
    "SELECT hubspot_contact_id
     FROM contacts
     WHERE id='$contact_id'"
);

$contactData = mysqli_fetch_assoc($contactQuery);

$hubspotContactId = $contactData['hubspot_contact_id'] ?? null;

$hubspotDealId = null;

if (!empty($hubspotContactId)) {

    try {

        $hubspot = new HubSpotService();

        $deal = $hubspot->createDeal(
            $invoice_no,
            $grand_total,
            $due_date,
            $invoice_id,
            "Unpaid",
            $status
        );

        if (
            $deal['status'] == 201 &&
            !empty($deal['response']['id'])
        ) {

            $hubspotDealId = $deal['response']['id'];

            $hubspot->associateDealWithContact(
                $hubspotDealId,
                $hubspotContactId
            );

            mysqli_query(
                $conn,
                "UPDATE invoices
         SET hubspot_deal_id='$hubspotDealId'
         WHERE id='$invoice_id'"
            );
        }

    } catch (Exception $e) {

        error_log($e->getMessage());

    }

}

// Save each product (loop)
$count = count($product_id);

for ($i = 0; $i < $count; $i++) {

    $p_id = $product_id[$i];
    $desc = $description[$i];
    $q = $qty[$i];
    $pr = $price[$i];
    $tx = $tax[$i];
    $amt = $amount[$i];


    // Save invoice item in IMS
    $itemInsert = mysqli_query($conn, "
        INSERT INTO invoice_items
        (
            invoice_id,
            product_id,
            description,
            qty,
            price,
            tax,
            amount
        )
        VALUES
        (
            '$invoice_id',
            '$p_id',
            '$desc',
            '$q',
            '$pr',
            '$tx',
            '$amt'
        )
    ");


    // Create HubSpot Line Item
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


            // Only create Line Item if
            // the IMS product already exists in HubSpot
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
                            "HubSpot Line Item associated successfully: " .
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


//skip pdf if status = draft
if ($isDraft) {

    echo json_encode([
        "status" => "success",
        "type" => "draft"
    ]);
    exit;
}



$pdfOutput = generateInvoicePDF($conn, $invoice_id);

$emailSent = sendInvoiceEmail(
    $contact_email,
    $contact_name,
    $invoice_no,
    $invoicePublicToken,
    $pdfOutput
);


if ($emailSent) {

    mysqli_query(
        $conn,
        "UPDATE invoices
         SET email_status='Sent'
         WHERE id='$invoice_id'"
    );

} else {

    mysqli_query(
        $conn,
        "UPDATE invoices
         SET email_status='Failed'
         WHERE id='$invoice_id'"
    );

}

echo json_encode([
    "status" => "success",
    "email_status" => $emailSent
]);

exit;

?>