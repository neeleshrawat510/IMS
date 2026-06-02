<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Page</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(to right, #4facfe, #00f2fe);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
        }

        .register-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .register-card h2 {
            text-align: center;
            margin-bottom: 25px;
            font-weight: bold;
            color: #333;
        }

        .form-control {
            height: 45px;
            border-radius: 10px;
        }

        .btn-register {
            width: 100%;
            height: 45px;
            border-radius: 10px;
            background: #0d6efd;
            border: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-register:hover {
            background: #084298;
        }

        .login-link {
            text-align: center;
            margin-top: 15px;
        }

        .login-link a {
            text-decoration: none;
        }

        .error {
            color: red;
        }
    </style>
</head>

<body>

    <!-- Register Card -->
    <div class="register-card">
        <h2>Login</h2>

        <form method="post" id="login">

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email"
                    class="form-control"
                    name="email"
                    id="email"
                    placeholder="Enter your email">
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password"
                    class="form-control"
                    name="password"
                    id="password"
                    placeholder="Enter password">
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary btn-register">
                Login
            </button>

            <!-- Login Link -->
            <div class="login-link">
                <p class="mt-3">
                    Not registered yet?
                    <a href="register.php">Register</a>
                </p>
            </div>
        </form>
    </div>

    <script>

    </script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- jQuery Validation Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

    <!-- Sweet alert cdn -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
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

                submitHandler: function(form) {
                    let formData = new FormData(form);
                    $.ajax ({
                        url: "php/login_user.php",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,

                        success: function(response) {
                            if (response == 'success') {
                                Swal.fire({
                                    position: "center",
                                    icon: "success",
                                    title: "You are successfully logged In",
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                window.location.href= "dashboard.php";
                            } else{

                            Swal.fire({
                                icon: "error",
                                title: "Login Failed",
                                text: "Invalid email or password"
                            });

                        }
                        },
                        error: function(response) {
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