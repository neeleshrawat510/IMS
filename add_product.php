<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        #productForm {
            width: 400px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-family: Arial, sans-serif;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 8px;
            border: 1px solid #aaa;
            border-radius: 4px;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
        label.error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }

        input.error {
            border: 1px solid red;
        }
    </style>
</head>

<body class="bg-light">

    <!-- HEADER NAVBAR -->
    <?php include("includes/header.php");  ?>

    <!-- MAIN DASHBOARD -->
    <div class="container py-4">

        <form id="productForm">
            <h3>Add Product</h3>
            <div class="form-group">
                <label for="product_code">Product Code</label>
                <input type="text" id="product_code" name="product_code" placeholder="">
            </div>

            <div class="form-group">
                <label for="product_name">Product Name</label>
                <input type="text" id="product_name" name="product_name" placeholder="">
            </div>

            <div class="form-group">
                <label for="cost_price">Cost Price ($)</label>
                <input type="text" id="cost_price" name="cost_price" placeholder="">
            </div>

            <div class="form-group">
                <label for="selling_price">Selling Price($)</label>
                <input type="text" id="selling_price" name="selling_price" placeholder="">
            </div>

            <button type="submit" id="submit">Add Product</button>
        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

   <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- jQuery Validation Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

    <!-- Sweet alert cdn -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- LOGOUT Redirect -->
    <script src="controller/logout.js"></script>

    <script>
        $(document).ready(function() {
           
        //validation on Product Form
            $("#productForm").validate({
                rules: {
                    product_code: {
                        required: true,
                        remote: {
                            url: "controller/check_product.php",
                            type: "POST"
                        }
                    },
                    product_name: {
                        required: true
                    },
                    cost_price: {
                        required: true,
                        number : true
                    },
                    selling_price: {
                        required: true,
                        number: true
                    }
                },
                messages: {
                    product_code: {
                        required: "This can't be empty",
                        remote: "Not available! Try different"
                    },
                    product_name: {
                        required: "This can't be empty"
                    },
                    cost_price: {
                        required: "This can't be empty",
                        number: "Only numbers allowed"
                    },
                    selling_price: {
                        required: "This can't be empty",
                        number: "Only numbers allowed"
                    }
                },

                submitHandler: function(form) {
                     
                    let formData = new FormData(form);
                    $.ajax({
                        url: "php/add_new_product.php",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        

                        success: function(response) {
                            if (response.trim() == 'success') {
                                Swal.fire({
                                    title: "Successful",
                                    text: "New Product added",
                                    icon: "success"

                                });
                            }
                             $("#productForm")[0].reset();
                        },
                        error: function() {
                            Swal.fire({
                                title: "error",
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