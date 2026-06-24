<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header('location: ../index.php');
}

//setup connection
include("../config/connection.php");

$allUsers = mysqli_query($conn, "SELECT * FROM `users` ORDER BY id DESC");

$data = [];
$sr = 1;

if(mysqli_num_rows($allUsers) > 0){
   while($row = mysqli_fetch_array($allUsers)){
    $data[] = [
        $sr++,
        $row['name'],
        $row['email'],
        $row['number']

    ];
   }

}
header('Content-Type: application/json');
echo json_encode($data);

?>