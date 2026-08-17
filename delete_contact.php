<?php

require_once "includes/auth_check.php";
require_once "includes/HubSpotService.php";
include("config/connection.php");

$contactId = intval($_POST['id']);

// Get HubSpot contact ID
$get = mysqli_query(
    $conn,
    "SELECT hubspot_contact_id
     FROM contacts
     WHERE id='$contactId'"
);

$row = mysqli_fetch_assoc($get);

$hubspotId = $row['hubspot_contact_id'] ?? null;


// Archive contact in HubSpot first
if (!empty($hubspotId)) {

    try {

        $hubspot = new HubSpotService();

        $hubspotResponse = $hubspot->deleteContact($hubspotId);

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


// Delete contact from IMS
$delete = mysqli_query(
    $conn,
    "DELETE FROM contacts
     WHERE id='$contactId'"
);

if ($delete) {

    echo "success";

} else {

    error_log(
        "IMS contact deletion failed: " .
        mysqli_error($conn)
    );

    echo "failed";
}