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
    <title>Profile | Invoice Management System</title>

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
                    <!-- PROFILE PAGE -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Client Information</span>

                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="history.back()">
                                <i class="bi bi-arrow-left"></i> Back
                            </button>
                        </div>


                        <div class="card-body">
                            <div class="row">
                                <input type="hidden" id="contact_id" value="<?php echo $id; ?>">
                                <div class="col-md-6">
                                    <strong>Name:</strong>
                                    <span id="name"></span>
                                </div>

                                <div class="col-md-6">
                                    <strong>Mobile:</strong>
                                    <span id="number"></span>
                                </div>

                                <div class="col-md-6">
                                    <strong>Email:</strong>
                                    <span id="email"></span>
                                </div>

                                <div class="col-md-6">
                                    <strong>Company:</strong>
                                    <span id="company"></span>
                                </div>

                                <div class="col-md-6">
                                    <strong>GSTIN:</strong>
                                    <span id="gst"></span>
                                </div>

                                <div class="col-md-6">
                                    <strong>Registration Date:</strong>
                                    <span id="created_at"></span>
                                </div>

                                <div class="col-12 mt-3">
                                    <strong>Address:</strong><br>
                                    <span id="address"></span>
                                </div>

                            </div>
                        </div>
                    </div>


                    <!-- INVOICE TABLE -->

                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Issued Invoices</span>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover nowrap w-100" id="invoiceTable"
                                    style="font-size: small;">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Invoice Id</th>
                                            <th>#</th>
                                            <th>Invoice No</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Payment Status</th>
                                            <th>Invoice</th>
                                        </tr>
                                    </thead>

                                    <tbody></tbody>
                                </table>
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

            let contact_id = $("#contact_id").val();

            $.ajax({
                url: "php/get_contact_profile.php",
                type: "GET",
                data: {
                    id: contact_id
                },
                dataType: "json",
                success: function (response) {

                    $("#name").text(response.name);
                    $("#number").text(response.number);
                    $("#email").text(response.email);
                    $("#company").text(response.company);
                    $("#gst").text(response.gst);
                    $("#address").text(response.address);
                    $("#created_at").text(response.created_at.split(' ')[0]);

                }
            });
            let table = $('#invoiceTable').DataTable({

                ajax: {
                    url: "php/get_contact_invoices.php",
                    data: {
                        id: contact_id
                    },
                    dataSrc: ""
                },
                columns: [
                    { data: 0, visible: false }, // Hidden Invoice ID
                    { data: 1 }, //serial no
                    { data: 2 },    //invoice no
                    { data: 3 },    //date
                    { data: 4 },    //grand total
                    { data: 5 },    //payment_status
                    { data: 6 }     //view
                ],
                createdRow: function (row, data) {
                    $(row).attr('data-id', data[0]); // store invoice id
                    $(row).css('cursor', 'pointer');
                }
            });

            //redirect when click on row anywhere
            $('#invoiceTable tbody').on('click', 'tr', function (e) {

                // Don't redirect when view button clicked
                if ($(e.target).closest('a, button').length) {
                    return;
                }

                let data = table.row(this).data();

                window.location.href = 'payment_history.php?id=' + data[0];
            });
        });
    </script>
</body>

</html>