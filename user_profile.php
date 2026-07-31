<?php

require_once "includes/auth_check.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Invoice management System">
    <title>User Profile | Invoice Management System</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.8/css/dataTables.dataTables.min.css">

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

        .profile-avatar-large {
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

        @media (max-width:991px) {
            .profile-avatar-large {
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
                    </div>

                    <div class="row g-4">

                        <!-- Left Card -->
                        <div class="col-lg-4">

                            <div class="card profile-card h-100">

                                <div class="card-body text-center">

                                    <div id="profileAvatar" class="profile-avatar-large mx-auto mb-3">
                                        N
                                    </div>

                                    <img id="profileImage"
                                        class="profile-avatar-large rounded-circle mx-auto mb-3 d-none" src=""
                                        alt="Profile">

                                    <h5 class="fw-semibold mb-1" id="profileName">
                                        User Name
                                    </h5>

                                    <p class="text-muted mb-4" id="profileEmail">
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

                                    <form class="row" id="editUser" enctype="multipart/form-data">

                                        <div class="col-md-6 mb-3">

                                            <label class="form-label">
                                                Full Name
                                            </label>

                                            <input type="text" class="form-control" name="name" id="name"
                                                placeholder="Enter your name">

                                        </div>

                                        <div class="col-md-6 mb-3">

                                            <label class="form-label">
                                                Email Address
                                            </label>

                                            <input type="email" class="form-control" name="email" id="email"
                                                placeholder="Enter email">

                                        </div>

                                        <div class="col-md-6 mb-3">

                                            <label class="form-label">
                                                Contact Number
                                            </label>

                                            <input type="tel" class="form-control" name="number" id="number"
                                                placeholder="7485965478">

                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            Save Changes
                                        </button>

                                    </form>

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

            // ----------------------------
            // Initialize Intl Tel Input
            // ----------------------------
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

            // ----------------------------
            // Fetch Logged In User
            // ----------------------------
            function loadProfile() {

                $.ajax({
                    url: "php/dashboard_user.php",
                    type: "GET",
                    dataType: "json",

                    success: function (data) {

                        $("#name").val(data.name);
                        $("#email").val(data.email);

                        if (data.number) {
                            iti.setNumber(data.number);
                        }

                        $("#profileName").text(data.name);
                        $("#profileEmail").text(data.email);

                        // ---------- Avatar ----------
                        if (data.profile_photo && data.profile_photo.trim() !== "") {

                            $("#profileImage")
                                .attr("src", data.profile_photo)
                                .removeClass("d-none");

                            $("#profileAvatar").addClass("d-none");

                        } else {

                            $("#profileAvatar")
                                .text(data.name.charAt(0).toUpperCase())
                                .removeClass("d-none");

                            $("#profileImage").addClass("d-none");
                        }

                    },

                    error: function (xhr) {
                        console.error(xhr.responseText);
                    }

                });

            }

            loadProfile();

            // ----------------------------
            // Phone Validation
            // ----------------------------
            $.validator.addMethod("phoneValid", function (value) {

                const country = iti.getSelectedCountryData().iso2;

                if (country === "in") {
                    return /^[6-9]\d{9}$/.test(value);
                }

                return iti.isValidNumber();

            }, "Please enter a valid phone number.");

            // ----------------------------
            // Form Validation
            // ----------------------------
            $("#editUser").validate({

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
                    }
                },

                messages: {
                    name: {
                        required: "Name is required"
                    },
                    email: {
                        required: "Email is required",
                        email: "Enter a valid email",
                        remote: "Email already exists."
                    },
                    number: {
                        required: "Number is required",
                        phoneValid: "Please enter a valid number",
                        remote: "Number already exists."
                    }
                },

                errorPlacement: function (error, element) {

                    if (element.attr("id") === "number") {
                        error.appendTo(element.closest(".col-md-6"));
                    } else {
                        error.insertAfter(element);
                    }

                },

                submitHandler: function (form) {

                    let formData = new FormData(form);

                    // Send full international number
                    formData.set("number", iti.getNumber());

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
                                    text: "Profile updated successfully."
                                });

                                // Reload profile with latest values
                                loadProfile();

                            } else {

                                Swal.fire({
                                    icon: "error",
                                    title: "Error",
                                    text: response.message || "Unable to update profile."
                                });

                            }

                        },

                        error: function (xhr) {

                            console.error(xhr.responseText);

                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: "Something went wrong."
                            });

                        }

                    });

                }

            });

        });
    </script>
</body>

</html>