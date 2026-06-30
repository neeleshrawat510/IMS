<?php

include("../../config/connection.php");
include("../../vendor/autoload.php");
include("jwt.php");

$client = new Google_Client([
    'client_id' => 'YOUR_GOOGLE_CLIENT_ID'
]);

$data = json_decode(file_get_contents("php://input"), true);

$idToken = $data['id_token'];

try {

    $payload = $client->verifyIdToken($idToken);

    if (!$payload) {

        echo json_encode([
            "status"=>"error",
            "message"=>"Invalid Google Token"
        ]);

        exit;
    }

    $email = $payload['email'];
    $name = $payload['name'];

    $result = mysqli_query($conn,
        "SELECT * FROM users WHERE email='$email'");

    if(mysqli_num_rows($result)==0){

        mysqli_query($conn,
            "INSERT INTO users(name,email)
             VALUES('$name','$email')");

        $userId = mysqli_insert_id($conn);

    }else{

        $user = mysqli_fetch_assoc($result);
        $userId = $user['id'];

    }

    $jwtPayload = [

        "user_id"=>$userId,
        "email"=>$email,
        "name"=>$name,
        "iat"=>time()
    ];

    $jwt = generateJWT($jwtPayload);

    echo json_encode([

        "status"=>"success",
        "jwt"=>$jwt

    ]);

}catch(Exception $e){

    echo json_encode([
        "status"=>"error",
        "message"=>$e->getMessage()
    ]);
}