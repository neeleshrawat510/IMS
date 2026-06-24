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
$contact_id = $_GET['contact_id'] ?? null;

if (!$contact_id) {
    echo json_encode([
        "response" => "error",
        "message" => "Contact ID is missing"
    ]);
    exit;
}

$checkContacts = mysqli_query($conn, "SELECT * FROM `contacts` WHERE `id` = '$contact_id'");

if(mysqli_num_rows($checkContacts) == '0'){
    echo json_encode([
        "response" => "Error",
        "Message" => "Invalid Contact Id"
    ]);
    exit;
}

$deleteContact = mysqli_query($conn, "DELETE FROM `contacts` WHERE `id` = '$contact_id'");

if($deleteContact){
    echo json_encode([
        "response" => "Success",
        "Message" => "Contact Deleted"
    ]);
}

?>