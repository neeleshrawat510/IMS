<?php

include("../config/connection.php");

if (isset($_POST['ids']) && !empty($_POST['ids'])) {

    $ids = array_map('intval', $_POST['ids']); 

    $idList = implode(',', $ids);

    $query = mysqli_query($conn, "UPDATE `contacts` SET `remove` = 0 WHERE id IN ($idList)");

    if ($query) {
        echo "success";
    } else {
        echo "Something went wrong";
    }

} else {
    echo "No IDs received";
}
?>