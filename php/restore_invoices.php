<?php
session_start();

//connection setup
include("../config/connection.php");

$restoreInvoices = mysqli_query($conn, "UPDATE `invoices` SET `remove` = '0'");

if ($restoreInvoices) {

    if (mysqli_affected_rows($conn) > 0) {
        echo "success";
    } else {
        echo "no_changes";
    }

} else {
    echo "failed";
}
?>