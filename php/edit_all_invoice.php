<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("location: ../index.php");
    exit();
}

include("../config/connection.php");

require_once("../vendor/autoload.php");

use Dompdf\Dompdf;
use Dompdf\Options;

date_default_timezone_set('Asia/Kolkata');


// POST DATA

$invoice_id   = $_POST['invoice_id'];

$contact_id   = $_POST['contact_id'];
$invoice_no   = $_POST['invoice_no'];
$invoice_date = $_POST['invoice_date'];
$due_date     = $_POST['due_date'];

$subtotal     = $_POST['subtotal'];
$tax_total    = $_POST['tax_total'];
$grand_total  = $_POST['grand_total'];
$status       = $_POST['status'];

$product_id  = $_POST['product_id'];
$description = $_POST['description'];
$qty         = $_POST['qty'];
$price       = $_POST['price'];
$tax         = $_POST['tax'];
$amount      = $_POST['amount'];

$dateToday = date('Y-m-d H:i:s');

$count = count($product_id);

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
        status = '$status'
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


for ($i = 0; $i < $count; $i++) {
    $pid = $product_id[$i];

    $html .= '
    <tr>
        <td>' . $productCodes[$pid] . '</td>
        <td>' . $description[$i] . '</td>
        <td>' . $qty[$i] . '</td>
        <td>' . $price[$i] . '</td>
        <td>' . $tax[$i] . '</td>
        <td>' . $amount[$i] . '</td>
    </tr>';
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
$fullPath = "../" . $fileName;

file_put_contents($fullPath, $pdfOutput);

//db path

mysqli_query($conn, "
UPDATE invoices 
SET pdf_path = '$fileName'
WHERE id = '$invoice_id'
");

echo json_encode([
    "status" => "success",
    "pdf" => $fileName
]);
exit;
?>
