<?php
session_start();

//connection setup
include("../config/connection.php");

$restoreContacts = mysqli_query($conn, "UPDATE `contacts` SET `remove` = '0'");

if ($restoreContacts) {

    if (mysqli_affected_rows($conn) > 0) {
        echo "success";
    } else {
        echo "no_changes";
    }

} else {
    echo "failed";
}
?>