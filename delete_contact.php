<?php

require_once "includes/auth_check.php";

//connection setup
include("config/connection.php");


$contactId = $_POST['id'];

$delete = mysqli_query($conn, "DELETE FROM `contacts` WHERE `id` = '$contactId'");

if($delete){
    echo "success";
}else{
    echo "failed";
}

?>