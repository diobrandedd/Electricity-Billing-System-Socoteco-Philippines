<?php
require_once __DIR__ . '/../config/config.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Dashboard'; ?> - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #17a2b8;
        }
        
        body {
            background-color: #f8f9fa;
        }
        
        .sidebar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 2px 10px;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }
        
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        
        .main-content {
            padding: 20px;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            border: none;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            border-radius: 8px;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .navbar-brand {
            font-weight: bold;
            color: white !important;
        }
        
        .user-info {
            color: rgba(255,255,255,0.9);
        }
        
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .stats-card .stats-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }
        
        .table {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .table thead th {
            background-color: var(--primary-color);
            color: white;
            border: none;
        }
        
        .badge {
            border-radius: 20px;
            padding: 8px 12px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h4 class="text-white">
                            <i class="fas fa-bolt me-2"></i>SOCOTECO II
                        </h4>
                        <small class="text-white-50">Billing System</small>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" 
                               href="<?php echo url('dashboard.php'); ?>">
                                <i class="fas fa-tachometer-alt"></i>Dashboard
                            </a>
                        </li>
                        
                        <?php if (in_array($_SESSION['role'], ['admin', 'cashier'])): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'customers.php' ? 'active' : ''; ?>" 
                               href="<?php echo url('customers.php'); ?>">
                                <i class="fas fa-users"></i>Customers
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (in_array($_SESSION['role'], ['admin', 'meter_reader'])): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'meter_readings.php' ? 'active' : ''; ?>" 
                               href="<?php echo url('meter_readings.php'); ?>">
                                <i class="fas fa-tachometer"></i>Meter Readings
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (in_array($_SESSION['role'], ['admin', 'cashier'])): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'bills.php' ? 'active' : ''; ?>" 
                               href="<?php echo url('bills.php'); ?>">
                                <i class="fas fa-file-invoice"></i>Bills
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (in_array($_SESSION['role'], ['admin', 'cashier'])): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'payments.php' ? 'active' : ''; ?>" 
                               href="<?php echo url('payments.php'); ?>">
                                <i class="fas fa-credit-card"></i>Payments
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (in_array($_SESSION['role'], ['admin', 'cashier'])): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'priority_calling_system.php' ? 'active' : ''; ?>" 
                               href="<?php echo url('priority_calling_system.php'); ?>">
                                <i class="fas fa-microphone"></i>Priority Calling
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (in_array($_SESSION['role'], ['admin', 'cashier'])): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'priority_queue_management.php' ? 'active' : ''; ?>" 
                               href="<?php echo url('priority_queue_management.php'); ?>">
                                <i class="fas fa-list"></i>Queue Management
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (in_array($_SESSION['role'], ['admin'])): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>" 
                               href="<?php echo url('reports.php'); ?>">
                                <i class="fas fa-chart-bar"></i>Reports
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (in_array($_SESSION['role'], ['admin'])): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : ''; ?>" 
                               href="<?php echo url('users.php'); ?>">
                                <i class="fas fa-user-cog"></i>User Management
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (in_array($_SESSION['role'], ['admin'])): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>" 
                               href="<?php echo url('settings.php'); ?>">
                                <i class="fas fa-cog"></i>Settings
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (in_array($_SESSION['role'], ['admin'])): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'priority_settings.php' ? 'active' : ''; ?>" 
                               href="<?php echo url('priority_settings.php'); ?>">
                                <i class="fas fa-ticket-alt"></i>Priority Settings
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if (in_array($_SESSION['role'], ['admin'])): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'feedback_management.php' ? 'active' : ''; ?>" 
                               href="<?php echo url('feedback_management.php'); ?>">
                                <i class="fas fa-comments"></i>Feedback Management
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>
            
            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <!-- Top Navigation -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><?php echo $page_title ?? 'Dashboard'; ?></h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" 
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-2"></i><?php echo $_SESSION['full_name']; ?>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo url('profile.php'); ?>">
                                    <i class="fas fa-user-edit me-2"></i>Profile
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo url('auth/logout.php'); ?>">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
