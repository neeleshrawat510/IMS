<?php

include("../config/connection.php");

if (isset($_POST['ids']) && !empty($_POST['ids'])) {

    $ids = array_map('intval', $_POST['ids']); 

    $idList = implode(',', $ids);

    $deleteAll = mysqli_query($conn, "DELETE FROM `contacts` WHERE id IN ($idList)");

    if ($deleteAll) {
        echo "success";
    } else {
        echo "Something went wrong";
    }

} else {
    echo "No IDs received";
}
?>