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
    <title>Update Product</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        #updateForm {
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

        <form id="updateForm">
            <h3>Update Product</h3>
            <input type="hidden" name="id" id="productId">
            <div class="form-group">
                <label for="productCode">Product Code</label>
                <input type="text" id="productCode" name="product_code" placeholder="">
            </div>

            <div class="form-group">
                <label for="productName">Product Name</label>
                <input type="text" id="productName" name="product_name" placeholder="">
            </div>

            <div class="form-group">
                <label for="costPrice">Cost Price ($)</label>
                <input type="text" id="costPrice" name="cost_price" placeholder="">
            </div>

            <div class="form-group">
                <label for="sellingPrice">Selling Price ($)</label>
                <input type="text" id="sellingPrice" name="selling_price" placeholder="">
            </div>

            <button type="submit" id="submit">Update Product</button>
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
            //get id from url
            let productId = new URLSearchParams(window.location.search).get('id');
            $.ajax({
                url: "controller/fetch_products.php",
                type: "GET",
                dataType: "json",
                data: {
                    id: productId
                },
                success: function(data) {
                    $("#productId").val(data.id);
                    $("#productCode").val(data.product_code);
                    $("#productName").val(data.product_name);
                    $("#costPrice").val(data.cost_price);
                    $("#sellingPrice").val(data.selling_price);
                }

            });
            $("#updateForm").validate({
                rules: {
                    product_code: {
                        required: true,
                        remote: {
                            url: "controller/check_product.php",
                            type: "POST",
                            data: {
                                id: function(){
                                    return $("#productId").val();
                                }
                            }
                        }
                    },
                    product_name: {
                        required: true
                    },
                    cost_price: {
                        required: true,
                        number: true
                    },
                    selling_price: {
                        required: true,
                        number: true
                    }
                },
                messages: {
                    product_code: {
                        required: "This can't be empty",
                        remote: "Not available! try different"
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
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, update it!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let formData = new FormData(form);

                            $.ajax({
                                url: "php/edit_all_product.php",
                                type: "POST",
                                data: formData,
                                contentType: false,
                                processData: false,

                                success: function(response) {
                                    if (response.trim() == 'success') {
                                        Swal.fire({
                                            title: "Successful",
                                            text: "Product Updated",
                                            icon: "success"
                                        });
                                    }
                                    window.location.href = "manage_product.php"
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

                }
            });
        });
    </script>

</body>

</html>