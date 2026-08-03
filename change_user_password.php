<?php

require_once "includes/auth_check.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Invoice management System">
    <title>Edit User | Invoice Management System</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- jQuery  -->

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- css -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@24.6.1/build/css/intlTelInput.css">
    <style>
        #contactsTable th,
        #contactsTable td {
            text-align: center !important;
            vertical-align: middle !important;
        }

        /* Fix width of phone input */
        /* Phone input wrapper */
        .input-group .iti {
            flex: 1 1 auto;
            width: 1%;
        }

        /* Prevent shrinking */
        .input-group .iti input {
            width: 100% !important;
        }

        /* Validation error */
        label.error {
            display: block !important;
            width: 100%;
            margin-top: 5px;
            color: #dc3545;
            font-size: 0.875rem;
        }

        /* Place phone error below the field */
        .iti+label.error {
            display: block !important;
            width: 100%;
        }

        input.error {
            border: 1px solid red;
        }

        label.error {
            color: red;
        }
    </style>
</head>

<div class="admin-shell">

    <div class="sidebar-backdrop" data-sidebar-close></div>

    <?php include("includes/sidebar.php"); ?>

    <div class="admin-main">

        <?php include("includes/header.php"); ?>

        <main class="dashboard-content">

            <div class="container py-4">

                <div class="row justify-content-center">

                    <div class="col-lg-6">

                        <div class="card shadow border-0">

                            <div class="card-header d-flex justify-content-between align-items-center">

                                <h5 class="mb-0">
                                    <i class="bi bi-shield-lock"></i>
                                    Change Password
                                </h5>

                                <button class="btn btn-outline-secondary btn-sm" onclick="history.back()">
                                    <i class="bi bi-arrow-left"></i> Back
                                </button>

                            </div>

                            <div class="card-body">

                                <form id="changePasswordForm">

                                    <div class="mb-3">
                                        <label class="form-label">Current Password</label>

                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-lock"></i>
                                            </span>

                                            <input type="password" class="form-control" name="current_password"
                                                id="current_password">
                                        </div>

                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">New Password</label>

                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-key"></i>
                                            </span>

                                            <input type="password" class="form-control" name="new_password"
                                                id="new_password">
                                        </div>

                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Confirm Password</label>

                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-key-fill"></i>
                                            </span>

                                            <input type="password" class="form-control" name="confirm_password"
                                                id="confirm_password">
                                        </div>

                                    </div>

                                    <button class="btn btn-primary w-100">
                                        <i class="bi bi-check-circle"></i>
                                        Update Password
                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </main>

    </div>

</div>

<script src="assets/js/main.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="controller/logout.js"></script>

<script>

    $("#changePasswordForm").validate({

        rules: {

            current_password: {
                required: true
            },

            new_password: {
                required: true,
                minlength: 6
            },

            confirm_password: {
                required: true,
                equalTo: "#new_password"
            }

        },

        messages: {

            current_password: "Enter current password",

            new_password: {
                required: "Enter new password",
                minlength: "Minimum 6 characters"
            },

            confirm_password: {
                required: "Confirm password",
                equalTo: "Passwords do not match"
            }

        },

        submitHandler: function (form) {

            $.ajax({

                url: "php/change_password.php",

                type: "POST",

                data: $(form).serialize(),

                dataType: "json",

                success: function (res) {

                    if (res.status == "success") {

                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: res.message
                        });

                        form.reset();

                    } else {

                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: res.message
                        });

                    }

                }

            });

        }

    });

</script>

</body>

</html>