<?php

require "../../config/connection.php";
require "jwt.php";

//include DOMPDF library
require_once("../../vendor/autoload.php");

use Dompdf\Dompdf;
use Dompdf\Options;


header("Content-Type: application/json");

//auth check
$headers = getallheaders();

if (!isset($headers['Authorization'])) {
    echo json_encode([
        "response" => "error",
        "message" => "Token Missing"
    ]);
    exit;
}

$token = str_replace("Bearer ", "", $headers['Authorization']);
$user = verifyJWT($token);

if (!$user) {
    echo json_encode([
        "response" => "error",
        "message" => "Invalid Token"
    ]);
    exit;
}

$user_id = $user['user_id'];
$user_name = $user['user_name'];


//input data
$data = json_decode(file_get_contents("php://input"), true);
$invoice_id = $data['invoice_id'] ?? '';
$contact_id = $data['contact_id'] ?? '';
$invoice_no = $data['invoice_no'] ?? '';
$invoice_date = date('Y-m-d');
$due_date = $data['due_date'] ?? '';
$status = $data['status'] ?? '';
$items = $data['items'] ?? [];

$created_at = date('Y-m-d H:i:s');


//validation
$errors = [];

if (empty($contact_id))
    $errors['contact_id'] = "Contact is required";

//fetch contacts
$contactQuery = mysqli_query($conn, "SELECT * FROM contacts WHERE id = '$contact_id'");

if (mysqli_num_rows($contactQuery) == 0) 
    $errors['invoice_no'] = "Contact not found for this id";

if (empty($invoice_no))
    $errors['invoice_no'] = "Invoice number is required";

//Check for duplicate invoice
$check = mysqli_query($conn, "SELECT id FROM invoices WHERE invoice_no = '$invoice_no'");

if (mysqli_num_rows($check) > 0)
     $errors['invoice_no'] = "Invoice no. already exist";

if (empty($due_date))
    $errors['due_date'] = "Due date is required";
if (empty($status))
    $errors['status'] = "Status is required";
if (empty($items))
    $errors['items'] = "Items are required";

if (!empty($errors)) {
    echo json_encode([
        "response" => "error",
        "errors" => $errors
    ]);
    exit;
}


$contact = mysqli_fetch_assoc($contactQuery);


//calculate totals
$subtotal = 0;
$tax_total = 0;
$grand_total = 0;

$productCache = [];

foreach ($items as $item) {
    $product_id = $item['product_id'];
    $qty = $item['qty'];

    $productQuery = mysqli_query($conn, "SELECT * FROM products WHERE id='$product_id'");

    if (mysqli_num_rows($productQuery) == 0) {
        echo json_encode([
            "Response" => "Error",
            "Message" => "Product not found for this id"
        ]);
        exit;
    }

    $product = mysqli_fetch_array($productQuery);

    $price = $product['selling_price'];
    $tax = $product['tax'];

    //auto calculate
    $lineSubtotal = $price * $qty;
    $taxAmount = ($lineSubtotal * $tax) / 100;
    $lineTotal = $lineSubtotal;

    $subtotal += $lineSubtotal;
    $tax_total += $taxAmount;

    $grand_total += $lineTotal + $taxAmount;


    $productCache[$product_id] = $product;


}

