<?php
require ("../../config/connection.php");
require ("jwt.php");

header("Content-Type: application/json");
$headers = getallheaders();

if (!isset($headers['Authorization'])) {
    echo json_encode([
        "response" => "error",
        "message" => "Token Missing"
    ]);
    exit;
}


$token = str_replace("Bearer", "", $headers['Authorization']);

$user = verifyJWT($token);

if (!$user) {
    echo json_encode([
        "response" => "error",
        "message" => "Invalid Token"
    ]);
    exit;
}

$user_id = $user['user_id'];
$user_name = $user['user_name'];


$data = json_decode(file_get_contents("php://input"), true);

$name = $data['name'] ?? '';
$number = $data['number'] ?? '';
$email = $data['email'] ?? '';
$company = $data['company'] ?? '';
$gst = $data['gst'] ?? '';
$address = $data['address'] ?? '';
$created_at = date('Y-m-d H:i:s'); 



$errors = [];

if (empty($name)) {
    $errors['name'] = "Name is required";
}
if (empty($number)) {
    $errors['number'] = "Number is required";
}

if (!preg_match('/^[0-9]{10}$/', $number)) {
    $errors['number'] = "Enter valid 10 digit mobile number";
}
if (empty($email)) {
    $errors['email'] = "Email is required";
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "Invalid email format";
}
if (empty($company)) {
    $errors['company'] = "Company Name is required";
}
if (empty($gst)) {
    $errors['gst'] = "GST is required";
}
if (!preg_match('/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/', $gst)) {
    $errors['gst'] = "Enter valid GST number";
}
if (empty($address)) {
    $errors['address'] = "Address is required";
}

$checkNumber = mysqli_query($conn, "SELECT * FROM contacts WHERE `number` = '$number'");

if(mysqli_num_rows($checkNumber) > 0){
    $errors['number'] = "Number Already Exists";
}

$checkGst = mysqli_query($conn, "SELECT * FROM contacts WHERE `gst` = '$gst'");

if(mysqli_num_rows($checkGst) > 0){
    $errors['gst'] = "GST Already Exists";
}

if (!empty($errors)) {
    echo json_encode([
        "response" => "error",
        "errors" => $errors
    ]);
    exit;
}


$insertContact = mysqli_query($conn, "INSERT INTO `contacts` (`created_by`,`name`,`number`,`email`,`company`,`gst`,`address`,`created_at`) VALUES('$user_name','$name','$number','$email','$company','$gst','$address','$created_at')");

if ($insertContact) {
    echo json_encode([
        "Message" => "Contact Added Successfully",
        "created by" => $user_name,
        "name" => $name,
        "number" => $number,
        "email" => $email,
        "company" => $company,
        "gst" => $gst,
        "address" => $address
    ]);
} else {
    echo json_encode([
        "response" => "error",
        "message" => "Failed to add contact"
    ]);
}



?>
