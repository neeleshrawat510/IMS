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
    <style>
        #contactsTable th,
        #contactsTable td {
            text-align: center !important;
            vertical-align: middle !important;
        }



        /* Validation error */
        label.error {
            display: block !important;
            width: 100%;
            margin-top: 5px;
            color: #dc3545;
            font-size: 0.875rem;
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
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Edit User</span>

                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="history.back()">
                                <i class="bi bi-arrow-left"></i> Back
                            </button>
                        </div>
                        <form class="card shadow-sm border-0 p-4 rounded-4 bg-white" id="userForm">
                            <div class="card-body">

                                <div class="row g-3">

                                    <!-- hidden id -->
                                    <input type="hidden" name="id" id="editUserId">
                                    <!-- Name -->
                                    <div class="col-md-6">
                                        <label for="name" class="form-label fw-semibold">
                                            Full Name
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-person"></i>
                                            </span>
                                            <input type="text" name="name" id="name" class="form-control"
                                                placeholder="Enter full name">
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
                                            <input type="email" name="email" id="email" class="form-control"
                                                placeholder="example@email.com">
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
                                            <input type="tel" name="number" id="number" class="form-control"
                                                placeholder="9876543210">
                                        </div>
                                    </div>


                                    <!-- Submit Button -->
                                    <div class="col-12 mt-3">
                                        <button class="btn btn-primary rounded-3" type="submit">
                                            <i class="bi bi-pencil-square me-1"></i>
                                            Edit User
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>
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

    <script>
        $(document).ready(function () {

            //number validation
            $.validator.addMethod("phoneValid", function (value, element) {
                return this.optional(element) || /^[6-9]\d{9}$/.test(value);
            }, "Please enter a valid 10-digit Indian mobile number.");
            let editUserId = new URLSearchParams(window.location.search).get('id');

            //get form data
            $.ajax({
                url: "controller/fetch_user.php",
                type: "GET",
                dataType: "json",
                data: {
                    id: editUserId
                },
                success: function (data) {
                    $("#editUserId").val(data.id);
                    $("#name").val(data.name);
                    $("#number").val(data.number);
                    $("#email").val(data.email);
                }
            });

            //validate form
            $("#userForm").validate({
                rules: {
                    name: {
                        required: true
                    },
                    email: {
                        required: true,
                        email: true,
                        remote: {
                            url: "controller/check_user_email.php",
                            type: "POST",
                            data: {
                                id: function () {
                                    return $("#editUserId").val();
                                }
                            }
                        }
                    },
                    number: {
                        required: true,
                        phoneValid: true,
                        remote: {
                            url: "controller/check_user_number.php",
                            type: "POST",
                            data: {
                                id: function () {
                                    return $("#editUserId").val();
                                }
                            }
                        }
                    }
                },
                messages: {
                    name: {
                        required: "Name is required"
                    },
                    email: {
                        required: "Email is required",
                        email: "required format abc@gmail.com",
                        remote: "Email already exist, Try another!"
                    },
                    number: {
                        required: "Number is required",
                        phoneValid: "Please enter valid number",
                        remote: "Number already exist, Try another!"
                    }

                },
                errorPlacement: function (error, element) {

                    if (element.attr("id") === "number") {
                        error.appendTo(element.closest(".col-md-6"));
                    }
                    else if (element.parent(".input-group").length) {
                        error.insertAfter(element.parent());
                    }
                    else {
                        error.insertAfter(element);
                    }

                },
                submitHandler: function (form) {

                    let formData = new FormData(form);
                    $.ajax({
                        url: "php/edit_user.php",
                        type: "POST",
                        data: formData,
                        dataType: "json",
                        contentType: false,
                        processData: false,

                        success: function (response) {

                            if (response.status === "success") {

                                Swal.fire({
                                    icon: "success",
                                    title: "Success",
                                    text: "User edited successfully."
                                }).then(() => {
                                    location.reload();
                                });

                            } else {

                                Swal.fire({
                                    icon: "error",
                                    title: "Error",
                                    text: response.message || "User could not be edited."
                                });

                            }

                        }, error: function () {
                            Swal.fire("error", "Something went wrong", "error");
                        }

                    });
                }
            });


        });
    </script>
</body>

</html>