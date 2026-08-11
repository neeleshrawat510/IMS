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

    $productId = mysqli_insert_id($conn);

    error_log("IMS PRODUCT CREATED: " . $productId);

    try {

        $hubspot = new HubSpotService();

        error_log("HUBSPOT SERVICE CREATED");

        $hubspotResponse = $hubspot->createProduct(
            $product_code,
            $product_name,
            $selling_price,
            $tax
        );

        error_log(
            "HUBSPOT RESPONSE: " .
            json_encode($hubspotResponse)
        );


        if (
            isset($hubspotResponse['status']) &&
            $hubspotResponse['status'] >= 200 &&
            $hubspotResponse['status'] < 300
        ) {

            $hubspotProductId =
                $hubspotResponse['response']['id'] ?? null;

            error_log(
                "HUBSPOT PRODUCT ID: " .
                ($hubspotProductId ?? 'NULL')
            );


            if ($hubspotProductId) {

                $updateSql = "
                    UPDATE products
                    SET hubspot_product_id = '$hubspotProductId'
                    WHERE id = '$productId'
                ";

                $updateResult = mysqli_query($conn, $updateSql);


                if ($updateResult) {

                    error_log(
                        "HUBSPOT PRODUCT ID SAVED: " .
                        $hubspotProductId
                    );

                } else {

                    error_log(
                        "FAILED TO SAVE HUBSPOT PRODUCT ID: " .
                        mysqli_error($conn)
                    );
                }

            } else {

                error_log(
                    "HUBSPOT PRODUCT ID IS NULL"
                );
            }

        } else {

            error_log(
                "HUBSPOT PRODUCT CREATION FAILED: " .
                json_encode($hubspotResponse)
            );
        }

    } catch (Exception $e) {

        error_log(
            "HUBSPOT EXCEPTION: " .
            $e->getMessage()
        );
    }

    echo "success";

} else {

    error_log(
        "IMS PRODUCT INSERT FAILED: " .
        mysqli_error($conn)
    );

    echo "failed";
}
?>