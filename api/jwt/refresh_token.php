<?php

include("../../config/connection.php");
include("jwt.php");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['refresh_token'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Refresh token required"
    ]);
    exit;
}

$refreshToken = $data['refresh_token'];

$result = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE refresh_token='$refreshToken'"
);

if (mysqli_num_rows($result) == 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid refresh token"
    ]);
    exit;
}

$user = mysqli_fetch_assoc($result);


// Verify refresh token and get $user

// Generate new JWT
$payload = [
    "user_id" => $user['id'],
    "email"   => $user['email'],
    "name"    => $user['name'],
    "iat"     => time(),
    "exp"     => time() + 3600
];

$newJwt = generateJWT($payload);

// Rotate refresh token
$newRefreshToken = bin2hex(random_bytes(64));

// Save new refresh token
mysqli_query(
    $conn,
    "UPDATE users
     SET refresh_token='$newRefreshToken'
     WHERE id=".$user['id']
);

// Return new tokens
echo json_encode([
    "status" => "success",
    "jwt" => $newJwt,
    "refresh_token" => $newRefreshToken
]);