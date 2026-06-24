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
    <title>Add New Contact | Invoice Management System</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 8px;
            border: 1px solid #aaa;
            border-radius: 4px;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        label.error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }

        input.error {
            border: 1px solid red;
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

                    <form class="panel needs-validation" id="contactForm">
                        <div class="panel-header">
                            <div>
                                <h2 class="h5 mb-1 section-title">
                                    <i class="bi bi-ui-checks-grid" aria-hidden="true"></i>
                                    <span>Contacts</span>
                                </h2>
                                <p class="text-muted mb-0">Add new contact</p>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="name">Name</label>
                                <input type="text" class="" name="name" id="name" />
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="number">Contact No.</label>
                                <input type="text" class="" name="number" id="number" />
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="email">Email</label>
                                <input type="text" class="" name="email" id="email" />
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="company">Company Name</label>
                                <input type="text" class="" name="company" id="company" />
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="gst">GST/VAT</label>
                                <input type="text" class="" name="gst" id="gst" />
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="address">Address</label>
                                <textarea name="address" class="" id="address" rows="4" cols="42"></textarea>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-send" aria-hidden="true"></i>Add Contact</button>
                            </div>

                    </form>

                </div>
            </main>
        </div>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery Validation Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

    <!-- Sweet alert cdn -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- LOGOUT Redirect -->
    <script src="controller/logout.js"></script>

    <script>
        $(document).ready(function() {

            // Custom method for GST validation
            $.validator.addMethod("gstin", function(value, element) {
                value = value.toUpperCase().trim();
                var gstRegex = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z][1-9A-Z]Z[0-9A-Z]$/;
                return this.optional(element) || gstRegex.test(value);
            }, "Please enter a valid GSTIN");


            $("#contactForm").validate({
                rules: {
                    name: {
                        required: true
                    },
                    number: {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 10,
                        remote: {
                            url: "controller/check_number.php",
                            type: "POST"
                        }
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    company: {
                        required: true
                    },
                    gst: {
                        required: true,
                        gstin: true
                    },
                    address: {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: "Name is required"
                    },
                    number: {
                        required: "Number is required",
                        digits: "Required only 0-9",
                        minlength: "10 digits required",
                        maxlength: "10 digits required",
                        remote: "This Contact Number is already exist"
                    },
                    email: {
                        required: "Email is required",
                        email: "abc@gmail.com format required"
                    },
                    company: {
                        required: "Company Name is required"
                    },
                    gst: {
                        required: "GST/VAT is required",
                        gstin: "22ABCDE2222A1Z3 format required"
                    },
                    address: {
                        required: "Address is required"
                    }
                },
                submitHandler: function(form) {

                    let formData = new FormData(form);
                    $.ajax({
                        url: "php/add_new_contact.php",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,

                        success: function(response) {
                            if (response.trim() == 'success') {
                                Swal.fire({
                                    title: "Successful",
                                    text: "New Contact added",
                                    icon: "success"
                                });
                                window.location.href = "manage_contact.php"
                            } else {
                                (response.trim() == 'failed');
                                Swal.fire({
                                    title: "Contact Not created! Try again",
                                    icon: "error",
                                    draggable: false
                                });

                            }
                            $("#contactForm")[0].reset();
                        },
                        error: function() {
                            Swal.fire({
                                title: "error",
                                text: "Something went wrong!",
                                icon: "error"
                            });
                        }
                    });
                }
            });
        });
    </script>

</body>

</html>