<?php
require_once "../includes/api_auth.php";
include("../config/connection.php");

require_once("../config/connection.php");

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Please login first."
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];

$current_password = trim($_POST['current_password']);
$new_password = trim($_POST['new_password']);
$confirm_password = trim($_POST['confirm_password']);

if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
    echo json_encode([
        "status" => "error",
        "message" => "All fields are required."
    ]);
    exit;
}

if ($new_password != $confirm_password) {
    echo json_encode([
        "status" => "error",
        "message" => "Passwords do not match."
    ]);
    exit;
}

if (strlen($new_password) < 6) {
    echo json_encode([
        "status" => "error",
        "message" => "Password must be at least 6 characters."
    ]);
    exit;
}

$current_password = mysqli_real_escape_string($conn, $current_password);
$new_password = mysqli_real_escape_string($conn, $new_password);
$confirm_password = mysqli_real_escape_string($conn, $confirm_password);
$sql = "SELECT password FROM users WHERE id='$user_id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo json_encode([
        "status" => "error",
        "message" => "User not found."
    ]);
    exit;
}

$user = mysqli_fetch_assoc($result);

// Verify current password
if ($user['password'] != md5($current_password)) {
    echo json_encode([
        "status" => "error",
        "message" => "Current password is incorrect."
    ]);
    exit;
}

// Check same password
if ($user['password'] == md5($new_password)) {
    echo json_encode([
        "status" => "error",
        "message" => "New password cannot be the same as the current password."
    ]);
    exit;
}

$newHash = md5($new_password);

$update = "UPDATE users SET password='$newHash' WHERE id='$user_id'";

if (mysqli_query($conn, $update)) {
    echo json_encode([
        "status" => "success",
        "message" => "Password changed successfully."
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to change password."
    ]);
}
?>