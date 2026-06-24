<?php
require "../../config/connection.php";
require "jwt.php";

header("Content-Type: application/json");
$headers = getallheaders();

if (!isset($headers['Authorization'])) {
    echo json_encode([
        "response" => "error",
        "message" => "Token Missing"
    ]);
    exit;
}

$token = str_replace("Bearer ", "", $headers['Authorization']);

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

$product_code = $data['product_code'] ?? '';
$product_name = $data['product_name'] ?? '';
$cost_price = $data['cost_price'] ?? '';
$selling_price = $data['selling_price'] ?? '';
$tax = $data['tax'] ?? '';
$created_at = date('Y-m-d H:i:s'); 



$errors = [];

if (empty($product_code)) {
    $errors['product_code'] = "Product code is required";
}
$checkProductCode = mysqli_query($conn, "SELECT * FROM products WHERE product_code = '$product_code'");

if(mysqli_num_rows($checkProductCode) > 0){
    $errors['product_code'] = "Product Code Already Exists";
}elseif
    (!preg_match('/^[0-9]+$/', $product_code)){
    $errors['product_code'] = "Product code should be only numbric value(0-9)";
        }

if(empty($product_name)) {
    $errors['product_name'] = "Product name is required";
}

if (empty($cost_price)) {
    $errors['cost_price'] = "Cost price is required";
}
if ($cost_price !== '' && !is_numeric($cost_price)) {
    $errors['cost_price'] = "Cost price must be a number";
}

if (empty($selling_price)) {
    $errors['selling_price'] = "Selling price is required";
}elseif
    ($selling_price !== '' && $selling_price < $cost_price) {
    $errors['selling_price'] = "Selling price must be greater than Cost Price";
}

if (empty($tax)) {
    $errors['tax'] = "Tax is required";
}


if (!empty($errors)) {
    echo json_encode([
        "response" => "error",
        "errors" => $errors
    ]);
    exit;
}


$insertProduct = mysqli_query($conn, "INSERT INTO `products` (`created_by`,`product_code`,`product_name`,`cost_price`,`selling_price`,`tax`,`created_at`) VALUES('$user_name','$product_code','$product_name','$cost_price','$selling_price','$tax','$created_at')");

if ($insertProduct) {
    echo json_encode([
        "Message" => "Product Added Successfully",
        "created by" => $user_name,
        "product_code" => $product_code,
        "product_name" => $product_name,
        "cost_price" => $cost_price,
        "selling_price" => $selling_price,
        "tax" => $tax
    ]);
} else {
    echo json_encode([
        "response" => "error",
        "message" => "Failed to add product"
    ]);
}



?>