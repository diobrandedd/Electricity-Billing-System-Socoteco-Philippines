<?php
require_once __DIR__ . '/../config/config.php';

if (isLoggedIn()) {
    logActivity('User logout', 'users', $_SESSION['user_id']);
}

// Destroy session
session_destroy();

// Redirect to login page
redirect('auth/login.php');
?>
