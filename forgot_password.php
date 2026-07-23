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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: "Segoe UI", sans-serif;
            background: linear-gradient(120deg, #1e293b, #334f8c);
            overflow-y: auto;
            overflow-x: hidden;
            padding: 20px;
        }


        /* Background circles */
        body:before,
        body:after {
            content: "";
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, #9c00ff, #c300ff);
            z-index: 0;
        }

        body:before {
            width: 150px;
            height: 150px;
            top: -30px;
            left: -30px;
        }

        body:after {
            width: 220px;
            height: 220px;
            right: -60px;
            bottom: 20px;
        }


        /* Main container */
        .login-box {
            width: 1000px;
            max-width: 95%;
            min-height: 550px;
            display: flex;
            position: relative;
            z-index: 1;
        }


        /* Left section */
        .left-section {
            flex: 1;
            color: white;
            padding: 80px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }


        .left-section h1 {
            font-size: 42px;
            font-weight: 700;
            line-height: 1.2;
        }


        .left-section h1 span {
            color: #79a8ff;
        }


        .left-section p {
            margin-top: 25px;
            color: #d0dcff;
            width: 330px;
            line-height: 1.7;
            font-size: 15px;
        }


        /* Login card */
        .login-card {
            width: 380px;
            background: #343434;
            padding: 35px;
            border-radius: 8px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, .35);
            align-self: center;
        }


        .logo {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            background: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin: auto;
            font-size: 28px;
        }


        .login-card h2 {
            color: white;
        }


        .login-card p {
            color: #aaa;
        }


        .form-control {
            height: 45px;
            background: transparent;
            border: 1px solid #aaa;
            color: white;
        }


        .form-control::placeholder {
            color: #bbb;
        }


        .form-control:focus {
            background: transparent;
            color: white;
            border-color: #4f86ff;
            box-shadow: none;
        }


        .input-group-text {
            background: transparent;
            border: 1px solid #aaa;
            color: white;
            cursor: pointer;
        }


        .form-check-label,
        .login-card a {
            color: #ddd;
            font-size: 14px;
        }


        .btn-primary {
            height: 45px;
            background: #3975db;
            border: none;
            border-radius: 4px;
        }


        .btn-primary:hover {
            background: #2563eb;
        }


        .divider {
            color: white;
            text-align: center;
            margin: 20px 0;
        }


        .footer {
            color: #aaa;
            font-size: 13px;
            text-align: center;
            margin-top: 20px;
        }


        label.error {
            color: #ff7676;
            font-size: 13px;
        }
        
        .success-msg{
            color: green;
        }

        @media(max-width:768px) {

            .login-box {
                display: block;
            }

            .left-section {
                display: none;
            }

            .login-card {
                margin: auto;
            }

        }
    </style>


<body>



        <!-- FORGOT PASSWORD CARD -->
        <div class="login-card">
            <div class="text-center mb-3">

                <div class="logo">
                    <i class="bi bi-receipt"></i>
                </div>

                <h2 class="mt-3">
                    FORGOT PASSWORD
                </h2>


                <p>
                    Enter your registered Email to get reset password link
                </p>

            </div>



            <form id="login">


                <div class="mb-3">

                    <input type="email" id="email" class="form-control" name="email" placeholder="Enter your Email" />
                </div>
                <div id="emailMessage" class="mt-2"></div>

                <button class="btn btn-primary w-100" type="submit">

                    Send Reset Link

                </button>

                <div class="footer">

                    © <?= date('Y') ?> Invoice Management System
                    <br>

                    Developed by
                    <strong>
                        Baseline IT Development Pvt Ltd
                    </strong>

                </div>



            </form>


        </div>



    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- jQuery Validation Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

    <!-- Sweet alert cdn -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
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

                submitHandler: function (form) {
                    let formData = new FormData(form);
                    $.ajax({
                        url: "controller/reset_password.php",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function () {
                            $("button[type=submit]").prop("disabled", true).text("Sending...");
                        },
                        success: function (response) {
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
                        error: function (response) {
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Something went wrong!"
                            });

                        },
                        complete: function () {
                            $("button[type=submit]").prop("disabled", false).text("Send Reset Link");
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>