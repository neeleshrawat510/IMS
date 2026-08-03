<?php

require_once "../includes/api_auth.php";
include("../config/connection.php");

//indian timezone
date_default_timezone_set('Asia/Kolkata');

header('Content-Type: application/json');


$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

if ($action == "upload_photo") {

    // upload photo

} elseif ($action == "remove_photo") {

    // remove photo

} else {

    // update profile

    $name = trim(mysqli_real_escape_string($conn, $_POST['name'] ?? ''));
    $email = trim(mysqli_real_escape_string($conn, $_POST['email'] ?? ''));
    $number = trim(mysqli_real_escape_string($conn, $_POST['number'] ?? ''));



    $update = mysqli_query($conn, "UPDATE `users` SET 
                                        `name` = '$name',
                                        `email` = '$email',
                                        `number` = '$number'
                                        WHERE `id` = '$user_id'");

    header('Content-Type: application/json');

    if ($update) {
        echo json_encode([
            "status" => "success",
            "email_status" => true
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => mysqli_error($conn)
        ]);
    }
}
?>