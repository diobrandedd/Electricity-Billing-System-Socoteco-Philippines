<?php
$page_title = 'Customer Details';
require_once 'includes/header.php';
requireRole(['admin', 'cashier']);

$customer_id = $_GET['id'] ?? null;

if (!$customer_id) {
    redirect('customers.php');
}

// Get customer details
$customer = fetchOne("
    SELECT c.*, cc.category_name 
    FROM customers c 
    JOIN customer_categories cc ON c.category_id = cc.category_id 
    WHERE c.customer_id = ?
", [$customer_id]);

if (!$customer) {
    redirect('customers.php');
}

// Get customer's billing history
$bills = fetchAll("
    SELECT b.*, mr.reading_date, mr.consumption
    FROM bills b
    JOIN meter_readings mr ON b.reading_id = mr.reading_id
    WHERE b.customer_id = ?
    ORDER BY b.created_at DESC
    LIMIT 10
", [$customer_id]);

// Get customer's payment history
$payments = fetchAll("
    SELECT p.*, b.bill_number, u.full_name as cashier_name
    FROM payments p
    JOIN bills b ON p.bill_id = b.bill_id
    JOIN users u ON p.cashier_id = u.user_id
    WHERE b.customer_id = ?
    ORDER BY p.payment_date DESC
    LIMIT 10
", [$customer_id]);

// Get latest meter reading
$latest_reading = fetchOne("
    SELECT * FROM meter_readings 
    WHERE customer_id = ? 
    ORDER BY reading_date DESC 
    LIMIT 1
", [$customer_id]);

// Get outstanding balance
$outstanding = fetchOne("
    SELECT 
        COUNT(*) as total_bills,
        SUM(total_amount) as total_amount,
        SUM(CASE WHEN status = 'paid' THEN total_amount ELSE 0 END) as paid_amount
    FROM bills 
    WHERE customer_id = ? AND status IN ('pending', 'overdue')
", [$customer_id]);
?>

<div class="row">
    <div class="col-md-4">
        <!-- Customer Information Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user me-2"></i>Customer Information
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-user fa-2x text-white"></i>
                    </div>
                </div>
                
                <h6 class="text-center mb-3">
                    <?php echo $customer['last_name'] . ', ' . $customer['first_name']; ?>
                    <?php if ($customer['middle_name']): ?>
                        <?php echo ' ' . $customer['middle_name']; ?>
                    <?php endif; ?>
                </h6>
                
                <table class="table table-sm">
                    <tr>
                        <td><strong>Account #:</strong></td>
                        <td><?php echo $customer['account_number']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Meter #:</strong></td>
                        <td><?php echo $customer['meter_number']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Category:</strong></td>
                        <td><span class="badge bg-primary"><?php echo $customer['category_name']; ?></span></td>
                    </tr>
                    <tr>
                        <td><strong>Address:</strong></td>
                        <td>
                            <?php echo $customer['address']; ?><br>
                            <?php echo $customer['barangay'] . ', ' . $customer['municipality']; ?><br>
                            <?php echo $customer['province']; ?>
                        </td>
                    </tr>
                    <?php if ($customer['contact_number']): ?>
                    <tr>
                        <td><strong>Contact:</strong></td>
                        <td><?php echo $customer['contact_number']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if ($customer['email']): ?>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td><?php echo $customer['email']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td><strong>Connected:</strong></td>
                        <td><?php echo formatDate($customer['connection_date'], 'M d, Y'); ?></td>
                    </tr>
                </table>
                
                <div class="d-grid gap-2">
                    <a href="customers.php?action=edit&id=<?php echo $customer['customer_id']; ?>" 
                       class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit Customer
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Account Summary Card -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Account Summary
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-primary"><?php echo $outstanding['total_bills']; ?></h4>
                            <small class="text-muted">Outstanding Bills</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-warning"><?php echo formatCurrency($outstanding['total_amount'] - $outstanding['paid_amount']); ?></h4>
                        <small class="text-muted">Outstanding Balance</small>
                    </div>
                </div>
                
                <?php if ($latest_reading): ?>
                <hr>
                <div class="text-center">
                    <h6>Latest Reading</h6>
                    <p class="mb-1">
                        <strong><?php echo $latest_reading['current_reading']; ?> kWh</strong>
                    </p>
                    <small class="text-muted">
                        <?php echo formatDate($latest_reading['reading_date'], 'M d, Y'); ?>
                    </small>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <!-- Billing History -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-file-invoice me-2"></i>Billing History
                </h5>
                <a href="bills.php?customer_id=<?php echo $customer_id; ?>" class="btn btn-sm btn-outline-primary">
                    View All Bills
                </a>
            </div>
            <div class="card-body">
                <?php if (empty($bills)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No billing history found.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Bill #</th>
                                    <th>Period</th>
                                    <th>Consumption</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Due Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bills as $bill): ?>
                                <tr>
                                    <td><?php echo $bill['bill_number']; ?></td>
                                    <td>
                                        <?php echo formatDate($bill['billing_period_start'], 'M d') . ' - ' . formatDate($bill['billing_period_end'], 'M d, Y'); ?>
                                    </td>
                                    <td><?php echo $bill['consumption']; ?> kWh</td>
                                    <td><?php echo formatCurrency($bill['total_amount']); ?></td>
                                    <td>
                                        <?php
                                        $status_class = '';
                                        switch ($bill['status']) {
                                            case 'paid':
                                                $status_class = 'bg-success';
                                                break;
                                            case 'overdue':
                                                $status_class = 'bg-danger';
                                                break;
                                            case 'pending':
                                                $status_class = 'bg-warning';
                                                break;
                                            default:
                                                $status_class = 'bg-secondary';
                                        }
                                        ?>
                                        <span class="badge <?php echo $status_class; ?>">
                                            <?php echo ucfirst($bill['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo formatDate($bill['due_date'], 'M d, Y'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Payment History -->
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-credit-card me-2"></i>Payment History
                </h5>
                <a href="payments.php?customer_id=<?php echo $customer_id; ?>" class="btn btn-sm btn-outline-primary">
                    View All Payments
                </a>
            </div>
            <div class="card-body">
                <?php if (empty($payments)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No payment history found.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>OR #</th>
                                    <th>Bill #</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Cashier</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($payments as $payment): ?>
                                <tr>
                                    <td><?php echo $payment['or_number']; ?></td>
                                    <td><?php echo $payment['bill_number']; ?></td>
                                    <td><?php echo formatCurrency($payment['amount_paid']); ?></td>
                                    <td><?php echo formatDate($payment['payment_date'], 'M d, Y'); ?></td>
                                    <td><?php echo $payment['cashier_name']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
