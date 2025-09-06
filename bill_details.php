<?php
$page_title = 'Bill Details';
require_once 'includes/header.php';
requireRole(['admin', 'cashier']);

$bill_id = $_GET['id'] ?? null;

if (!$bill_id) {
    redirect('bills.php');
}

// Get bill details
$bill = fetchOne("
    SELECT b.*, c.*, cc.category_name, mr.reading_date, mr.consumption, mr.reading_type
    FROM bills b
    JOIN customers c ON b.customer_id = c.customer_id
    JOIN customer_categories cc ON c.category_id = cc.category_id
    JOIN meter_readings mr ON b.reading_id = mr.reading_id
    WHERE b.bill_id = ?
", [$bill_id]);

if (!$bill) {
    redirect('bills.php');
}

// Get payment history for this bill
$payments = fetchAll("
    SELECT p.*, u.full_name as cashier_name
    FROM payments p
    JOIN users u ON p.cashier_id = u.user_id
    WHERE p.bill_id = ?
    ORDER BY p.payment_date DESC
", [$bill_id]);

// Calculate total paid amount
$total_paid = array_sum(array_column($payments, 'amount_paid'));
$balance = $bill['total_amount'] - $total_paid;
?>

<div class="row">
    <div class="col-md-8">
        <!-- Bill Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-file-invoice me-2"></i>Bill Details - <?php echo $bill['bill_number']; ?>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Customer Information</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Account #:</strong></td>
                                <td><?php echo $bill['account_number']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Name:</strong></td>
                                <td>
                                    <?php echo $bill['last_name'] . ', ' . $bill['first_name']; ?>
                                    <?php if ($bill['middle_name']): ?>
                                        <?php echo ' ' . $bill['middle_name']; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Address:</strong></td>
                                <td>
                                    <?php echo $bill['address']; ?><br>
                                    <?php echo $bill['barangay'] . ', ' . $bill['municipality']; ?><br>
                                    <?php echo $bill['province']; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Meter #:</strong></td>
                                <td><?php echo $bill['meter_number']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Category:</strong></td>
                                <td><span class="badge bg-primary"><?php echo $bill['category_name']; ?></span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Billing Information</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Bill #:</strong></td>
                                <td><?php echo $bill['bill_number']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Billing Period:</strong></td>
                                <td>
                                    <?php echo formatDate($bill['billing_period_start'], 'M d, Y') . ' - ' . formatDate($bill['billing_period_end'], 'M d, Y'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Reading Date:</strong></td>
                                <td><?php echo formatDate($bill['reading_date'], 'M d, Y'); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Consumption:</strong></td>
                                <td>
                                    <span class="badge bg-info">
                                        <?php echo number_format($bill['consumption'], 2); ?> kWh
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Reading Type:</strong></td>
                                <td>
                                    <?php
                                    $type_class = '';
                                    switch ($bill['reading_type']) {
                                        case 'actual':
                                            $type_class = 'bg-success';
                                            break;
                                        case 'estimated':
                                            $type_class = 'bg-warning';
                                            break;
                                        case 'adjusted':
                                            $type_class = 'bg-info';
                                            break;
                                    }
                                    ?>
                                    <span class="badge <?php echo $type_class; ?>">
                                        <?php echo ucfirst($bill['reading_type']); ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Due Date:</strong></td>
                                <td><?php echo formatDate($bill['due_date'], 'M d, Y'); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
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
                            </tr>
                        </table>
                    </div>
                </div>
                
                <hr>
                
                <!-- Bill Breakdown -->
                <h6>Bill Breakdown</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Description</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Generation Charge (<?php echo number_format($bill['consumption'], 2); ?> kWh)</td>
                                <td class="text-end"><?php echo formatCurrency($bill['generation_charge']); ?></td>
                            </tr>
                            <tr>
                                <td>Distribution Charge</td>
                                <td class="text-end"><?php echo formatCurrency($bill['distribution_charge']); ?></td>
                            </tr>
                            <tr>
                                <td>Transmission Charge</td>
                                <td class="text-end"><?php echo formatCurrency($bill['transmission_charge']); ?></td>
                            </tr>
                            <tr>
                                <td>System Loss Charge</td>
                                <td class="text-end"><?php echo formatCurrency($bill['system_loss_charge']); ?></td>
                            </tr>
                            <tr class="table-light">
                                <td><strong>Subtotal</strong></td>
                                <td class="text-end">
                                    <strong><?php echo formatCurrency($bill['generation_charge'] + $bill['distribution_charge'] + $bill['transmission_charge'] + $bill['system_loss_charge']); ?></strong>
                                </td>
                            </tr>
                            <tr>
                                <td>VAT (12%)</td>
                                <td class="text-end"><?php echo formatCurrency($bill['vat']); ?></td>
                            </tr>
                            <tr class="table-success">
                                <td><strong>Total Amount</strong></td>
                                <td class="text-end">
                                    <strong><?php echo formatCurrency($bill['total_amount']); ?></strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-end mt-3">
                    <a href="bill_print.php?id=<?php echo $bill['bill_id']; ?>" 
                       class="btn btn-primary me-2" target="_blank">
                        <i class="fas fa-print me-2"></i>Print Bill
                    </a>
                    <?php if ($balance > 0): ?>
                    <a href="payments.php?action=add&bill_id=<?php echo $bill['bill_id']; ?>" 
                       class="btn btn-success">
                        <i class="fas fa-credit-card me-2"></i>Process Payment
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Payment Summary -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-credit-card me-2"></i>Payment Summary
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center mb-3">
                    <div class="col-6">
                        <h4 class="text-primary"><?php echo formatCurrency($bill['total_amount']); ?></h4>
                        <small class="text-muted">Total Amount</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success"><?php echo formatCurrency($total_paid); ?></h4>
                        <small class="text-muted">Amount Paid</small>
                    </div>
                </div>
                
                <hr>
                
                <div class="text-center">
                    <h4 class="<?php echo $balance > 0 ? 'text-warning' : 'text-success'; ?>">
                        <?php echo formatCurrency($balance); ?>
                    </h4>
                    <small class="text-muted">
                        <?php echo $balance > 0 ? 'Outstanding Balance' : 'Fully Paid'; ?>
                    </small>
                </div>
                
                <?php if ($balance > 0): ?>
                <div class="d-grid mt-3">
                    <a href="payments.php?action=add&bill_id=<?php echo $bill['bill_id']; ?>" 
                       class="btn btn-success">
                        <i class="fas fa-credit-card me-2"></i>Process Payment
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Payment History -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>Payment History
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($payments)): ?>
                    <div class="text-center py-3">
                        <i class="fas fa-credit-card fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No payments recorded</p>
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($payments as $payment): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold"><?php echo $payment['or_number']; ?></div>
                                <small class="text-muted">
                                    <?php echo formatDate($payment['payment_date'], 'M d, Y'); ?><br>
                                    <?php echo $payment['cashier_name']; ?>
                                </small>
                            </div>
                            <span class="badge bg-success rounded-pill">
                                <?php echo formatCurrency($payment['amount_paid']); ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
