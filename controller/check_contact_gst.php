<?php
require_once "../includes/api_auth.php";
include("../config/connection.php");

$gst = $_POST['gst'];
$id =  isset($_POST['id']) ? (int) $_POST['id'] : 0;
$sql = mysqli_query($conn, "SELECT * FROM `contacts` WHERE `gst` = '$gst' AND `id` != '$id' AND `remove` = '0'");

if(mysqli_num_rows($sql) > 0){
    echo "false";
}else{
    echo "true";
}
exit();
?>