<?php

require_once "../includes/api_auth.php";
include("../config/connection.php");
include("../controller/role_check.php");

requireRole("Admin");

if (isset($_POST['ids']) && !empty($_POST['ids'])) {

    $ids = array_map('intval', $_POST['ids']); 

    $idList = implode(',', $ids);

    $deleteProducts = mysqli_query($conn, "DELETE FROM `products` WHERE id IN ($idList)");

    if ($deleteProducts) {
        echo "success";
    } else {
        echo mysqli_error($conn);
    }

} else {
    echo "No IDs received";
}
?>