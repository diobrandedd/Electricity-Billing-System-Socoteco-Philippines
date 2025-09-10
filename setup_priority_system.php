<?php
/**
 * Priority System Setup Script
 * This script sets up the priority number system database tables and initial data
 */

require_once 'config/config.php';

// Check if user is logged in and has admin role
requireRole(['admin']);

$pageTitle = "Priority System Setup";
include 'includes/header.php';

$setupComplete = false;
$errors = [];
$success = [];

// Handle setup form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCSRFToken($_POST['csrf_token'] ?? '')) {
    try {
        $db = getDB();
        $db->beginTransaction();
        
        // Read and execute the priority system SQL file
        $sqlFile = __DIR__ . '/database/priority_system.sql';
        if (!file_exists($sqlFile)) {
            throw new Exception("Priority system SQL file not found");
        }
        
        $sql = file_get_contents($sqlFile);
        
        // Split SQL into individual statements
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        
        foreach ($statements as $statement) {
            if (!empty($statement) && !preg_match('/^--/', $statement)) {
                $db->exec($statement);
            }
        }
        
        $db->commit();
        
        $setupComplete = true;
        $success[] = "Priority system database tables created successfully!";
        $success[] = "System settings configured!";
        $success[] = "Initial queue status created!";
        
        logActivity('Priority system setup completed', 'system_setup');
        
    } catch (Exception $e) {
        $db->rollBack();
        $errors[] = "Setup failed: " . $e->getMessage();
    }
}

// Check if tables already exist
$tablesExist = false;
try {
    $db = getDB();
    $result = $db->query("SHOW TABLES LIKE 'priority_numbers'");
    $tablesExist = $result->rowCount() > 0;
} catch (Exception $e) {
    // Tables don't exist yet
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Priority System Setup</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Priority Setup</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <h5><i class="fas fa-exclamation-triangle"></i> Setup Errors</h5>
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <h5><i class="fas fa-check-circle"></i> Setup Complete!</h5>
            <ul class="mb-0">
                <?php foreach ($success as $msg): ?>
                    <li><?php echo htmlspecialchars($msg); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Priority System Installation</h5>
                </div>
                <div class="card-body">
                    <?php if ($tablesExist && !$setupComplete): ?>
                        <div class="alert alert-warning">
                            <h5><i class="fas fa-exclamation-triangle"></i> Tables Already Exist</h5>
                            <p>The priority system tables already exist in your database. If you want to reinstall, you may need to drop the existing tables first.</p>
                        </div>
                    <?php endif; ?>

                    <?php if (!$setupComplete): ?>
                        <div class="alert alert-info">
                            <h5><i class="fas fa-info-circle"></i> What This Setup Will Do</h5>
                            <ul>
                                <li>Create priority number system database tables</li>
                                <li>Add system settings for priority management</li>
                                <li>Initialize queue status for today</li>
                                <li>Set up indexes for optimal performance</li>
                            </ul>
                        </div>

                        <form method="POST" id="setupForm">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="confirmSetup" required>
                                    <label class="custom-control-label" for="confirmSetup">
                                        I understand that this will create new database tables and I have backed up my database
                                    </label>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg" id="setupButton">
                                <i class="fas fa-cog"></i> Install Priority System
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="text-center">
                            <h4 class="text-success mb-4">
                                <i class="fas fa-check-circle"></i> Priority System Installed Successfully!
                            </h4>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="priority_settings.php" class="btn btn-primary btn-lg mb-3">
                                        <i class="fas fa-cogs"></i> Configure Settings
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <a href="priority_queue_management.php" class="btn btn-success btn-lg mb-3">
                                        <i class="fas fa-list"></i> Manage Queue
                                    </a>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="priority_number_generator.php" class="btn btn-info btn-lg mb-3">
                                        <i class="fas fa-ticket-alt"></i> Generate Priority
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <a href="priority_display.php" class="btn btn-warning btn-lg mb-3" target="_blank">
                                        <i class="fas fa-tv"></i> View Display
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- System Requirements -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">System Requirements</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>PHP Version</span>
                        <span class="badge <?php echo version_compare(PHP_VERSION, '7.4.0', '>=') ? 'badge-success' : 'badge-danger'; ?>">
                            <?php echo PHP_VERSION; ?>
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>MySQL/MariaDB</span>
                        <span class="badge badge-success">Required</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>PDO Extension</span>
                        <span class="badge <?php echo extension_loaded('pdo') ? 'badge-success' : 'badge-danger'; ?>">
                            <?php echo extension_loaded('pdo') ? 'Installed' : 'Missing'; ?>
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>JSON Extension</span>
                        <span class="badge <?php echo extension_loaded('json') ? 'badge-success' : 'badge-danger'; ?>">
                            <?php echo extension_loaded('json') ? 'Installed' : 'Missing'; ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Database Status -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Database Status</h5>
                </div>
                <div class="card-body">
                    <?php
                    try {
                        $db = getDB();
                        $result = $db->query("SELECT COUNT(*) as count FROM priority_numbers");
                        $priorityCount = $result->fetch()['count'];
                        
                        $result = $db->query("SELECT COUNT(*) as count FROM priority_queue_status");
                        $queueCount = $result->fetch()['count'];
                        
                        $result = $db->query("SELECT COUNT(*) as count FROM service_days");
                        $serviceCount = $result->fetch()['count'];
                    } catch (Exception $e) {
                        $priorityCount = $queueCount = $serviceCount = 0;
                    }
                    ?>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Priority Numbers</span>
                        <span class="badge badge-info"><?php echo $priorityCount; ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Queue Status</span>
                        <span class="badge badge-info"><?php echo $queueCount; ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Service Days</span>
                        <span class="badge badge-info"><?php echo $serviceCount; ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Help -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Need Help?</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">If you encounter any issues during setup:</p>
                    <ul class="text-muted">
                        <li>Check database permissions</li>
                        <li>Ensure MySQL/MariaDB is running</li>
                        <li>Verify database connection settings</li>
                        <li>Check PHP error logs</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Handle setup form submission
    $('#setupForm').on('submit', function(e) {
        e.preventDefault();
        
        if (!$('#confirmSetup').is(':checked')) {
            Swal.fire('Error', 'Please confirm that you understand the setup process', 'error');
            return;
        }
        
        Swal.fire({
            title: 'Install Priority System',
            text: 'This will create new database tables. Are you sure you want to continue?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, install it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#setupButton').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Installing...');
                this.submit();
            }
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>
