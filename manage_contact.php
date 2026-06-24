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
                    <!-- CONTACT TABLE -->
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Manage Contacts</span>

                            <button class="btn btn-primary btn-sm" id="archivedContacts">
                                Archived Contacts
                            </button>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover nowrap w-100" id="contactsTable"
                                    style="font-size: small;">
                                    <thead class="table-light">
                                        <tr>
                                            <th><input type="checkbox" id="selectAll">
                                            <button class="btn btn-sm text-primary p-0 ms-2" id="archiveSelected" title="Unarchive Selected">
                                                    <i class="bi bi-box-arrow-down"></i>
                                                </button>
                                                <button class="btn btn-sm text-danger p-0 ms-2" id="deleteSelected" title="Delete Selected">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </th>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Contact Number</th>
                                            <th>Email</th>
                                            <th>Company Name</th>
                                            <th>Action</th>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {

            //data table
            let table = $('#contactsTable').DataTable({
                ajax: {
                    url: "php/manage_all_contacts.php",
                    dataSrc: ""
                },
                columns: [{
                        data: 0, // id column FOR CHECKBOX
                        orderable: false,
                        render: function(data) {
                            return `<input type="checkbox" class="row-check" data-id="${data}">`;
                        }
                    },
                    {
                        data: 1
                    }, //S. No
                    {
                        data: 2
                    }, //name
                    {
                        data: 3
                    }, //"number"
                    {
                        data: 4
                    }, //email
                    {
                        data: 5
                    }, //company
                    {
                        data: 6
                    } //action
                ],

                createdRow: function(row, data) {
                    $(row).attr('data-id', data[0]); // store contact id
                    $(row).css('cursor', 'pointer');
                }
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
                    Swal.fire("No selection", "Please select contacts first", "info");
                    return;
                }

                Swal.fire({
                    title: "Are you sure",
                    text: "These Contacts will be Archived !",
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    confirmButtonText: "Yes, Archive"
                }).then((result) => {

                    if (result.isConfirmed) {

                        $.ajax({
                            url: "php/archive_multiple_contacts.php",
                            type: "POST",
                            data: {
                                ids: ids
                            },
                            success: function(response) {

                                if (response.trim() === "success") {

                                    Swal.fire("Archived!", "Contacts archived successfully", "success");

                                    table.ajax.reload();

                                } else {
                                    Swal.fire("Error", "Archive failed", "error");
                                }
                            }
                        });
                    }
                });
            });

            //Delete multiple 
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
                    Swal.fire("No selection", "Please select contacts first", "info");
                    return;
                }

                Swal.fire({
                    title: "Are you sure",
                    text: "These Contacts will Permanently Deleted !",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    confirmButtonText: "Yes, Archive"
                }).then((result) => {

                    if (result.isConfirmed) {

                        $.ajax({
                            url: "php/delete_multiple_contacts.php",
                            type: "POST",
                            data: {
                                ids: ids
                            },
                            success: function(response) {

                                if (response.trim() === "success") {

                                    Swal.fire("Archived!", "Contacts deleted successfully", "success");

                                    table.ajax.reload();

                                } else {
                                    Swal.fire("Error", "Delete failed", "error");
                                }
                            }
                        });
                    }
                });
            });

            //redirect when click on row anywhere
            $('#contactsTable tbody').on('click', 'tr', function(e) {

                // Don't redirect when edit/archive button clicked
                if ($(e.target).closest('a, button, input[type="checkbox"]').length) {
                    return;
                }

                let id = $(this).attr('data-id');

                window.location.href = 'profile.php?id=' + id;
            });

            //archive single
            $(document).on('click', '.archive-btn', function(e) {
                e.preventDefault();

                let id = $(this).data('id');

                $.ajax({
                    url: 'php/archive_contact.php',
                    type: 'POST',
                    data: {
                        id: id
                    },
                    success: function(response) {

                        if (response.trim() === "success") {

                            Swal.fire("Archived!", "Contact has been archived", "success");

                            // Reload DataTable
                            $('#contactsTable').DataTable().ajax.reload();

                        } else {
                            Swal.fire("Error", "Archive failed!", "error");
                        }
                    },
                    error: function() {
                        Swal.fire("Error", "Something went wrong!", "error");
                    }
                });
            });

            //Delete
            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();

                let id = $(this).data('id');

                Swal.fire({
                    title: "Are you sure?",
                    text: "This contact will be Permanently Deleted!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, Delete it!"
                }).then((result) => {

                    if (result.isConfirmed) {

                        $.ajax({
                            url: 'delete_contact.php',
                            type: 'POST',
                            data: {
                                id: id
                            },
                            success: function(response) {

                                if (response.trim() === "success") {

                                    Swal.fire({
                                        title: "Deleted!",
                                        text: "Contact has been deleted.",
                                        icon: "success",
                                        timer: 1500,
                                        showConfirmButton: false
                                    });

                                    // reload DataTable
                                    $('#contactsTable').DataTable().ajax.reload();

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

            //archived contacts
            $("#archivedContacts").click(function() {
                window.location.href = "manage_archived_contacts.php";
            });

        });
    </script>

</body>

</html>