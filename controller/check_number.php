<?php
require_once "../includes/api_auth.php";
include("../config/connection.php");

$number = $_POST['number'];
$id =  isset($_POST['id']) ? (int) $_POST['id'] : 0;
$sql = mysqli_query($conn, "SELECT * FROM `contacts` WHERE `number` = '$number' AND `id` != '$id' AND `remove` = '0'");

if(mysqli_num_rows($sql) > 0){
    echo "false";
}else{
    echo "true";
}
exit();
?>