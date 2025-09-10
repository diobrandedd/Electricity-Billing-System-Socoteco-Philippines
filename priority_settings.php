<?php
/**
 * Priority System Settings
 * Allows administrators to configure priority number system settings
 */

require_once 'config/config.php';

// Check if user is logged in and has admin role
requireRole(['admin']);

$pageTitle = "Priority System Settings";
include 'includes/header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCSRFToken($_POST['csrf_token'] ?? '')) {
    $settings = [
        'priority_daily_capacity' => $_POST['daily_capacity'] ?? 1000,
        'priority_advance_days' => $_POST['advance_days'] ?? 7,
        'priority_expiry_hours' => $_POST['expiry_hours'] ?? 24,
        'priority_notification_enabled' => $_POST['notification_enabled'] ?? 0,
        'priority_auto_assign_days' => $_POST['auto_assign_days'] ?? 1,
        'priority_weekend_service' => $_POST['weekend_service'] ?? 0,
        'priority_break_start' => $_POST['break_start'] ?? '12:00',
        'priority_break_end' => $_POST['break_end'] ?? '13:00'
    ];
    
    foreach ($settings as $key => $value) {
        setSystemSetting($key, $value);
    }
    
    logActivity('Priority system settings updated', 'system_settings');
    
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> Settings updated successfully!
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
          </div>';
}

// Get current settings
$currentSettings = [
    'daily_capacity' => getSystemSetting('priority_daily_capacity', 1000),
    'advance_days' => getSystemSetting('priority_advance_days', 7),
    'expiry_hours' => getSystemSetting('priority_expiry_hours', 24),
    'notification_enabled' => getSystemSetting('priority_notification_enabled', 1),
    'auto_assign_days' => getSystemSetting('priority_auto_assign_days', 1),
    'weekend_service' => getSystemSetting('priority_weekend_service', 0),
    'break_start' => getSystemSetting('priority_break_start', '12:00'),
    'break_end' => getSystemSetting('priority_break_end', '13:00')
];
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Priority System Settings</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Priority Settings</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">System Configuration</h5>
                </div>
                <div class="card-body">
                    <form method="POST" id="settingsForm">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="daily_capacity">Daily Capacity</label>
                                    <input type="number" class="form-control" id="daily_capacity" name="daily_capacity" 
                                           value="<?php echo $currentSettings['daily_capacity']; ?>" 
                                           min="1" max="5000" required>
                                    <small class="form-text text-muted">Maximum number of customers that can be served per day</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="advance_days">Advance Booking Days</label>
                                    <input type="number" class="form-control" id="advance_days" name="advance_days" 
                                           value="<?php echo $currentSettings['advance_days']; ?>" 
                                           min="1" max="30" required>
                                    <small class="form-text text-muted">Number of days in advance customers can get priority numbers</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="expiry_hours">Priority Number Expiry (Hours)</label>
                                    <input type="number" class="form-control" id="expiry_hours" name="expiry_hours" 
                                           value="<?php echo $currentSettings['expiry_hours']; ?>" 
                                           min="1" max="168" required>
                                    <small class="form-text text-muted">Hours after which a priority number expires if not served</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="auto_assign_days">Auto Assign Service Days</label>
                                    <select class="form-control" id="auto_assign_days" name="auto_assign_days">
                                        <option value="1" <?php echo $currentSettings['auto_assign_days'] ? 'selected' : ''; ?>>Enabled</option>
                                        <option value="0" <?php echo !$currentSettings['auto_assign_days'] ? 'selected' : ''; ?>>Disabled</option>
                                    </select>
                                    <small class="form-text text-muted">Automatically assign service days when generating priority numbers</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="weekend_service">Weekend Service</label>
                                    <select class="form-control" id="weekend_service" name="weekend_service">
                                        <option value="1" <?php echo $currentSettings['weekend_service'] ? 'selected' : ''; ?>>Enabled</option>
                                        <option value="0" <?php echo !$currentSettings['weekend_service'] ? 'selected' : ''; ?>>Disabled</option>
                                    </select>
                                    <small class="form-text text-muted">Allow priority numbers for weekend service</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="notification_enabled">Notifications</label>
                                    <select class="form-control" id="notification_enabled" name="notification_enabled">
                                        <option value="1" <?php echo $currentSettings['notification_enabled'] ? 'selected' : ''; ?>>Enabled</option>
                                        <option value="0" <?php echo !$currentSettings['notification_enabled'] ? 'selected' : ''; ?>>Disabled</option>
                                    </select>
                                    <small class="form-text text-muted">Enable SMS/Email notifications for priority numbers</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="break_start">Break Start Time</label>
                                    <input type="time" class="form-control" id="break_start" name="break_start" 
                                           value="<?php echo $currentSettings['break_start']; ?>">
                                    <small class="form-text text-muted">Start time for lunch break (optional)</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="break_end">Break End Time</label>
                                    <input type="time" class="form-control" id="break_end" name="break_end" 
                                           value="<?php echo $currentSettings['break_end']; ?>">
                                    <small class="form-text text-muted">End time for lunch break (optional)</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Settings
                            </button>
                            <button type="button" class="btn btn-secondary ml-2" onclick="resetToDefaults()">
                                <i class="fas fa-undo"></i> Reset to Defaults
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- System Status -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">System Status</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Priority System</span>
                        <span class="badge badge-success">Active</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Real-time Display</span>
                        <span class="badge badge-success">Running</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Queue Management</span>
                        <span class="badge badge-success">Operational</span>
                    </div>
                    <hr>
                    <div class="text-center">
                        <a href="priority_display.php" class="btn btn-info btn-sm" target="_blank">
                            <i class="fas fa-tv"></i> View Display
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="priority_queue_management.php" class="btn btn-primary">
                            <i class="fas fa-cogs"></i> Queue Management
                        </a>
                        <a href="priority_number_generator.php" class="btn btn-success">
                            <i class="fas fa-ticket-alt"></i> Generate Priority
                        </a>
                        <button type="button" class="btn btn-warning" onclick="clearExpiredNumbers()">
                            <i class="fas fa-trash"></i> Clear Expired
                        </button>
                        <button type="button" class="btn btn-info" onclick="resetQueue()">
                            <i class="fas fa-refresh"></i> Reset Queue
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- System Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">System Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">Version</small>
                            <p class="mb-0"><strong>1.0.0</strong></p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Last Updated</small>
                            <p class="mb-0"><strong><?php echo date('M j, Y'); ?></strong></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">Database</small>
                            <p class="mb-0"><strong>MySQL</strong></p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">PHP Version</small>
                            <p class="mb-0"><strong><?php echo PHP_VERSION; ?></strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Handle form submission
    $('#settingsForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        
        $.ajax({
            url: 'priority_settings.php',
            type: 'POST',
            data: formData,
            dataType: 'html',
            success: function(response) {
                // Show success message
                $('.container-fluid').prepend('<div class="alert alert-success alert-dismissible fade show" role="alert"><i class="fas fa-check-circle"></i> Settings updated successfully!<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>');
                
                // Scroll to top
                $('html, body').animate({ scrollTop: 0 }, 500);
            },
            error: function() {
                alert('Error updating settings. Please try again.');
            }
        });
    });
});

