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