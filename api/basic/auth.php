<?php

if (empty($user) || empty($pass)) {
    echo json_encode([
        "status" => "error",
        "message" => "Username and password required"
    ]);
    exit;
}

// email format validation
if (!filter_var($user, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid email format"
    ]);
    exit;
}

//password format validation
$pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";

if (!preg_match($pattern, $pass)) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid password format"
    ]);
    exit;
}

$user = mysqli_real_escape_string($conn, $user);
$pass = md5($pass ?? '');

$authUser = mysqli_query($conn, "SELECT * FROM `users` WHERE `email`= '$user' AND `password` = '$pass'");

if (mysqli_num_rows($authUser) == 0) {
    header('WWW-Authenticate: Basic realm="My API"');
    header('HTTP/1.0 401 Unauthorized');

    echo json_encode([
        "status" => "error",
        "message" => "Unauthorized user"
    ]);
    exit;
}

?>