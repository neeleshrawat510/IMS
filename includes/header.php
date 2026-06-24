<!-- HEADER NAV BAR -->
<nav class="navbar admin-navbar navbar-expand bg-white">
    <div class="container-fluid px-3 px-lg-4">

        <div class="collapse navbar-collapse" id="navMenu">
            <button class="sidebar-toggle" type="button" data-sidebar-toggle aria-controls="adminSidebar"
                aria-expanded="true" aria-label="Toggle sidebar">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <!-- left section -->
            <div class="flex-grow-1"></div>

            <!-- Username -->
            <div class="flex-grow-1 text-center">
                <span id="userName" class="fw-bold"></span>
            </div>

            <!-- Right Buttons -->
            <div class="flex-grow-1 d-flex justify-content-end">
                <a class="btn btn-primary me-2" href="add_invoice.php">
                    Create Invoice
                </a>

                <button class="btn btn-danger" id="logoutBtn">
                    Logout
                </button>
            </div>

        </div>

    </div>
</nav>


<script>
    $(document).ready(function() {
        $.ajax({
            url: "php/dashboard_user.php",
            type: "GET",
            dataType: "json",

            success: function(data) {
                $("#userName").html("User : " + data.name);
            },

            error: function() {
                $("#userName").text("User Not Found");
            }
        });
    });
</script>