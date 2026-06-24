<?php
include("../../config/connection.php");
include("../../vendor/autoload.php");

date_default_timezone_set("Asia/Kolkata");

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secret =  "3!@^4*&(5*&^#6%$^&#7@%$%$8%$#*(9";

function generateJWT($payload){
    global $secret;

    return JWT:: encode($payload, $secret, 'HS256');
}

function verifyJWT($jwt){
    global $secret;

    try{
        $decoded = JWT::decode($jwt, new key($secret, 'HS256'));
        return (array) $decoded;
    }catch (Exception $e){
        return false;
    }
}

?>