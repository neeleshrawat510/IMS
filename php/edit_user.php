<?php

require_once "../includes/api_auth.php";
if ($_SESSION['role'] !== 'Admin') {
    http_response_code(403);

    echo json_encode([
        "status" => "error",
        "message" => "Access denied."
    ]);

    exit;
}
include("../config/connection.php");

date_default_timezone_set('Asia/Kolkata');

header("Content-Type: application/json");

// User ID to edit (comes from hidden field)
$user_id = (int)($_POST['id'] ?? 0);

if ($user_id <= 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid user ID."
    ]);
    exit;
}

$action = $_POST['action'] ?? '';

if ($action === "upload_photo") {

    // Upload photo

} elseif ($action === "remove_photo") {

    // Remove photo

} else {

    $name = trim(mysqli_real_escape_string($conn, $_POST['name'] ?? ''));
    $email = trim(mysqli_real_escape_string($conn, $_POST['email'] ?? ''));
    $number = trim(mysqli_real_escape_string($conn, $_POST['number'] ?? ''));

    $update = mysqli_query($conn, "
        UPDATE users
        SET
            name = '$name',
            email = '$email',
            number = '$number'
        WHERE id = '$user_id'
    ");

    if ($update) {

        if (mysqli_affected_rows($conn) >= 0) {
            echo json_encode([
                "status" => "success"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "No changes made."
            ]);
        }

    } else {

        echo json_encode([
            "status" => "error",
            "message" => mysqli_error($conn)
        ]);

    }
}