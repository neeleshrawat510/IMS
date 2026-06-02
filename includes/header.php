<!-- HEADER NAV BAR -->

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container-fluid">

            <a class="navbar-brand fw-bold" href="dashboard.php">InvoiceSys</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMenu">

                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <!-- Product Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown">
                            Product
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="add_product.php">Add Product</a></li>
                            <li><a class="dropdown-item" href="manage_product.php">View Products</a></li>
                        </ul>
                    </li>

                    <!-- Invoice Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Invoice
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="add_invoice.php">Create Invoice</a></li>
                            <li><a class="dropdown-item" href="manage_invoice.php">All Invoices</a></li>
                        </ul>
                    </li>

                    <!-- Contact Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Contact
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="add_contact.php">Create New Contact</a></li>
                            <li><a class="dropdown-item" href="manage_contact.php">Manage Contacts</a></li>
                        </ul>
                    </li>

                </ul>

                <div class="text-light me-5" id="userName"></div>

                <div class="d-flex">
                    <a class="btn btn-outline-light me-2" href="add_invoice.php">New Invoice</a>
                    <button class="btn btn-outline-light btn-danger ms-2" id="logoutBtn">Logout</button>

                </div>

            </div>
        </div>
    </nav>


    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


    <script>
        $(document).ready(function() {
            $.ajax({
                url: "php/dashboard_user.php",
                type: "GET",
                dataType: "json",

                success: function(data) {
                    $("#userName").html("<b>User : <b>" + " " + data.name);
                },
                error: function(){
                    $("#userName").text("User Not Found");
                }
            });
        });
    </script>