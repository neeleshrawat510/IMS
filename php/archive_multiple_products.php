<?php

include("../config/connection.php");

if (isset($_POST['ids']) && !empty($_POST['ids'])) {

    $ids = array_map('intval', $_POST['ids']); 

    $idList = implode(',', $ids);

    $archiveProducts = mysqli_query($conn, "UPDATE `products` SET `remove` = 1 WHERE id IN ($idList)");

    if ($archiveProducts) {
        echo "success";
    } else {
        echo mysqli_error($conn);
    }

} else {
    echo "No IDs received";
}
?>