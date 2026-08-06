<?php

require_once "../includes/api_auth.php";
include("../config/connection.php");
require_once "../includes/HubSpotService.php";

$name = trim(mysqli_real_escape_string($conn, $_POST['name'] ?? ''));
$number = trim(mysqli_real_escape_string($conn, $_POST['number'] ?? ''));
$email = trim(mysqli_real_escape_string($conn, $_POST['email'] ?? ''));
$company = trim(mysqli_real_escape_string($conn, $_POST['company'] ?? ''));
$gst = trim(mysqli_real_escape_string($conn, $_POST['gst'] ?? ''));
$address = trim(mysqli_real_escape_string($conn, $_POST['address'] ?? ''));
$todayDate = date('Y-m-d H:i:s');
$created_by = $_SESSION['user_name'];


$insert = mysqli_query($conn, "INSERT INTO `contacts` (`name`, `number`, `email`,`company`, `gst`, `address`, `created_at`, `created_by`) VALUES('$name', '$number', '$email', '$company', '$gst', '$address', '$todayDate', '$created_by')");

if ($insert) {

    try {

        $hubspot = new HubSpotService();

        // Split full name into first & last name
        $nameParts = explode(' ', trim($name), 2);

        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? '';

        $hubspotResponse = $hubspot->createContact(
            $firstName,
            $lastName,
            $email,
            $number,
            $company,
            $address,
            $gst
        );

        $hubspotId = $hubspotResponse['response']['id'] ?? NULL;

        //update hubspot contact id in DB
        if ($hubspotId) {

            $contactId = mysqli_insert_id($conn);

            mysqli_query(
                $conn,
                "UPDATE contacts
         SET hubspot_contact_id = '$hubspotId'
         WHERE id = '$contactId'"
            );

        }

        // Optional: log response for debugging
        // file_put_contents("hubspot.log", print_r($hubspotResponse, true), FILE_APPEND);

    } catch (Exception $e) {

        // Don't stop IMS if HubSpot fails.
        error_log("HubSpot Error: " . $e->getMessage());

    }

    echo "success";

} else {

    echo "failed";

}
?>