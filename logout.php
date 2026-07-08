<?php
//session
session_start();

//connection setup
include("config/connection.php");

//remove refresh token from db
if (isset($_SESSION['user_id'])){
    $userId = $_SESSION['user_id'];

    mysqli_query($conn, "UPDATE users SET refresh_token = NULL, refresh_token_expires_at = NULL 
                                WHERE id='$userId'");
}


//destroy everthing stored in session
$_SESSION =[];

session_destroy();


//delete JWT cookie

setcookie(
    "auth_token", "", time() -3600, "/"
);

//delete refresh token
setcookie(
    "refresh_token", "", time() -3600, "/"
);

echo "success";
?>