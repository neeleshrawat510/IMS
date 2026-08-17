<?php

require_once "includes/auth_check.php";
require_once "includes/HubSpotService.php";

include("config/connection.php");

$productId = intval($_POST['id']);


// Get HubSpot Product ID
$get = mysqli_query(
    $conn,
    "SELECT hubspot_product_id
     FROM products
     WHERE id='$productId'"
);

$row = mysqli_fetch_assoc($get);

$hubspotProductId = $row['hubspot_product_id'] ?? null;


// Archive Product in HubSpot first
if (!empty($hubspotProductId)) {

    try {

        $hubspot = new HubSpotService();

        $hubspotResponse = $hubspot->deleteProduct(
            $hubspotProductId
        );

        // Check HubSpot response
        if (
            $hubspotResponse['status'] < 200 ||
            $hubspotResponse['status'] >= 300
        ) {

            error_log(
                "HubSpot product archive failed: " .
                json_encode($hubspotResponse)
            );

            echo "failed";
            exit;
        }

        error_log(
            "HubSpot product archived successfully: " .
            $hubspotProductId
        );

    } catch (Exception $e) {

        error_log(
            "HubSpot product archive error: " .
            $e->getMessage()
        );

        echo "failed";
        exit;
    }
}


// Delete Product from IMS
$delete = mysqli_query(
    $conn,
    "DELETE FROM products
     WHERE id='$productId'"
);

if ($delete) {

    echo "success";

} else {

    error_log(
        "IMS product deletion failed: " .
        mysqli_error($conn)
    );

    echo "failed";
}

?>