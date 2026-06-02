<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("location: index.php");
    exit();
}
include("config/connection.php");

//total invoice
$sql = mysqli_query($conn, "SELECT * FROM `invoices`");
$totalInvoices = mysqli_num_rows($sql);

//total contacts
$sql = mysqli_query($conn, "SELECT * FROM `contacts`");
$totalContacts = mysqli_num_rows($sql);

//total products
$sql = mysqli_query($conn, "SELECT * FROM `products`");
$totalProducts = mysqli_num_rows($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

</head>

<body class="bg-light">

    <!-- HEADER NAVBAR -->
    <?php include("includes/header.php");  ?>

    <!-- MAIN DASHBOARD -->
    <div class="container py-4">

        <!-- SUMMARY CARDS -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Total Invoices</h6>
                        <h3><?= $totalInvoices ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Total Contacts</h6>
                        <h3 class="text-success"><?= $totalContacts ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Total Products</h6>
                        <h3 class="text-danger"><?= $totalProducts ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- INVOICE TABLE -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold">
                Recent Invoices
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Customer</th>
                                <th>Number</th>
                                <th>Date</th>
                                <th>Invoice No</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody id="tableData">

                        </tbody>

                    </table>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- LOGOUT Redirect -->
    <script src="controller/logout.js"></script>


    <script>
        $(document).ready(function() {
            loadData();

            function loadData() {
                $.ajax({
                    url: "php/manage_all_invoices.php",
                    type: "GET",
                    success: function(response) {
                        $("#tableData").html(response);
                    },
                    error: function() {
                        $("#tableData").html("Something went wrong");
                    }
                });

            }

        });
    </script>
</body>

</html>