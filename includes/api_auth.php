<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../api/jwt/jwt.php";

$token = $_COOKIE['auth_token'] ?? null;

// No JWT found
if (!$token) {

    session_destroy();

    http_response_code(401);

    echo json_encode([
        "status" => "error",
        "message" => "Unauthorized"
    ]);

    exit();
}

// Verify JWT
$payload = verifyJWT($token);

if (!$payload) {

    setcookie(
        "auth_token",
        "",
        time() - 3600,
        "/"
    );

    session_destroy();

    http_response_code(401);

    echo json_encode([
        "status" => "error",
        "message" => "Session expired. Please login again."
    ]);

    exit();
}

/*
|--------------------------------------------------------------------------
| Recreate Session from JWT
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION['user_id']) ||
    $_SESSION['user_id'] != $payload['user_id']
) {

    session_regenerate_id(true);

    $_SESSION['user_id'] = $payload['user_id'];
    $_SESSION['user_name'] = $payload['user_name'];
    $_SESSION['email'] = $payload['email'];
}