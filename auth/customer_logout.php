<?php
require_once __DIR__ . '/../config/config.php';

// Only clear customer-specific session keys
unset($_SESSION['customer_id'], $_SESSION['customer_account_number'], $_SESSION['customer_name']);

redirect('auth/customer_login.php');
?>


