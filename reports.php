<?php
$page_title = 'Reports & Analytics';
require_once 'includes/header.php';
requireRole(['admin']);

$report_type = $_GET['report'] ?? 'dashboard';
$date_from = $_GET['date_from'] ?? date('Y-m-01');
$date_to = $_GET['date_to'] ?? date('Y-m-d');
$message = '';

// Get report data based on type
switch ($report_type) {
    case 'collection':
        $collection_data = fetchAll("
            SELECT 
                DATE(p.payment_date) as payment_date,
                COUNT(*) as payment_count,
                SUM(p.amount_paid) as total_collection
            FROM payments p
            WHERE p.payment_date BETWEEN ? AND ?
            GROUP BY DATE(p.payment_date)
            ORDER BY payment_date DESC
        ", [$date_from, $date_to]);
        
        $total_collection = array_sum(array_column($collection_data, 'total_collection'));
        $total_payments = array_sum(array_column($collection_data, 'payment_count'));
        break;
        
    case 'aging':
        $aging_data = fetchAll("
            SELECT 
                c.account_number,
                c.first_name,
                c.last_name,
                b.bill_number,
                b.total_amount,
                b.due_date,
                DATEDIFF(CURDATE(), b.due_date) as days_overdue,
                CASE 
                    WHEN DATEDIFF(CURDATE(), b.due_date) <= 0 THEN 'Current'
                    WHEN DATEDIFF(CURDATE(), b.due_date) <= 30 THEN '1-30 Days'
                    WHEN DATEDIFF(CURDATE(), b.due_date) <= 60 THEN '31-60 Days'
                    WHEN DATEDIFF(CURDATE(), b.due_date) <= 90 THEN '61-90 Days'
                    ELSE 'Over 90 Days'
                END as aging_category
            FROM bills b
            JOIN customers c ON b.customer_id = c.customer_id
            WHERE b.status IN ('pending', 'overdue')
            ORDER BY days_overdue DESC
        ");
        
        $aging_summary = [];
        foreach ($aging_data as $row) {
            $category = $row['aging_category'];
            if (!isset($aging_summary[$category])) {
                $aging_summary[$category] = ['count' => 0, 'amount' => 0];
            }
            $aging_summary[$category]['count']++;
            $aging_summary[$category]['amount'] += $row['total_amount'];
        }
        break;
        
    case 'revenue':
        $revenue_data = fetchAll("
            SELECT 
                DATE_FORMAT(p.payment_date, '%Y-%m') as month,
                SUM(p.amount_paid) as total_revenue,
                COUNT(*) as payment_count
            FROM payments p
            WHERE p.payment_date BETWEEN ? AND ?
            GROUP BY DATE_FORMAT(p.payment_date, '%Y-%m')
            ORDER BY month DESC
        ", [$date_from, $date_to]);
        
        $total_revenue = array_sum(array_column($revenue_data, 'total_revenue'));
        break;
        
    case 'usage':
        $usage_data = fetchAll("
            SELECT 
                c.barangay,
                c.municipality,
                cc.category_name,
                COUNT(DISTINCT c.customer_id) as customer_count,
                AVG(mr.consumption) as avg_consumption,
                SUM(mr.consumption) as total_consumption
            FROM meter_readings mr
            JOIN customers c ON mr.customer_id = c.customer_id
            JOIN customer_categories cc ON c.category_id = cc.category_id
            WHERE mr.reading_date BETWEEN ? AND ?
            GROUP BY c.barangay, c.municipality, cc.category_name
            ORDER BY total_consumption DESC
        ", [$date_from, $date_to]);
        break;
        
    case 'customers':
        $customer_data = fetchAll("
            SELECT 
                cc.category_name,
                COUNT(*) as customer_count,
                COUNT(CASE WHEN c.is_active = 1 THEN 1 END) as active_customers
            FROM customers c
            JOIN customer_categories cc ON c.category_id = cc.category_id
            GROUP BY cc.category_name
            ORDER BY customer_count DESC
        ");
        break;
}

// Get system statistics
$stats = [
    'total_customers' => fetchOne("SELECT COUNT(*) as count FROM customers WHERE is_active = 1")['count'],
    'total_bills' => fetchOne("SELECT COUNT(*) as count FROM bills")['count'],
    'total_payments' => fetchOne("SELECT COUNT(*) as count FROM payments")['count'],
    'total_revenue' => fetchOne("SELECT COALESCE(SUM(amount_paid), 0) as total FROM payments")['total'],
    'overdue_bills' => fetchOne("SELECT COUNT(*) as count FROM bills WHERE status = 'overdue' OR (status = 'pending' AND due_date < CURDATE())")['count'],
    'pending_bills' => fetchOne("SELECT COUNT(*) as count FROM bills WHERE status = 'pending'")['count']
];
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Reports & Analytics
                </h5>
            </div>
            <div class="card-body">
                <!-- Report Navigation -->
                <ul class="nav nav-tabs mb-4" id="reportTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?php echo $report_type == 'dashboard' ? 'active' : ''; ?>" 
                                onclick="loadReport('dashboard')">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?php echo $report_type == 'collection' ? 'active' : ''; ?>" 
                                onclick="loadReport('collection')">
                            <i class="fas fa-money-bill-wave me-2"></i>Collection Report
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?php echo $report_type == 'aging' ? 'active' : ''; ?>" 
                                onclick="loadReport('aging')">
                            <i class="fas fa-clock me-2"></i>Aging Report
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?php echo $report_type == 'revenue' ? 'active' : ''; ?>" 
                                onclick="loadReport('revenue')">
                            <i class="fas fa-chart-line me-2"></i>Revenue Report
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?php echo $report_type == 'usage' ? 'active' : ''; ?>" 
                                onclick="loadReport('usage')">
                            <i class="fas fa-bolt me-2"></i>Usage Report
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?php echo $report_type == 'customers' ? 'active' : ''; ?>" 
                                onclick="loadReport('customers')">
                            <i class="fas fa-users me-2"></i>Customer Report
                        </button>
                    </li>
                </ul>
                
                <!-- Date Range Filter -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label class="form-label">From Date</label>
                        <input type="date" class="form-control" id="date_from" value="<?php echo $date_from; ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">To Date</label>
                        <input type="date" class="form-control" id="date_to" value="<?php echo $date_to; ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <button class="btn btn-primary d-block" onclick="applyDateFilter()">
                            <i class="fas fa-filter me-2"></i>Apply Filter
                        </button>
                    </div>
                </div>
                
                <!-- Report Content -->
                <div id="reportContent">
                    <?php if ($report_type == 'dashboard'): ?>
                        <!-- Dashboard Overview -->
                        <div class="row">
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
                                                <div class="text-xs font-weight-bold text-uppercase mb-1">Total Bills</div>
                                                <div class="h5 mb-0 font-weight-bold"><?php echo number_format($stats['total_bills']); ?></div>
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
                                                <div class="text-xs font-weight-bold text-uppercase mb-1">Total Revenue</div>
                                                <div class="h5 mb-0 font-weight-bold"><?php echo formatCurrency($stats['total_revenue']); ?></div>
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
                        
                    <?php elseif ($report_type == 'collection'): ?>
                        <!-- Collection Report -->
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="card-title mb-0">Daily Collection Report</h6>
                                <div>
                                    <span class="badge bg-success me-2">Total: <?php echo formatCurrency($total_collection); ?></span>
                                    <span class="badge bg-info">Payments: <?php echo number_format($total_payments); ?></span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Payment Count</th>
                                                <th>Total Collection</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($collection_data as $row): ?>
                                            <tr>
                                                <td><?php echo formatDate($row['payment_date'], 'M d, Y'); ?></td>
                                                <td><?php echo number_format($row['payment_count']); ?></td>
                                                <td><?php echo formatCurrency($row['total_collection']); ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                    <?php elseif ($report_type == 'aging'): ?>
                        <!-- Aging Report -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Aging Report - Outstanding Bills</h6>
                            </div>
                            <div class="card-body">
                                <!-- Aging Summary -->
                                <div class="row mb-4">
                                    <?php foreach ($aging_summary as $category => $data): ?>
                                    <div class="col-md-2">
                                        <div class="text-center">
                                            <h5><?php echo $data['count']; ?></h5>
                                            <small class="text-muted"><?php echo $category; ?></small>
                                            <br>
                                            <small class="text-primary"><?php echo formatCurrency($data['amount']); ?></small>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-striped data-table">
                                        <thead>
                                            <tr>
                                                <th>Account #</th>
                                                <th>Customer</th>
                                                <th>Bill #</th>
                                                <th>Amount</th>
                                                <th>Due Date</th>
                                                <th>Days Overdue</th>
                                                <th>Category</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($aging_data as $row): ?>
                                            <tr>
                                                <td><?php echo $row['account_number']; ?></td>
                                                <td><?php echo $row['last_name'] . ', ' . $row['first_name']; ?></td>
                                                <td><?php echo $row['bill_number']; ?></td>
                                                <td><?php echo formatCurrency($row['total_amount']); ?></td>
                                                <td><?php echo formatDate($row['due_date'], 'M d, Y'); ?></td>
                                                <td>
                                                    <span class="badge <?php echo $row['days_overdue'] > 0 ? 'bg-danger' : 'bg-success'; ?>">
                                                        <?php echo $row['days_overdue']; ?> days
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-warning"><?php echo $row['aging_category']; ?></span>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                    <?php elseif ($report_type == 'revenue'): ?>
                        <!-- Revenue Report -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Monthly Revenue Report</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <canvas id="revenueChart" height="100"></canvas>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Month</th>
                                                        <th>Revenue</th>
                                                        <th>Payments</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($revenue_data as $row): ?>
                                                    <tr>
                                                        <td><?php echo date('M Y', strtotime($row['month'] . '-01')); ?></td>
                                                        <td><?php echo formatCurrency($row['total_revenue']); ?></td>
                                                        <td><?php echo number_format($row['payment_count']); ?></td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    <?php elseif ($report_type == 'usage'): ?>
                        <!-- Usage Report -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Usage Report by Location and Category</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped data-table">
                                        <thead>
                                            <tr>
                                                <th>Location</th>
                                                <th>Category</th>
                                                <th>Customers</th>
                                                <th>Avg Consumption</th>
                                                <th>Total Consumption</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($usage_data as $row): ?>
                                            <tr>
                                                <td><?php echo $row['barangay'] . ', ' . $row['municipality']; ?></td>
                                                <td><span class="badge bg-primary"><?php echo $row['category_name']; ?></span></td>
                                                <td><?php echo number_format($row['customer_count']); ?></td>
                                                <td><?php echo number_format($row['avg_consumption'], 2); ?> kWh</td>
                                                <td><?php echo number_format($row['total_consumption'], 2); ?> kWh</td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                    <?php elseif ($report_type == 'customers'): ?>
                        <!-- Customer Report -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Customer Distribution by Category</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <canvas id="customerChart" height="200"></canvas>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Category</th>
                                                        <th>Total Customers</th>
                                                        <th>Active Customers</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($customer_data as $row): ?>
                                                    <tr>
                                                        <td><?php echo $row['category_name']; ?></td>
                                                        <td><?php echo number_format($row['customer_count']); ?></td>
                                                        <td><?php echo number_format($row['active_customers']); ?></td>
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
            </div>
        </div>
    </div>
</div>

<script>
function loadReport(reportType) {
    const dateFrom = document.getElementById('date_from').value;
    const dateTo = document.getElementById('date_to').value;
    
    let url = `reports.php?report=${reportType}`;
    if (dateFrom) url += `&date_from=${dateFrom}`;
    if (dateTo) url += `&date_to=${dateTo}`;
    
    window.location.href = url;
}

function applyDateFilter() {
    const reportType = '<?php echo $report_type; ?>';
    loadReport(reportType);
}

// Revenue Chart
<?php if ($report_type == 'revenue' && !empty($revenue_data)): ?>
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: [
            <?php foreach ($revenue_data as $row): ?>
                '<?php echo date('M Y', strtotime($row['month'] . '-01')); ?>',
            <?php endforeach; ?>
        ],
        datasets: [{
            label: 'Monthly Revenue',
            data: [
                <?php foreach ($revenue_data as $row): ?>
                    <?php echo $row['total_revenue']; ?>,
                <?php endforeach; ?>
            ],
            backgroundColor: 'rgba(102, 126, 234, 0.8)',
            borderColor: 'rgba(102, 126, 234, 1)',
            borderWidth: 1
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
        }
    }
});
<?php endif; ?>

// Customer Chart
<?php if ($report_type == 'customers' && !empty($customer_data)): ?>
const customerCtx = document.getElementById('customerChart').getContext('2d');
const customerChart = new Chart(customerCtx, {
    type: 'doughnut',
    data: {
        labels: [
            <?php foreach ($customer_data as $row): ?>
                '<?php echo $row['category_name']; ?>',
            <?php endforeach; ?>
        ],
        datasets: [{
            data: [
                <?php foreach ($customer_data as $row): ?>
                    <?php echo $row['customer_count']; ?>,
                <?php endforeach; ?>
            ],
            backgroundColor: [
                '#667eea',
                '#764ba2',
                '#f093fb',
                '#f5576c',
                '#4facfe'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
<?php endif; ?>
</script>

<?php require_once 'includes/footer.php'; ?>
