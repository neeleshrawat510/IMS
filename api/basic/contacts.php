<?php

header("Content-Type: application/json");


//credentials from request
$user = $_SERVER['PHP_AUTH_USER'] ?? null;
$pass = $_SERVER['PHP_AUTH_PW'] ?? null;

// check and seperate username and password
if (!$user && isset($_SERVER['HTTP_AUTHORIZATION'])) {
    $auth = $_SERVER['HTTP_AUTHORIZATION'];
    if (strpos($auth, 'Basic ') === 0) {
        $decoded = base64_decode(substr($auth, 6));
        list($user, $pass) = explode(':', $decoded);
    }
}


//connection setup
include("../config/connection.php");

//authorization file
include("auth.php");

$method = $_SERVER['REQUEST_METHOD'];
//JSON from request body
$data = json_decode(file_get_contents("php://input"), true);

$returnArray = array();
if ($method == 'POST') {

    $name = $data['name'] ?? '';
    $number = $data['number'] ?? '';
    $email = $data['email'] ?? '';
    $company = $data['company'] ?? '';
    $gst = $data['gst'] ?? '';
    $address = $data['address'] ?? '';


    if ($name == '') {
        $returnArray['status'] = "error";
        $returnArray['message'] = "Please Enter your name";
    } else if ($number == '') {
        $returnArray['status'] = "error";
        $returnArray['message'] = "Please Enter your number";
    } else if (!preg_match('/^[0-9]{10}$/', $number)) {
        $returnArray['status'] = "error";
        $returnArray['message'] = "Please Enter valid 10 digit number";
    } else if ($email == '') {
        $returnArray['status'] = "error";
        $returnArray['message'] = "Please Enter your email";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $returnArray['status'] = "error";
        $returnArray['message'] = "Please Enter valid email (e.g john@gmail.com)";
    } else if ($company == '') {
        $returnArray['status'] = "error";
        $returnArray['message'] = "Please Enter your company";
    } else if ($gst == '') {
        $returnArray['status'] = "error";
        $returnArray['message'] = "Please Enter your gst";
    } else if (!preg_match('/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/', strtoupper($gst))) {
        $returnArray['status'] = "error";
        $returnArray['message'] = "Please Enter valid GST number";
    } else if ($address == '') {
        $returnArray['status'] = "error";
        $returnArray['message'] = "Please Enter your address";
    } else {

        $checkEmail = mysqli_query($conn, "SELECT * FROM contacts WHERE email = '$email'");

        if (mysqli_num_rows($checkEmail) > 0) {

            $returnArray['status'] = "error";
            $returnArray['message'] = "Email already exists";
        } else {
            $checkGst = mysqli_query($conn, "SELECT * FROM contacts WHERE gst = '$gst'");

            if (mysqli_num_rows($checkGst) > 0) {

                $returnArray['status'] = "error";
                $returnArray['message'] = "GSTIN already exists";
            } else {

                $insertContacts = mysqli_query($conn, "INSERT INTO `contacts`(`name`,`number`,`email`,`company`,`gst`,`address`) 
                                                VALUES('$name','$number','$email','$company','$gst','$address')");
                if ($insertContacts) {
                    $returnArray['status'] = true;
                    $returnArray['message'] = "Contact added successfully";
                    $returnArray['id'] = mysqli_insert_id($conn);
                } else {
                    $returnArray['status'] = false;
                    $returnArray['message'] = mysqli_error($conn);
                }
            }
        }
    }

    echo json_encode($returnArray);
}

// if ($method == "GET") {

//     $result = mysqli_query($conn, "SELECT * FROM contacts");

//     $users = [];

//     while ($row = mysqli_fetch_array($result)) {
//         $users[] = $row;
//     }

//     echo json_encode($users);
// }
