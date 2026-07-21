<?php

date_default_timezone_set("Asia/Kolkata");

require_once __DIR__ . "/config/connection.php";

mysqli_query(
    $conn,
    "UPDATE invoices
     SET status='Overdue'
     WHERE due_date < CURDATE()
     AND status='Sent' AND payment_status='Unpaid'"   
);


?>