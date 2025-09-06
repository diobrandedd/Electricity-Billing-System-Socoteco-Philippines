<?php
$page_title = 'Dashboard';
require_once 'includes/header.php';

// Get dashboard statistics
$stats = [];

// Total customers
$stats['total_customers'] = fetchOne("SELECT COUNT(*) as count FROM customers WHERE is_active = 1")['count'];

// Total bills this month
$stats['bills_this_month'] = fetchOne("
    SELECT COUNT(*) as count FROM bills 
    WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) 
    AND YEAR(created_at) = YEAR(CURRENT_DATE())
")['count'];

// Total payments this month
$stats['payments_this_month'] = fetchOne("
    SELECT COALESCE(SUM(amount_paid), 0) as total FROM payments 
    WHERE MONTH(payment_date) = MONTH(CURRENT_DATE()) 
    AND YEAR(payment_date) = YEAR(CURRENT_DATE())
")['total'];

// Overdue bills
$stats['overdue_bills'] = fetchOne("
    SELECT COUNT(*) as count FROM bills 
    WHERE status = 'overdue' OR (status = 'pending' AND due_date < CURDATE())
")['count'];

// Recent activities
$recent_activities = fetchAll("
    SELECT 
        a.action,
        a.table_name,
        a.created_at,
        u.full_name as user_name
    FROM audit_trail a
    JOIN users u ON a.user_id = u.user_id
    ORDER BY a.created_at DESC
    LIMIT 10
");

// Monthly revenue chart data
$monthly_revenue = fetchAll("
    SELECT 
        DATE_FORMAT(payment_date, '%Y-%m') as month,
        SUM(amount_paid) as total
    FROM payments 
    WHERE payment_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
    GROUP BY DATE_FORMAT(payment_date, '%Y-%m')
    ORDER BY month
");
?>

<div class="row">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Total Customers</div>
                        <div class="h5 mb-0 font-weight-bold"><?php echo number_format($stats['total_customers']); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users stats-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Bills This Month</div>
                        <div class="h5 mb-0 font-weight-bold"><?php echo number_format($stats['bills_this_month']); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-invoice stats-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Revenue This Month</div>
                        <div class="h5 mb-0 font-weight-bold"><?php echo formatCurrency($stats['payments_this_month']); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-peso-sign stats-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Overdue Bills</div>
                        <div class="h5 mb-0 font-weight-bold"><?php echo number_format($stats['overdue_bills']); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle stats-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Revenue Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-line me-2"></i>Monthly Revenue Trend
                </h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Recent Activities -->
    <div class="col-xl-4 col-lg-5">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>Recent Activities
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <?php foreach ($recent_activities as $activity): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold"><?php echo ucfirst(str_replace('_', ' ', $activity['action'])); ?></div>
                            <small class="text-muted"><?php echo $activity['user_name']; ?></small>
                        </div>
                        <small class="text-muted"><?php echo formatDate($activity['created_at'], 'M j, g:i A'); ?></small>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Quick Actions -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php if (in_array($_SESSION['role'], ['admin', 'cashier'])): ?>
                    <div class="col-md-3 mb-3">
                        <a href="<?php echo url('customers.php?action=add'); ?>" class="btn btn-primary w-100">
                            <i class="fas fa-user-plus me-2"></i>Add Customer
                        </a>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (in_array($_SESSION['role'], ['admin', 'meter_reader'])): ?>
                    <div class="col-md-3 mb-3">
                        <a href="<?php echo url('meter_readings.php?action=add'); ?>" class="btn btn-success w-100">
                            <i class="fas fa-tachometer me-2"></i>Record Reading
                        </a>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (in_array($_SESSION['role'], ['admin', 'cashier'])): ?>
                    <div class="col-md-3 mb-3">
                        <a href="<?php echo url('payments.php?action=add'); ?>" class="btn btn-warning w-100">
                            <i class="fas fa-credit-card me-2"></i>Process Payment
                        </a>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (in_array($_SESSION['role'], ['admin'])): ?>
                    <div class="col-md-3 mb-3">
                        <a href="<?php echo url('reports.php'); ?>" class="btn btn-info w-100">
                            <i class="fas fa-chart-bar me-2"></i>View Reports
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Revenue Chart
const ctx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [
            <?php foreach ($monthly_revenue as $revenue): ?>
                '<?php echo date('M Y', strtotime($revenue['month'] . '-01')); ?>',
            <?php endforeach; ?>
        ],
        datasets: [{
            label: 'Monthly Revenue',
            data: [
                <?php foreach ($monthly_revenue as $revenue): ?>
                    <?php echo $revenue['total']; ?>,
                <?php endforeach; ?>
            ],
            borderColor: 'rgb(102, 126, 234)',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '₱' + value.toLocaleString();
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Revenue: ₱' + context.parsed.y.toLocaleString();
                    }
                }
            }
        }
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>
