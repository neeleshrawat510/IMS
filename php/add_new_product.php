<?php

require_once "../includes/api_auth.php";
require_once "../includes/HubSpotService.php";

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

    $productId = mysqli_insert_id($conn);

    try {

        $hubspot = new HubSpotService();

        $hubspotResponse = $hubspot->createProduct(
            $product_name,
            $product_code,
            $selling_price,
            $tax
        );


        if (
            $hubspotResponse['status'] >= 200 &&
            $hubspotResponse['status'] < 300
        ) {

            $hubspotProductId =
                $hubspotResponse['response']['id'] ?? null;


            if ($hubspotProductId) {

                mysqli_query(
                    $conn,
                    "UPDATE products
                     SET hubspot_product_id='$hubspotProductId'
                     WHERE id='$productId'"
                );
            }

        } else {

            error_log(
                "HubSpot product creation failed: " .
                json_encode($hubspotResponse)
            );
        }

    } catch (Exception $e) {

        error_log(
            "HubSpot product sync error: " .
            $e->getMessage()
        );
    }


    echo "success";

} else {

    echo "failed";
}

?>