// Reset to default values
function resetToDefaults() {
    Swal.fire({
        title: 'Reset to Defaults',
        text: 'Are you sure you want to reset all settings to their default values?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, reset!'
    }).then((result) => {
        if (result.isConfirmed) {
            $('#daily_capacity').val(1000);
            $('#advance_days').val(7);
            $('#expiry_hours').val(24);
            $('#notification_enabled').val(1);
            $('#auto_assign_days').val(1);
            $('#weekend_service').val(0);
            $('#break_start').val('12:00');
            $('#break_end').val('13:00');
            
            Swal.fire('Reset!', 'Settings have been reset to default values.', 'success');
        }
    });
}

// Clear expired priority numbers
function clearExpiredNumbers() {
    Swal.fire({
        title: 'Clear Expired Numbers',
        text: 'This will remove all expired priority numbers from the system. Continue?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, clear them!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'ajax/priority_number.php',
                type: 'POST',
                data: {
                    action: 'clear_expired',
                    csrf_token: '<?php echo generateCSRFToken(); ?>'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Cleared!', response.message, 'success');
                    } else {
                        Swal.fire('Error', response.error, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'An error occurred while clearing expired numbers', 'error');
                }
            });
        }
    });
}

// Reset queue
function resetQueue() {
    Swal.fire({
        title: 'Reset Queue',
        text: 'This will reset the current queue status. This action cannot be undone!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, reset queue!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'ajax/priority_number.php',
                type: 'POST',
                data: {
                    action: 'reset_queue',
                    csrf_token: '<?php echo generateCSRFToken(); ?>'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Reset!', response.message, 'success');
                    } else {
                        Swal.fire('Error', response.error, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'An error occurred while resetting queue', 'error');
                }
            });
        }
    });
}
</script>

<?php include 'includes/footer.php'; ?>
