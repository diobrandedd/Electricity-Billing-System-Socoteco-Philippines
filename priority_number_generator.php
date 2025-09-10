<?php
/**
 * Priority Number Generator - Customer Interface
 * Allows customers to generate priority numbers for service
 */

require_once 'config/config.php';
require_once 'includes/PriorityNumberGenerator.php';

// Check if user is logged in
requireLogin();

// Get customer information
$customerId = $_SESSION['customer_id'] ?? null;
if (!$customerId) {
    // For demo purposes, we'll use the first customer if no customer_id in session
    $sql = "SELECT customer_id FROM customers WHERE is_active = 1 LIMIT 1";
    $result = fetchOne($sql);
    $customerId = $result['customer_id'] ?? null;
}

if (!$customerId) {
    redirect('customers.php');
}

// Get customer details
$sql = "SELECT * FROM customers WHERE customer_id = ?";
$customer = fetchOne($sql, [$customerId]);

// Get customer's existing priority numbers
$priorityGenerator = new PriorityNumberGenerator();
$existingPriority = $priorityGenerator->getCustomerPendingPriority($customerId);
$priorityHistory = $priorityGenerator->getCustomerPriorityHistory($customerId, 5);

// Get queue statistics
$queueStats = $priorityGenerator->getQueueStatistics();

