<?php

require "../../config/connection.php";
require "jwt.php";

header("Content-Type: application/json");

//auth check
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


//input data
$data = json_decode(file_get_contents("php://input"), true);
$invoice_id = $data['invoice_id'] ?? '';
$contact_id = $data['contact_id'] ?? '';
$invoice_no = $data['invoice_no'] ?? '';
$invoice_date = date('Y-m-d');
$due_date = $data['due_date'] ?? '';
$status = $data['status'] ?? '';
$items = $data['items'] ?? [];

$created_at = date('Y-m-d H:i:s');


//validation
$errors = [];

if (empty($contact_id))
    $errors['contact_id'] = "Contact is required";
if (empty($invoice_no))
    $errors['invoice_no'] = "Invoice number is required";
if (empty($due_date))
    $errors['due_date'] = "Due date is required";
if (empty($status))
    $errors['status'] = "Status is required";
if (empty($items))
    $errors['items'] = "Items are required";

if (!empty($errors)) {
    echo json_encode([
        "response" => "error",
        "errors" => $errors
    ]);
    exit;
}


//Check for duplicate invoice
$check = mysqli_query($conn, "SELECT id FROM invoices WHERE invoice_no = '$invoice_no'");

if (mysqli_num_rows($check) > 0) {
    echo json_encode([
        "response" => "error",
        "message" => "Invoice already exists"
    ]);
    exit;
}


//fetch contacts
$contactQuery = mysqli_query($conn, "SELECT * FROM contacts WHERE id = '$contact_id'");

if (mysqli_num_rows($contactQuery) == 0) {
    echo json_encode([
        "Response" => "Error",
        "Message" => "Contact not found"
    ]);
    exit;
}

$contact = mysqli_fetch_assoc($contactQuery);


//calculate totals
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
            "Response" => "Error",
            "Message" => "Product not found"
        ]);
        exit;
    }

    $product = mysqli_fetch_array($productQuery);

    $price = $product['selling_price'];
    $tax = $product['tax'];

    //auto calculate
    $lineSubtotal = $price * $qty;
    $taxAmount = ($lineSubtotal * $tax) / 100;
    $lineTotal = $lineSubtotal;

    $subtotal += $lineSubtotal;
    $tax_total += $taxAmount;

    $grand_total += $lineTotal + $taxAmount;


    $productCache[$product_id] = $product;


}

$insertInvoice = mysqli_query($conn, "INSERT INTO `invoices` (`contact_id`, `invoice_no`, `invoice_date`, `due_date`, `subtotal`, `tax_total`, `grand_total`, `status`, `created_at`, `created_by`)
                                        VALUES ('$contact_id', '$invoice_no', '$invoice_date', '$due_date', '$subtotal', '$tax_total', '$grand_total', '$status', '$created_at', '$user_name')
                                        ");

if (!$insertInvoice) {
    echo json_encode([
        "Response" => "Error",
        "Message" => "Failed to create Invoice"
    ]);
    exit;
}

//store invoice ID
// $invoice_id = mysqli_insert_id($conn);


//insertedItems

$insertedItems = [];

foreach ($items as $item) {

    $product_id = $item['product_id'];
    $qty = $item['qty'];

    $product = $productCache[$product_id];

    $description = $product['product_name'];
    $price = $product['selling_price'];
    $tax = $product['tax'];

    $lineSubtotal = $qty * $price;
    $amount = $lineSubtotal;

    //insert Invoice Items

    $insertInvoiceItems = mysqli_query($conn, "INSERT INTO `invoice_items` (`invoice_id`, `product_id`, `description`, `qty`, `price`, `tax`, `amount`)
                                            VALUES('$invoice_id', '$product_id', '$description', '$qty', '$price', '$tax', '$amount')");

    if (!$insertInvoiceItems) {
        echo json_encode([
            "Response" => "Error",
            "Message" => "Failed to save Invoice Items"
        ]);
        exit;
    }

    $insertedItems[] = [
        "product_id" => $product_id,
        "product_name" => $description,
        "price" => $price,
        "qty" => $qty,
        "tax" => $tax,
        "amount" => $amount
    ];
}

echo json_encode([
    "response" => "success",
    "message" => "Invoice Created Successfully",

    "invoice" => [
        "invoice_id" => $invoice_id,
        "invoice_no" => $invoice_no,
        "invoice_date" => $invoice_date,
        "due_date" => $due_date,
        "subtotal" => $subtotal,
        "tax_total" => $tax_total,
        "grand_total" => $grand_total,
        "status" => $status,
        "created_by" => $user_name
    ],

    "contact" => [
        "id" => $contact['id'],
        "name" => $contact['name'],
        "email" => $contact['email'],
        "number" => $contact['number']
    ],

    "items" => $insertedItems
]);