$insertInvoice = mysqli_query($conn, "INSERT INTO `invoices` (`contact_id`, `invoice_no`, `invoice_date`, `due_date`, `subtotal`, `tax_total`, `grand_total`, `status`, `created_at`, `created_by`)
                                        VALUES ('$contact_id', '$invoice_no', '$invoice_date', '$due_date', '$subtotal', '$tax_total', '$grand_total', '$status', '$created_at', '$user_name')
                                        ");

if (!$insertInvoice) {
    echo json_encode([
        "Response" => "Error",
        "Message" => "Failed to create Invoice"
    ]);
    exit;
}

//store invoice ID
$invoice_id = mysqli_insert_id($conn);


//insertedItems

$insertedItems = [];

foreach ($items as $item) {

    $product_id = $item['product_id'];
    $qty = $item['qty'];

    $product = $productCache[$product_id];

    $description = $product['product_name'];
    $price = $product['selling_price'];
    $tax = $product['tax'];

    $lineSubtotal = $qty * $price;
    $amount = $lineSubtotal;

    //insert Invoice Items

    $insertInvoiceItems = mysqli_query($conn, "INSERT INTO `invoice_items` (`invoice_id`, `product_id`, `description`, `qty`, `price`, `tax`, `amount`)
                                            VALUES('$invoice_id', '$product_id', '$description', '$qty', '$price', '$tax', '$amount')");

    if (!$insertInvoiceItems) {
        echo json_encode([
            "Response" => "Error",
            "Message" => "Failed to save Invoice Items"
        ]);
        exit;
    }

    $insertedItems[] = [
        "product_id" => $product_id,
        "product_name" => $description,
        "price" => $price,
        "qty" => $qty,
        "tax" => $tax,
        "amount" => $amount
    ];
}

echo json_encode([
    "response" => "success",
    "message" => "Invoice Created Successfully",

    "invoice" => [
        "invoice_id" => $invoice_id,
        "invoice_no" => $invoice_no,
        "invoice_date" => $invoice_date,
        "due_date" => $due_date,
        "subtotal" => $subtotal,
        "tax_total" => $tax_total,
        "grand_total" => $grand_total,
        "status" => $status,
        "created_by" => $user_name
    ],

    "contact" => [
        "id" => $contact['id'],
        "name" => $contact['name'],
        "email" => $contact['email'],
        "number" => $contact['number']
    ],

    "items" => $insertedItems
]);



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




// SAVING PDF

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
        <td><b>Invoice No:</b> ' . $invoice_no . '</td>
        <td class="right"><b>Date:</b> ' . $invoice_date . '</td>
    </tr>
    <tr>
        <td><b>Due Date:</b> ' . $due_date . '</td>
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
            ' . $contact_name . ' <br>
            ' . $contact_company . ' <br>
            ' . $contact_gst . ' <br>
            ' . $contact_number . ' | ' . $contact_email . '<br>
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


$productCodes = [];

$result = mysqli_query($conn, "
    SELECT id, product_code
    FROM products
");

while ($row = mysqli_fetch_assoc($result)) {
    $productCodes[$row['id']] = $row['product_code'];
}


$html .= '';

foreach ($items as $item) {

    $pid = $item['product_id'];
    $qty = $item['qty'];

    $product = $productCache[$pid];

    $description = $product['product_name'];
    $price = $product['selling_price'];
    $tax = $product['tax'];

    $lineTotal = $price * $qty;

    $html .= "
    <tr>
        <td>{$product['product_code']}</td>
        <td>{$description}</td>
        <td>{$qty}</td>
        <td>{$price}</td>
        <td>{$tax}</td>
        <td>{$lineTotal}</td>
    </tr>";
}

$html .= '
</table>

<br>

<!-- TOTALS -->
<table class="totals" align="right">
    <tr>
        <th>Subtotal</th>
        <td class="right">' . $subtotal . '</td>
    </tr>
    <tr>
        <th>Tax</th>
        <td class="right">' . $tax_total . '</td>
    </tr>
    <tr>
        <th>Grand Total</th>
        <td class="right"><b>' . $grand_total . '</b></td>
    </tr>
</table>

<div style="clear:both;"></div>
';

//generate pdf using DOMPDF
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);

// Paper size
$dompdf->setPaper('A4', 'portrait');

$dompdf->render();


//save pdf

$pdfOutput = $dompdf->output();

$fileName = "invoices/invoice_" . $invoice_id . ".pdf";
$fullPath = "../../" . $fileName;

file_put_contents($fullPath, $pdfOutput);

//db path

mysqli_query($conn, "UPDATE invoices SET pdf_path = '$fileName' WHERE id = '$invoice_id'");

echo json_encode([
    "Pdf" => "Saved Successfully"
    ]);
exit;
?>