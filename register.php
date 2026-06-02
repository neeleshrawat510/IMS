<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register Page</title>

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
        <h2>Create Account</h2>

        <form method="post" id="register">

            <span id="msg"></span>

            <!-- Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text"
                    class="form-control"
                    name="name"
                    id="name"
                    placeholder="Enter your name">
            </div>

            <!-- Number -->
            <div class="mb-3">
                <label for="number" class="form-label">Mobile Number</label>
                <input type="text"
                    class="form-control"
                    name="number"
                    id="number"
                    placeholder="Enter your number">
            </div>

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

            <!-- Confirm Password -->
            <div class="mb-3">
                <label for="cnfPassword" class="form-label">Confirm Password</label>
                <input type="password"
                    class="form-control"
                    name="cnfPassword"
                    id="cnfPassword"
                    placeholder="Enter confirm Password">
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary btn-register">
                Register
            </button>

            <!-- Login Link -->
            <div class="login-link">
                <p class="mt-3">
                    Already have an account?
                    <a href="index.php">Login</a>
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
            // Add validator for strong password
            $.validator.addMethod("strongPassword", function(value, element) {
                return this.optional(element) || /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/.test(value);
            });

            $("#register").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 4
                    },
                    number: {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 10
                    },
                    email: {
                        required: true,
                        email: true,
                        remote: {
                            url: "controller/check_email.php",
                            type: "GET"
                        }
                    },
                    password: {
                        required: true,
                        strongPassword: true
                    },
                    cnfPassword: {
                        required: true,
                        equalTo: "#password"

                    }
                },
                messages: {
                    name: {
                        required: "Name is required",
                        minlength: "Minimum 4 characters required"
                    },
                    number: {
                        required: "Number is required",
                        digits: "Required only 0-9",
                        minlength: "10 digits Required",
                        maxlength: "10 digits Required"
                    },
                    email: {
                        required: "Email is required",
                        email: "abc@gmail.com format required",
                        remote: "Email already exists"
                    },
                    password: {
                        required: "Password is required",
                        strongPassword: "atleast 1 uppercase, lowercase, number and special character required"
                    },
                    cnfPassword: {
                        required: "Confirm password is required",
                        equalTo: "Confirm password and password should match"
                    }

                },

                submitHandler: function(form) {
                    let formData = new FormData(form);
                    $.ajax({
                        url: "php/register_user.php",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,

                        success: function(response) {

                            if (response.trim() == "success") {

                                Swal.fire({
                                    title: "Registered!",
                                    text: "You are registered successfully",
                                    icon: "success"
                                });
                            //reset form after submission
                                $("#register")[0].reset();

                            }
                        },
                        error : function(){
                                Swal.fire({
                                    title: "Error!",
                                    text: "An error occured",
                                    icon: "error"
                                });
                        }
                        
                    });

                }
            });
            
});
            
    </script>
</body>

</html>