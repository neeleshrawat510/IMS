<?php

require_once "../includes/api_auth.php";
include("../config/connection.php");

//indian timezone
date_default_timezone_set('Asia/Kolkata');

header('Content-Type: application/json');


$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

if ($action == "upload_photo") {

    if (!isset($_FILES['photo'])) {
        echo json_encode([
            "status" => "error",
            "message" => "No file selected."
        ]);
        exit;
    }

    $file = $_FILES['photo'];

    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        echo json_encode([
            "status" => "error",
            "message" => "Only JPG, JPEG, PNG and WEBP files are allowed."
        ]);
        exit;
    }

    if ($file['size'] > 2 * 1024 * 1024) {
        echo json_encode([
            "status" => "error",
            "message" => "Maximum file size is 2MB."
        ]);
        exit;
    }

    if (!is_dir("../uploads/profile")) {
        mkdir("../uploads/profile", 0777, true);
    }

    $filename = "user_" . $user_id . "_" . time() . "." . $ext;

    $target = "../uploads/profile/" . $filename;

    if (move_uploaded_file($file['tmp_name'], $target)) {

        // Delete old photo
        $old = mysqli_fetch_assoc(mysqli_query($conn, "SELECT profile_photo FROM users WHERE id='$user_id'"));

        if (!empty($old['profile_photo'])) {

            $oldFile = "../" . $old['profile_photo'];

            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        $path = "uploads/profile/" . $filename;

        mysqli_query($conn, "UPDATE users SET profile_photo='$path' WHERE id='$user_id'");

        echo json_encode([
            "status" => "success",
            "photo" => $path
        ]);

    } else {

        echo json_encode([
            "status" => "error",
            "message" => "Unable to upload photo."
        ]);

    }

    exit;
} elseif ($action == "remove_photo") {

    $result = mysqli_query($conn, "SELECT profile_photo FROM users WHERE id='$user_id'");
    $row = mysqli_fetch_assoc($result);

    if (!empty($row['profile_photo'])) {

        $file = "../" . $row['profile_photo'];

        if (file_exists($file)) {
            unlink($file);
        }
    }

    mysqli_query($conn, "UPDATE users SET profile_photo=NULL WHERE id='$user_id'");

    echo json_encode([
        "status" => "success"
    ]);

    exit;
} else {

    // update profile

    $name = trim(mysqli_real_escape_string($conn, $_POST['name'] ?? ''));
    $email = trim(mysqli_real_escape_string($conn, $_POST['email'] ?? ''));
    $number = trim(mysqli_real_escape_string($conn, $_POST['number'] ?? ''));



    $update = mysqli_query($conn, "UPDATE `users` SET 
                                        `name` = '$name',
                                        `email` = '$email',
                                        `number` = '$number'
                                        WHERE `id` = '$user_id'");

    header('Content-Type: application/json');

    if ($update) {
        echo json_encode([
            "status" => "success",
            "email_status" => true
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => mysqli_error($conn)
        ]);
    }
}
?>