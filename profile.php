<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("location: index.php");
    exit();
}

$id = intval($_GET['id']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Invoice management System">
    <title>Manage Contacts | Invoice Management System</title>

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
                        <div class="card-header">
                            Client Information
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
                    <table class="table table-striped table-hover nowrap w-100" id="invoiceTable" style="font-size: small;">
                        <h5>Invoice Generated for this Client</h5>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Invoice No</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody></tbody>
                    </table>
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
        success: function(response) {

            $("#name").text(response.name);
            $("#number").text(response.number);
            $("#email").text(response.email);
            $("#company").text(response.company);
            $("#gst").text(response.gst);
            $("#address").text(response.address);
            $("#created_at").text(response.created_at.split(' ')[0]);

        }
    });

    $('#invoiceTable').DataTable({
        ajax: {
            url: "php/get_contact_invoices.php",
            data: {
                id: contact_id
            },
            dataSrc: ""
        },
        columns: [
            { data: 0 }, //serial no
            { data: 1 },    //invoice no
            { data: 2 },    //date
            { data: 3 },    //grand total
            { data: 4 },    //status
            { data: 5 }     //view
        ]
    });

});
</script>
    </body>

</html>