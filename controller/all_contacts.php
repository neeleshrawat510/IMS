<?php

session_start();
include("../config/connection.php");

$keyword = $_GET['keyword'];

$sql = mysqli_query($conn, "SELECT * FROM `contacts` WHERE
                                                    `fname` LIKE '%$keyword%'
                                                OR  `lname` LIKE '%$keyword%'
                                                OR  `number` LIKE '%$keyword%'
                                                OR  `email` LIKE '%$keyword%'
                                            LIMIT 10
                                        ");

$data = [];

while ($row = mysqli_fetch_array($sql)) {
    $data[] = $row;
}

echo json_encode($data);

?>