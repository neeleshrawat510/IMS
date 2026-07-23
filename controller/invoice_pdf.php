<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Generate invoice PDF from database
 *
 * @param mysqli $conn
 * @param int $invoiceId
 * @return string PDF Binary Data
 */

function generateInvoicePDF($conn, $invoiceId)
{
    $invoiceId = (int)$invoiceId;

    // ========= FETCH INVOICE + CONTACT ===============
    
    $invoiceQuery = mysqli_query($conn, "
        SELECT
            invoices.*,
            contacts.name,
            contacts.company,
            contacts.gst,
            contacts.number,
            contacts.email
        FROM invoices

        INNER JOIN contacts
            ON contacts.id = invoices.contact_id

        WHERE invoices.id = '$invoiceId'
        LIMIT 1
    ");

    if (!$invoiceQuery || mysqli_num_rows($invoiceQuery) == 0) {
        return false;
    }

    $invoice = mysqli_fetch_assoc($invoiceQuery);


        // ================== FETCH ITEMS ================

$items = [];

$itemQuery = mysqli_query($conn, "
    SELECT
        invoice_items.*,
        products.product_code
    FROM invoice_items
    LEFT JOIN products
        ON products.id = invoice_items.product_id
    WHERE invoice_items.invoice_id = '$invoiceId'
");

while ($row = mysqli_fetch_assoc($itemQuery)) {
    $items[] = $row;
}

// =================== PDF FORMAT =================
 

$html = '
<style>
    body {
        font-family: Arial, sans-serif;
        font-size:12px;
        color:#333;
    }

    .header{
        text-align:center;
        margin-bottom:20px;
    }

    .header h1{
        margin:0;
    }

    table{
        width:100%;
        border-collapse:collapse;
    }

    .info-table td{
        padding:4px 0;
    }

    .items th{
        background:#f2f2f2;
        border:1px solid #ddd;
        padding:8px;
        text-align:center;
    }

    .items td{
        border:1px solid #ddd;
        padding:8px;
        text-align:center;
    }

    .totals{
        width:40%;
        float:right;
        margin-top:20px;
    }

    .totals td,
    .totals th{
        border:1px solid #ddd;
        padding:8px;
    }

    .right{
        text-align:right;
    }

</style>

<div class="header">
<h1>INVOICE</h1>
<hr>
</div>

<table class="info-table">

<tr>

<td>

<b>Invoice No :</b>

'.$invoice["invoice_no"].'

</td>

<td class="right">

<b>Date :</b>

'.$invoice["invoice_date"].'

</td>

</tr>

<tr>

<td>

<b>Due Date :</b>

'.$invoice["due_date"].'

</td>

<td></td>

</tr>

</table>

<br>

<table>

<tr>

<td width="50%">

<b>From :</b><br>

Baseline IT Development Pvt Ltd<br>

Mohali

</td>

<td width="50%">

<b>Bill To :</b><br>

'.$invoice["name"].'<br>

'.$invoice["company"].'<br>

'.$invoice["gst"].'<br>

'.$invoice["number"].' | '.$invoice["email"].'

</td>

</tr>

</table>

<br>

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

//Fetch all products
foreach ($items as $item) {

    $html .= '

    <tr>

        <td>'.$item["product_code"].'</td>

        <td>'.$item["description"].'</td>

        <td>'.$item["qty"].'</td>

        <td>'.$item["price"].'</td>

        <td>'.$item["tax"].'</td>

        <td>'.$item["amount"].'</td>

    </tr>

    ';

}

$html .= '

</table>

<br>

<table class="totals" align="right">

<tr>

<th>Subtotal</th>

<td class="right">'.$invoice["subtotal"].'</td>

</tr>

<tr>

<th>Tax</th>

<td class="right">'.$invoice["tax_total"].'</td>

</tr>

<tr>

<th>Grand Total</th>

<td class="right">

<b>'.$invoice["grand_total"].'</b>

</td>

</tr>

</table>

<div style="clear:both"></div>

';

return $html;
}