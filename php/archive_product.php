<?php

require_once "../includes/api_auth.php";
require_once "../includes/HubSpotService.php";
include("../config/connection.php");

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


// Update HubSpot Product status
if (!empty($hubspotProductId)) {

    try {

        $hubspot = new HubSpotService();

        $hubspotResponse = $hubspot->updateProductStatus(
            $hubspotProductId,
            "Inactive"
        );
        error_log(
    "HubSpot Product Status Response: " .
    json_encode($hubspotResponse)
);

        if (
            $hubspotResponse['status'] < 200 ||
            $hubspotResponse['status'] >= 300
        ) {

            error_log(
                "HubSpot product status update failed: " .
                json_encode($hubspotResponse)
            );

            echo "failed";
            exit;
        }

        error_log(
            "HubSpot product status updated to Archive: " .
            $hubspotProductId
        );

    } catch (Exception $e) {

        error_log(
            "HubSpot product status update error: " .
            $e->getMessage()
        );

        echo "failed";
        exit;
    }
}


// Archive Product in IMS
$archiveProduct = mysqli_query(
    $conn,
    "UPDATE products
     SET remove='1'
     WHERE id='$productId'"
);

if ($archiveProduct) {

    echo "success";

} else {

    error_log(
        "IMS product archive failed: " .
        mysqli_error($conn)
    );

    echo "failed";
}

?>