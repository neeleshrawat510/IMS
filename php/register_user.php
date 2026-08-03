<?php
require_once "../includes/api_auth.php";
//setting up connection with DB
include("../config/connection.php");
require_once("../vendor/autoload.php");
require_once "../controller/registration_email.php";



//input fields with mysql injection
$name = trim(mysqli_real_escape_string($conn, $_POST['name']) ?? '');
$number = trim(mysqli_real_escape_string($conn, $_POST['number']) ?? '');
$email = trim(mysqli_real_escape_string($conn, $_POST['email']) ?? '');
$plainPassword = trim($_POST['password'] ?? '');
$hashedPassword = md5($plainPassword);
$role = 'User'; //By default

$insertUser = mysqli_query($conn, "INSERT INTO `users` (`name`, `number`, `email`, `password`, `role`) VALUES ('$name','$number','$email','$hashedPassword', '$role')");

if (!$insertUser) {
    echo json_encode([
        "status" => "error",
        "message" => mysqli_error($conn)
    ]);
    exit;
}


$emailSent = sendUserCredentials(
    $email,
    $name,
    $plainPassword
);

echo json_encode([
    "status" => "success",
    "email_status" => $emailSent
]);
exit;

?>