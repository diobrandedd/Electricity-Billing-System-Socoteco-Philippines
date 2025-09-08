<?php
require_once __DIR__ . '/../config/config.php';

// If already logged in as any user, redirect appropriately
if (isset($_SESSION['customer_id']) && !empty($_SESSION['customer_id'])) {
    redirect('customer/home.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $account_number = trim($_POST['account_number'] ?? '');
    $contact_number = trim($_POST['contact_number'] ?? '');
    $first_name = trim($_POST['first_name'] ?? '');

    if ($account_number === '' || $contact_number === '' || $first_name === '') {
        $error = 'All fields are required.';
    } else {
        // Normalize inputs
        $sql = "SELECT * FROM customers WHERE account_number = ? AND contact_number = ? AND LOWER(first_name) = LOWER(?) AND is_active = 1 LIMIT 1";
        $customer = fetchOne($sql, [$account_number, $contact_number, $first_name]);

        if ($customer) {
            // Ensure no admin session conflicts when customer logs in
            unset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['full_name'], $_SESSION['role']);
            // Set customer session only (separate from admin/users)
            $_SESSION['customer_id'] = $customer['customer_id'];
            $_SESSION['customer_account_number'] = $customer['account_number'];
            $_SESSION['customer_name'] = $customer['first_name'] . ' ' . $customer['last_name'];

            // Render page that triggers success modal and redirects
            $success = true;
        } else {
            $error = 'No matching customer found. Please check your details.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
    <style>
        body { background-color: #f5f7fb; }
        .login-card { max-width: 480px; margin: 6rem auto; }
    </style>
    <script>
        if (window.top !== window.self) { window.top.location = window.location; }
    </script>
</head>
<body>
    <div class="container">
        <div class="card shadow-sm login-card">
            <div class="card-body p-4">
                <div class="text-center mb-3">
                    <h4 class="mb-0">Customer Login</h4>
                    <small class="text-muted">Enter your account details to continue</small>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <div><?php echo htmlspecialchars($error); ?></div>
                    </div>
                <?php endif; ?>

                <form method="post" action="">
                    <div class="mb-3">
                        <label class="form-label">Account Number</label>
                        <input type="text" name="account_number" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="contact_number" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-right-to-bracket me-1"></i> Login
                    </button>
                </form>
                <div class="mt-3 text-center">
                    <a href="<?php echo url('auth/login.php'); ?>">Admin/Staff Login</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <?php if (!empty($success)): ?>
    <!-- Success Modal -->
    <div class="modal fade" id="loginSuccessModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title"><i class="fas fa-check-circle text-success me-2"></i>Login Successful</h5>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Welcome, <?php echo htmlspecialchars($_SESSION['customer_name']); ?>.</p>
                </div>
                <div class="modal-footer border-0">
                    <a href="<?php echo url('customer/home.php'); ?>" class="btn btn-success">Continue</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        const modal = new bootstrap.Modal(document.getElementById('loginSuccessModal'));
        modal.show();
        setTimeout(function(){ window.location.href = '<?php echo url('customer/home.php'); ?>'; }, 1500);
    </script>
    <?php endif; ?>
</body>
</html>


