<aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
      <div class="sidebar-header">
        <a class="brand-mark" href="dashboard.php" aria-label="dashboard">
          <span class="brand-icon"><span class="brand-title">IMS</span></span>
          <span class="brand-copy">
            <span class="brand-title">InvoiceSys</span>
            <span class="brand-subtitle" id="userName"></span>
          </span>
        </a>
      </div>

      <nav class="sidebar-nav">
        <a class="nav-link" href="dashboard.php">
          <span class="nav-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
          <span class="nav-text">Dashboard</span>
        </a>
        
        <!-- CONTACTS / CLIENTS -->
        <a class="nav-link" href="add_contact.php">
          <span class="nav-icon"><i class="bi bi-person-badge" aria-hidden="true"></i></span>
          <span class="nav-text">Add Contact</span>
        </a>
        <a class="nav-link" href="manage_contact.php">
          <span class="nav-icon"><i class="bi bi-ui-checks-grid" aria-hidden="true"></i></span>
          <span class="nav-text">Manage Contacts</span>
        </a>


        <!-- PRODUCTS -->
         <a class="nav-link" href="add_product.php">
          <span class="nav-icon"><i class="bi bi-plus-circle" aria-hidden="true"></i></span>
          <span class="nav-text">Add Product</span>
        </a>
        <a class="nav-link" href="manage_product.php">
          <span class="nav-icon"><i class="bi bi-pencil-square" aria-hidden="true"></i></span>
          <span class="nav-text">Manage Products</span>
        </a>


        <!-- INVOICE -->

        <a class="nav-link" href="add_invoice.php">
          <span class="nav-icon"><i class="bi bi-plus-square" aria-hidden="true"></i></span>
          <span class="nav-text">Add Invoice</span>
        </a>
        <a class="nav-link" href="manage_invoice.php">
          <span class="nav-icon"><i class="bi bi-eye" aria-hidden="true"></i></span>
          <span class="nav-text">Manage Invoice</span>
        </a>


        <!-- USERS -->
      <a class="nav-link" href="setting.php">
          <span class="nav-icon"><i class="bi bi-gear" aria-hidden="true"></i></span>
          <span class="nav-text">Settings</span>
        </a>

      </nav>
    </aside>

    <script>
   
        $(document).ready(function() {

        //highlight current tab:
        let currentPage = window.location.pathname.split("/").pop();

    $(".nav-link").each(function () {
        let linkPage = $(this).attr("href");

        if (linkPage === currentPage) {
            $(this).addClass("active");
        }
    });

        //GET USERNAME on sidebar
        $.ajax({
            url: "php/dashboard_user.php",
            type: "GET",
            dataType: "json",

            success: function(data) {
                $("#userName").html(data.name);
            },

            error: function() {
                $("#userName").text("User Not Found");
            }
        });
    });

</script>

