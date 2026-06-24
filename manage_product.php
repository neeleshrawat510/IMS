<?php
session_start();

if (!isset($_SESSION['user_id'])) {

    header("location: index.php");

    exit();
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Invoice management System">
    <title>Manage Products | Invoice Management System</title>
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
    #productsTable th,
    #productsTable td {
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
                    <!-- PRODUCT TABLE -->
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Archived Products</span>

                            <button class="btn btn-primary btn-sm" id="archivedProducts">
                                Archived Products
                            </button>
                        </div>
                        <div class="card-body">
                            <table id="productsTable" class="table table-striped table-hover nowrap w-100" style="font-size: small;">
                                <thead class="table-light">
                                    <tr>
                                        <th><input type="checkbox" id="selectAll">
                                        <button class="btn btn-sm text-primary p-0 ms-2" id="archiveSelected" title="Delete Selected">
                                                <i class="bi bi-box-arrow-down"></i>
                                            </button>    
                                        <button class="btn btn-sm text-danger p-0 ms-2" id="deleteSelected" title="Delete Selected">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </th>
                                        <th>#</th>
                                        <th>Product Code</th>
                                        <th>Product Name</th>
                                        <th>Cost Price</th>
                                        <th>Selling Price</th>
                                        <th>Tax %</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="assets/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- LOGOUT Redirect -->
    <script src="controller/logout.js"></script>

    <script>
        $(document).ready(function() {

            //dataTable
            let table = $('#productsTable').DataTable({

                ajax: {
                    url: "php/manage_all_products.php",
                    dataSrc: ""
                },
                responsive: true,
                autoWidth: false,
                columns: [

                    {
                        data: 1, // id column FOR CHECKBOX
                        orderable: false,
                        render: function(data) {
                            return `<input type="checkbox" class="row-check" data-id="${data}">`;
                        }
                    },
                    {
                        data: 0
                    }, //serial no
                    {
                        data: 2
                    }, //product_code
                    {
                        data: 3
                    }, //"product_name"
                    {
                        data: 4
                    }, //cost_price
                    {
                        data: 5
                    }, //selling_price
                    {
                        data: 6
                    }, //tax
                    {
                        data: 7
                    } //action

                ]

            });


            //select all rows
            $(document).on('click', '#selectAll', function() {

                let rows = table.rows({
                    search: 'applied'
                }).nodes();

                $('input.row-check', rows).prop('checked', this.checked);
            });

            //individual select
            $(document).on('change', '.row-check', function() {

                let rows = table.rows({
                    search: 'applied'
                }).nodes();

                let total = $('input.row-check', rows).length;
                let checked = $('input.row-check:checked', rows).length;

                $('#selectAll').prop('checked', total === checked);
            });
            //delete multiple 
            $('#deleteSelected').on('click', function() {

                let ids = [];

                table.rows().every(function() {

                    let row = this.node();
                    let checkbox = $(row).find('.row-check');

                    if (checkbox.prop('checked')) {
                        ids.push(checkbox.data('id'));
                    }
                });

                if (ids.length === 0) {
                    Swal.fire("No selection", "Please select products first", "info");
                    return;
                }

                Swal.fire({
                    title: "Delete selected products?",
                    text: "This action cannot be undone!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    confirmButtonText: "Yes, delete"
                }).then((result) => {

                    if (result.isConfirmed) {

                        $.ajax({
                            url: "php/delete_multiple_products.php",
                            type: "POST",
                            data: {
                                ids: ids
                            },
                            success: function(response) {

                                if (response.trim() === "success") {

                                    Swal.fire("Deleted!", "Products deleted successfully", "success");

                                    table.ajax.reload();

                                } else {
                                    Swal.fire("Error", "Delete failed", "error");
                                }
                            }
                        });
                    }
                });
            });

             //archive multiple 
            $('#archiveSelected').on('click', function() {

                let ids = [];

                table.rows().every(function() {

                    let row = this.node();
                    let checkbox = $(row).find('.row-check');

                    if (checkbox.prop('checked')) {
                        ids.push(checkbox.data('id'));
                    }
                });

                if (ids.length === 0) {
                    Swal.fire("No selection", "Please select products first", "info");
                    return;
                }

                Swal.fire({
                    title: "Archive selected products?",
                    text: "Do you want to archive selected products",
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    confirmButtonText: "Yes, archive"
                }).then((result) => {

                    if (result.isConfirmed) {

                        $.ajax({
                            url: "php/archive_multiple_products.php",
                            type: "POST",
                            data: {
                                ids: ids
                            },
                            success: function(response) {

                                if (response.trim() === "success") {

                                    Swal.fire("Archived!", "Products archived successfully", "success");

                                    table.ajax.reload();

                                } else {
                                    Swal.fire("Error", "Archive failed", "error");
                                }
                            }
                        });
                    }
                });
            });

            //delete product
            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();

                let id = $(this).data('id');

                Swal.fire({
                    title: "Are you sure?",
                    text: "This Product will be permanently deleted!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {

                    if (result.isConfirmed) {

                        $.ajax({
                            url: 'delete_product.php',
                            type: 'POST',
                            data: {
                                id: id
                            },
                            success: function(response) {

                                if (response.trim() === "success") {

                                    Swal.fire({
                                        title: "Deleted!",
                                        text: "Product has been deleted.",
                                        icon: "success",
                                        timer: 1500,
                                        showConfirmButton: false
                                    });

                                    // reload DataTable
                                    $('#productsTable').DataTable().ajax.reload();

                                } else {
                                    Swal.fire("Error", "Delete failed!", "error");
                                }
                            },
                            error: function() {
                                Swal.fire("Error", "Something went wrong!", "error");
                            }
                        });

                    }
                });
            });

            //Archive product
            $(document).on('click', '.archive-btn', function(e) {
                e.preventDefault();

                let id = $(this).data('id');

                $.ajax({
                    url: 'php/archive_product.php',
                    type: 'POST',
                    data: {
                        id: id
                    },
                    success: function(response) {

                        if (response.trim() === "success") {

                            Swal.fire("Archived!", "Product has been archived", "success");

                            // Reload DataTable
                            $('#productsTable').DataTable().ajax.reload();

                        } else {
                            Swal.fire("Error", "Archive failed!", "error");
                        }
                    },
                    error: function() {
                        Swal.fire("Error", "Something went wrong!", "error");
                    }
                });
            });

            //archived products
             $("#archivedProducts").click(function() {
                window.location.href = "manage_archived_products.php";
            });

        });
    </script>
</body>

</html>