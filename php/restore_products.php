<?php
session_start();

//connection setup
include("../config/connection.php");

$restoreProducts = mysqli_query($conn, "UPDATE `products` SET `remove` = '0'");

if ($restoreProducts) {

    if (mysqli_affected_rows($conn) > 0) {
        echo "success";
    } else {
        echo "no_changes";
    }

} else {
    echo "failed";
}
?>