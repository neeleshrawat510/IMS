<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Invoice Management System | Baseline IT Development Pvt Ltd</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Font -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        body{
            font-family:'Poppins',sans-serif;
            scroll-behavior:smooth;
            background:#f8fafc;
        }

        .navbar{
            background:#fff;
            box-shadow:0 5px 20px rgba(0,0,0,.08);
        }

        .navbar-brand{
            font-weight:700;
            color:#0d6efd!important;
        }

        .nav-link{
            font-weight:500;
        }

        .hero{
            background:linear-gradient(135deg,#0d6efd,#4f8dfd);
            color:#fff;
            padding:100px 0;
        }

        .hero h1{
            font-size:3rem;
            font-weight:700;
        }

        .hero p{
            font-size:1.1rem;
            opacity:.95;
        }

        .hero-card{
            background:#fff;
            color:#333;
            border-radius:20px;
            padding:30px;
            box-shadow:0 20px 40px rgba(0,0,0,.15);
        }

        .section-title{
            font-weight:700;
            margin-bottom:15px;
        }

        .section-padding{
            padding:90px 0;
        }

        .feature-badge{
            background:#e9f2ff;
            color:#0d6efd;
            display:inline-block;
            padding:8px 15px;
            border-radius:30px;
            font-weight:600;
            margin:5px;
        }

        .company-card{
            background:#fff;
            border:none;
            border-radius:20px;
            box-shadow:0 10px 30px rgba(0,0,0,.08);
            transition:.3s;
        }

        .company-card:hover{
            transform:translateY(-8px);
        }

        .login-btn{
            padding:12px 28px;
            font-weight:600;
        }

        .learn-btn{
            padding:12px 28px;
            border:2px solid #fff;
            color:#fff;
        }

        .learn-btn:hover{
            background:#fff;
            color:#0d6efd;
        }

        .stat-box{
            background:rgba(255,255,255,.15);
            border-radius:15px;
            padding:20px;
            text-align:center;
            backdrop-filter:blur(5px);
        }

        .stat-box h3{
            font-weight:700;
        }
    </style>

</head>

<body>

<!-- ================= NAVBAR ================= -->

<nav class="navbar navbar-expand-lg sticky-top">

    <div class="container">

        <a class="navbar-brand" href="#">
            <i class="bi bi-receipt-cutoff"></i>
            Invoice Management System
        </a>

        <button class="navbar-toggler" data-bs-toggle="collapse"
            data-bs-target="#menu">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="menu">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">
                    <a href="#home" class="nav-link">Home</a>
                </li>

                <li class="nav-item">
                    <a href="#about" class="nav-link">About</a>
                </li>

                <li class="nav-item">
                    <a href="#project" class="nav-link">Project</a>
                </li>

                <li class="nav-item">
                    <a href="#features" class="nav-link">Features</a>
                </li>

                <li class="nav-item ms-lg-3">

                    <a href="login.php" class="btn btn-primary px-4">

                        Login

                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>


<!-- ================= HERO ================= -->

<section class="hero" id="home">

    <div class="container">

        <div class="row align-items-center gy-5">

            <div class="col-lg-6">

                <span class="badge bg-light text-primary px-3 py-2 mb-3">
                    Modern Business Solution
                </span>

                <h1>
                    Smart Invoice Management
                    Made Simple
                </h1>

                <p class="mt-4">

                    A web-based Invoice Management System developed to
                    simplify invoice creation, customer management,
                    PDF generation, email delivery and payment tracking —
                    all from one secure platform.

                </p>

                <div class="mt-4">

                    <a href="login.php" class="btn btn-light login-btn me-3">

                        <i class="bi bi-box-arrow-in-right"></i>

                        Login to System

                    </a>

                    <a href="#about" class="btn learn-btn">

                        Learn More

                    </a>

                </div>

                <div class="row mt-5">

                    <div class="col-4">

                        <div class="stat-box">

                            <h3>100%</h3>

                            <small>Secure</small>

                        </div>

                    </div>

                    <div class="col-4">

                        <div class="stat-box">

                            <h3>Fast</h3>

                            <small>Workflow</small>

                        </div>

                    </div>

                    <div class="col-4">

                        <div class="stat-box">

                            <h3>24/7</h3>

                            <small>Access</small>

                        </div>

                    </div>

                </div>

            </div>

            <div class="col-lg-6">

                <div class="hero-card">

                    <h4 class="fw-bold mb-4">

                        <i class="bi bi-speedometer2 text-primary"></i>

                        Dashboard Overview

                    </h4>

                    <div class="mb-3">

                        <strong>Invoice</strong>

                        <div class="text-muted">
                            INV-10025
                        </div>

                    </div>

                    <div class="mb-3">

                        <strong>Customer</strong>

                        <div class="text-muted">
                            ABC Technologies
                        </div>

                    </div>

                    <div class="mb-3">

                        <strong>Amount</strong>

                        <div class="text-success fw-bold">
                            ₹18,500
                        </div>

                    </div>

                    <div class="mb-4">

                        <strong>Status</strong>

                        <br>

                        <span class="badge bg-success">

                            Paid

                        </span>

                    </div>

                    <hr>

                    <h6 class="fw-bold">

                        Recent Activity

                    </h6>

                    <ul class="list-group list-group-flush">

                        <li class="list-group-item">
                            ✔ Invoice Generated
                        </li>

                        <li class="list-group-item">
                            ✔ PDF Created
                        </li>

                        <li class="list-group-item">
                            ✔ Email Sent
                        </li>

                        <li class="list-group-item">
                            ✔ Payment Received
                        </li>

                    </ul>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- ================= ABOUT COMPANY ================= -->

<section class="section-padding" id="about">

    <div class="container">

        <div class="row align-items-center g-5">

            <div class="col-lg-6">

                <h2 class="section-title">

                    About Baseline IT Development Pvt Ltd

                </h2>

                <p class="text-muted">

                    Baseline IT Development Pvt Ltd develops reliable,
                    scalable and user-friendly software solutions that help
                    businesses automate everyday operations.

                </p>

                <p class="text-muted">

                    We focus on delivering secure, efficient and modern
                    applications that improve productivity while reducing
                    manual work through smart digital solutions.

                </p>

            </div>

            <div class="col-lg-6">

                <div class="card company-card">

                    <div class="card-body p-5">

                        <h4 class="mb-4">

                            Why Choose Us?

                        </h4>

                        <p>

                            ✔ Modern Web Technologies

                        </p>

                        <p>

                            ✔ Secure Software Solutions

                        </p>

                        <p>

                            ✔ Responsive User Interface

                        </p>

                        <p>

                            ✔ Business Automation

                        </p>

                        <p>

                            ✔ Continuous Innovation

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- ================= ABOUT PROJECT ================= -->

<section class="section-padding bg-white" id="project">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="section-title">

                About the Invoice Management System

            </h2>

            <p class="text-muted">

                A complete web-based solution for creating, managing,
                sending and tracking invoices professionally.

            </p>

        </div>

        <div class="row g-4">

            <div class="col-md-4">

                <div class="card company-card h-100">

                    <div class="card-body text-center">

                        <i class="bi bi-lightbulb fs-1 text-primary"></i>

                        <h5 class="mt-3">

                            Purpose

                        </h5>

                        <p class="text-muted">

                            Simplify invoice management while improving
                            accuracy, organization and payment tracking.

                        </p>

                    </div>

                </div>

            </div>

            <div class="col-md-4">

                <div class="card company-card h-100">

                    <div class="card-body text-center">

                        <i class="bi bi-shield-check fs-1 text-success"></i>

                        <h5 class="mt-3">

                            Security

                        </h5>

                        <p class="text-muted">

                            Secure authentication and reliable data handling
                            ensure safe access to business information.

                        </p>

                    </div>

                </div>

            </div>

            <div class="col-md-4">

                <div class="card company-card h-100">

                    <div class="card-body text-center">

                        <i class="bi bi-graph-up-arrow fs-1 text-warning"></i>

                        <h5 class="mt-3">

                            Productivity

                        </h5>

                        <p class="text-muted">

                            Reduce manual work and save valuable time through
                            automation and centralized management.

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- ================= PROBLEM SECTION ================= -->

<section class="section-padding bg-light" id="problem">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="section-title">
                Problems Faced by Businesses
            </h2>

            <p class="text-muted">
                Traditional invoice management often results in unnecessary delays,
                errors and inefficient record keeping.
            </p>

        </div>

        <div class="row g-4">

            <div class="col-md-6 col-lg-3">

                <div class="card company-card h-100 text-center">

                    <div class="card-body p-4">

                        <i class="bi bi-file-earmark-text text-danger fs-1"></i>

                        <h5 class="mt-3">Manual Invoices</h5>

                        <p class="text-muted">
                            Preparing invoices manually consumes valuable business time.
                        </p>

                    </div>

                </div>

            </div>

            <div class="col-md-6 col-lg-3">

                <div class="card company-card h-100 text-center">

                    <div class="card-body p-4">

                        <i class="bi bi-clock-history text-warning fs-1"></i>

                        <h5 class="mt-3">Payment Delays</h5>

                        <p class="text-muted">
                            Difficulty in tracking pending and completed payments.
                        </p>

                    </div>

                </div>

            </div>

            <div class="col-md-6 col-lg-3">

                <div class="card company-card h-100 text-center">

                    <div class="card-body p-4">

                        <i class="bi bi-folder-x text-primary fs-1"></i>

                        <h5 class="mt-3">Poor Record Keeping</h5>

                        <p class="text-muted">
                            Searching previous invoices becomes increasingly difficult.
                        </p>

                    </div>

                </div>

            </div>

            <div class="col-md-6 col-lg-3">

                <div class="card company-card h-100 text-center">

                    <div class="card-body p-4">

                        <i class="bi bi-exclamation-triangle text-danger fs-1"></i>

                        <h5 class="mt-3">Human Errors</h5>

                        <p class="text-muted">
                            Manual calculations often lead to incorrect invoice amounts.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- ================= SOLUTION ================= -->

<section class="section-padding">

    <div class="container">

        <div class="row align-items-center g-5">

            <div class="col-lg-6">

                <span class="badge bg-primary mb-3">
                    Smart Business Solution
                </span>

                <h2 class="section-title">
                    How Our System Solves These Problems
                </h2>

                <p class="text-muted mb-4">

                    The Invoice Management System centralizes customer,
                    invoice and payment management into one secure platform,
                    eliminating repetitive manual tasks.

                </p>

                <div class="feature-badge">
                    <i class="bi bi-check-circle-fill"></i>
                    Digital Invoice Creation
                </div>

                <div class="feature-badge">
                    <i class="bi bi-check-circle-fill"></i>
                    Automatic PDF Generation
                </div>

                <div class="feature-badge">
                    <i class="bi bi-check-circle-fill"></i>
                    Email Integration
                </div>

                <div class="feature-badge">
                    <i class="bi bi-check-circle-fill"></i>
                    Payment Tracking
                </div>

                <div class="feature-badge">
                    <i class="bi bi-check-circle-fill"></i>
                    Secure Authentication
                </div>

                <div class="feature-badge">
                    <i class="bi bi-check-circle-fill"></i>
                    Centralized Records
                </div>

            </div>

            <div class="col-lg-6">

                <div class="hero-card">

                    <h4 class="fw-bold mb-4">

                        Project Workflow

                    </h4>

                    <div class="d-flex align-items-center mb-3">

                        <i class="bi bi-person-plus-fill text-primary fs-3 me-3"></i>

                        Add Customer

                    </div>

                    <div class="d-flex align-items-center mb-3">

                        <i class="bi bi-box-seam text-success fs-3 me-3"></i>

                        Add Products

                    </div>

                    <div class="d-flex align-items-center mb-3">

                        <i class="bi bi-receipt text-warning fs-3 me-3"></i>

                        Generate Invoice

                    </div>

                    <div class="d-flex align-items-center mb-3">

                        <i class="bi bi-envelope-check text-info fs-3 me-3"></i>

                        Send Invoice

                    </div>

                    <div class="d-flex align-items-center">

                        <i class="bi bi-credit-card text-success fs-3 me-3"></i>

                        Receive Payment

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- ================= FEATURES ================= -->

<section class="section-padding bg-white" id="features">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="section-title">
                Core Features
            </h2>

            <p class="text-muted">
                Everything required to efficiently manage invoices from one place.
            </p>

        </div>

        <div class="row g-4">

            <div class="col-md-6 col-lg-3">
                <div class="card company-card text-center h-100">
                    <div class="card-body p-4">
                        <i class="bi bi-file-earmark-plus fs-1 text-primary"></i>
                        <h5 class="mt-3">Invoice Creation</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card company-card text-center h-100">
                    <div class="card-body p-4">
                        <i class="bi bi-people fs-1 text-success"></i>
                        <h5 class="mt-3">Contact Management</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card company-card text-center h-100">
                    <div class="card-body p-4">
                        <i class="bi bi-box-seam fs-1 text-warning"></i>
                        <h5 class="mt-3">Products</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card company-card text-center h-100">
                    <div class="card-body p-4">
                        <i class="bi bi-filetype-pdf fs-1 text-danger"></i>
                        <h5 class="mt-3">PDF Generation</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card company-card text-center h-100">
                    <div class="card-body p-4">
                        <i class="bi bi-envelope-paper fs-1 text-info"></i>
                        <h5 class="mt-3">Email Integration</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card company-card text-center h-100">
                    <div class="card-body p-4">
                        <i class="bi bi-credit-card-2-front fs-1 text-success"></i>
                        <h5 class="mt-3">Payment Tracking</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card company-card text-center h-100">
                    <div class="card-body p-4">
                        <i class="bi bi-shield-lock fs-1 text-primary"></i>
                        <h5 class="mt-3">Secure Login</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card company-card text-center h-100">
                    <div class="card-body p-4">
                        <i class="bi bi-bar-chart-line fs-1 text-dark"></i>
                        <h5 class="mt-3">Dashboard</h5>
                    </div>
                </div>
            </div>

        </div>

    </div>

</section>

<!-- ================= BENEFITS ================= -->

<section class="section-padding bg-light" id="benefits">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="section-title">
                Benefits of the System
            </h2>

            <p class="text-muted">
                Designed to improve productivity while making invoice management
                faster, smarter and more reliable.
            </p>

        </div>

        <div class="row g-4">

            <div class="col-md-6">

                <div class="card company-card h-100">

                    <div class="card-body p-4">

                        <h5>
                            <i class="bi bi-clock-history text-primary me-2"></i>
                            Saves Time
                        </h5>

                        <p class="text-muted mb-0">
                            Create professional invoices within seconds instead of
                            manually preparing documents every time.
                        </p>

                    </div>

                </div>

            </div>

            <div class="col-md-6">

                <div class="card company-card h-100">

                    <div class="card-body p-4">

                        <h5>
                            <i class="bi bi-check2-circle text-success me-2"></i>
                            Reduces Errors
                        </h5>

                        <p class="text-muted mb-0">
                            Automatic calculations minimize mistakes and improve
                            invoice accuracy.
                        </p>

                    </div>

                </div>

            </div>

            <div class="col-md-6">

                <div class="card company-card h-100">

                    <div class="card-body p-4">

                        <h5>
                            <i class="bi bi-folder2-open text-warning me-2"></i>
                            Better Organization
                        </h5>

                        <p class="text-muted mb-0">
                            Store invoices, customers and products in one centralized
                            and searchable system.
                        </p>

                    </div>

                </div>

            </div>

            <div class="col-md-6">

                <div class="card company-card h-100">

                    <div class="card-body p-4">

                        <h5>
                            <i class="bi bi-shield-lock text-danger me-2"></i>
                            Secure Access
                        </h5>

                        <p class="text-muted mb-0">
                            Authentication and secure data handling help protect
                            business information.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- ================= TECHNOLOGIES ================= -->

<section class="section-padding bg-white" id="technology">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="section-title">
                Technologies Used
            </h2>

            <p class="text-muted">
                Built using modern web technologies.
            </p>

        </div>

        <div class="row g-4 justify-content-center">

            <div class="col-6 col-md-4 col-lg-2">
                <div class="card company-card text-center">
                    <div class="card-body py-4">
                        <i class="bi bi-filetype-html text-danger fs-1"></i>
                        <h6 class="mt-3">HTML5</h6>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2">
                <div class="card company-card text-center">
                    <div class="card-body py-4">
                        <i class="bi bi-palette text-primary fs-1"></i>
                        <h6 class="mt-3">CSS3</h6>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2">
                <div class="card company-card text-center">
                    <div class="card-body py-4">
                        <i class="bi bi-bootstrap-fill text-purple fs-1"></i>
                        <h6 class="mt-3">Bootstrap 5</h6>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2">
                <div class="card company-card text-center">
                    <div class="card-body py-4">
                        <i class="bi bi-filetype-js text-warning fs-1"></i>
                        <h6 class="mt-3">JavaScript</h6>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2">
                <div class="card company-card text-center">
                    <div class="card-body py-4">
                        <i class="bi bi-code-square text-info fs-1"></i>
                        <h6 class="mt-3">jQuery</h6>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2">
                <div class="card company-card text-center">
                    <div class="card-body py-4">
                        <i class="bi bi-server text-success fs-1"></i>
                        <h6 class="mt-3">PHP & MySQL</h6>
                    </div>
                </div>
            </div>

        </div>

    </div>

</section>



<!-- ================= CALL TO ACTION ================= -->

<section class="py-5 text-white"
    style="background:linear-gradient(135deg,#0d6efd,#2563eb);">

    <div class="container text-center">

        <h2 class="fw-bold">
            Ready to Explore the Project?
        </h2>

        <p class="lead mt-3 mb-4">

            Login and experience a smarter way to manage invoices,
            customers and payments.

        </p>

        <a href="login.php" class="btn btn-light btn-lg px-5">

            <i class="bi bi-box-arrow-in-right"></i>

            Login to System

        </a>

    </div>

</section>



<!-- ================= FOOTER ================= -->

<footer class="bg-dark text-light pt-5 pb-3">

    <div class="container">

        <div class="row">

            <div class="col-lg-5">

                <h4 class="fw-bold">

                    <i class="bi bi-receipt-cutoff"></i>

                    Invoice Management System

                </h4>

                <p class="text-light-emphasis mt-3">

                    A modern web-based application developed to simplify
                    invoice creation, payment tracking and customer
                    management.

                </p>

            </div>

            <div class="col-lg-4">

                <h5>Quick Links</h5>

                <ul class="list-unstyled mt-3">

                    <li class="mb-2">
                        <a href="#home" class="text-decoration-none text-light-emphasis">Home</a>
                    </li>

                    <li class="mb-2">
                        <a href="#about" class="text-decoration-none text-light-emphasis">About</a>
                    </li>

                    <li class="mb-2">
                        <a href="#features" class="text-decoration-none text-light-emphasis">Features</a>
                    </li>

                    <li>
                        <a href="login.php" class="text-decoration-none text-light-emphasis">Login</a>
                    </li>

                </ul>

            </div>

            <div class="col-lg-3">

                <h5>Developed By</h5>

                <p class="text-light-emphasis mt-3">

                    <strong>Baseline IT Development Pvt Ltd</strong>

                </p>

                <p class="text-light-emphasis">

                    Building secure and modern business software solutions.

                </p>

            </div>

        </div>

        <hr class="border-secondary">

        <div class="text-center text-light-emphasis">

            © 2026 Baseline IT Development Pvt Ltd.
            All Rights Reserved.

        </div>

    </div>

</footer>



<!-- Bootstrap JS -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<!-- jQuery -->

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>

$(function(){

    $('a[href^="#"]').on('click', function(e){

        e.preventDefault();

        $('html, body').animate({

            scrollTop: $($(this).attr('href')).offset().top - 70

        },600);

    });

});

</script>

</body>
</html>