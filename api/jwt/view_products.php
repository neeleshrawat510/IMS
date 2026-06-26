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


$data = verifyJWT($token);

if (!$data) {
    echo json_encode([
        "response" => "error",
        "message" => "Invalid Token"
    ]);
    exit;
}

//set params
$id = $_GET['id'] ?? '';
$code = $_GET['code'] ?? '';
$name = $_GET['name'] ?? '';

$allowedParams = ['id', 'code', 'name'];
$receivedParams = array_keys($_GET);

$invalidParams = array_diff($receivedParams, $allowedParams);

if (!empty($invalidParams)) {
    echo json_encode([
        "response" => "Error",
        "message" => "Invalid Parameter(s) used",
        "Invalid Params" => array_values($invalidParams),
        "Allowed Params" => $allowedParams
    ]);
    exit;
}

//single or multiple record

$is_single = isset($_GET['id']) && $_GET['id'] !== '';
$id = $is_single ? (int) $_GET['id'] : null;

//pagination

$page_size_allowed = [25, 50, 75, 100];

$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$page_size = isset($_GET['page_size']) ? (int) $_GET['page_size'] : 25;

if (!in_array($page_size, $page_size_allowed)) {
    echo json_encode([
        "response" => "error",
        "message" => "invalid page size. allowed: 25, 50, 75, 100"
    ]);
    exit;
}
$offset = ($page - 1) * $page_size;

//validation on product code

if (!preg_match('/^[0-9]+$/', $code)) {
    $errors['product_code'] = "Product code should be only numbric value(0-9)";
}


//get data from DB
$sql = "SELECT * FROM `products` WHERE 1=1";

if ($is_single) {
    $sql .= " AND id = $id";
}

if (!empty($_GET['code'])) {
    $code = mysqli_real_escape_string($conn, $_GET['code']);
    $sql .= " AND product_code = '%$code%'";
}

if (!empty($_GET['name'])) {
    $name = mysqli_real_escape_string($conn, $_GET['name']);
    $sql .= " AND product_name LIKE '%$name%'";
}


//count pages
$count_sql = "
SELECT COUNT(DISTINCT `id`) as total FROM products WHERE 1=1";

if (!empty($code)) {
    $count_sql .= " AND product_code LIKE '%$code%'";
}

if ($name) {
    $count_sql .= " AND product_name LIKE '%$name%'";
}

$count_result = mysqli_query($conn, $count_sql);
$total_row = mysqli_fetch_assoc($count_result);
$total_records = $total_row['total'];

$total_pages = $is_single ? 1 : ceil($total_records / $page_size);

//pagination
if ($is_single) {
    $sql .= " GROUP BY id LIMIT 1";
} else {
    $sql .= " GROUP BY id ORDER BY id DESC LIMIT $page_size OFFSET $offset";
}

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo json_encode([
        "response" => "error",
        "message" => "no data found"
    ]);
    exit;
}



$viewProducts = mysqli_query($conn, $sql);


//response
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

//prev and next navigation for single data
$navigation = null;
$baseUrl = "http://localhost/Invoice_management_System/api/jwt/";
if ($is_single) {

    $prev_next_sql = "
        SELECT 
            (SELECT id FROM products WHERE id < $id ORDER BY id DESC LIMIT 1) AS prev_id,
            (SELECT id FROM products WHERE id > $id ORDER BY id ASC LIMIT 1) AS next_id
    ";

    $prev_next_result = mysqli_query($conn, $prev_next_sql);
    $prev_next = mysqli_fetch_assoc($prev_next_result);

    $navigation = [
        "previous_id" => $prev_next['prev_id'],
        "next_id" => $prev_next['next_id'],
        "view_prev" => $prev_next['prev_id'] ? $baseUrl . "view_products.php?id=" . $prev_next['prev_id'] : null,
        "view_next" => $prev_next['next_id'] ? $baseUrl . "view_products.php?id=" . $prev_next['next_id'] : null
    ];
}

//response
$response = [
    "message" => "all products",
    "data" => array_values($products)
];

if (!$is_single) {
    $response["pagination"] = [
        "page" => $page,
        "page_size" => $page_size,
        "total_records" => $total_records,
        "total_pages" => $total_pages,
        "has_next" => $page < $total_pages,
        "has_prev" => $page > 1
    ];
} else {
    $response["navigation"] = $navigation;
}

echo json_encode($response);
?>