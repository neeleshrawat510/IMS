<?php

require_once "../includes/api_auth.php";
include("../config/connection.php");

// Change this path according to your actual function file location
require_once "../controller/send_email.php";


header("Content-Type: application/json");


$id = intval($_POST['id'] ?? 0);
if (!$id) {

    echo json_encode([
        "status" => false,
        "message" => "Invalid invoice ID."
    ]);

    exit;
}


// Fetch invoice + customer details

$query = mysqli_query($conn, "

    SELECT

        invoices.invoice_no,
        invoices.invoice_public_token,
        invoices.pdf_path,

        contacts.name,
        contacts.email

    FROM invoices

    INNER JOIN contacts

    ON invoices.contact_id = contacts.id

    WHERE invoices.id='$id'

    LIMIT 1

");


if (mysqli_num_rows($query) == 0) {

    echo json_encode([
        "status" => false,
        "message" => "Invoice not found."
    ]);

    exit;

}


$invoice = mysqli_fetch_assoc($query);



// Check customer email

if (empty($invoice['email'])) {

    echo json_encode([
        "status" => false,
        "message" => "Customer email not found."
    ]);

    exit;

}



// Convert database path into server path

$pdfPath = "../" . $invoice['pdf_path'];



// Check PDF exists

if (!file_exists($pdfPath)) {

    echo json_encode([
        "status" => false,
        "message" => "Invoice PDF not found."
    ]);

    exit;

}



// Send Email

$emailSent = sendInvoiceEmail(

    $invoice['email'],

    $invoice['name'],

    $invoice['invoice_no'],

    $invoice['invoice_public_token'],

    $pdfPath

);




// Update status

if ($emailSent) {


    mysqli_query(
        $conn,

        "UPDATE invoices

         SET email_status='Sent'

         WHERE id='$id'"

    );


    echo json_encode([

        "status" => true,

        "message" => "Invoice sent successfully."

    ]);


} else {


    mysqli_query(
        $conn,

        "UPDATE invoices

         SET email_status='Failed'

         WHERE id='$id'"

    );


    echo json_encode([

        "status" => false,

        "message" => "Email sending failed."

    ]);

}


?>