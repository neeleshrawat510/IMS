<?php

require_once "../includes/api_auth.php";
require_once "../includes/HubSpotService.php";
include("../config/connection.php");

$contactId = intval($_POST['id']);

$get = mysqli_query($conn, "SELECT hubspot_contact_id FROM contacts WHERE id='$contactId'");
$row = mysqli_fetch_assoc($get);

$hubspotId = $row['hubspot_contact_id'] ?? null;

$unarchive = mysqli_query($conn, "UPDATE contacts SET remove=0 WHERE id='$contactId'");

if ($unarchive) {

    if (!empty($hubspotId)) {
        try {
            $hubspot = new HubSpotService();
            $hubspot->updateContactStatus($hubspotId, "Active");
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    echo "success";

} else {

    echo "failed";

}