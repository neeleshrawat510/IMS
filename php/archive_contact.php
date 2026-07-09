<?php

require_once "../includes/api_auth.php";
include("../config/connection.php");
include("../controller/role_check.php");

requireRole("Admin");


$contactId = $_POST['id'];

$delete = mysqli_query($conn, "UPDATE `contacts` SET `remove` = '1' WHERE `id` = '$contactId'");

if($delete){
    echo "success";
}else{
    echo "failed";
}

?>