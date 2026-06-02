<?php
session_start();

// connection setup
include("../config/connection.php");

if(!isset($_SESSION['user_id'])){
    header("location: index.php");
    exit();
}

$sql = mysqli_query($conn, "
    SELECT 
        invoices.id,
        contacts.fname,
        contacts.number,
        invoices.invoice_date,
        invoices.invoice_no
    FROM invoices 
    INNER JOIN contacts 
        ON invoices.contact_id = contacts.id
");

if (mysqli_num_rows($sql) > 0) {

    while ($data = mysqli_fetch_array($sql)) {
?>
        <tr>
            <td><?= $data['fname'] ?></td>
            <td><?= $data['number'] ?></td>
            <td><?= $data['invoice_date'] ?></td>
            <td><?= $data['invoice_no'] ?></td>

            <td>
                <a href="print_invoice.php?id=<?= $data['id']; ?>" class="btn btn-success">
                    Print Invoice
                </a>

                <a href="delete_invoice.php?id=<?= $data['id'] ?>" class="btn btn-danger">
                    Remove
                </a>
            </td>
        </tr>

<?php
    }

} else {
    echo "<tr><td colspan='5'>No Data Found</td></tr>";
}
?>