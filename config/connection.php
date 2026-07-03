<?php

$conn = mysqli_connect('hayabusa.proxy.rlwy.net','root','WrQipwjnRMzSQlQWEPJoklzlGKVdkKcv','railway', '28665');

if (!$conn) {
    die("DB Connection failed: "  . mysqli_connect_error());
}
?>