<?php

require_once "../includes/api_auth.php";
include("../config/connection.php");


$sql = mysqli_query($conn, "
    SELECT 
        invoices.id,
        contacts.name,
        contacts.number,
        invoices.invoice_date,
        invoices.invoice_no,
        invoices.status,
        invoices.payment_status,
        invoices.grand_total
    FROM invoices 
    INNER JOIN contacts 
        ON invoices.contact_id = contacts.id
        WHERE invoices.remove = '0'
");


$data = [];
$sr = 1;
if (mysqli_num_rows($sql) > 0) {

    while ($row = mysqli_fetch_array($sql)) {

        //disable editing if payment status is paid
        $editButton = '';

        if ($row['payment_status'] != 'Paid') {
            $editButton = '
        <a href="edit_invoice.php?id=' . $row['id'] . '" class="btn btn-primary btn-sm" title="Edit">
            <i class="bi bi-pencil"></i>
        </a>';
        } else {
            $editButton = '
        <button class="btn btn-secondary btn-sm" title="Paid invoices cannot be edited" disabled>
            <i class="bi bi-pencil"></i>
        </button>';
        }

        $data[] = [
            $row['id'],
            $sr++,
            $row['invoice_no'],
            $row['name'],
            $row['invoice_date'],
            $row['grand_total'],
            $row['payment_status'],
            '<a href="php/view_invoice.php?id=' . $row['id'] . '" target="_blank" class="btn btn-success btn-sm me-1" title="View">
                        <i class="bi bi-eye"></i> 
                    </a>

                    ' . $editButton . '

                    <a href="php/download_invoice.php?id=' . $row['id'] . '" class="btn btn-primary btn-sm" title="Download">
                        <i class="bi bi-download"></i> 
                    </a>
                    <a href="#" class="btn btn-danger btn-sm delete-btn" title="Delete" data-id="' . $row['id'] . '">                          <i class="bi bi-trash"></i>
                    </a>'
        ];
    }

}
header('Content-Type: application/json');
echo json_encode($data);
?>