<?php
require ("../../config/connection.php");
require ("jwt.php");

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

$invoice_id = $data['invoice_id'] ?? '';
$contact_id = $data['contact_id'] ?? '';
$due_date   = $data['due_date'] ?? '';
$status     = $data['status'] ?? '';
$items      = $data['items'] ?? [];

$updated_at = date('Y-m-d H:i:s');

$errors = [];

if (empty($invoice_id)) $errors['invoice_id'] = "Invoice ID is required";
if (empty($contact_id))  $errors['contact_id'] = "Contact is required";
if (empty($due_date))    $errors['due_date'] = "Due date is required";
if (empty($status))      $errors['status'] = "Status is required";
if (empty($items))       $errors['items'] = "Items are required";

$checkInvoice = mysqli_query($conn, "SELECT id FROM invoices WHERE id='$invoice_id'");
if (mysqli_num_rows($checkInvoice) == 0) {
    echo json_encode([
        "response" => "error",
        "message" => "Invoice not found"
    ]);
    exit;
}


if (!empty($errors)) {
    echo json_encode([
        "response" => "error",
        "errors" => $errors
    ]);
    exit;
}

// Check contact 
$contactQuery = mysqli_query($conn, "SELECT * FROM contacts WHERE id = '$contact_id'");
if (mysqli_num_rows($contactQuery) == 0) {
    echo json_encode([
        "response" => "error",
        "message" => "Contact not found"
    ]);
    exit;
}

$contact = mysqli_fetch_assoc($contactQuery);

// Calculate totals 
$subtotal = 0;
$tax_total = 0;
$grand_total = 0;

$productCache = [];

foreach ($items as $item) {

    $product_id = $item['product_id'];
    $qty = $item['qty'];

    $productQuery = mysqli_query($conn, "SELECT * FROM products WHERE id='$product_id'");

    if (mysqli_num_rows($productQuery) == 0) {
        echo json_encode([
            "response" => "error",
            "message" => "Product not found"
        ]);
        exit;
    }

    $product = mysqli_fetch_assoc($productQuery);

    $price = $product['selling_price'];
    $tax   = $product['tax'];

    $lineSubtotal = $price * $qty;
    $taxAmount = ($lineSubtotal * $tax) / 100;

    $subtotal += $lineSubtotal;
    $tax_total += $taxAmount;
    $grand_total += ($lineSubtotal + $taxAmount);

    $productCache[$product_id] = $product;
}

// Update invoice 
$updateInvoice = mysqli_query($conn, "UPDATE `invoices` SET 
    `contact_id` = '$contact_id',
    `due_date` = '$due_date',
    `subtotal` = '$subtotal',
    `tax_total` = '$tax_total',
    `grand_total` = '$grand_total',
    `status` = '$status',
    `updated_at` = '$updated_at',
    `updated_by` = '$user_name'
WHERE `id` = '$invoice_id'");

if (!$updateInvoice) {
    echo json_encode([
        "response" => "error",
        "message" => "Failed to edit Invoice"
    ]);
    exit;
}

// Delete old items (IMPORTANT: use original invoice_id) 
mysqli_query($conn, "DELETE FROM `invoice_items` WHERE `invoice_id` = '$invoice_id'");

// Insert new items 
$insertedItems = [];

foreach ($items as $item) {

    $product_id = $item['product_id'];
    $qty = $item['qty'];

    $product = $productCache[$product_id];

    $description = $product['product_name'];
    $price = $product['selling_price'];
    $tax = $product['tax'];

    $lineSubtotal = $qty * $price;

    $insertInvoiceItems = mysqli_query($conn, "INSERT INTO `invoice_items` 
        (`invoice_id`, `product_id`, `description`, `qty`, `price`, `tax`, `amount`)
        VALUES
        ('$invoice_id', '$product_id', '$description', '$qty', '$price', '$tax', '$lineSubtotal')");

    if (!$insertInvoiceItems) {
        echo json_encode([
            "response" => "error",
            "message" => "Failed to save Invoice Items"
        ]);
        exit;
    }

    $insertedItems[] = [
        "product_id" => $product_id,
        "product_name" => $description,
        "price" => $price,
        "qty" => $qty,
        "tax" => $tax,
        "amount" => $lineSubtotal
    ];
}

// FINAL RESPONSE (OUTSIDE LOOP) 
echo json_encode([
    "response" => "success",
    "message" => "Invoice Edited Successfully",

    "invoice" => [
        "invoice_id" => $invoice_id,
        "due_date" => $due_date,
        "subtotal" => $subtotal,
        "tax_total" => $tax_total,
        "grand_total" => $grand_total,
        "status" => $status,
        "updated_by" => $user_name
    ],

    "contact" => [
        "id" => $contact['id'],
        "name" => $contact['name'],
        "email" => $contact['email'],
        "number" => $contact['number']
    ],

    "items" => $insertedItems
]);

?>