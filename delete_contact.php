<?php

require_once "includes/auth_check.php";

//connection setup
include("config/connection.php");
include("controller/role_check.php");

requireRole("Admin");

$contactId = $_POST['id'];

$delete = mysqli_query($conn, "DELETE FROM `contacts` WHERE `id` = '$contactId'");

if($delete){
    echo "success";
}else{
    echo "failed";
}

?>