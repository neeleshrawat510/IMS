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
    <title>Dashboard | Invoice Management System</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>
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
                    <div class="page-heading">
                        <div class="page-heading-copy">
                            <span class="page-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
                            <div>
                                <p class="eyebrow mb-1">Overview</p>
                                <h1 class="h3 mb-1">Dashboard</h1>
                                <p class="text-muted mb-0">Monitor performance, sales, users, and support from one clean workspace.</p>
                            </div>
                        </div>
                        <!-- <div class="heading-actions"><button class="btn btn-outline-secondary btn-sm" type="button"><i
                                    class="bi bi-download" aria-hidden="true"></i> Export</button><button class="btn btn-primary btn-sm"
                                type="button"><i class="bi bi-file-earmark-plus" aria-hidden="true"></i> Create Report</button></div> -->
                    </div>

                    <!-- card martics -->
                    <section class="row g-3 mt-1" aria-label="Dashboard metrics">
                        <div class="col-12 col-sm-6">
                            <article class="metric-card metric-primary">
                                <div class="metric-top">
                                    <span class="metric-label">Revenue</span>
                                    <span class="metric-icon"><i class="bi bi-currency-rupee" aria-hidden="true"></i></span>
                                </div>
                                <div class="metric-value" id="totalRevenue"></div>

                            </article>
                        </div>

                        <div class="col-12 col-sm-6">
                            <article class="metric-card metric-success">
                                <div class="metric-top">
                                    <span class="metric-label">Total Invoices</span>
                                    <span class="metric-icon"><i class="bi bi-receipt" aria-hidden="true"></i></span>
                                </div>
                                <div class="metric-value" id="totalInvoices"></div>

                            </article>
                        </div>

                        <div class="col-12 col-sm-4">
                            <article class="metric-card metric-success">
                                <div class="metric-top">
                                    <span class="metric-label">Paid</span>
                                    <span class="metric-icon"><i class="bi bi-check-circle-fill" aria-hidden="true"></i></span>
                                </div>
                                <div class="metric-value" id="paidInvoices"></div>

                            </article>
                        </div>

                        <div class="col-12 col-sm-4">
                            <article class="metric-card metric-warning">
                                <div class="metric-top">
                                    <span class="metric-label">Unpaid</span>
                                    <span class="metric-icon"><i class="bi bi-hourglass-split" aria-hidden="true"></i></span>
                                </div>
                                <div class="metric-value" id="unpaidInvoices"></div>

                            </article>
                        </div>

                        <div class="col-12 col-sm-4">
                            <article class="metric-card metric-danger">
                                <div class="metric-top">
                                    <span class="metric-label">OverDue</span>
                                    <span class="metric-icon"><i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i></span>
                                </div>
                                <div class="metric-value" id="overdueInvoices"></div>

                            </article>
                        </div>
                    </section>

                    <!-- MONTHLY REVENUE CHARTS -->
                    <section class="row g-3 mt-1">
                        <div class="col-12 col-xl-8">
                            <div class="panel h-100">
                                <div class="panel-header">
                                    <div>
                                        <h2 class="h5 mb-1 section-title"><i class="bi bi-bar-chart-line" aria-hidden="true"></i><span>Revenue Trend</span></h2>
                                        <p class="text-muted mb-0">Monthly Revenue chart</p>
                                    </div>
                                </div>
                                <!-- bar graph -->
                                <canvas class="chart-bars" aria-label="Revenue chart" id="revenueChart">
                                </canvas>
                            </div>
                        </div>
                        <div class="col-12 col-xl-4">
                            <div class="panel h-100">
                                <div class="panel-header">
                                    <div>
                                        <h2 class="h5 mb-1 section-title"><i class="bi bi-pie-chart" aria-hidden="true"></i><span>Invoice Chart</span></h2>
                                        <p class="text-muted mb-0"></p>
                                    </div>
                                </div>
                                <!-- pie chart -->
                                <canvas id="invoiceChart"></canvas>
                            </div>
                        </div>
                    </section>

                    <section class="panel mt-3">
                        <div class="panel-header">
                            <div>
                                <h2 class="h5 mb-1 section-title"><i class="bi bi-receipt" aria-hidden="true"></i><span>Recent
                                        Invoices</span></h2>

                            </div>
                            <a class="btn btn-outline-secondary btn-sm" href="manage_invoice.php">All Invoices</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover nowrap w-100" id="invoiceTable" style="font-size: small;">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Invoice_no</th>
                                        <th>Name</th>
                                        <th>Invoice Date</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </main>


        </div>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>

    <!-- BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- CHART -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <!-- LOGOUT Redirect -->
    <script src="controller/logout.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {

            function loadDashboard() {

                $.ajax({
                    url: "php/dashboard_data.php",
                    type: "GET",
                    dataType: "json",
                    success: function(res) {

                        //cards metrics
                        $("#totalInvoices").text(res.metrics.invoices);
                        $("#paidInvoices").text(res.metrics.paidInvoices);
                        $("#unpaidInvoices").text(res.metrics.unpaidInvoices);
                        $("#overdueInvoices").text(res.metrics.overdueInvoices);
                        $("#totalRevenue").text(res.metrics.totalRevenue);

                        renderCharts(res.charts);
                    }
                });
            }
            // BAR GRAPH
            function renderCharts(charts) {
                new Chart($("#revenueChart")[0], {
                    type: "bar",
                    data: {
                        labels: charts.months,
                        datasets: [{
                            label: "Revenue",
                            data: charts.revenue,
                            backgroundColor: "#4e73df"
                        }]
                    }
                });
                //PIE CHART
                new Chart($("#invoiceChart")[0], {
                    type: "pie",
                    data: {
                        labels: ['Paid', 'Unpaid', 'Overdue'],
                        datasets: [{
                            data: [charts.status.Paid,
                                charts.status.Unpaid,
                                charts.status.Overdue
                            ],
                            backgroundColor: ["#1cc88a", "#f6c23e", "#e74a3b"]
                        }]
                    },
                });
            }


            loadDashboard();


        });
    </script>
    <script>
        $('#invoiceTable').DataTable({
            ajax: {
                url: "php/manage_all_invoices.php",
                dataSrc: ""
            },
            columns: [{
                    data: 1
                }, //serial no
                {
                    data: 2
                }, //Invoice Number
                {
                    data: 3
                }, //"Name"
                {
                    data: 4
                }, //Invoice date
                {
                    data: 5
                }, //Total Amount(grand total)
                {
                    data: 6
                }, //status

            ]
        });
    </script>
</body>

</html>