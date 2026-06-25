<?php

require("../../config/connection.php");
require("jwt.php");
require_once("../../vendor/autoload.php");

use Dompdf\Dompdf;
use Dompdf\Options;

header("Content-Type: application/json");

//   AUTH CHECK
$headers = getallheaders();

if (!isset($headers['Authorization'])) {
    exit(json_encode(["response" => "error", "message" => "Token Missing"]));
}

$token = str_replace("Bearer ", "", $headers['Authorization']);
$user = verifyJWT($token);

if (!$user) {
    exit(json_encode(["response" => "error", "message" => "Invalid Token"]));
}

$user_id = $user['user_id'];
$user_name = $user['user_name'];

//   INPUT
$data = json_decode(file_get_contents("php://input"), true);

$invoice_id = $_GET['id'] ?? null;

if (!$invoice_id || !is_numeric($invoice_id)) {
    exit(json_encode(["response" => "error", "message" => "Invalid invoice id"]));
}

$invoice_id = mysqli_real_escape_string($conn, $invoice_id);

//   FETCH INVOICE
$invoiceRes = mysqli_query($conn, "SELECT * FROM invoices WHERE id='$invoice_id'");
if (mysqli_num_rows($invoiceRes) == 0) {
    exit(json_encode(["response" => "error", "message" => "Invoice not found"]));
}

$invoice = mysqli_fetch_assoc($invoiceRes);

//   VALIDATION
$errors = [];
$fields = [];

$contact_id = $data['contact_id'] ?? $invoice['contact_id'];
$due_date   = $data['due_date'] ?? $invoice['due_date'];
$status     = $data['status'] ?? $invoice['status'];
$items      = $data['items'] ?? null;

/* Contact */
if (isset($data['contact_id'])) {
    $contact_id = mysqli_real_escape_string($conn, $data['contact_id']);

    $contactRes = mysqli_query($conn, "SELECT * FROM contacts WHERE id='$contact_id'");
    if (mysqli_num_rows($contactRes) == 0) {
        exit(json_encode(["response" => "error", "message" => "Contact not found"]));
    }

    $fields[] = "`contact_id`='$contact_id'";
}

/* Due Date */
if (isset($data['due_date'])) {
    if (!DateTime::createFromFormat('Y-m-d', $due_date)) {
        $errors['due_date'] = "Invalid date format (YYYY-MM-DD)";
    } else {
        $fields[] = "`due_date`='$due_date'";
    }
}

/* Status */
if (isset($data['status'])) {
    if (!preg_match('/^[a-zA-Z ]+$/', $status)) {
        $errors['status'] = "Status must contain only letters";
    } else {
        $fields[] = "`status`='$status'";
    }
}

/* Items */
if (isset($data['items'])) {
    if (!is_array($items) || empty($items)) {
        $errors['items'] = "Items must be a non-empty array";
    }
}

if (!empty($errors)) {
    exit(json_encode(["response" => "error", "errors" => $errors]));
}

//   CALCULATIONS
$subtotal = 0;
$tax_total = 0;
$grand_total = 0;

$productCache = [];

if ($items) {
    foreach ($items as $item) {

        $pid = mysqli_real_escape_string($conn, $item['product_id']);
        $qty = (int)$item['qty'];

        $productRes = mysqli_query($conn, "SELECT * FROM products WHERE id='$pid'");
        if (mysqli_num_rows($productRes) == 0) {
            exit(json_encode(["response" => "error", "message" => "Product not found"]));
        }

        $product = mysqli_fetch_assoc($productRes);
        $productCache[$pid] = $product;

        $price = $product['selling_price'];
        $tax   = $product['tax'];

        $lineSubtotal = $price * $qty;
        $taxAmount = ($lineSubtotal * $tax) / 100;

        $subtotal += $lineSubtotal;
        $tax_total += $taxAmount;
        $grand_total += ($lineSubtotal + $taxAmount);
    }
}

//   UPDATE INVOICE
$fields[] = "`updated_at`=NOW()";
$fields[] = "`updated_by`='$user_name'";

if ($items) {
    $fields[] = "`subtotal`='$subtotal'";
    $fields[] = "`tax_total`='$tax_total'";
    $fields[] = "`grand_total`='$grand_total'";
}

$updateQuery = "UPDATE invoices SET " . implode(",", $fields) . " WHERE id='$invoice_id'";

if (!mysqli_query($conn, $updateQuery)) {
    exit(json_encode(["response" => "error", "message" => "Invoice update failed"]));
}

//   REPLACE ITEMS
if ($items) {

    mysqli_query($conn, "DELETE FROM invoice_items WHERE invoice_id='$invoice_id'");

    foreach ($items as $item) {

        $pid = $item['product_id'];
        $qty = $item['qty'];
        $product = $productCache[$pid];

        $desc = $product['product_name'];
        $price = $product['selling_price'];
        $tax = $product['tax'];

        $amount = $price * $qty;

        mysqli_query($conn, "
            INSERT INTO invoice_items
            (invoice_id, product_id, description, qty, price, tax, amount)
            VALUES
            ('$invoice_id', '$pid', '$desc', '$qty', '$price', '$tax', '$amount')
        ");
    }
}

//   FETCH CONTACT (ONCE)
$contactRes = mysqli_query($conn, "SELECT * FROM contacts WHERE id='$contact_id'");
$contact = mysqli_fetch_assoc($contactRes);

//   FETCH UPDATED INVOICE
$invoiceRes = mysqli_query($conn, "SELECT * FROM invoices WHERE id='$invoice_id'");
$invoice = mysqli_fetch_assoc($invoiceRes);

//   PDF GENERATION

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

$html = "
<h2>Invoice #{$invoice['invoice_no']}</h2>
<p>Customer: {$contact['name']}</p>
<p>Total: {$invoice['grand_total']}</p>
";

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$pdfOutput = $dompdf->output();

$fileName = "invoices/invoice_{$invoice_id}.pdf";
$fullPath = "../../" . $fileName;

file_put_contents($fullPath, $pdfOutput);

/* update pdf path */
mysqli_query($conn, "UPDATE invoices SET pdf_path='$fileName' WHERE id='$invoice_id'");

//   FINAL RESPONSE (ONLY ONCE)
echo json_encode([
    "response" => "success",
    "message" => "Invoice updated successfully",
    "invoice" => [
        "id" => $invoice_id,
        "subtotal" => $subtotal,
        "tax_total" => $tax_total,
        "grand_total" => $grand_total,
        "status" => $status,
        "updated_by" => $user_name
    ],
    "contact" => $contact,
    "pdf" => $fileName
]);

exit;