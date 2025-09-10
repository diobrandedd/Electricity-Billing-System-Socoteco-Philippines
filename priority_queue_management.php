<?php
/**
 * Priority Queue Management - Admin Interface
 * Allows staff to manage the current priority number and view queue status
 */

require_once 'config/config.php';
require_once 'includes/PriorityNumberGenerator.php';

// Check if user is logged in and has admin/cashier role
requireRole(['admin', 'cashier']);

$priorityGenerator = new PriorityNumberGenerator();

// Get current queue status
$currentStatus = $priorityGenerator->getCurrentPriorityNumber();
$queueStats = $priorityGenerator->getQueueStatistics();
$upcomingNumbers = $priorityGenerator->getUpcomingPriorityNumbers(20);

$pageTitle = "Priority Queue Management";
include 'includes/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Priority Queue Management</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Queue Management</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Status Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h2 class="text-primary" id="current-serving"><?php echo $currentStatus['current_priority_number'] ?? 0; ?></h2>
                    <p class="text-muted mb-0">Currently Serving</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h2 class="text-success" id="served-today"><?php echo $currentStatus['served_count'] ?? 0; ?></h2>
                    <p class="text-muted mb-0">Served Today</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h2 class="text-warning" id="pending-today"><?php echo $queueStats['today_pending']; ?></h2>
                    <p class="text-muted mb-0">Pending Today</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h2 class="text-info" id="daily-capacity"><?php echo $currentStatus['daily_capacity'] ?? 1000; ?></h2>
                    <p class="text-muted mb-0">Daily Capacity</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Queue Control Panel -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Queue Control</h5>
                </div>
                <div class="card-body">
                    <form id="queueControlForm">
                        <input type="hidden" name="action" value="update_current">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        
                        <div class="form-group">
                            <label for="priority_number">Next Priority Number to Serve</label>
                            <input type="number" class="form-control" id="priority_number" name="priority_number" 
                                   value="<?php echo ($currentStatus['current_priority_number'] ?? 0) + 1; ?>" 
                                   min="1" required>
                            <small class="form-text text-muted">Enter the priority number you are about to serve</small>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check"></i> Mark as Served
                        </button>
                        
                        <button type="button" class="btn btn-info ml-2" onclick="refreshQueueStatus()">
                            <i class="fas fa-sync"></i> Refresh Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success" onclick="serveNextNumber()">
                            <i class="fas fa-forward"></i> Serve Next Number
                        </button>
                        
                        <button type="button" class="btn btn-warning" onclick="skipNumber()">
                            <i class="fas fa-step-forward"></i> Skip Current Number
                        </button>
                        
                        <button type="button" class="btn btn-info" onclick="viewQueueDetails()">
                            <i class="fas fa-list"></i> View Full Queue
                        </button>
                        
                        <button type="button" class="btn btn-secondary" onclick="exportQueueReport()">
                            <i class="fas fa-download"></i> Export Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Priority Numbers -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Upcoming Priority Numbers (Next 20)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="upcomingTable">
                            <thead>
                                <tr>
                                    <th>Priority #</th>
                                    <th>Customer Name</th>
                                    <th>Account Number</th>
                                    <th>Contact</th>
                                    <th>Generated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($upcomingNumbers as $number): ?>
                                <tr>
                                    <td>
                                        <span class="badge badge-primary badge-lg"><?php echo $number['priority_number']; ?></span>
                                    </td>
                                    <td><?php echo htmlspecialchars($number['first_name'] . ' ' . $number['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($number['account_number']); ?></td>
                                    <td><?php echo htmlspecialchars($number['contact_number'] ?? 'N/A'); ?></td>
                                    <td><?php echo date('M j, Y g:i A', strtotime($number['generated_at'])); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" onclick="serveNumber(<?php echo $number['priority_number']; ?>)">
                                            <i class="fas fa-check"></i> Serve
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="cancelNumber(<?php echo $number['priority_number']; ?>)">
                                            <i class="fas fa-times"></i> Cancel
                                        </button>
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

    <!-- Queue Statistics -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Today's Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="text-success"><?php echo $queueStats['served_today']; ?></h4>
                                <p class="text-muted mb-0">Served</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="text-warning"><?php echo $queueStats['today_pending']; ?></h4>
                                <p class="text-muted mb-0">Pending</p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="text-info"><?php echo $queueStats['total_pending']; ?></h4>
                                <p class="text-muted mb-0">Total Pending</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="text-primary"><?php echo $queueStats['tomorrow_pending']; ?></h4>
                                <p class="text-muted mb-0">Tomorrow</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Service Efficiency</h5>
                </div>
                <div class="card-body">
                    <?php
                    $served = $queueStats['served_today'];
                    $capacity = $queueStats['daily_capacity'];
                    $percentage = $capacity > 0 ? round(($served / $capacity) * 100, 1) : 0;
                    ?>
                    <div class="progress mb-3" style="height: 25px;">
                        <div class="progress-bar" role="progressbar" style="width: <?php echo $percentage; ?>%" 
                             aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100">
                            <?php echo $percentage; ?>%
                        </div>
                    </div>
                    <p class="text-muted mb-0">
                        <strong><?php echo $served; ?></strong> of <strong><?php echo $capacity; ?></strong> customers served today
                    </p>
                    <small class="text-muted">
                        <?php echo $capacity - $served; ?> customers remaining capacity
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Queue Details Modal -->
<div class="modal fade" id="queueDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Full Queue Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="queueDetailsContent">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin"></i> Loading...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Handle queue control form submission
    $('#queueControlForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        
        $.ajax({
            url: 'ajax/priority_number.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            beforeSend: function() {
                $('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire('Success!', response.message, 'success');
                    refreshQueueStatus();
                } else {
                    Swal.fire('Error', response.error, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'An error occurred while updating queue status', 'error');
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false).html('<i class="fas fa-check"></i> Mark as Served');
            }
        });
    });
    
    // Auto-refresh queue status every 30 seconds
    setInterval(refreshQueueStatus, 30000);
});

