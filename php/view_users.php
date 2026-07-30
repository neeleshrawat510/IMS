<?php

require_once "../includes/api_auth.php";
include("../config/connection.php");
include("../controller/role_check.php");

requireRole("Admin");
$allUsers = mysqli_query($conn, "SELECT * FROM `users` WHERE role = 'user' ORDER BY id DESC");

$data = [];
$sr = 1;

if (mysqli_num_rows($allUsers) > 0) {
    while ($row = mysqli_fetch_array($allUsers)) {
        $data[] = [
            $sr++,
            $row['name'],
            $row['email'],
            $row['number'],
            
            '<a href="user_update.php?id=' . $row['id'] . '" class="btn btn-success btn-sm me-1" title="Edit">
                <i class="bi bi-pencil"></i>
            </a>
            <a href="#" class="btn btn-danger btn-sm delete-btn" title="Delete" data-id="' . $row['id'] . '">                  
            <i class="bi bi-trash"></i>
            </a>'

        ];
    }

}
header('Content-Type: application/json');
echo json_encode($data);

?>