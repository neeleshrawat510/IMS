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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Invoice management System">
    <title>Add Product | Invoice Management System</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
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

    <div class="admin-shell">
        <div class="sidebar-backdrop" data-sidebar-close></div>

        <!-- INCLUDE SIDEBAR -->
        <?php include("includes/sidebar.php"); ?>

        <div class="admin-main">

            <!-- INCLUDE HEADER -->
            <?php include("includes/header.php"); ?>

            <!-- MAIN CONTENT -->
            <main class="dashboard-content">
                <div class="container-fluid px-3 px-lg-4 py-4">
                    <form class="panel needs-validation" id="updateForm">
                        <div class="panel-header">
                            <input type="hidden" name="id" id="productId">
                            <div>
                                <h2 class="h5 mb-1 section-title">
                                    <i class="bi bi-ui-checks-grid" aria-hidden="true"></i>
                                    <span>Product</span>
                                </h2>
                                <p class="text-muted mb-0">Add new product</p>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="product_code">Product Code</label>
                                <input type="text" class="" name="product_code" id="productCode" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="product_name">Product Name</label>
                                <input type="text" class="" name="product_name" id="productName" />
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="cost_price">Cost Price</label>
                                <input type="text" class="" name="cost_price" id="costPrice" />
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="selling_price">Selling Price</label>
                                <input type="text" class="" name="selling_price" id="sellingPrice" />
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="tax">Tax %</label>
                                <select class="form-select" name="tax" id="tax">
                                    <option selected disabled>Choose</option>
                                    <option value="0">0%</option>
                                    <option value="5">5%</option>
                                    <option value="18">18%</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-send" aria-hidden="true"></i> Edit Product</button>
                            </div>
                        </div>
                    </form>
                </div>
        </div>
        </main>
    </div>


    <script src="assets/js/main.js"></script>


    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!--  jQuery validation plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- LOGOUT scripts -->
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
                    $("#tax").val(data.tax);
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
                                id: function() {
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
                    },
                    tax: {
                        required: true
                    }
                },
                messages: {
                    product_code: {
                        required: "This Field can't be empty",
                        remote: "Not available! try different"
                    },
                    product_name: {
                        required: "This Field can't be empty"
                    },
                    cost_price: {
                        required: "This Field can't be empty",
                        number: "Only numbers allowed"
                    },
                    selling_price: {
                        required: "This Field can't be empty",
                        number: "Only numbers allowed"
                    },
                    tax: {
                        required: "This Field can't be empty"
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