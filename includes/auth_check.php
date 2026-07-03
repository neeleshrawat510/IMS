<?php
// Include at the top of every protected page:
// require_once("includes/auth_check.php");

require_once __DIR__ . "/../api/jwt/jwt.php";

$token = $_COOKIE['auth_token'] ?? null;

if (!$token) {
    header("location: /index.php");
    exit();
}

$payload = verifyJWT($token);

if (!$payload) {
    setcookie('auth_token', '', time() - 3600, '/');
    header("location: /index.php");
    exit();
}

session_start();
$_SESSION['user_id']   = $payload['user_id'];
$_SESSION['email']     = $payload['email'] ?? null;
$_SESSION['user_name'] = $payload['user_name'] ?? ($payload['name'] ?? null);