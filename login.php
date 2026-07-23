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

            <h1>
                Smart Invoicing<br>
                <span>Made Simple</span>
            </h1>

            <p>
                A secure and reliable solution to create professional invoices,
                manage clients, track payments, and improve your business workflow.
            </p>

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