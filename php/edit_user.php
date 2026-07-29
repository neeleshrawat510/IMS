<?php

require_once "../includes/api_auth.php";

//indian timezone
date_default_timezone_set('Asia/Kolkata');


include("../config/connection.php");




$user_id    = $_POST['id'];

$name = trim(mysqli_real_escape_string($conn, $_POST['product_code'] ?? ''));
$email = trim(mysqli_real_escape_string($conn, $_POST['product_name'] ?? ''));
$number = trim(mysqli_real_escape_string($conn, $_POST['cost_price'] ?? ''));



$update = mysqli_query($conn, "UPDATE `users` SET 
                                        `name` = '$name',
                                        `email` = '$email',
                                        `number` = '$number',
                                        WHERE `id` = '$user_id'");

if($update){
    echo "success";
}else{
    echo "failed";
}
?>