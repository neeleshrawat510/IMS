<?php

require_once "includes/auth_check.php";

//get id 
$id = intval($_GET['id']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Invoice management System">
    <title>Payment History | Invoice Management System</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.8/css/dataTables.dataTables.min.css">
    <!-- jQuery  -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.3.8/js/dataTables.min.js"></script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<style>
    #contactsTable th,
    #contactsTable td {
        text-align: center !important;
        vertical-align: middle !important;
    }
</style>

<body>
    <div class="admin-shell">
        <div class="sidebar-backdrop" data-sidebar-close></div>

        <!-- INCLUDE SIDEBAR -->
        <?php include("includes/sidebar.php"); ?>

        <div class="admin-main">

            <!-- INCLUDE HEADER -->
            <?php include("includes/header.php"); ?>

            <!-- MAIN CONTENT -->
            <main class="dashboard-content">
                <div class="container-fluid px-3 px-lg-4 py-4">

                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Payment History</h5>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">

                                <!-- PAYMENT TABLE -->
                                <table class="table table-striped table-hover nowrap w-100" id="paymentHistory"
                                    style="font-size: small;">
                                    <h5>Payment History</h5>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Invoice No</th>
                                            <th>Payment Gateway</th>
                                            <th>Payment Method</th>
                                            <th>Transaction Id</th>
                                            <th>Currency</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Failure Reason</th>
                                            <th>Paid At</th>
                                            <th>Action</th>

                                        </tr>
                                    </thead>

                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        </main>
    </div>
    </div>

    <script src="assets/js/main.js"></script>

    <!-- LOGOUT Redirect -->
    <script src="controller/logout.js"></script>

    <script>
        $(document).ready(function () {

            let invoice_id = <?= $id ?>;


            $('#paymentHistory').DataTable({
                ajax: {
                    url: "php/get_payment_history.php",
                    data: {
                        id: invoice_id
                    },
                    dataSrc: ""
                },
                columns: [
                    { data: 0 }, //serial no
                    { data: 1 },    //invoice no
                    { data: 2 },    //gateway
                    { data: 3 },    //payment method
                    { data: 4 },    //transaction id
                    { data: 5 },     //currency
                    { data: 6 },     //amount
                    { data: 7 },     //status
                    { data: 8 },     //failure reason
                    { data: 9 },     //paid at
                    { data: 10 }     //action


                ]
            });

        });
    </script>
</body>

</html>