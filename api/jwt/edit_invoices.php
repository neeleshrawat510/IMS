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
$due_date = $data['due_date'] ?? $invoice['due_date'];
$status = $data['status'] ?? $invoice['status'];
$items = $data['items'] ?? null;

/* Contact */
if (isset($data['contact_id'])) {
    $contact_id = mysqli_real_escape_string($conn, $data['contact_id']);

    $contactRes = mysqli_query($conn, "SELECT * FROM contacts WHERE id='$contact_id'");
    if (mysqli_num_rows($contactRes) == 0) {
        echo json_encode(["response" => "error", "message" => "Contact not found"]);
        exit;
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
$subtotal = $invoice['subtotal'];
$tax_total = $invoice['tax_total'];
$grand_total = $invoice['grand_total'];

$productCache = [];

if ($items) {
    $subtotal = 0;
    $tax_total = 0;
    $grand_total = 0;
    foreach ($items as $item) {

        $pid = mysqli_real_escape_string($conn, $item['product_id']);
        $qty = (int) $item['qty'];

        $productRes = mysqli_query($conn, "SELECT * FROM products WHERE id='$pid'");
        if (mysqli_num_rows($productRes) == 0) {
            exit(json_encode(["response" => "error", "message" => "Product not found"]));
        }

        $product = mysqli_fetch_assoc($productRes);
        $productCache[$pid] = $product;

        $price = $product['selling_price'];
        $tax = $product['tax'];

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
//base wt
$baseUrl = "http://localhost/Invoice_management_System/api/jwt/";

//fetch products
$itemRes = mysqli_query($conn, "SELECT * FROM invoice_items WHERE invoice_id='$invoice_id'");

$products = [];

while ($row = mysqli_fetch_assoc($itemRes)) {
    $products[] = [
        "product_id" => $row['product_id'],
        "product_name" => $row['description'],
        "qty" => $row['qty'],
        "price" => $row['price'],
        "tax" => $row['tax'],
        "amount" => $row['amount'],
        "view_details" => $baseUrl . "view_products.php?id=" . $row['product_id']
    ];
}

//   FETCH UPDATED INVOICE
$invoiceRes = mysqli_query($conn, "SELECT * FROM invoices WHERE id='$invoice_id'");
$invoice = mysqli_fetch_assoc($invoiceRes);

$contact_id = $invoice['contact_id'];

//   FETCH CONTACT (ONCE)
$contactRes = mysqli_query($conn, "SELECT * FROM contacts WHERE id='$contact_id'");
$contact = mysqli_fetch_assoc($contactRes);

$check = mysqli_query($conn, "SELECT * FROM invoice_items WHERE invoice_id='$invoice_id'");

if (!$check) {
    die(mysqli_error($conn));
}

if (mysqli_num_rows($check) == 0) {
    die("NO ITEMS FOUND for invoice_id: " . $invoice_id);
}

//   PDF GENERATION

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

$html = '
<style>
    body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
    .header { text-align: center; margin-bottom: 20px; }
    .header h1 { margin: 0; }
    .invoice-box { width: 100%; }

    .info-table td { padding: 4px 0; }

    table { border-collapse: collapse; width: 100%; }

    .items th {
        background: #f2f2f2;
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }

    .items td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }

    .right {
        text-align: right;
    }

    .totals {
        margin-top: 20px;
        width: 40%;
        float: right;
    }

    .totals th, .totals td {
        border: 1px solid #ddd;
        padding: 8px;
    }

    .section {
        margin-bottom: 15px;
    }
</style>

<div class="header">
    <h1>INVOICE</h1>
    <hr>
</div>

<table width="100%" class="info-table">
    <tr>
        <td><b>Invoice No:</b> ' . $invoice['invoice_no'] . '</td>
        <td class="right"><b>Date:</b> ' . $invoice['invoice_date'] . '</td>
    </tr>
    <tr>
        <td><b>Due Date:</b> ' . $invoice['due_date'] . '</td>
        <td></td>
    </tr>
</table>

<br>

<!-- CUSTOMER / COMPANY INFO SECTION -->
<table width="100%" class="section">
    <tr>
        <td width="50%">
            <b>From:</b><br>
            Baselline It Dev<br>
            Mohali<br>
        </td>

        <td width="50%">
            <b>Bill To:</b><br>
            ' . $contact['name'] . ' <br>
            ' . $contact['company'] . ' <br>
            ' . $contact['gst'] . ' <br>
            ' . $contact['number'] . ' | ' . $contact['email'] . '<br>
        </td>
    </tr>
</table>

<br>

<!-- ITEMS TABLE -->
<table class="items">
    <tr>
        <th>Product</th>
        <th>Description</th>
        <th>Qty</th>
        <th>Price</th>
        <th>Tax</th>
        <th>Total</th>
    </tr>
';


$itemResult = mysqli_query($conn, "
SELECT
    ii.*,
    p.product_code
FROM invoice_items ii
LEFT JOIN products p
ON ii.product_id = p.id
WHERE ii.invoice_id='$invoice_id'
");

while ($row = mysqli_fetch_assoc($itemResult)) {

    $html .= "
    <tr>
        <td>{$row['product_code']}</td>
        <td>{$row['description']}</td>
        <td>{$row['qty']}</td>
        <td>{$row['price']}</td>
        <td>{$row['tax']}%</td>
        <td>{$row['amount']}</td>
    </tr>";
}

$html .= '
</table>

<br>

<!-- TOTALS -->
<table class="totals" align="right">
    <tr>
        <th>Subtotal</th>
        <td class="right">' . $invoice['subtotal'] . '</td>
    </tr>
    <tr>
        <th>Tax</th>
        <td class="right">' . $invoice['tax_total'] . '</td>
    </tr>
    <tr>
        <th>Grand Total</th>
        <td class="right"><b>' . $invoice['grand_total'] . '</b></td>
    </tr>
</table>

<div style="clear:both;"></div>
</body>
</html>
';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$pdfOutput = $dompdf->output();

$fileName = "invoices/invoice_{$invoice_id}.pdf";
$fullPath = "../../" . $fileName;

file_put_contents($fullPath, $pdfOutput);

/* update pdf path */
mysqli_query($conn, "UPDATE invoices SET pdf_path = '$fileName' WHERE id = '$invoice_id'");

$invoiceResult = mysqli_query($conn, "SELECT * FROM invoices WHERE `id` = '$invoice_id'");
$invoiceData = mysqli_fetch_assoc($invoiceResult);

//   FINAL RESPONSE (ONLY ONCE)
echo json_encode([
    "response" => "success",
    "message" => "Invoice updated successfully",
    "invoice" => [
        "id" => $invoiceData['id'],
        "subtotal" => $invoiceData['subtotal'],
        "tax_total" => $invoiceData['tax_total'],
        "grand_total" => $invoiceData['grand_total'],
        "status" => $invoiceData['status'],
        "updated_by" => $user_name
    ],

    "Product" => $products,

    "contact" => [
        "contact_id" => $contact['id'],
        "name" => $contact['name'],
        "number" => $contact['number'],
        "view detail" => $baseUrl . "view_contacts.php?id=" . $contact['id']
    ],
    "pdf" => $fileName
]);

exit;