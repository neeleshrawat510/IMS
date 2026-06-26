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

//validation on name, number, email & gst

if (!preg_match('/^[0-9]+$/', $number)) {
    $errors['number'] = "number should be only(0-9)";
}
if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors['email'] = "incorrect email format";
}


$sql = "SELECT * FROM `contacts` WHERE 1=1";

if(!empty($_GET['id'])){
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $sql .=" AND `id` = '$id'";
}

if(!empty($_GET['name'])){
    $code = mysqli_real_escape_string($conn, $_GET['name']);
    $sql .=" AND `name` = '%$name%'";
}

if(!empty($_GET['number'])){
    $name = mysqli_real_escape_string($conn, $_GET['number']);
    $sql .=" AND `number` = '%$number%'";
}

if(!empty($_GET['email'])){
    $name = mysqli_real_escape_string($conn, $_GET['email']);
    $sql .=" AND `email` LIKE '%$email%'";
}

if(!empty($_GET['gst'])){
    $name = mysqli_real_escape_string($conn, $_GET['gst']);
    $sql .=" AND `gst` = '%$gst%'";
}

//count pages
$count_sql = "
SELECT COUNT(DISTINCT `id`) as total FROM contacts WHERE 1=1";

if (!empty($name)) {
    $count_sql .= " AND `name` LIKE '%$name%'";
}

if ($number) {
    $count_sql .= " AND `number` LIKE '%$number%'";
}

if ($number) {
    $count_sql .= " AND `email` LIKE '%$email%'";
}

if ($number) {
    $count_sql .= " AND `gst` LIKE '%$gst%'";
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

$viewContacts = mysqli_query($conn, $sql);


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

//prev and next navigation for single data
$navigation = null;
$baseUrl = "http://localhost/Invoice_management_System/api/jwt/";
if ($is_single) {

    $prev_next_sql = "
        SELECT 
            (SELECT id FROM contacts WHERE id < $id ORDER BY id DESC LIMIT 1) AS prev_id,
            (SELECT id FROM contacts WHERE id > $id ORDER BY id ASC LIMIT 1) AS next_id
    ";

    $prev_next_result = mysqli_query($conn, $prev_next_sql);
    $prev_next = mysqli_fetch_assoc($prev_next_result);

    $navigation = [
        "previous_id" => $prev_next['prev_id'],
        "next_id" => $prev_next['next_id'],
        "view_prev" => $prev_next['prev_id'] ? $baseUrl . "view_contacts.php?id=" . $prev_next['prev_id'] : null,
        "view_next" => $prev_next['next_id'] ? $baseUrl . "view_contacts.php?id=" . $prev_next['next_id'] : null
    ];
}

//response
$response = [
    "message" => "all contacts",
    "data" => array_values($contacts)
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


?>