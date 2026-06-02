<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("location: index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Products</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

</head>

<body class="bg-light">

    <!-- HEADER NAVBAR -->
    <?php   include("includes/header.php");  ?>

    <!-- MAIN DASHBOARD -->
    <div class="container py-4">

        
        <!-- PRODUCT TABLE -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold">
                Manage Products
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product Code</th>
                                <th>Product Name</th>
                                <th>Cost Price</th>
                                <th>Selling price</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody id="tableData">
                            
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- LOGOUT Redirect -->
     <script src="controller/logout.js"></script>
    
    <script>
        $(document).ready(function(){
            loadData();
            function loadData(){
            $.ajax({
                url: "php/manage_all_products.php",
                type: "GET",
                success: function(response){
                    $("#tableData").html(response);
                },
                error: function(){
                    $("#tableData").html("Something went wrong");
                }
            });

            }
            
        });
    </script>

</body>

</html>