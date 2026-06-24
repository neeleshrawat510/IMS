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


$user = verifyJWT($token);

if(!$user){
    echo json_encode([
        "response" => "error",
        "message" => "Invalid Token"
    ]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$invoice_id = $data['invoice_id'];

if (!isset($data['invoice_id']) || empty($data['invoice_id'])) {
    echo json_encode([
        "response" => "Error",
        "message" => "Invoice ID is missing"
    ]);
    exit;
}

$checkinvoices = mysqli_query($conn, "SELECT * FROM `invoices` WHERE `id` = '$invoice_id'");

if(mysqli_num_rows($checkinvoices) == '0'){
    echo json_encode([
        "response" => "Error",
        "Message" => "Invalid Invoice Id"
    ]);
    exit;
}

$deleteinvoices = mysqli_query($conn, "DELETE FROM `invoices` WHERE `id` = '$invoice_id'");

if($deleteinvoices){
    echo json_encode([
        "response" => "Success",
        "Message" => "Invoice Deleted"
    ]);
}

?>