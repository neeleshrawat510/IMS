<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

include "config/connection.php";
require_once "controller/invoice_pdf.php";

echo generateInvoicePDF($conn, 30);