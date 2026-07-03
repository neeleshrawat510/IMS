<?php

$conn = mysqli_connect('mysql.railway.internal','root','vlGfzDyXIncakRtZQhQlDbQKFVncJwAD','railway', '3306');

if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}
?>