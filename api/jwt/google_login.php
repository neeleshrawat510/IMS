<?php

include("../../config/connection.php");
include("../../vendor/autoload.php");
include("jwt.php");

$client = new Google_Client([
    'client_id' => getenv('client_id')
]);

$data = json_decode(file_get_contents("php://input"), true);

$idToken = $data['id_token'];

try {

    $payload = $client->verifyIdToken($idToken);

    if (!$payload) {

        echo json_encode([
            "status" => "error",
            "message" => "Invalid Google Token"
        ]);

        exit;
    }

    $email = $payload['email'];
    $name = $payload['name'];

    $result = mysqli_query(
        $conn,
        "SELECT * FROM users WHERE email='$email'"
    );

    if (mysqli_num_rows($result) == 0) {

        mysqli_query(
            $conn,
            "INSERT INTO users(name,email)
             VALUES('$name','$email')"
        );

        $userId = mysqli_insert_id($conn);

    } else {

        $user = mysqli_fetch_assoc($result);
        $userId = $user['id'];

    }

    $jwtPayload = [
    "user_id"   => $userId,
    "user_name" => $name,
    "email"     => $email
];

    $jwt = generateJWT($jwtPayload);


    //generate refresh token
    $refreshToken = bin2hex(random_bytes(64));
    $expiresAt = date('Y-m-d H:i:s', strtotime('+30 days'));

    mysqli_query(
        $conn,
        "UPDATE users 
         SET refresh_token='$refreshToken', refresh_token_expires_at='$expiresAt'

         WHERE id=$userId"
    );


    echo json_encode([

        "status" => "success",
        "token" => $jwt,
        "refresh_token" => $refreshToken

    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}