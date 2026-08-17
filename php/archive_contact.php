<?php

require_once "../includes/api_auth.php";
require_once "../includes/HubSpotService.php";
include("../config/connection.php");

$contactId = intval($_POST['id']);


// Get HubSpot Contact ID
$get = mysqli_query(
    $conn,
    "SELECT hubspot_contact_id
     FROM contacts
     WHERE id='$contactId'"
);

$row = mysqli_fetch_assoc($get);

$hubspotId = $row['hubspot_contact_id'] ?? null;


// Archive in HubSpot first
if (!empty($hubspotId)) {

    try {

        $hubspot = new HubSpotService();

        $hubspotResponse = $hubspot->updateContactStatus(
            $hubspotId,
            "Archive"
        );

        // Check HubSpot response
        if (
            $hubspotResponse['status'] < 200 ||
            $hubspotResponse['status'] >= 300
        ) {
            

            error_log(
                "HubSpot contact archive failed: " .
                json_encode($hubspotResponse)
            );

            echo "failed";
            exit;
        }

        error_log(
            "HubSpot contact archived successfully: " .
            $hubspotId
        );

    } catch (Exception $e) {

        error_log(
            "HubSpot contact archive error: " .
            $e->getMessage()
        );

        echo "failed";
        exit;
    }
}


// Archive in IMS
$archive = mysqli_query(
    $conn,
    "UPDATE contacts
     SET remove=1
     WHERE id='$contactId'"
);


if ($archive) {

    echo "success";

} else {

    error_log(
        "IMS contact archive failed: " .
        mysqli_error($conn)
    );

    echo "failed";
}