<?php

$conn = mysqli_connect('hayabusa.proxy.rlwy.net','root','vlGfzDyXIncakRtZQhQlDbQKFVncJwAD','railway', '59389');

if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}
?>