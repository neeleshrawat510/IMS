<?php

include("../../config/connection.php");
include("jwt.php");

$data = json_decode(file_get_contents("php://input"), true);

$email = $data['email'];
$password = md5($data['password']);

$result = mysqli_query($conn, "
    SELECT * 
    FROM users 
    WHERE email='$email' 
    AND password='$password'
");

if (mysqli_num_rows($result) == 0) {

    echo json_encode([
        "status" => "error",
        "message" => "User not found"
    ]);

    exit;
}

$user = mysqli_fetch_assoc($result);

$payload = [
    "user_id" => $user['id'],
    "user_name" => $user['name'],
    "email" => $user['email']
];

$token = generateJWT($payload);


// Generate Refresh Token
$refreshToken = bin2hex(random_bytes(64));
$expiresAt = date("Y-m-d H:i:s", strtotime("+30 days"));

mysqli_query(
    $conn,
    "UPDATE users
     SET
        refresh_token='$refreshToken',
        refresh_token_expires_at='$expiresAt'
     WHERE id=".$user['id']
);


echo json_encode([
    "status" => "success",
    "token" => $token,
    "refresh_token" => $refreshToken
]);

?>