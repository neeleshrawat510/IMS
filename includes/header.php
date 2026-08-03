<head>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/js/bootstrap.bundle.min.js"></script>
</head>
<!-- HEADER NAVBAR -->
<nav class="navbar admin-navbar navbar-expand bg-white">
    <div class="container-fluid px-3 px-lg-4">

        <!-- Sidebar Toggle -->
        <button class="sidebar-toggle me-3" type="button" data-sidebar-toggle aria-controls="adminSidebar"
            aria-expanded="true">

            <span></span>
            <span></span>
            <span></span>

        </button>

        <!-- Page Title -->
        <div>
            <h5 class="fw-bold mb-0">
                Invoice Management System
            </h5>
        </div>

        <!-- Right Side -->
        <div class="ms-auto navbar-actions">

            <a href="add_invoice.php" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i>
                Create Invoice
            </a>

            <!-- Future Notifications -->
            <!-- <button class="btn icon-button position-relative">
                <i class="bi bi-bell"></i>

                <span class="notification-dot"></span>
            </button> -->

            <!-- Profile -->
            <div class="dropdown">

                <button class="btn profile-button" data-bs-toggle="dropdown">

                    <div class="profile-avatar-wrapper">

                        <img id="userImage" class="avatar-sm rounded-circle d-none" src="" alt="Profile">

                        <div id="userAvatar" class="profile-avatar">
                            U
                        </div>

                    </div>

                    <span class="d-none d-lg-inline" id="userName">
                        User
                    </span>

                    <i class="bi bi-chevron-down"></i>

                </button>

                <ul class="dropdown-menu dropdown-menu-end">

                    <li class="px-3 py-2">
                        <strong id="dropdownUserName">User</strong><br>
                        <small class="text-muted" id="userEmail">
                        </small>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a href="user_profile.php" class="dropdown-item">
                            <i class="bi bi-person"></i>
                            My Profile
                        </a>
                    </li>

                    <li>
                        <a href="change_user_password.php" class="dropdown-item">
                            <i class="bi bi-key"></i>
                            Change Password
                        </a>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <button class="dropdown-item text-danger" id="logoutBtn">
                            <i class="bi bi-box-arrow-right"></i>
                            Logout
                        </button>
                    </li>

                </ul>

            </div>

        </div>

    </div>
</nav>

<script>
    function loadHeaderUser() {

        $.ajax({
            url: "php/dashboard_user.php",
            dataType: "json",

            success: function (data) {

                $("#userName").text(data.name);
                $("#dropdownUserName").text(data.name);
                $("#userEmail").text(data.email);

                // If profile photo exists
                if (data.profile_photo && data.profile_photo.trim() !== "") {

                    $("#userImage")
                        .attr("src", data.profile_photo)
                        .removeClass("d-none");

                    $("#userAvatar").addClass("d-none");

                } else {

                    $("#userAvatar")
                        .text(data.name.charAt(0).toUpperCase())
                        .removeClass("d-none");

                    $("#userImage").addClass("d-none");
                }

            }
        });
    }
    $(function () {
        loadHeaderUser();
    });
</script>