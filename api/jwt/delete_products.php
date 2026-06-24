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
$product_id = $_GET['product_id'] ?? null;

if (!$product_id) {
    echo json_encode([
        "response" => "error",
        "message" => "Product ID is missing"
    ]);
    exit;
}

$checkProducts = mysqli_query($conn, "SELECT * FROM `products` WHERE `id` = '$product_id'");

if(mysqli_num_rows($checkProducts) == '0'){
    echo json_encode([
        "response" => "Error",
        "Message" => "Invalid Product Id"
    ]);
    exit;
}

$deleteProducts = mysqli_query($conn, "DELETE FROM `products` WHERE `id` = '$product_id'");

if($deleteProducts){
    echo json_encode([
        "response" => "Success",
        "Message" => "Product Deleted"
    ]);
}

?>