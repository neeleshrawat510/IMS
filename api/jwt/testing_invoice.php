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

$user_name = $user['user_name'];

$data = json_decode(file_get_contents("php://input"), true);

$contact_id = $data['contact_id'] ?? '';
$invoice_no = trim($data['invoice_no'] ?? '');
$due_date = $data['due_date'] ?? '';
$status = $data['status'] ?? 'Pending';
$items = $data['items'] ?? [];

$invoice_date = date('Y-m-d');
$created_at = date('Y-m-d H:i:s');

$errors = [];

if (empty($contact_id)) {
    $errors['contact_id'] = "Contact ID is required";
}

if (empty($invoice_no)) {
    $errors['invoice_no'] = "Invoice Number is required";
}

if (empty($due_date)) {
    $errors['due_date'] = "Due Date is required";
}

if (empty($items) || !is_array($items)) {
    $errors['items'] = "At least one invoice item is required";
}

if (!empty($errors)) {
    echo json_encode([
        "response" => "error",
        "errors" => $errors
    ]);
    exit;
}

/* Check Invoice Number */
$check = mysqli_query(
    $conn,
    "SELECT id FROM invoices WHERE invoice_no='" . mysqli_real_escape_string($conn, $invoice_no) . "'"
);

if (mysqli_num_rows($check) > 0) {
    echo json_encode([
        "response" => "error",
        "message" => "Invoice Number already exists"
    ]);
    exit;
}

/* Calculate Totals */
$subtotal = 0;
$tax_total = 0;

foreach ($items as $item) {

    $qty = floatval($item['qty']);
    $price = floatval($item['price']);
    $tax = floatval($item['tax']);

    $lineAmount = $qty * $price;

    $subtotal += $lineAmount;
    $tax_total += $tax;
}

$grand_total = $subtotal + $tax_total;

mysqli_begin_transaction($conn);

try {

    $invoiceQuery = "
        INSERT INTO invoices
        (
            contact_id,
            invoice_no,
            invoice_date,
            due_date,
            subtotal,
            tax_total,
            grand_total,
            status,
            created_at,
            created_by
        )
        VALUES
        (
            '$contact_id',
            '$invoice_no',
            '$invoice_date',
            '$due_date',
            '$subtotal',
            '$tax_total',
            '$grand_total',
            '$status',
            '$created_at',
            '$user_name'
        )
    ";

    if (!mysqli_query($conn, $invoiceQuery)) {
        throw new Exception(mysqli_error($conn));
    }

    $invoice_id = mysqli_insert_id($conn);

    $responseItems = [];

foreach ($items as $item) {

    $product_id = $item['product_id'] ?? 0;
    $description = mysqli_real_escape_string(
        $conn,
        $item['description'] ?? ''
    );

    $qty = floatval($item['qty']);
    $price = floatval($item['price']);
    $tax = floatval($item['tax']);

    $amount = ($qty * $price) + $tax;

    $itemQuery = "
        INSERT INTO invoice_items
        (
            invoice_id,
            product_id,
            description,
            qty,
            price,
            tax,
            amount
        )
        VALUES
        (
            '$invoice_id',
            '$product_id',
            '$description',
            '$qty',
            '$price',
            '$tax',
            '$amount'
        )
    ";

    if (!mysqli_query($conn, $itemQuery)) {
        throw new Exception(mysqli_error($conn));
    }

    $responseItems[] = [
        "product_id" => $product_id,
        "description" => $description,
        "qty" => $qty,
        "price" => $price,
        "tax" => $tax,
        "amount" => $amount
    ];
}
        if (!mysqli_query($conn, $itemQuery)) {
            throw new Exception(mysqli_error($conn));
        }
    

    mysqli_commit($conn);

    echo json_encode([
        "response" => "success",
        "message" => "Invoice Created Successfully",
        "invoice_id" => $invoice_id,
        "invoice_no" => $invoice_no,
        "items[]" => [
            "product_id" => $product_id,
                "description" => $description,
                "qty" => $qty,
                "price" => $price,
                "tax" => $tax,
                "amount"=> $amount

        ],
        "subtotal" => $subtotal,
        "tax_total" => $tax_total,
        "grand_total" => $grand_total
    ]);

} catch (Exception $e) {

    mysqli_rollback($conn);

    echo json_encode([
        "response" => "error",
        "message" => $e->getMessage()
    ]);
}
?>