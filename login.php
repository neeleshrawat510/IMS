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
            font-family: "Segoe UI", sans-serif;

            display: flex;
            justify-content: center;
            align-items: center;

            padding: 25px;

            overflow: hidden;

            background:
                radial-gradient(circle at top left, rgba(37, 99, 235, .35), transparent 28%),
                radial-gradient(circle at bottom right, rgba(79, 70, 229, .35), transparent 30%),
                linear-gradient(135deg, #eef4ff, #f7f9fc);
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(0, 0, 0, .04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 0, 0, .04) 1px, transparent 1px);

            background-size: 45px 45px;

            z-index: -2;
        }

        body::after {

            content: "";

            position: fixed;

            width: 500px;
            height: 500px;

            background: #2563eb22;

            filter: blur(90px);

            border-radius: 50%;

            top: -180px;
            right: -150px;

            z-index: -1;

        }

        .card {

            width: 100%;
            max-width: 1100px;

            min-height: 640px;

            border: none;

            border-radius: 22px;

            background: #fff;

            box-shadow:
                0 30px 60px rgba(15, 23, 42, .12),
                0 10px 25px rgba(15, 23, 42, .08);

        }

        .left {
            position: relative;
            min-height: 700px
        }

        .left {

            min-height: 640px;

            position: relative;

        }

        .overlay {

            position: absolute;

            inset: 0;

            background:
                linear-gradient(rgba(16, 24, 40, .45),
                    rgba(16, 24, 40, .75));

            display: flex;

            flex-direction: column;

            justify-content: flex-end;

            padding: 45px;

            color: white;

        }

        .right {

            padding: 40px 60px;

            display: flex;

            align-items: center;

        }

        .logo {

            width: 74px;
            height: 74px;

            border-radius: 20px;

            background:
                linear-gradient(135deg, #2563eb, #4f46e5);

            color: white;

            display: flex;

            justify-content: center;

            align-items: center;

            font-size: 34px;

            margin: auto;

            box-shadow:
                0 15px 30px rgba(37, 99, 235, .25);

        }

        .form-control {

            height: 48px;

            border-radius: 12px;

            border: 1px solid #dbe3ef;

            background: #f8fafc;

        }

        .form-control:focus {

            border-color: #2563eb;

            background: white;

            box-shadow: 0 0 0 .2rem rgba(37, 99, 235, .12);

        }

        .input-group-text {

            background: #f8fafc;

            border: 1px solid #dbe3ef;

            border-left: none;

            border-radius: 0 12px 12px 0;

        }

        .btn-primary {

            height: 50px;

            border: none;

            border-radius: 12px;

            font-weight: 600;

            background:
                linear-gradient(135deg, #2563eb, #4f46e5);

            transition: .3s;

        }

        .btn-primary:hover {

            transform: translateY(-2px);

            box-shadow: 0 12px 25px rgba(37, 99, 235, .25);

        }

        h2 {

            font-weight: 700;

            color: #111827;

        }

        .text-muted {

            color: #6b7280 !important;

        }

        .footer {

            margin-top: 22px;

            text-align: center;

            color: #94a3b8;

            font-size: 13px;

        }

        .footer strong {

            color: #2563eb;

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
            <div class="overlay">

                <h2 class="fw-bold mb-3">
                    Invoice Management System
                </h2>

                <p class="mb-4">
                    Simplify billing, manage customers, monitor payments, and generate professional invoices from one
                    secure platform.
                </p>

                <div class="mb-2">
                    <i class="bi bi-check-circle-fill text-success"></i>
                    Professional Invoice Creation
                </div>

                <div class="mb-2">
                    <i class="bi bi-check-circle-fill text-success"></i>
                    Customer & Product Management
                </div>

                <div class="mb-2">
                    <i class="bi bi-check-circle-fill text-success"></i>
                    Payment Tracking
                </div>

                <div class="mt-4 small">
                    <div class="text-white-50">
                        Developed by
                    </div>

                    <strong class="fs-6">
                        Baseline IT Development Pvt Ltd
                    </strong>

                </div>

            </div>
            <div class="col-lg-7 right">
                <div class="text-center mb-4">
                    <div class="logo"><i class="bi bi-receipt"></i></div>
                    <h2 class="mt-3">Welcome Back</h2>
                    <p class="text-muted">Sign in to continue</p>
                </div>
                <form id="login">
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Enter your Email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" id="password" name="password" class="form-control"
                                placeholder="Enter your Password">
                            <span class="input-group-text" id="togglePassword"><i class="bi bi-eye"></i></span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">Remember me</label>
                        </div>
                        <a href="forgot_password.php">Forgot password?</a>
                    </div>
                    <button class="btn btn-primary w-100" type="submit">Sign In</button>
                    <div class="text-center my-3 text-muted">or</div>
                    <!-- Google Sign-In -->
                    <div id="g_id_onload"
                        data-client_id="92348507939-74ujcuui4ce2g0pt2ipk287voa8io6sg.apps.googleusercontent.com"
                        data-callback="handleGoogleLogin">
                    </div>
                    <div class="g_id_signin d-flex justify-content-center" data-type="standard" data-width="300"></div>
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