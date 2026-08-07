<?php

require_once "../includes/api_auth.php";
require_once "../includes/HubSpotService.php";
include("../config/connection.php");

$contactId = intval($_POST['id']);

$get = mysqli_query($conn, "SELECT hubspot_contact_id FROM contacts WHERE id='$contactId'");
$row = mysqli_fetch_assoc($get);

$hubspotId = $row['hubspot_contact_id'] ?? null;

$archive = mysqli_query($conn, "UPDATE contacts SET remove=1 WHERE id='$contactId'");

if ($archive) {

    if (!empty($hubspotId)) {
        try {
            $hubspot = new HubSpotService();
            $hubspot->updateContactStatus($hubspotId, "Archived");
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    echo "success";

} else {

    echo "failed";

}