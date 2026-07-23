<?php

include "config/connection.php";
require_once "controller/invoice_pdf.php";

echo generateInvoicePDF($conn, 29);