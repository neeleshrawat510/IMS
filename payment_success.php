<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #eef5ff, #f8fbff);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: "Segoe UI", sans-serif;
        }

        .payment-card {
            max-width: 520px;
            width: 100%;
            border: none;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.08);
        }

        .success-icon {
            width: 90px;
            height: 90px;
            background: #d1fae5;
            color: #16a34a;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 42px;
            margin: 0 auto 20px;
        }

        .info-box {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 18px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .label {
            color: #6c757d;
            font-weight: 600;
        }

        .value {
            font-weight: 700;
            color: #212529;
        }

        .status-badge {
            background: #198754;
            color: #fff;
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 600;
        }

        .company {
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <div class="card payment-card">

        <div class="card-body p-5 text-center">

            <div class="success-icon">
                <i class="bi bi-check-lg"></i>
            </div>

            <h2 class="fw-bold text-success mb-2">
                Payment Successful
            </h2>

            <p class="text-muted mb-4">
                Your payment has been received successfully.
                Thank you for choosing our services.
            </p>

            <div class="info-box text-start">

                <div class="info-row">
                    <span class="label">Invoice Number</span>
                    <span class="value">
                        <?php echo htmlspecialchars($invoice['invoice_no']); ?>
                    </span>
                </div>

                <div class="info-row">
                    <span class="label">Amount Paid</span>
                    <span class="value">
                        ₹<?php echo number_format($invoice['grand_total'], 2); ?>
                    </span>
                </div>

                <div class="info-row">
                    <span class="label">Payment Status</span>
                    <span class="status-badge">
                        <?php echo ucfirst($invoice['payment_status']); ?>
                    </span>
                </div>

            </div>

            <div class="alert alert-success mt-4 mb-4">
                <i class="bi bi-shield-check me-2"></i>
                Your transaction has been securely processed and your invoice has been updated.
            </div>

            <a href="index.php" class="btn btn-primary px-4">
                <i class="bi bi-house-door me-2"></i>
                Back to Home
            </a>

            <hr class="my-4">

            <div class="company">
                Powered by <strong>Baseline IT Development Pvt. Ltd.</strong>
            </div>

        </div>

    </div>

</body>

</html>