<?php
include("config/connection.php");

$token = $_GET['token'] ?? '';

if (!$token) {
    die("Invalid reset link.");
}

// validate token once
$query = mysqli_query($conn, "
    SELECT * FROM users 
    WHERE reset_token='$token' 
    AND token_expiry > NOW()
");

if (mysqli_num_rows($query) == 0) {
    die("This reset link is invalid or expired.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forgot Password</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
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

        .success-msg{
            color: green;
        }
    </style>
</head>

<body>

    <!-- Login Card -->
    <section class=" text-center text-lg-start vh-100">

        <div class="card mb-3">
            <div class="row g-0 d-flex align-items-center">
                <div class="col-lg-4 d-none d-lg-flex">
                    <img src="uploads/login_img1.jpg" alt="Login Image"
                        class="w-100 rounded-t-5 rounded-tr-lg-0 rounded-bl-lg-5" />
                </div>
                <div class="col-lg-8">
                    <div class="card-body py-5 px-md-5">

                        <form method="post" id="resetForm">
                            <h3 class="text-primary text-center mb-5">RESET YOUR PASSWORD</h3>
                           
                            <!-- reset token -->
                            <input type="hidden" name="token" value="<?= $token ?>">
                            <!-- Email input -->
                            <div data-mdb-input-init class="form-outline mb-4">
                                <label class="form-label" for="password">Create New Password</label>
                                <input type="password" id="password" class="form-control" name="password" placeholder="Enter Password"/>
                            </div>
                            <div data-mdb-input-init class="form-outline mb-4">
                                <label class="form-label" for="confirm_password">Confirm Password</label>
                                <input type="password" id="confirm_password" class="form-control" name="confirm_password" placeholder="Enter Confirm Password"/>
                            </div>

                            <!-- Submit button -->
                            <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-4">Send Reset Link</button>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- jQuery Validation Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

    <!-- Sweet alert cdn -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
             // Add validator for strong password
            $.validator.addMethod("strongPassword", function(value, element) {
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
                submitHandler: function(form) {
                    $.ajax({
                        url: "php/new_password.php",
                        type: "POST",
                        data: $(form).serialize(),

                        beforeSend: function() {
                            $("button[type=submit]").prop("disabled", true).text("Updating...");
                        },

                        success: function(response) {

                            if (response.trim() == "success") {
                                Swal.fire({
                                    icon: "success",
                                    title: "Password Updated",
                                    text: "You can now login",
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = "index.php";
                                });

                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Error",
                                    text: "Failed to update password"
                                });
                            }
                        },

                        error: function() {
                            Swal.fire({
                                icon: "error",
                                title: "Oops",
                                text: "Something went wrong"
                            });
                        },

                        complete: function() {
                            $("button[type=submit]").prop("disabled", false).text("Update Password");
                        }
                    });

                }
            });
        });
    </script>

</body>

</html>