// Refresh queue status
function refreshQueueStatus() {
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
                
                // Update the priority number input
                $('#priority_number').val(response.data.current_serving + 1);
            }
        }
    });
}

// Serve next number
function serveNextNumber() {
    var currentServing = parseInt($('#current-serving').text());
    var nextNumber = currentServing + 1;
    
    $('#priority_number').val(nextNumber);
    $('#queueControlForm').submit();
}

// Skip current number
function skipNumber() {
    Swal.fire({
        title: 'Skip Current Number',
        text: 'Are you sure you want to skip the current number? This will mark it as expired.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, skip it!'
    }).then((result) => {
        if (result.isConfirmed) {
            var currentServing = parseInt($('#current-serving').text());
            var nextNumber = currentServing + 1;
            
            $('#priority_number').val(nextNumber);
            $('#queueControlForm').submit();
        }
    });
}

// Serve specific number
function serveNumber(priorityNumber) {
    $('#priority_number').val(priorityNumber);
    $('#queueControlForm').submit();
}

// Cancel specific number
function cancelNumber(priorityNumber) {
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
                    reason: 'Cancelled by staff',
                    csrf_token: '<?php echo generateCSRFToken(); ?>'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Cancelled!', response.message, 'success');
                        location.reload();
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

// View queue details
function viewQueueDetails() {
    $('#queueDetailsModal').modal('show');
    
    $.ajax({
        url: 'ajax/priority_number.php',
        type: 'GET',
        data: { action: 'get_upcoming', limit: 50 },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                var html = '<div class="table-responsive"><table class="table table-striped">';
                html += '<thead><tr><th>Priority #</th><th>Customer</th><th>Account</th><th>Generated</th><th>Status</th></tr></thead>';
                html += '<tbody>';
                
                response.data.forEach(function(item) {
                    html += '<tr>';
                    html += '<td><span class="badge badge-primary">' + item.priority_number + '</span></td>';
                    html += '<td>' + item.first_name + ' ' + item.last_name + '</td>';
                    html += '<td>' + item.account_number + '</td>';
                    html += '<td>' + new Date(item.generated_at).toLocaleString() + '</td>';
                    html += '<td><span class="badge badge-warning">' + item.status + '</span></td>';
                    html += '</tr>';
                });
                
                html += '</tbody></table></div>';
                $('#queueDetailsContent').html(html);
            }
        }
    });
}

// Export queue report
function exportQueueReport() {
    window.open('ajax/priority_number.php?action=export_report', '_blank');
}
</script>

<style>
.badge-lg {
    font-size: 1.1rem;
    padding: 0.5rem 0.8rem;
}

.progress {
    border-radius: 10px;
}

.progress-bar {
    border-radius: 10px;
}
</style>

<?php include 'includes/footer.php'; ?>
