<?php

$conn = mysqli_connect('hayabusa.proxy.rlwy.net','root','vlGfzDyXIncakRtZQhQlDbQKFVncJwAD','railway', '59389');

if (!$conn) {
    die("DB Connection failed: "  . mysqli_connect_error());
}
?>