<?php

include("jwt.php");

$headers = getallheaders();

/* ---------------- AUTH ---------------- */

if (!isset($headers["Authorization"])) {
    echo json_encode([
        "response" => "error",
        "message" => "token missing"
    ]);
    exit;
}

$token = str_replace("Bearer ", "", $headers["Authorization"]);
$data = verifyJWT($token);

if (!$data) {
    echo json_encode([
        "response" => "error",
        "message" => "invalid token"
    ]);
    exit;
}


//valid params
$allowed_params = ['id', 'invoice_no', 'start_date', 'end_date', 'page', 'page_size'];
$received_params = array_keys($_GET);
$invalidParams = array_diff($received_params, $allowed_params);

if (!empty($invalidParams)) {
    echo json_encode([
        "response" => "error",
        "message" => "invalid paramter(s)",
        "invalid params" => array_values($invalidParams),
        "allowed_params" => $allowedParams
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


//validate dates
$invoice_no = isset($_GET['invoice_no']) ? trim($_GET['invoice_no']) : null;
$start_date = $_GET['start_date'] ?? null;
$end_date = $_GET['end_date'] ?? null;


if ($start_date && !DateTime::createFromFormat('Y-m-d', $start_date)) {
    echo json_encode([
        "response" => "error",
        "message" => "invalid start_date format. use YYYY-MM-DD"
    ]);
    exit;
}

if ($end_date && !DateTime::createFromFormat('Y-m-d', $end_date)) {
    echo json_encode([
        "response" => "error",
        "message" => "invalid end_date format. use YYYY-MM-DD"
    ]);
    exit;
}

//get data from DB table
$sql = "
SELECT
    invoices.*,
    contacts.name AS contact_name,
    invoice_items.product_id,
    invoice_items.price,
    invoice_items.tax,
    invoice_items.amount,
    invoice_items.qty,
    products.product_code,
    products.product_name
FROM invoices
LEFT JOIN contacts ON invoices.contact_id = contacts.id
LEFT JOIN invoice_items ON invoices.id = invoice_items.invoice_id
LEFT JOIN products ON invoice_items.product_id = products.id
WHERE 1=1
";


//filteration
if ($is_single) {
    $sql .= " AND invoices.id = $id";
}

if (!empty($invoice_no)) {
    $invoice_no = mysqli_real_escape_string($conn, $invoice_no);
    $sql .= " AND invoices.invoice_no LIKE '%$invoice_no%'";
}

if ($start_date && $end_date) {
    $sql .= " AND invoices.invoice_date BETWEEN '$start_date' AND '$end_date'";
}


//count pages
$count_sql = "
SELECT COUNT(DISTINCT invoices.id) as total
FROM invoices
LEFT JOIN contacts ON invoices.contact_id = contacts.id
WHERE 1=1
";

if (!empty($invoice_no)) {
    $count_sql .= " AND invoices.invoice_no LIKE '%$invoice_no%'";
}

if ($start_date && $end_date) {
    $count_sql .= " AND invoices.invoice_date BETWEEN '$start_date' AND '$end_date'";
}

$count_result = mysqli_query($conn, $count_sql);
$total_row = mysqli_fetch_assoc($count_result);
$total_records = $total_row['total'];

$total_pages = $is_single ? 1 : ceil($total_records / $page_size);


//pagination
if ($is_single) {
    $sql .= " GROUP BY invoices.id LIMIT 1";
} else {
    $sql .= " GROUP BY invoices.id ORDER BY invoices.id DESC LIMIT $page_size OFFSET $offset";
}

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo json_encode([
        "response" => "error",
        "message" => "no data found"
    ]);
    exit;
}


//response
$invoices = [];
$baseUrl = "http://localhost/Invoice_management_System/api/jwt/";

while ($row = mysqli_fetch_assoc($result)) {

    $invoice_id = $row['id'];

    if (!isset($invoices[$invoice_id])) {
        $invoices[$invoice_id] = [
            "invoice_id" => $row['id'],
            "invoice_no" => strtolower($row['invoice_no']),
            "invoice_date" => $row['invoice_date'],
            "due_date" => $row['due_date'],
            "contact_id" => $row['contact_id'],
            "contact_name" => strtolower($row['contact_name']),
            "view_contact" => $baseUrl . "view_contacts.php?id=" . $row['contact_id'],
            "products" => [],
            "grand_total" => $row['grand_total']
        ];
    }

    $invoices[$invoice_id]["products"][] = [
        "product_id" => $row['product_id'],
        "product_code" => strtolower($row['product_code']),
        "product_name" => strtolower($row['product_name']),
        "price" => $row['price'],
        "qty" => $row['qty'],
        "view_product" => $baseUrl . "view_products.php?id=" . $row['product_id']
    ];
}

//prev and next navigation for single data
$navigation = null;

if ($is_single) {

    $prev_next_sql = "
        SELECT 
            (SELECT id FROM invoices WHERE id < $id ORDER BY id DESC LIMIT 1) AS prev_id,
            (SELECT id FROM invoices WHERE id > $id ORDER BY id ASC LIMIT 1) AS next_id
    ";

    $prev_next_result = mysqli_query($conn, $prev_next_sql);
    $prev_next = mysqli_fetch_assoc($prev_next_result);

    $navigation = [
        "previous_id" => $prev_next['prev_id'],
        "next_id" => $prev_next['next_id'],
        "view_prev" => $prev_next['prev_id'] ? $baseUrl . "view_invoices.php?id=" . $prev_next['prev_id'] : null,
        "view_next" => $prev_next['next_id'] ? $baseUrl . "view_invoices.php?id=" . $prev_next['next_id'] : null
    ];
}

//response
$response = [
    "message" => "all invoices",
    "data" => array_values($invoices)
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