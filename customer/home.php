<?php
require_once __DIR__ . '/../config/config.php';

// Require customer session
if (!isset($_SESSION['customer_id'])) {
    redirect('auth/customer_login.php');
}

$customer_name = $_SESSION['customer_name'] ?? 'Customer';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Portal - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Customer Portal</a>
            <div class="d-flex">
                <a class="btn btn-outline-light" href="<?php echo url('auth/customer_logout.php'); ?>">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <div class="alert alert-success">
                    Welcome, <?php echo htmlspecialchars($customer_name); ?>!
                </div>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Account</h5>
                        <p class="card-text">Account Number: <?php echo htmlspecialchars($_SESSION['customer_account_number'] ?? ''); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Overview</h5>
                        <p class="text-muted mb-0">This is a placeholder customer dashboard. We can add bills and payments here later.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


