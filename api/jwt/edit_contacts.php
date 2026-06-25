<?php
require("../../config/connection.php");
require("jwt.php");

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

//GET Id from url
$contact_id = $_GET['id'] ?? '';
if (empty($contact_id) || !is_numeric($contact_id)) {
    echo json_encode([
        "response" => "error",
        "message" => "Empty or Invalid contact_id"
    ]);
    exit;
}

$checkContactId = mysqli_query($conn, "SELECT * FROM contacts WHERE `id` = '$contact_id'");
if(mysqli_num_rows($checkContactId)==0){
    echo json_encode([
    "response" => "error",
    "message" => "Contact not found for this id"
    ]);
    exit;
}
$data = json_decode(file_get_contents("php://input"), true);

$name = $data['name'] ?? '';
$number = $data['number'] ?? '';
$email = $data['email'] ?? '';
$company = $data['company'] ?? '';
$gst = $data['gst'] ?? '';
$address = $data['address'] ?? '';
$updated_at = date('Y-m-d H:i:s');


$errors = [];
$fields = [];

//name
if (isset($data['name'])) {
    $name = trim($data['name']);
    if (!preg_match('/^[a-zA-Z ]+$/', $name)) {
        $errors['name'] = "Only alphabets allowed";
    } else {
        $fields[] = "`name` = '$name'";
    }
}

//number
if (isset($data['number'])) {
    $number = trim($data['number']);
    if (!preg_match('/^[0-9]{10}$/', $number)) {
        $errors['number'] = "Enter valid 10 digit mobile number";
    } else {
        $checkNumber = mysqli_query($conn, "SELECT * FROM contacts WHERE `number` = '$number' AND `id` != '$contact_id'");

        if (mysqli_num_rows($checkNumber) > 0) {
            $errors['number'] = "Number Already Exists";
        } else {
            $fields[] = "`number` = '$number'";
        }
    }
}

//email
if (isset($data['email'])) {
    $email = trim($data['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    } else {
        $fields[] = "`email` = '$email'";
    }
}

//company
if (isset($data['company'])) {
    $company = trim($data['company']);
    $fields[] = "`company` = '$company'";
}

//address
if (isset($data['address'])) {
    $address = trim($data['address']);
    $fields[] = "`address` = '$address'";
}


if (!empty($errors)) {
    echo json_encode([
        "response" => "error",
        "errors" => $errors
    ]);
    exit;
}
if (empty($fields)) {
    echo json_encode([
        "response" => "error",
        "message" => "No fields provided for update"
    ]);
    exit;
}

$fields[] = "`updated_by` = '$user_name'";
$fields[] = "`updated_at` = '$updated_at'";



$updateContacts = mysqli_query($conn, "UPDATE `contacts` SET " . implode(", ", $fields) . " WHERE id = '$contact_id'");


if ($updateContacts) {
    echo json_encode([
        "Message" => "Product Edited Successfully",
        "name" => $name,
        "number" => $number,
        "email" => $email,
        "company" => $company,
        "address" => $address,
        "updated by" => $user_name
    ]);
} else {    
    echo json_encode([
        "response" => "error",
        "message" => "Failed to edit contact"
    ]);
}

?>