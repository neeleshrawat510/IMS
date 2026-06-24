<?php

include ("jwt.php");

$headers = getallheaders();

//check if token is missing
if(!isset($headers["Authorization"])){
echo json_encode([
    "response" => "error",
    "message" => "Token Missing"
]);
exit;
}

//remove bearer if there is
$token = str_replace("Bearer ", "", $headers["Authorization"]);


$data = verifyJWT($token);

if(!$data){
    echo json_encode([
        "response" => "error",
        "message" => "Invalid Token"
    ]);
    exit;
}

$id = $_GET['id'] ?? '';
$name = $_GET['name'] ?? '';
$number = $_GET['number'] ?? '';
$email = $_GET['email'] ?? '';
$gst = $_GET['gst'] ?? '';


$allowedParams = ['id','name', 'number', 'email', 'gst',];
$receivedParams = array_keys($_GET);

$invalidParams = array_diff($receivedParams, $allowedParams);

if(!empty($invalidParams)){
    echo json_encode([
        "response" => "Error",
        "message" => "Invalid Parameter(s) used",
        "Invalid Params" => array_values($invalidParams),
        "Allowed Params" => $allowedParams
    ]);
    exit;
}


$sql = "SELECT * FROM `contacts` WHERE 1=1";

if(!empty($_GET['id'])){
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $sql .=" AND id = '$id'";
}

if(!empty($_GET['name'])){
    $code = mysqli_real_escape_string($conn, $_GET['name']);
    $sql .=" AND `name` = '$name'";
}

if(!empty($_GET['number'])){
    $name = mysqli_real_escape_string($conn, $_GET['number']);
    $sql .=" AND `number` = '$number'";
}

if(!empty($_GET['email'])){
    $name = mysqli_real_escape_string($conn, $_GET['email']);
    $sql .=" AND `email` = '$email'";
}

if(!empty($_GET['gst'])){
    $name = mysqli_real_escape_string($conn, $_GET['gst']);
    $sql .=" AND `gst` = '$gst'";
}

$viewContacts = mysqli_query($conn, $sql);

if(mysqli_num_rows($viewContacts)==0){
    echo json_encode([
    "Response" => "Error",
    "Message" => "No Data Found"
    ]);
    exit;
}

$contacts = [];

while ($row = mysqli_fetch_assoc($viewContacts)) {
    $contacts[] = [
        "Contact id" => $row['id'],
        "Name" => $row['name'],
        "Number" => $row['number'],
        "Email" => $row['email'],
        "Company" => $row['company'],
        "gst" => $row['gst'],
        "address" => $row['address']
    ];
}

echo json_encode([
    "message" => "All Contacts",
    "contacts" => $contacts
]);




?>