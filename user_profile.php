<?php

require_once "includes/auth_check.php";

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
    <!-- css -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@24.6.1/build/css/intlTelInput.css">
    <style>
        body {
            background: #f5f7fb;
        }

        .profile-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, .06);
        }

        .profile-avatar {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0d6efd, #4f8ef7);
            color: #fff;
            font-size: 54px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 10px;
            user-select: none;
        }

        .card-header {
            border-radius: 16px 16px 0 0 !important;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-control {
            height: 48px;
            border-radius: 10px;
        }

        .btn {
            border-radius: 10px;
        }

        @media(max-width:991px) {

            .profile-avatar {
                width: 120px;
                height: 120px;
                font-size: 48px;
            }

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
                <div class="container-fluid py-4">

                    <!-- Page Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h3 class="fw-bold mb-1">My Profile</h3>
                            <p class="text-muted mb-0">
                                Manage your personal information and account settings.
                            </p>
                        </div>

                        <button class="btn btn-primary px-4" id="btnUpdateProfile">
                            <i class="fas fa-save me-2"></i>
                            Save Changes
                        </button>
                    </div>

                    <div class="row g-4">

                        <!-- Left Card -->
                        <div class="col-lg-4">

                            <div class="card profile-card h-100">

                                <div class="card-body text-center">

                                    <div class="profile-avatar mx-auto mb-3">
                                        N
                                    </div>

                                    <h5 class="fw-semibold mb-1">
                                        User Name
                                    </h5>

                                    <p class="text-muted mb-4">
                                        user@email.com
                                    </p>

                                    <div class="d-grid gap-2">

                                        <button class="btn btn-outline-primary">
                                            <i class="fas fa-camera me-2"></i>
                                            Change Photo
                                        </button>

                                        <button class="btn btn-outline-danger">
                                            <i class="fas fa-trash-alt me-2"></i>
                                            Remove Photo
                                        </button>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <!-- Right Card -->
                        <div class="col-lg-8">

                            <div class="card profile-card h-100">

                                <div class="card-header bg-white border-0 pt-4 px-4">
                                    <h5 class="mb-0 fw-semibold">
                                        Personal Information
                                    </h5>
                                </div>

                                <div class="card-body px-4">

                                    <div class="row">

                                        <div class="col-md-6 mb-3">

                                            <label class="form-label">
                                                Full Name
                                            </label>

                                            <input type="text" class="form-control" id="name"
                                                placeholder="Enter your name">

                                        </div>

                                        <div class="col-md-6 mb-3">

                                            <label class="form-label">
                                                Email Address
                                            </label>

                                            <input type="email" class="form-control" id="email"
                                                placeholder="Enter email">

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- Security Card -->

                    <div class="card profile-card mt-4">

                        <div class="card-body d-flex justify-content-between align-items-center flex-wrap">

                            <div>

                                <h5 class="mb-2">
                                    Security
                                </h5>

                                <p class="text-muted mb-0">
                                    Keep your account secure by updating your password regularly.
                                </p>

                            </div>

                            <button class="btn btn-outline-secondary mt-3 mt-md-0">
                                <i class="fas fa-key me-2"></i>
                                Reset Password
                            </button>

                        </div>

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
    <!-- DATATABLE -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/intlTelInput.min.js"></script>
    <script>
        $(document).ready(function () {

            //country code
            const phoneInput = document.querySelector("#number");

            const iti = window.intlTelInput(phoneInput, {
                initialCountry: "auto",
                geoIpLookup: function (callback) {
                    fetch("https://ipapi.co/json/")
                        .then(res => res.json())
                        .then(data => callback(data.country_code))
                        .catch(() => callback("in"));
                },
                preferredCountries: ["in", "us", "gb"],
                separateDialCode: true,
                nationalMode: true,
                autoPlaceholder: "aggressive",
                loadUtilsOnInit: () =>
                    import("https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js")
            });


            // Add validator for strong password
            $.validator.addMethod("strongPassword", function (value, element) {
                return this.optional(element) || /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/.test(value);
            });

            //valid phone no
            $.validator.addMethod("phoneValid", function (value) {
                const country = iti.getSelectedCountryData().iso2;

                if (country === "in") {
                    return /^[6-9]\d{9}$/.test(value);
                }

                return iti.isValidNumber();
            }, "Please enter a valid phone number.");


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
                            type: "POST"
                        }
                    },
                    number: {
                        required: true,
                        phoneValid: true,
                        remote: {
                            url: "controller/check_user_number.php",
                            type: "POST"
                        }
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
                        email: "required format abc@gmail.com",
                        remote: "Email already exist, Try another!"
                    },
                    number: {
                        required: "Number is required",
                        phoneValid: "Please enter valid number",
                        remote: "Number already exist, Try another!"
                    },
                    password: {
                        required: "Password is required",
                        strongPassword: "atleast one Uppercase, lowercase, number and special character required"
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
                        url: "php/register_user.php",
                        type: "POST",
                        data: formData,
                        dataType: "json",
                        contentType: false,
                        processData: false,

                        success: function (response) {

                            if (response.status === "success") {

                                if (response.email_status) {

                                    Swal.fire({
                                        icon: "success",
                                        title: "Success",
                                        text: "User added successfully and login credentials have been emailed."
                                    }).then(() => {
                                        table.ajax.reload(null, false);
                                        $("#userForm")[0].reset();
                                    });

                                } else {

                                    Swal.fire({
                                        icon: "warning",
                                        title: "User Added",
                                        text: "User added successfully, but the email could not be sent."
                                    }).then(() => {
                                        table.ajax.reload(null, false);
                                        $("#userForm")[0].reset();
                                    });

                                }

                            } else {

                                Swal.fire({
                                    icon: "error",
                                    title: "Error",
                                    text: response.message || "User could not be added."
                                });

                            }

                        },
                        error: function () {
                            Swal.fire("error", "Something went wrong", "error");
                        }

                    });
                }
            });



            //data table
            const table = $("#userTable").DataTable({
                ajax: {
                    url: "php/view_users.php",
                    dataSrc: ""
                },
                columns: [
                    { data: 0 },
                    { data: 1 },
                    { data: 2 },
                    { data: 3 },
                    { data: 4 }
                ]
            });
            // delete user
            $(document).on('click', '.delete-btn', function (e) {
                e.preventDefault();

                let id = $(this).data('id');

                Swal.fire({
                    title: "Are you sure?",
                    text: "This user will be Permanently Deleted!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, Delete it!"
                }).then((result) => {

                    if (result.isConfirmed) {

                        $.ajax({
                            url: 'delete_user.php',
                            type: 'POST',
                            data: {
                                id: id
                            },
                            success: function (response) {

                                if (response.trim() === "success") {

                                    Swal.fire({
                                        title: "Deleted!",
                                        text: "User has been deleted.",
                                        icon: "success",
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        table.ajax.reload(null, false);
                                    });

                                } else {
                                    Swal.fire("Error", "Delete failed!", "error");
                                }
                            },
                            error: function () {
                                Swal.fire("Error", "Something went wrong!", "error");
                            }
                        });

                    }
                });
            });

        });


    </script>
</body>

</html>