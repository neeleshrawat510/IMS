<?php

require_once "../includes/api_auth.php";
require_once "../includes/HubSpotService.php";

// Indian Timezone
date_default_timezone_set("ASIA/KOLKATA");

include("../config/connection.php");


$product_code = trim(
    mysqli_real_escape_string(
        $conn,
        $_POST['product_code'] ?? ''
    )
);

$product_name = trim(
    mysqli_real_escape_string(
        $conn,
        $_POST['product_name'] ?? ''
    )
);

$cost_price = trim(
    mysqli_real_escape_string(
        $conn,
        $_POST['cost_price'] ?? ''
    )
);

$selling_price = trim(
    mysqli_real_escape_string(
        $conn,
        $_POST['selling_price'] ?? ''
    )
);

$tax = trim(
    mysqli_real_escape_string(
        $conn,
        $_POST['tax'] ?? ''
    )
);

$todayDate = date('Y-m-d H:i:s');
$created_by = $_SESSION['user_name'];


$insert = mysqli_query(
    $conn,
    "INSERT INTO products
    (
        product_code,
        product_name,
        cost_price,
        selling_price,
        tax,
        created_at,
        created_by
    )
    VALUES
    (
        '$product_code',
        '$product_name',
        '$cost_price',
        '$selling_price',
        '$tax',
        '$todayDate',
        '$created_by'
    )"
);


if ($insert) {

    // Get newly created IMS product ID
    $productId = mysqli_insert_id($conn);

    try {

        // Create product in HubSpot
        $hubspot = new HubSpotService();

        $hubspotResponse = $hubspot->createProduct(
            $product_code,
            $product_name,
            $selling_price,
            $tax
        );


        // Check HubSpot response
        if (
            $hubspotResponse['status'] >= 200 &&
            $hubspotResponse['status'] < 300
        ) {

            $hubspotProductId =
                $hubspotResponse['response']['id'] ?? null;


            // Save HubSpot Product ID in IMS
            if ($hubspotProductId) {

                mysqli_query(
                    $conn,
                    "UPDATE products
                     SET hubspot_product_id='$hubspotProductId'
                     WHERE id='$productId'"
                );

                error_log(
                    "HubSpot product created successfully. " .
                    "IMS Product ID: $productId, " .
                    "HubSpot Product ID: $hubspotProductId"
                );
            }

        } else {

            error_log(
                "HubSpot product creation failed: " .
                json_encode($hubspotResponse)
            );
        }


    } catch (Exception $e) {

        // Do not fail IMS product creation
        // if HubSpot sync fails

        error_log(
            "HubSpot product sync error: " .
            $e->getMessage()
        );
    }


    // IMS product creation succeeded
    echo "success";


} else {

    echo "failed";
}

?>