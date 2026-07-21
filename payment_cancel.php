<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Cancelled</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #fff8e6, #fffdf7);
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

        .cancel-icon {
            width: 90px;
            height: 90px;
            background: #fff3cd;
            color: #f59e0b;
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
            text-align: left;
        }

        .info-box p {
            margin-bottom: 12px;
        }

        .info-box p:last-child {
            margin-bottom: 0;
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

            <div class="cancel-icon">
                <i class="bi bi-x-lg"></i>
            </div>

            <h2 class="fw-bold text-warning mb-2">
                Payment Cancelled
            </h2>

            <p class="text-muted mb-4">
                Your payment was cancelled or was not completed.
            </p>

            <div class="info-box">

                <p>
                    <i class="bi bi-info-circle text-primary me-2"></i>
                    <strong>No amount has been deducted</strong> from your account.
                </p>

                <p>
                    <i class="bi bi-arrow-repeat text-success me-2"></i>
                    You can use the <strong>same payment link</strong> to complete your payment later.
                </p>

                <p>
                    <i class="bi bi-shield-check text-secondary me-2"></i>
                    Your invoice remains unchanged until a successful payment is received.
                </p>

            </div>

            <div class="alert alert-warning mt-4 mb-4">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                If you experienced a technical issue during payment, please try again or contact support.
            </div>

            <!-- <a href="index.php" class="btn btn-outline-primary px-4">
                <i class="bi bi-house-door me-2"></i>
                Back to Home
            </a> -->

            <hr class="my-4">

            <div class="company">
                Powered by <strong>Baseline IT Development Pvt. Ltd.</strong>
            </div>

        </div>

    </div>

</body>

</html>