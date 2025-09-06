<?php
$page_title = 'System Settings';
require_once 'includes/header.php';

requireRole(['admin']);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action == 'update_settings') {
        $settings = [
            'company_name' => sanitizeInput($_POST['company_name'] ?? ''),
            'company_address' => sanitizeInput($_POST['company_address'] ?? ''),
            'vat_rate' => floatval($_POST['vat_rate'] ?? 0),
            'penalty_rate' => floatval($_POST['penalty_rate'] ?? 0),
            'due_days' => intval($_POST['due_days'] ?? 0),
            'generation_rate' => floatval($_POST['generation_rate'] ?? 0),
            'distribution_rate' => floatval($_POST['distribution_rate'] ?? 0),
            'transmission_rate' => floatval($_POST['transmission_rate'] ?? 0),
            'system_loss_rate' => floatval($_POST['system_loss_rate'] ?? 0)
        ];
        
        foreach ($settings as $key => $value) {
            setSystemSetting($key, $value);
        }
        
        logActivity('System settings updated', 'system_settings');
        $success = 'Settings updated successfully.';
    }
}

// Get current settings
$current_settings = [];
$settings_keys = ['company_name', 'company_address', 'vat_rate', 'penalty_rate', 'due_days', 
                 'generation_rate', 'distribution_rate', 'transmission_rate', 'system_loss_rate'];

foreach ($settings_keys as $key) {
    $current_settings[$key] = getSystemSetting($key, '');
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-cog me-2"></i>System Settings</h2>
            </div>

            <?php if (isset($success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="action" value="update_settings">
                
                <div class="row">
                    <!-- Company Information -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5><i class="fas fa-building me-2"></i>Company Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="company_name" class="form-label">Company Name</label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" 
                                           value="<?php echo htmlspecialchars($current_settings['company_name']); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="company_address" class="form-label">Company Address</label>
                                    <textarea class="form-control" id="company_address" name="company_address" rows="3"><?php echo htmlspecialchars($current_settings['company_address']); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Billing Settings -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5><i class="fas fa-calculator me-2"></i>Billing Settings</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="vat_rate" class="form-label">VAT Rate (%)</label>
                                    <input type="number" class="form-control" id="vat_rate" name="vat_rate" 
                                           value="<?php echo $current_settings['vat_rate']; ?>" step="0.01" min="0" max="100">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="penalty_rate" class="form-label">Penalty Rate (%)</label>
                                    <input type="number" class="form-control" id="penalty_rate" name="penalty_rate" 
                                           value="<?php echo $current_settings['penalty_rate']; ?>" step="0.01" min="0" max="100">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="due_days" class="form-label">Due Days</label>
                                    <input type="number" class="form-control" id="due_days" name="due_days" 
                                           value="<?php echo $current_settings['due_days']; ?>" min="1" max="365">
                                    <div class="form-text">Number of days from bill date to due date</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rate Settings -->
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5><i class="fas fa-bolt me-2"></i>Electricity Rates (per kWh)</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="generation_rate" class="form-label">Generation Rate</label>
                                            <div class="input-group">
                                                <span class="input-group-text">₱</span>
                                                <input type="number" class="form-control" id="generation_rate" name="generation_rate" 
                                                       value="<?php echo $current_settings['generation_rate']; ?>" step="0.0001" min="0">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="distribution_rate" class="form-label">Distribution Rate</label>
                                            <div class="input-group">
                                                <span class="input-group-text">₱</span>
                                                <input type="number" class="form-control" id="distribution_rate" name="distribution_rate" 
                                                       value="<?php echo $current_settings['distribution_rate']; ?>" step="0.0001" min="0">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="transmission_rate" class="form-label">Transmission Rate</label>
                                            <div class="input-group">
                                                <span class="input-group-text">₱</span>
                                                <input type="number" class="form-control" id="transmission_rate" name="transmission_rate" 
                                                       value="<?php echo $current_settings['transmission_rate']; ?>" step="0.0001" min="0">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="system_loss_rate" class="form-label">System Loss Rate</label>
                                            <div class="input-group">
                                                <span class="input-group-text">₱</span>
                                                <input type="number" class="form-control" id="system_loss_rate" name="system_loss_rate" 
                                                       value="<?php echo $current_settings['system_loss_rate']; ?>" step="0.0001" min="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Information -->
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5><i class="fas fa-info-circle me-2"></i>System Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">System Version</label>
                                            <input type="text" class="form-control" value="<?php echo SITE_VERSION; ?>" readonly>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">PHP Version</label>
                                            <input type="text" class="form-control" value="<?php echo PHP_VERSION; ?>" readonly>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Server Time</label>
                                            <input type="text" class="form-control" value="<?php echo date('Y-m-d H:i:s'); ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Settings
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-save functionality (optional)
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input, textarea, select');
    
    // Add change event listeners to all inputs
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            // You can add auto-save functionality here if needed
        });
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>
