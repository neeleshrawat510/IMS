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
$code = $_GET['code'] ?? '';
$name = $_GET['name'] ?? '';

$allowedParams = ['id','code', 'name'];
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


$sql = "SELECT * FROM `products` WHERE 1=1";

if(!empty($_GET['id'])){
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $sql .=" AND id = '$id'";
}

if(!empty($_GET['code'])){
    $code = mysqli_real_escape_string($conn, $_GET['code']);
    $sql .=" AND product_code = '$code'";
}

if(!empty($_GET['name'])){
    $name = mysqli_real_escape_string($conn, $_GET['name']);
    $sql .=" AND product_name = '$name'";
}

$viewProducts = mysqli_query($conn, $sql);

if(mysqli_num_rows($viewProducts)==0){
    echo json_encode([
    "Response" => "Error",
    "Message" => "No Data Found"
    ]);
    exit;
}


$products = [];

while ($product = mysqli_fetch_array($viewProducts)) {
    $products[] = [
            "id" => $product['id'],
            "product code" => $product['product_code'],
            "product name" => $product['product_name'],
            "cost price" => $product['cost_price'],
            "selling_price" => $product['selling_price'],
            "tax %" => $product['tax']
        ];
}

echo json_encode([
    "message" => "All Products",
    "products" => $products
]);

?>