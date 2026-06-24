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

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery  -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        #contactsTable th,
        #contactsTable td {
            text-align: center !important;
            vertical-align: middle !important;
        }

        input.error {
            border: 1px solid red;
        }

        label.error {
            color: red;
        }
    </style>
</head>


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
                            Add user
                        </div>
                        <form class="card shadow-sm border-0 p-4 rounded-4 bg-white" id="userForm">
                            <div class="card-body">

                                <div class="row g-3">

                                    <!-- Name -->
                                    <div class="col-md-6">
                                        <label for="name" class="form-label fw-semibold">
                                            Full Name
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-person"></i>
                                            </span>
                                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter full name">
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-6">
                                        <label for="email" class="form-label fw-semibold">
                                            Email Address
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-envelope"></i>
                                            </span>
                                            <input type="email" name="email" id="email" class="form-control" placeholder="example@email.com">
                                        </div>
                                    </div>

                                    <!-- Phone -->
                                    <div class="col-md-6">
                                        <label for="number" class="form-label fw-semibold">
                                            Phone Number
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-telephone"></i>
                                            </span>
                                            <input type="text" name="number" id="number" class="form-control" placeholder="+91 9876543210">
                                        </div>
                                    </div>

                                    <!-- Password -->
                                    <div class="col-md-6">
                                        <label for="password" class="form-label fw-semibold">
                                            Password
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-lock"></i>
                                            </span>
                                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter secure password">
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="col-12 mt-3">
                                        <button class="btn btn-primary rounded-3" type="submit">
                                            <i class="bi bi-person-plus-fill me-1"></i>
                                            Add User
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>
                    <section class="panel mt-3">
                        <div class="panel-header">
                            <div>
                                <h2 class="h5 mb-1 section-title"><i class="bi bi-people" aria-hidden="true"></i>
                                    <span>Users</span>
                                </h2>

                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover nowrap w-100" id="userTable" style="font-size: small;">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Number </th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>
    <!-- JQUERY -->
    <script src="assets/js/main.js"></script>

    <!-- jQuery Validation Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

    <!-- Sweet alert cdn -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- LOGOUT Redirect -->
    <script src="controller/logout.js"></script>
    <!-- DATATABLE -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            // Add validator for strong password
            $.validator.addMethod("strongPassword", function(value, element) {
                return this.optional(element) || /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/.test(value);
            });

            $("#userForm").validate({
                rules: {
                    name: {
                        required: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    number: {
                        required: true,
                        minlength: 10,
                        maxlength: 10
                    },
                    password: {
                        required: true,
                        strongPassword: true
                    }
                },
                messages: {
                    name: {
                        required: "Name is required"
                    },
                    email: {
                        required: "Email is required",
                        email: "required format abc@gmail.com"
                    },
                    number: {
                        required: "Number is required",
                        minlength: "10 digits required",
                        maxlength: "10 digits required"
                    },
                    password: {
                        required: "Password is required",
                        strongPassword: "atleast one Uppercase, lowercase, number and special character required"
                    }
                },
                submitHandler: function(form) {
                    let formData = new FormData(form);
                    $.ajax({
                        url: "php/register_user.php",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,

                        success: function(response) {
                            if (response.trim() === "success") {
                                Swal.fire("success", "User Added Successfully", "success")
                                    .then(() => {
                                        location.reload();
                                    });
                            } else {
                                Swal.fire("info", "User Not Added, Try again!", "info");
                            }
                        },
                        error: function() {
                            Swal.fire("error", "Something went wrong", "error");
                        }

                    });
                }
            });



            //data table
            $("#userTable").DataTable({
                ajax: {
                        url: "php/view_users.php",
                        dataSrc: ""
                },
                columns:  [
                    {data: 0},//sr no
                    {data: 1},//name
                    {data: 2},//email
                    {data: 3},//number
                ]
            });
        });
    </script>
</body>

</html>