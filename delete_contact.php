<?php

require_once "includes/auth_check.php";
require_once "includes/HubSpotService.php";
include("config/connection.php");

$contactId = intval($_POST['id']);

$get = mysqli_query($conn, "SELECT hubspot_contact_id FROM contacts WHERE id='$contactId'");
$row = mysqli_fetch_assoc($get);

$hubspotId = $row['hubspot_contact_id'] ?? null;

$delete = mysqli_query($conn, "DELETE FROM contacts WHERE id='$contactId'");

if ($delete) {

    if (!empty($hubspotId)) {

        try {

            $hubspot = new HubSpotService();

            $hubspot->archiveContact($hubspotId);

        } catch (Exception $e) {

            error_log($e->getMessage());

        }

    }

    echo "success";

} else {

    echo "failed";

}