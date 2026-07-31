<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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

        /* Hero Badge */

        .hero-badge {

            display: inline-block;
            padding: 8px 16px;
            margin-bottom: 25px;

            background: rgba(255, 255, 255, .08);

            border: 1px solid rgba(255, 255, 255, .12);

            border-radius: 30px;

            color: #9ec5ff;

            font-size: 13px;

            letter-spacing: .5px;

        }

        /* Heading */

        .left-section h1 {

            font-size: 50px;

            font-weight: 700;

            line-height: 1.15;

            margin-bottom: 20px;

        }

        .left-section h1 span {

            color: #79a8ff;

        }

        /* Description */

        .hero-description {

            width: 430px;

            color: #d9e5ff;

            font-size: 16px;

            line-height: 1.8;

            margin-bottom: 35px;

        }

        /* Feature List */

        .feature-list {

            margin-bottom: 40px;

        }

        .feature-item {

            display: flex;

            align-items: center;

            margin-bottom: 18px;

            font-size: 16px;

        }

        .feature-item i {

            color: #79a8ff;

            margin-right: 12px;

            font-size: 18px;

        }

        /* Stats */

        .stats {

            display: flex;

            gap: 20px;

        }

        .stat-box {

            min-width: 120px;

        }

        .stat-box h3 {

            color: white;

            font-size: 28px;

            font-weight: 700;

            margin-bottom: 5px;

        }

        .stat-box small {

            color: #b7c7ec;

            font-size: 13px;

        }

        /* Login card */
        .login-card {

            width: 390px;

            padding: 42px;

            border-radius: 18px;

            background: rgba(48, 48, 48, .82);

            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);

            border: 1px solid rgba(255, 255, 255, .08);

            box-shadow:
                0 25px 60px rgba(0, 0, 0, .45);

            align-self: center;

            transition: .3s;

        }

        .login-card:hover {

            transform: translateY(-3px);

        }


        .logo {

            width: 72px;
            height: 72px;

            margin: auto;

            border-radius: 50%;

            background: linear-gradient(135deg, #2563eb, #4f86ff);

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 30px;

            color: white;

            box-shadow:
                0 12px 25px rgba(37, 99, 235, .45);

            position: relative;

        }

        .logo::after {

            content: "";

            width: 14px;
            height: 14px;

            border-radius: 50%;

            background: #8b5cf6;

            position: absolute;

            top: 4px;
            right: 4px;

            border: 2px solid #343434;

        }


        .login-card h2 {

            margin-top: 20px;

            font-size: 38px;

            font-weight: 700;

            color: white;

        }

        .login-card p {

            color: #b8b8b8;

            margin-bottom: 28px;

        }





        .form-control {

            height: 52px;

            border-radius: 10px;

            background: rgba(255, 255, 255, .03);

            border: 1px solid rgba(255, 255, 255, .15);

            color: white;

            padding-left: 15px;

            transition: .3s;

        }

        .form-control::placeholder {

            color: #b7b7b7;

        }

        .form-control:focus {

            background: rgba(255, 255, 255, .05);

            border-color: #4f86ff;

            color: white;

            box-shadow:
                0 0 0 4px rgba(79, 134, 255, .15);

        }

        .input-group-text {

            background: rgba(255, 255, 255, .03);

            border: 1px solid rgba(255, 255, 255, .15);

            color: #cfcfcf;

            transition: .3s;

        }

        .input-group-text:hover {

            color: white;

        }


        .form-check-label,
        .login-card a {
            color: #ddd;
            font-size: 14px;
        }


        .btn-primary {

            height: 52px;

            border: none;

            border-radius: 10px;

            background:
                linear-gradient(90deg, #2563eb, #4f86ff);

            font-weight: 600;

            letter-spacing: .5px;

            transition: .3s;

        }

        .btn-primary:hover {

            transform: translateY(-2px);

            background:
                linear-gradient(90deg, #1d4ed8, #3975db);

            box-shadow:
                0 14px 25px rgba(37, 99, 235, .35);

        }


        .divider {

            display: flex;

            align-items: center;

            color: #a9a9a9;

            margin: 26px 0;

            font-size: 14px;

        }

        .divider::before,
        .divider::after {

            content: "";

            flex: 1;

            height: 1px;

            background: rgba(255, 255, 255, .12);

        }

        .divider::before {

            margin-right: 12px;

        }

        .divider::after {

            margin-left: 12px;

        }


        .footer {

            margin-top: 30px;

            color: #999;

            font-size: 12px;

            line-height: 1.8;

        }


        label.error {
            color: #ff7676;
            font-size: 13px;
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


    <div class="login-box">


        <!-- LEFT CONTENT -->
        <div class="left-section">

            <div class="hero-badge">
                Trusted Invoice Platform
            </div>

            <h1>
                Invoice Management
                <br>
                <span>Made Simple</span>
            </h1>

            <p class="hero-description">
                Create professional invoices, manage customers, collect online payments,
                and monitor your business from one secure platform.
            </p>

            <div class="feature-list">

                <div class="feature-item">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Professional Invoicing</span>
                </div>

                <div class="feature-item">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Online Payment Collection</span>
                </div>

                <div class="feature-item">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Customer & Product Management</span>
                </div>

                <div class="feature-item">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Reports & Analytics</span>
                </div>

            </div>

            <div class="stats">

                <div class="stat-box">
                    <h3>5000+</h3>
                    <small>Invoices Created</small>
                </div>

                <div class="stat-box">
                    <h3>₹2.8 Cr+</h3>
                    <small>Payments Processed</small>
                </div>

                <div class="stat-box">
                    <h3>99.9%</h3>
                    <small>Secure Platform</small>
                </div>

            </div>

        </div>



        <!-- LOGIN CARD -->
        <div class="login-card">


            <div class="text-center mb-3">

                <div class="logo">
                    <i class="bi bi-receipt"></i>
                </div>


                <h2 class="mt-3">
                    Welcome Back
                </h2>


                <p>
                    Sign in to continue
                </p>

            </div>



            <form id="login">


                <div class="mb-3">

                    <input type="email" id="email" name="email" class="form-control" placeholder="Email address">

                </div>



                <div class="mb-3">

                    <div class="input-group">

                        <input type="password" id="password" name="password" class="form-control"
                            placeholder="Password">


                        <span class="input-group-text" id="togglePassword">

                            <i class="bi bi-eye"></i>

                        </span>


                    </div>

                </div>




                <div class="d-flex justify-content-between mb-3">


                    <div class="form-check">

                        <input class="form-check-input" type="checkbox" id="rememberMe">


                        <label class="form-check-label">
                            Remember me
                        </label>

                    </div>



                    <a href="forgot_password.php">
                        Forgot password?
                    </a>


                </div>



                <button class="btn btn-primary w-100" type="submit">

                    SIGN IN

                </button>



                <div class="divider">
                    or
                </div>




                <!-- Google Login KEEP SAME -->

                <div id="g_id_onload"
                    data-client_id="92348507939-74ujcuui4ce2g0pt2ipk287voa8io6sg.apps.googleusercontent.com"
                    data-callback="handleGoogleLogin">
                </div>


                <div class="g_id_signin d-flex justify-content-center" data-type="standard" data-width="300">
                </div>




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


    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- jQuery Validation Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

    <!-- Sweet alert cdn -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {

            // Show/Hide Password
            $("#togglePassword").click(function () {
                const password = $("#password");
                const icon = $(this).find("i");

                if (password.attr("type") === "password") {
                    password.attr("type", "text");
                    icon.removeClass("bi-eye").addClass("bi-eye-slash");
                } else {
                    password.attr("type", "password");
                    icon.removeClass("bi-eye-slash").addClass("bi-eye");
                }
            });

            //load saved data
            if (localStorage.getItem("rememberMe") === "true") {
                $("#email").val(localStorage.getItem("email"));
                $("#password").val(localStorage.getItem("password"));
                $("#rememberMe").prop("checked", true);
            }

            // Shared success handler for both login methods
            function onLoginSuccess(token, refreshToken) {
                // Store JWT in a cookie so it travels with normal page navigation
                document.cookie = "auth_token=" + token + "; path=/; max-age=3600; SameSite=Lax" +
                    (location.protocol === "https:" ? "; Secure" : "");

                document.cookie =
                    "refresh_token=" + refreshToken +
                    "; path=/; max-age=" + (30 * 24 * 60 * 60) + "; SameSite=Lax" +
                    (location.protocol === "https:" ? "; Secure" : "");

                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "You are successfully logged In",
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = "dashboard.php";
                });
            }

            // Google Sign-In callback (invoked by Google's library)
            window.handleGoogleLogin = function (response) {
                $.ajax({
                    url: "api/jwt/google_login.php",
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify({ id_token: response.credential }),
                    success: function (res) {
                        res = typeof res === "string" ? JSON.parse(res) : res;

                        if (res.status === "success") {
                            onLoginSuccess(res.token, res.refresh_token);
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Google Login Failed",
                                text: res.message || "Something went wrong"
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({ icon: "error", title: "Oops...", text: "Something went wrong!" });
                    }
                });
            };

            // validation
            $("#login").validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true
                    }
                },
                messages: {
                    email: {
                        required: "Email is required",
                        email: "abc@gmail.com format required"
                    },
                    password: {
                        required: "Password is required"
                    }
                },
                errorPlacement: function (error, element) {
                    if (element.parent(".input-group").length) {
                        error.insertAfter(element.parent());   // Place error after the entire input-group
                    } else {
                        error.insertAfter(element);
                    }
                },

                submitHandler: function (form) {

                    // Save login details
                    if ($("#rememberMe").is(":checked")) {

                        localStorage.setItem("rememberMe", "true");
                        localStorage.setItem("email", $("#email").val());

                    } else {

                        localStorage.removeItem("rememberMe");
                        localStorage.removeItem("email");
                    }

                    $.ajax({
                        url: "api/jwt/login.php",
                        type: "POST",
                        contentType: "application/json",
                        data: JSON.stringify({
                            email: $("#email").val(),
                            password: $("#password").val()
                        }),
                        success: function (res) {

                            res = typeof res === "string" ? JSON.parse(res) : res;

                            if (res.status === "success") {

                                onLoginSuccess(res.token, res.refresh_token);
                            } else {

                                Swal.fire({
                                    icon: "error",
                                    title: "Login Failed",
                                    text: res.message || "Invalid email or password"
                                });

                            }

                        },
                        error: function () {

                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Something went wrong!"
                            });

                        }
                    });

                }
            });
        });
    </script>
</body>

</html>