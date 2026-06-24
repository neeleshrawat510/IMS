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

                        <form method="post" id="forgotForm">
                            <h3 class="text-primary text-center mb-5">FORGOT PASSWORD</h3>
                            <!-- Email input -->
                            <div data-mdb-input-init class="form-outline mb-4">
                                <label class="form-label" for="email">Email address</label>
                                <input type="email" id="email" class="form-control" name="email" placeholder="Enter your Email" />
                            </div>
                            <div id="emailMessage" class="mt-2"></div>

                            <!--  LOGIN if you have password-->
                            <div class="row mb-4">
                                <div class="col">
                                    <a href="index.php">Login Here</a>
                                </div>
                            </div>

                            <!-- Submit button -->
                            <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-4">Send Reset Link</button>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Section: Design Block -->


    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- jQuery Validation Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

    <!-- Sweet alert cdn -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $("#forgotForm").validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    }
                },
                messages: {
                    email: {
                        required: "Email is required",
                        email: "abc@gmail.com format required"
                    }
                },

                submitHandler: function(form) {
                    let formData = new FormData(form);
                    $.ajax({
                        url: "controller/reset_password.php",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function() {
                            $("button[type=submit]").prop("disabled", true).text("Sending...");
                        },
                        success: function(response) {
                            if (response.trim() == 'success') {

                                $("#emailMessage")
                                    .removeClass("error-message")
                                    .addClass("success-msg")
                                    .text("Please check your email to reset password");

                                Swal.fire({
                                    position: "center",
                                    icon: "success",
                                    title: "Email Verified",
                                    text: "Password reset link send to your Email",
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            } else {

                                Swal.fire({
                                    icon: "error",
                                    title: "Failed",
                                    text: "Invalid email"
                                });

                            }
                        },
                        error: function(response) {
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Something went wrong!"
                            });

                        },
                        complete: function() {
                            $("button[type=submit]").prop("disabled", false).text("Send Reset Link");
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>