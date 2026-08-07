<?php

require_once "../includes/api_auth.php";
require_once "../includes/HubSpotService.php";
include("../config/connection.php");

if (isset($_POST['ids']) && !empty($_POST['ids'])) {

    $ids = array_map('intval', $_POST['ids']);
    $idList = implode(',', $ids);

    $result = mysqli_query($conn, "SELECT hubspot_contact_id FROM contacts WHERE id IN ($idList)");

    $archive = mysqli_query($conn, "UPDATE contacts SET remove=1 WHERE id IN ($idList)");

    if ($archive) {

        $hubspot = new HubSpotService();

        while ($row = mysqli_fetch_assoc($result)) {

            if (!empty($row['hubspot_contact_id'])) {

                try {

                    $hubspot->updateContactStatus(
                        $row['hubspot_contact_id'],
                        "Archive"
                    );

                } catch (Exception $e) {

                    error_log($e->getMessage());

                }

            }

        }

        echo "success";

    } else {

        echo "Something went wrong";

    }

} else {

    echo "No IDs received";

}