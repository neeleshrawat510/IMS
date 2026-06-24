<?php
session_start();


include("../config/connection.php");

$id = intval($_GET['id']);

$sql = mysqli_query($conn,"
SELECT *
FROM contacts
WHERE `id`='$id' AND `remove` = '0'
");

$row = mysqli_fetch_assoc($sql);


header('Content-Type: application/json');
echo json_encode($row);