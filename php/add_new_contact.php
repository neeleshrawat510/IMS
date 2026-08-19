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
    // Get newly created IMS contact ID
    $contactId = mysqli_insert_id($conn);

    // Send new contact to Zapier
    try {

        $zapierUrl = getenv('ZAPIER_WEBHOOK_URL');

        $zapierData = [
            "event" => "contact.created",
            "contact_id" => $contactId,
            "name" => $name,
            "email" => $email,
            "phone" => $number,
            "company" => $company,
            "gst" => $gst,
            "address" => $address,
            "created_at" => $todayDate,
            "created_by" => $created_by
        ];

        $ch = curl_init($zapierUrl);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($zapierData));

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json"
        ]);

        $zapierResponse = curl_exec($ch);

        if (curl_errno($ch)) {

            error_log(
                "Zapier Webhook Error: " .
                curl_error($ch)
            );

        } else {

            $zapierStatus = curl_getinfo(
                $ch,
                CURLINFO_HTTP_CODE
            );

            error_log(
                "Zapier Webhook Response: HTTP " .
                $zapierStatus .
                " | " .
                $zapierResponse
            );
        }

        curl_close($ch);

    } catch (Exception $e) {

        // Zapier failure should NOT stop contact creation
        error_log(
            "Zapier Error: " .
            $e->getMessage()
        );
    }

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