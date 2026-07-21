<?php

require_once "../includes/api_auth.php";
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