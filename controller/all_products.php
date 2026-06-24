<?php

session_start();
include("../config/connection.php");

$keyword = $_GET['keyword'];

$sql = mysqli_query($conn, "SELECT * FROM `products` WHERE
                                                    `product_code` LIKE '%$keyword%'
                                                OR  `product_name` LIKE '%$keyword%'
                                                
                                            LIMIT 10
                                        ");

$data = [];

while ($row = mysqli_fetch_array($sql)) {
    $data[] = $row;
}

echo json_encode($data);

?>