<?php
include ("../../config/connection.php");
include ("../../vendor/autoload.php");
include ("jwt.php");

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$data = json_decode(file_get_contents("php://input"), true);

$email = $data['email'];
$password = md5($data['password']);


// if (empty($email) || empty($password)) {
//     echo json_encode([
//         "status" => "error",
//         "message" => "Email and password required"
//     ]);
//     exit;
// }

// // email format validation
// if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//     echo json_encode([
//         "status" => "error",
//         "message" => "Invalid email format"
//     ]);
//     exit;
// }

// //password format validation
// $pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";

// if (!preg_match($pattern, $password)) {
//     echo json_encode([
//         "status" => "error",
//         "message" => "Invalid password format"
//     ]);
//     exit;
// }



$result = mysqli_query($conn, "SELECT * FROM `users` WHERE `email` = '$email' AND `password` = '$password'");

if(mysqli_num_rows($result) == 0){
    echo json_encode([
        "response"=> "error",
        "message" => "User not found"
    ]);
    exit;
}

$user = mysqli_fetch_array($result);

$payload = [
    "user_id" => $user['id'],
    "user_name" => $user['name'],
    "email" => $user['email'],
    "iat" => time()

];

$token = generateJWT($payload);

echo json_encode([
    "token"=> $token
]);
?>