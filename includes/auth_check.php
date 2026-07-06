<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../api/jwt/jwt.php";

$token = $_COOKIE['auth_token'] ?? null;

if (!$token) {

    session_destroy();

    header("Location: index.php");
    exit();
}

$payload = verifyJWT($token);

if (!$payload) {

    setcookie(
        "auth_token",
        "",
        time() - 3600,
        "/"
    );

    session_destroy();

    header("Location: index.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| Session Synchronization
|--------------------------------------------------------------------------
|
| JWT is the source of truth.
| Session is recreated from JWT.
|
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