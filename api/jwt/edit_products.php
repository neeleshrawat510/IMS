<?php

include("jwt.php");

$headers = getallheaders();

//check if token is missing
if (!isset($headers["Authorization"])) {
    echo json_encode([
        "response" => "error",
        "message" => "Token Missing"
    ]);
    exit;
}

//remove bearer if there is
$token = str_replace("Bearer ", "", $headers["Authorization"]);


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

//GET id from url
$product_id = $_GET['id'];
if (empty($product_id) || !is_numeric($product_id)) {
    echo json_encode([
        "response" => "error",
        "message" => "Empty or Invalid product_id"
    ]);
    exit;
}

$checkProductId = mysqli_query($conn, "SELECT * FROM products WHERE `id` = '$product_id'");
if (mysqli_num_rows($checkProductId) == 0) {
    echo json_encode([
        "response" => "error",
        "message" => "Product not found for this id"
    ]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$product_code = $data['product_code'] ?? '';
$product_name = $data['product_name'] ?? '';
$cost_price = $data['cost_price'] ?? '';
$selling_price = $data['selling_price'] ?? '';
$tax = $data['tax'] ?? '';
$updated_at = date('Y-m-d H:i:s');



$errors = [];
$fields = [];


//product name
if (isset($data['product_name'])) {
    $product_name = trim($data['product_name']);
    $fields[] = "`product_name` = '$product_name'";
}

//cost price
if (isset($data['cost_price'])) {
    $cost_price = trim($data['cost_price']);
    if ($cost_price !== '' && !is_numeric($cost_price)) {
        $errors['cost_price'] = "Cost price must be a number";
    } else {
        $fields[] = "`cost_price` = '$cost_price'";
    }
}

//selling price
if (isset($data['selling_price'])) {
    $selling_price = trim($data['selling_price']);
    if ($selling_price !== '' && !is_numeric($selling_price)) {
        $errors['selling_price'] = "Cost price must be a number";
    }
    $comparePrice = mysqli_query($conn, "SELECT * FROM products WHERE id = $product_id");
    $fetch = mysqli_fetch_assoc($comparePrice);
    $cost_price = $fetch['cost_price'];

    if ($selling_price !== '' && $selling_price <= $cost_price) {
        $errors['selling_price'] = "Selling price must be greater than Cost Price";
    } else {
        $fields[] = "`selling_price` = '$selling_price'";
    }
}

//tax
if (isset($data['tax'])) {
    $tax = trim($data['tax']);
    if ($tax !== '' && !is_numeric($tax)) {
        $errors['tax'] = "Tax must be a number";
    } else {
        $fields[] = "`tax` = '$tax'";
    }
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
        "errors" => "No fields provided for update"
    ]);
    exit;
}
$fields[] = "`updated_by` = '$user_name'";
$fields[] = "`updated_at` = '$updated_at'";



$editProducts = mysqli_query($conn, "UPDATE `products` SET " . implode(",", $fields) . " WHERE `id` = '$product_id'");

$productResult = mysqli_query($conn, "SELECT * FROM `products` WHERE `id` = '$product_id'");
$productData = mysqli_fetch_assoc($productResult);
if ($editProducts) {
    echo json_encode([
        "Message" => "Product Edited Successfully",
        "edited by" => $user_name,
        "product name" => $productData['product_name'],
        "cost price" => $productData['cost_price'],
        "selling price" => $productData['selling_price'],
        "tax" => $productData['tax']
    ]);
}
?>