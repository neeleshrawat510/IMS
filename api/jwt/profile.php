<?php

include ("jwt.php");

$headers = getallheaders();

if(!isset($headers["Authorization"])){
echo json_encode([
    "response" => "error",
    "message" => "Token Missing"
]);
exit;
}

$token = str_replace("Bearer ", "", $headers["Authorization"]);

$data = verifyJWT($token);

if(!$data){
    echo json_encode([
        "response" => "error",
        "message" => "Invalid Token"
    ]);
    exit;
}

echo json_encode([
        "response" => "Success",
        "message" => "Access Granted",
        "user" => $data
])

?>