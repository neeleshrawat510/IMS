<?php

require_once "../includes/api_auth.php";
include("../controller/role_check.php");
var_dump(headers_sent($file, $line));
var_dump($file, $line);
exit;

requireRole("Admin");

include("../config/connection.php");


$sql = mysqli_query($conn, "SELECT * FROM `contacts` WHERE `remove` = '0' ORDER BY id DESC");

$data = [];
$sr = 1;
if (mysqli_num_rows($sql) > 0) {
    while ($row = mysqli_fetch_array($sql)) {

        $data[] = [
            $row['id'],
            $sr++,
            $row['name'],
            $row['number'],
            $row['email'],
            $row['company'],
            '<a href="edit_contact.php?id=' . $row['id'] . '" class="btn btn-success btn-sm me-1"  title="Edit">
                <i class="bi bi-pencil"></i>
            </a>
            <a href="#" class="btn btn-primary btn-sm archive-btn"  title="Archive" data-id="' . $row['id'] . '"> 
            <i class="bi bi-box-arrow-down"></i>
            </a>
            <a href="#" class="btn btn-danger btn-sm delete-btn"  title="Delete" data-id="' . $row['id'] . '"> 
            <i class="bi bi-trash"></i>
            </a>'
        ];
    }
}
header('Content-Type: application/json');
echo json_encode($data);
