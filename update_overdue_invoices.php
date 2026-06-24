<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
//connection setup
include("C:/xampp_amrit/htdocs/Invoice_management_System/config/connection.php");

//USING TASK SCHEDULER TO CHECK DUE DATE FIELD EVERYDAY 
$updateDueDate = mysqli_query($conn, "UPDATE `invoices` SET `status` = 'Overdue' WHERE due_date < CURDATE()");

?>