$pageTitle = "Priority Number Generator";
include 'includes/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Priority Number Generator</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Priority Number</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Information Card -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4"><strong>Name:</strong></div>
                        <div class="col-sm-8"><?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4"><strong>Account Number:</strong></div>
                        <div class="col-sm-8"><?php echo htmlspecialchars($customer['account_number']); ?></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4"><strong>Contact:</strong></div>
                        <div class="col-sm-8"><?php echo htmlspecialchars($customer['contact_number'] ?? 'N/A'); ?></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Queue Statistics -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Queue Status</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="text-center">
                                <h3 class="text-primary"><?php echo $queueStats['current_serving']; ?></h3>
                                <p class="text-muted mb-0">Currently Serving</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-center">
                                <h3 class="text-success"><?php echo $queueStats['served_today']; ?></h3>
                                <p class="text-muted mb-0">Served Today</p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-6">
                            <small class="text-muted">Pending Today: <strong><?php echo $queueStats['today_pending']; ?></strong></small>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted">Daily Capacity: <strong><?php echo $queueStats['daily_capacity']; ?></strong></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Priority Number Generation -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Generate Priority Number</h5>
                </div>
                <div class="card-body">
                    <?php if ($existingPriority): ?>
                        <!-- Existing Priority Number -->
                        <div class="alert alert-info">
                            <h5><i class="fas fa-ticket-alt"></i> You already have a priority number!</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Priority Number:</strong> <span class="badge badge-primary badge-lg"><?php echo $existingPriority['priority_number']; ?></span></p>
                                    <p><strong>Service Date:</strong> <?php echo date('F j, Y', strtotime($existingPriority['service_date'])); ?></p>
                                    <p><strong>Status:</strong> <span class="badge badge-warning"><?php echo ucfirst($existingPriority['status']); ?></span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Generated:</strong> <?php echo date('M j, Y g:i A', strtotime($existingPriority['generated_at'])); ?></p>
                                    <p><strong>Estimated Wait:</strong> 
                                        <?php 
                                        $dayNumber = ceil($existingPriority['priority_number'] / 1000);
                                        $positionInDay = (($existingPriority['priority_number'] - 1) % 1000) + 1;
                                        echo "Day {$dayNumber}, Position {$positionInDay}";
                                        ?>
                                    </p>
                                </div>
                            </div>
                            <button type="button" class="btn btn-danger btn-sm" onclick="cancelPriorityNumber(<?php echo $existingPriority['priority_number']; ?>)">
                                <i class="fas fa-times"></i> Cancel Priority Number
                            </button>
                        </div>
                    <?php else: ?>
                        <!-- Generate New Priority Number -->
                        <form id="priorityForm">
                            <input type="hidden" name="action" value="generate">
                            <input type="hidden" name="customer_id" value="<?php echo $customerId; ?>">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="preferred_date">Preferred Service Date (Optional)</label>
                                        <input type="date" class="form-control" id="preferred_date" name="preferred_date" 
                                               min="<?php echo date('Y-m-d'); ?>" 
                                               max="<?php echo date('Y-m-d', strtotime('+7 days')); ?>">
                                        <small class="form-text text-muted">Leave blank for automatic assignment</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div>
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                <i class="fas fa-ticket-alt"></i> Generate Priority Number
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Priority Number History -->
    <?php if (!empty($priorityHistory)): ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Priority Number History</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Priority Number</th>
                                    <th>Service Date</th>
                                    <th>Status</th>
                                    <th>Generated</th>
                                    <th>Served</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($priorityHistory as $history): ?>
                                <tr>
                                    <td><span class="badge badge-primary"><?php echo $history['priority_number']; ?></span></td>
                                    <td><?php echo date('M j, Y', strtotime($history['service_date'])); ?></td>
                                    <td>
                                        <?php
                                        $statusClass = [
                                            'pending' => 'warning',
                                            'served' => 'success',
                                            'expired' => 'danger',
                                            'cancelled' => 'secondary'
                                        ];
                                        $class = $statusClass[$history['status']] ?? 'secondary';
                                        ?>
                                        <span class="badge badge-<?php echo $class; ?>"><?php echo ucfirst($history['status']); ?></span>
                                    </td>
                                    <td><?php echo date('M j, Y g:i A', strtotime($history['generated_at'])); ?></td>
                                    <td>
                                        <?php echo $history['served_at'] ? date('M j, Y g:i A', strtotime($history['served_at'])) : 'N/A'; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Real-time Queue Display -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Live Queue Display</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="queue-display">
                            <h2 class="text-primary" id="current-serving"><?php echo $queueStats['current_serving']; ?></h2>
                            <p class="text-muted">Currently Serving</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="queue-display">
                            <h2 class="text-success" id="served-today"><?php echo $queueStats['served_today']; ?></h2>
                            <p class="text-muted">Served Today</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="queue-display">
                            <h2 class="text-warning" id="pending-today"><?php echo $queueStats['today_pending']; ?></h2>
                            <p class="text-muted">Pending Today</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="queue-display">
                            <h2 class="text-info" id="daily-capacity"><?php echo $queueStats['daily_capacity']; ?></h2>
                            <p class="text-muted">Daily Capacity</p>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <small class="text-muted">Last updated: <span id="last-updated"><?php echo date('g:i A'); ?></span></small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Handle priority number generation
    $('#priorityForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        
        $.ajax({
            url: 'ajax/priority_number.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            beforeSend: function() {
                $('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Generating...');
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Success!',
                        html: `
                            <div class="text-center">
                                <h2 class="text-primary mb-3">${response.priority_number}</h2>
                                <p><strong>Service Date:</strong> ${new Date(response.service_date).toLocaleDateString()}</p>
                                <p><strong>Estimated Wait:</strong> Day ${response.estimated_wait_time.day_number}, Position ${response.estimated_wait_time.position_in_day}</p>
                            </div>
                        `,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', response.error, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'An error occurred while generating priority number', 'error');
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false).html('<i class="fas fa-ticket-alt"></i> Generate Priority Number');
            }
        });
    });
    
    // Real-time queue updates
    function updateQueueDisplay() {
        $.ajax({
            url: 'ajax/priority_display.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#current-serving').text(response.data.current_serving);
                    $('#served-today').text(response.data.served_today);
                    $('#pending-today').text(response.data.today_pending);
                    $('#daily-capacity').text(response.data.daily_capacity);
                    $('#last-updated').text(new Date().toLocaleTimeString());
                }
            }
        });
    }
    
    // Update queue display every 10 seconds
    setInterval(updateQueueDisplay, 10000);
});

// Cancel priority number function
function cancelPriorityNumber(priorityNumber) {
    Swal.fire({
        title: 'Cancel Priority Number',
        text: 'Are you sure you want to cancel this priority number?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, cancel it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'ajax/priority_number.php',
                type: 'POST',
                data: {
                    action: 'cancel',
                    priority_number: priorityNumber,
                    reason: 'Cancelled by customer',
                    csrf_token: '<?php echo generateCSRFToken(); ?>'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Cancelled!', response.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', response.error, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'An error occurred while cancelling priority number', 'error');
                }
            });
        }
    });
}
</script>

<style>
.queue-display {
    padding: 20px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    margin-bottom: 20px;
}

.queue-display h2 {
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 10px;
}

.badge-lg {
    font-size: 1.2rem;
    padding: 0.5rem 1rem;
}
</style>

<?php include 'includes/footer.php'; ?>
