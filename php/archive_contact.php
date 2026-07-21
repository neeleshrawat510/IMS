<?php

require_once "../includes/api_auth.php";
include("../config/connection.php");


$contactId = $_POST['id'];

$delete = mysqli_query($conn, "UPDATE `contacts` SET `remove` = '1' WHERE `id` = '$contactId'");

if($delete){
    echo "success";
}else{
    echo "failed";
}

?>