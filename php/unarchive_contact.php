<?php

require_once "../includes/api_auth.php";
include("../config/connection.php");

$contactId = $_POST['id'];

$unarchive = mysqli_query($conn, "UPDATE `contacts` SET remove = 0 WHERE `id` = '$contactId'");

if($unarchive){
    echo "success";
}else{
    echo "failed";
}

?>