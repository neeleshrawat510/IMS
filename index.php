<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Page</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
        }

        section {
            height: 100vh;
        }

        .card,
        .row,
        .col-lg-4 {
            height: 100%;
        }

        img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .rounded-t-5 {
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }

        @media (min-width: 992px) {
            .rounded-tr-lg-0 {
                border-top-right-radius: 0;
            }

            .rounded-bl-lg-5 {
                border-bottom-left-radius: 0.5rem;
            }
        }

        label.error {
            color: red;
        }
    </style>
</head>

<body>

    <!-- Login Card -->
    <section class="vh-100">
        <div class="card h-100 border-0 rounded-0">
            <div class="row g-0 h-100">
                <div class="col-lg-4 d-none d-lg-block">
                    <img src="uploads/login_img1.jpg" class="img-fluid w-100 h-100" alt="Login"
                        style="object-fit: cover;">
                </div>
                <div class="col-lg-8 d-flex align-items-center">
                    <div class="card-body">

                        <form method="post" id="login">
                            <h3 class="text-primary text-center mb-5">LOGIN</h3>
                            <!-- Email input -->
                            <div data-mdb-input-init class="form-outline mb-4">
                                <label class="form-label" for="email">Email address</label>
                                <input type="email" id="email" class="form-control" name="email"
                                    placeholder="Enter your Email" />
                            </div>

                            <!-- Password input -->
                            <div data-mdb-input-init class="form-outline mb-4">
                                <label class="form-label" for="password">Password</label>
                                <input type="password" id="password" class="form-control" name="password"
                                    placeholder="Enter your Password" />
                            </div>


                            <!-- Remember me & forgot password -->
                            <div class="row mb-4">
                                <div class="col d-flex justify-content-start">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="rememberMe" />
                                        <label class="form-check-label" for="rememberMe">
                                            Remember me
                                        </label>
                                    </div>
                                </div>

                                <div class="col text-end">
                                    <a href="forgot_password.php">Forgot password?</a>
                                </div>
                            </div>

                            <!-- Submit button -->
                            <button type="submit" data-mdb-button-init data-mdb-ripple-init
                                class="btn btn-primary btn-block mb-4">Sign in</button>

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
        $(document).ready(function () {

            //load saved data
            if (localStorage.getItem("rememberMe") === "true") {

                $("#email").val(localStorage.getItem("email"));
                $("#password").val(localStorage.getItem("password"));
                $("#rememberMe").prop("checked", true);
            }
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

                submitHandler: function (form) {
                    // save login details
                    if ($("#rememberMe").is(":checked")) {

                        localStorage.setItem("rememberMe", true);
                        localStorage.setItem("email", $("#email").val());
                        localStorage.setItem("password", $("#password").val());

                    } else {

                        localStorage.removeItem("rememberMe");
                        localStorage.removeItem("email");
                        localStorage.removeItem("password");
                    }

                    let formData = new FormData(form);
                    $.ajax({
                        url: "php/login_user.php",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,

                        success: function (response) {
                            if (response.trim() == 'success') {
                                Swal.fire({
                                    position: "center",
                                    icon: "success",
                                    title: "You are successfully logged In",
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    window.location.href = "dashboard.php";
                                });
                            } else {

                                Swal.fire({
                                    icon: "error",
                                    title: "Login Failed",
                                    text: "Invalid email or password"
                                });

                            }
                        },
                        error: function (response) {
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