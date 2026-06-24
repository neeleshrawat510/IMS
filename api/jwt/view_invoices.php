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
$invoice_id = $_GET['id'] ?? '';
$invoice_date = $_GET['invoice_date'] ?? '';
$invoice_no = $_GET['invoice_no'] ?? '';


$allowedParams = ['id', 'invoice_date', 'invoice_no'];
$receivedParams = array_keys($_GET);

$invalidParams = array_diff($receivedParams, $allowedParams);

if (!empty($invalidParams)) {
    echo json_encode([
        "response" => "error",
        "message" => "Invalid parameter(s) used",
        "invalid_params" => array_values($invalidParams),
        "allowed_params" => $allowedParams
    ]);
    exit;
}

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
if (!empty($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $sql .= " AND invoices.id = '$id'";
}

if (!empty($_GET['invoice_date'])) {
    $date = mysqli_real_escape_string($conn, $_GET['invoice_date']);
    $sql .= " AND invoices.invoice_date = '$invoice_date'";
}

if (!empty($_GET['invoice_no'])) {
    $date = mysqli_real_escape_string($conn, $_GET['invoice_no']);
    $sql .= " AND invoices.invoice_no = '$invoice_no'";
}

$viewInvoices = mysqli_query($conn, $sql);

if (mysqli_num_rows($viewInvoices) == 0) {
    echo json_encode([
        "response" => "error",
        "message" => "No Data Found"
    ]);
    exit;
}
$invoices = [];

$baseUrl = "http://localhost/Invoice_management_System/api/jwt/";
while ($row = mysqli_fetch_assoc($viewInvoices)) {

    $invoice_id = $row['id'];

    if (!isset($invoices[$invoice_id])) {
        $invoices[$invoice_id] = [
            "invoice_id" => $row['id'],
            "invoice_no" => $row['invoice_no'],
            "invoice_date" => $row['invoice_date'],
            "due_date" => $row['due_date'],
            "contact_id" => $row['contact_id'],
            "contact_name" => $row['contact_name'],
            "View Contact" => $baseUrl . "view_contacts.php?id=" . $row['contact_id'],
            "products" => [],
            "grand_total" => $row['grand_total']
        ];
    }

    $invoices[$invoice_id]["products"][] = [
        "product_id" => $row['product_id'],
        "product_code" => $row['product_code'],
        "product_name" => $row['product_name'],
        "price" => $row['price'],
        "qty" => $row['qty'],
        "View Product" => $baseUrl . "view_products.php?id=" . $row['product_id']
    ];
}

echo json_encode([
    "message" => "All invoices",
    "invoices" => array_values($invoices)
]);



?>