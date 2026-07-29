<?php

require_once "../includes/api_auth.php";

//indian timezone
date_default_timezone_set('Asia/Kolkata');

include("../config/connection.php");

require_once("../vendor/autoload.php");
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
echo json_encode([
    "debug" => [
        "contact_email" => $contact_email,
        "contact_name" => $contact_name,
        "invoice_no" => $invoice_no,
        "invoicePublicToken" => $invoicePublicToken,
        "pdfLength" => strlen($pdfOutput),
        "app_url" => getenv("APP_URL"),
        "brevo_key_exists" => !empty(getenv("BREVO_API_KEY"))
    ]
]);
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

// Save each product (loop)
$count = count($product_id);

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

    mysqli_query($conn, "
        INSERT INTO `invoice_items`
        (`invoice_id`, `product_id`, `description`, `qty`, `price`, `tax`, `amount`)
        VALUES ('$invoice_id', '$p_id', '$desc', '$q', '$pr', '$tx', '$amt')
    ");
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




$pdfOutput = generateInvoicePDF($conn, $invoice_id);


echo "<pre>";
var_dump([
    'contact_email' => $contact_email,
    'contact_name' => $contact_name,
    'invoice_no' => $invoice_no,
    'invoicePublicToken' => $invoicePublicToken,
    'pdfLength' => strlen($pdfOutput)
]);
exit;

$emailSent = sendInvoiceEmail(
    $contact_email,
    $contact_name,
    $invoice_no,
    $invoicePublicToken,
    $pdfOutput
);


if ($emailSent) {

    mysqli_query($conn,
        "UPDATE invoices
         SET email_status='Sent'
         WHERE id='$invoice_id'"
    );

} else {

    mysqli_query($conn,
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
