<?php

include("../../vendor/autoload.php");
include("jwt.php");

$headers = getallheaders();

$auth = $headers['Authorization'];

$token = str_replace("Bearer ", "", $auth);

$user = verifyJWT($token);

echo json_encode($user);