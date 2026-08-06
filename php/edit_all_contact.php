<?php

require_once "../includes/api_auth.php";
include("../config/connection.php");
require_once "../includes/HubSpotService.php";

$contactId = $_POST['id'] ?? null;

if (!$contactId) {
    echo "missing id";
    exit();
}

$name = trim(mysqli_real_escape_string($conn, $_POST['name']  ??  ''));
$number = trim(mysqli_real_escape_string($conn, $_POST['number']  ??  ''));
$email = trim(mysqli_real_escape_string($conn, $_POST['email']  ??  ''));
$company = trim(mysqli_real_escape_string($conn, $_POST['company']  ??  ''));
$gst = trim(mysqli_real_escape_string($conn, $_POST['gst']  ??  ''));
$address = trim(mysqli_real_escape_string($conn, $_POST['address']  ??  ''));
$todayDate = date('Y-m-d H:i:s'); //set update date & time

$getHubspotId = mysqli_query(
    $conn,
    "SELECT hubspot_contact_id FROM contacts WHERE id='$contactId'"
);

$contact = mysqli_fetch_assoc($getHubspotId);

$hubspotContactId = $contact['hubspot_contact_id'] ?? null;

$update = mysqli_query($conn, "UPDATE `contacts` SET
                                                `name` = '$name',
                                                `number` = '$number',
                                                `email` = '$email',
                                                `company` = '$company',
                                                `gst` = '$gst',
                                                `address` = '$address',
                                                `updated_at` = '$todayDate'
                                            WHERE `id` = '$contactId'");

if ($update) {

    try {

        if ($hubspotContactId) {

            $hubspot = new HubSpotService();

            $nameParts = explode(' ', trim($name), 2);

            $firstName = $nameParts[0];
            $lastName  = $nameParts[1] ?? '';

            $hubspot->updateContact(
                $hubspotContactId,
                $firstName,
                $lastName,
                $email,
                $number,
                $company,
                $address,
                $gst
            );

        }

    } catch (Exception $e) {

        error_log("HubSpot Update Error : " . $e->getMessage());

    }

    echo "success";

} else {

    echo "failed";

}

?>