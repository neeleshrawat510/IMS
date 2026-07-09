<?php

require_once "../includes/api_auth.php";
include("../config/connection.php");
include("../controller/role_check.php");

requireRole("Admin");

$id = intval($_GET['id']);

$sql = mysqli_query($conn,"
SELECT *
FROM contacts
WHERE `id`='$id' AND `remove` = '0'
");

$row = mysqli_fetch_assoc($sql);


header('Content-Type: application/json');
echo json_encode($row);