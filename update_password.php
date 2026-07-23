<?php
include("config/connection.php");

$token = $_GET['token'] ?? '';

if (!$token) {
    die("Invalid or missing reset token.");
}

// validate token once
$query = mysqli_query($conn, "
    SELECT id FROM users 
    WHERE reset_token='$token' 
    AND token_expiry > NOW()
");

if (mysqli_num_rows($query) == 0) {
    die("This password reset link is invalid or has expired.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Management System | Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Identity Services -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <style>
        body {
            margin: 0;
            font-family: Segoe UI, sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 25px
        }

        .card {
            max-width: 1150px;
            width: 100%;
            border: none;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0, 0, 0, .25)
        }

        .left {
            position: relative;
            min-height: 700px
        }

        .left img {
            width: 100%;
            height: 100%;
            object-fit: cover
        }

        .overlay {
            position: absolute;
            inset: 0;
            background: rgba(20, 40, 90, .55);
            color: #fff;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end
        }

        .right {
            padding: 55px
        }

        .logo {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #2563eb;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin: auto
        }

        .form-control {
            height: 52px
        }

        .btn-primary {
            height: 52px;
            border-radius: 10px
        }

        .footer {
            font-size: 13px;
            color: #666;
            text-align: center;
            margin-top: 25px
        }

        .rounded-t-5 {
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }

        label.error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }


        @media (min-width: 992px) {
            .rounded-tr-lg-0 {
                border-top-right-radius: 0;
            }

            .rounded-bl-lg-5 {
                border-bottom-left-radius: 0.5rem;
            }
        }

        .success-msg {
            color: green;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="row g-0">
            <div class="col-lg-5 d-none d-lg-block left">
                <img src="uploads/login_img1.jpg" alt="">
                <!-- <div class="overlay">
                    <h1>Invoice Management System</h1>
                    <p>Manage invoices, customers and payments from one secure platform.</p>
                    <ul>
                        <li>Create Professional Invoices</li>
                        <li>Manage Products</li>
                        <li>Manage Customers</li>
                        <li>Secure Authentication</li>
                    </ul>
                    <p><strong>Developed by</strong><br>Baseline IT Development Pvt Ltd</p>
                </div> -->
            </div>
            <div class="col-lg-7 right">
                <div class="text-center mb-4">
                    <div class="logo"><i class="bi bi-receipt"></i></div>
                    <h2 class="mt-3">Reset Password</h2>
                    <p class="text-muted">Setup a new password</p>
                </div>
                <form method="post" id="resetForm">

                    <!-- reset token -->
                    <input type="hidden" name="token" value="<?= $token ?>">
                    <!-- Email input -->
                    <div data-mdb-input-init class="form-outline mb-4">
                        <label class="form-label" for="password">Create New Password</label>
                        <input type="password" id="password" class="form-control" name="password"
                            placeholder="Enter Password" />
                    </div>
                    <div data-mdb-input-init class="form-outline mb-4">
                        <label class="form-label" for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" class="form-control" name="confirm_password"
                            placeholder="Enter Confirm Password" />
                    </div>

                    <!-- Submit button -->
                    <button class="btn btn-primary w-100" type="submit">Reset Password</button>
                    <div class="footer">
                        © <?= date('Y') ?> Invoice Management System<br>
                        Developed by <strong>Baseline IT Development Pvt Ltd</strong>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- jQuery Validation Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

    <!-- Sweet alert cdn -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            // Add validator for strong password
            $.validator.addMethod("strongPassword", function (value, element) {
                return this.optional(element) || /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/.test(value);
            });

            $("#resetForm").validate({
                rules: {
                    password: {
                        required: true,
                        strongPassword: true
                    },
                    confirm_password: {
                        required: true,
                        equalTo: "#password"
                    }
                },
                messages: {
                    password: {
                        required: "This field is required",
                        strongPassword: "Atleast 1 Uppercase, lowercase, number and special character required"
                    },
                    confirm_password: {
                        required: "This field is required",
                        equalTo: "Password and confirm password should be same"
                    }
                },
                submitHandler: function (form) {
                    $.ajax({
                        url: "php/new_password.php",
                        type: "POST",
                        data: $(form).serialize(),

                        beforeSend: function () {
                            $("button[type=submit]").prop("disabled", true).text("Updating...");
                        },

                        success: function (response) {

                            if (response.trim() == "success") {
                                Swal.fire({
                                    icon: "success",
                                    title: "Password Updated",
                                    text: "You can now login",
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = "login.php";
                                });

                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Error",
                                    text: "Failed to update password"
                                });
                            }
                        },

                        error: function () {
                            Swal.fire({
                                icon: "error",
                                title: "Oops",
                                text: "Something went wrong"
                            });
                        },

                        complete: function () {
                            $("button[type=submit]").prop("disabled", false).text("Update Password");
                        }
                    });

                }
            });
        });
    </script>

</body>

</html>