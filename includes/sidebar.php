//SIDEBAR

<aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
  <div class="sidebar-header">
    <a class="brand-mark" href="dashboard.php" aria-label="dashboard">
      <span class="brand-icon"><span class="brand-title">IMS</span></span>
      <span class="brand-copy">
        <span class="brand-title">InvoiceSys</span>
        <span class="brand-subtitle">
            <?= htmlspecialchars($_SESSION['user_name']) ?>
        </span>
    </a>
  </div>

  <nav class="sidebar-nav">
    <a class="nav-link" href="dashboard.php">
      <span class="nav-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
      <span class="nav-text">Dashboard</span>
    </a>

    //ADMIN DASHBOARD
    <?php if ($_SESSION['role'] == "Admin") { ?>

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

      // USER DASHBOARD

    <?php } else { ?>

      //add contact
      <a class="nav-link" href="add_contact.php">
        <span class="nav-icon"><i class="bi bi-person-badge" aria-hidden="true"></i></span>
        <span class="nav-text">Add Contact</span>
      </a>

      //add product
      <a class="nav-link" href="add_product.php">
        <span class="nav-icon"><i class="bi bi-plus-circle" aria-hidden="true"></i></span>
        <span class="nav-text">Add Product</span>
      </a>

      //add invoice
      <a class="nav-link" href="add_invoice.php">
        <span class="nav-icon"><i class="bi bi-plus-square" aria-hidden="true"></i></span>
        <span class="nav-text">Add Invoice</span>
      </a>

    <?php } ?>


  </nav>
</aside>
