<?php
$page_title = 'Payment Management';
require_once 'includes/header.php';
requireRole(['admin', 'cashier']);

$action = $_GET['action'] ?? 'list';
$payment_id = $_GET['id'] ?? null;
$message = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!verifyCSRFToken($csrf_token)) {
        $error = 'Invalid request. Please try again.';
    } else {
        if ($action == 'add') {
            $bill_id = (int)$_POST['bill_id'];
            $payment_date = $_POST['payment_date'];
            $amount_paid = (float)$_POST['amount_paid'];
            $payment_method = $_POST['payment_method'];
            $notes = sanitizeInput($_POST['notes']);
            
            // Validation
            if (empty($bill_id) || empty($payment_date) || $amount_paid <= 0) {
                $error = 'Please fill in all required fields with valid values.';
            } else {
                // Get bill details
                $bill = fetchOne("SELECT * FROM bills WHERE bill_id = ?", [$bill_id]);
                
                if (!$bill) {
                    $error = 'Bill not found.';
                } else {
                    // Check if bill is already fully paid
                    $total_paid = fetchOne("
                        SELECT COALESCE(SUM(amount_paid), 0) as total 
                        FROM payments 
                        WHERE bill_id = ?
                    ", [$bill_id])['total'];
                    
                    $remaining_balance = $bill['total_amount'] - $total_paid;
                    
                    if ($remaining_balance <= 0) {
                        $error = 'This bill is already fully paid.';
                    } else if ($amount_paid > $remaining_balance) {
                        $error = 'Payment amount cannot exceed the remaining balance of ' . formatCurrency($remaining_balance);
                    } else {
                        // Generate OR number
                        $or_number = generateORNumber();
                        
                        // Record payment
                        $sql = "INSERT INTO payments (bill_id, payment_date, amount_paid, payment_method, or_number, cashier_id, notes) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)";
                        
                        $stmt = executeQuery($sql, [
                            $bill_id, $payment_date, $amount_paid, $payment_method, $or_number, $_SESSION['user_id'], $notes
                        ]);
                        
                        $payment_id = $stmt->getConnection()->lastInsertId();
                        
                        // Record payment history
                        $history_sql = "INSERT INTO payment_history (bill_id, payment_id, amount, payment_date) 
                                       VALUES (?, ?, ?, ?)";
                        executeQuery($history_sql, [$bill_id, $payment_id, $amount_paid, $payment_date]);
                        
                        // Update bill status
                        $new_total_paid = $total_paid + $amount_paid;
                        $new_status = ($new_total_paid >= $bill['total_amount']) ? 'paid' : 'pending';
                        
                        $update_sql = "UPDATE bills SET status = ? WHERE bill_id = ?";
                        executeQuery($update_sql, [$new_status, $bill_id]);
                        
                        logActivity('Payment recorded', 'payments', $payment_id);
                        $message = 'Payment recorded successfully. OR Number: ' . $or_number;
                        $action = 'list';
                    }
                }
            }
        }
    }
}

// Get payments list
if ($action == 'list') {
    $search = $_GET['search'] ?? '';
    $date_from = $_GET['date_from'] ?? '';
    $date_to = $_GET['date_to'] ?? '';
    $customer_filter = $_GET['customer_id'] ?? '';
    
    $sql = "SELECT p.*, b.bill_number, b.total_amount, c.account_number, c.first_name, c.last_name, u.full_name as cashier_name
            FROM payments p
            JOIN bills b ON p.bill_id = b.bill_id
            JOIN customers c ON b.customer_id = c.customer_id
            JOIN users u ON p.cashier_id = u.user_id";
    $params = [];
    $conditions = [];
    
    if ($search) {
        $conditions[] = "(p.or_number LIKE ? OR b.bill_number LIKE ? OR c.account_number LIKE ? OR c.first_name LIKE ? OR c.last_name LIKE ?)";
        $search_param = "%{$search}%";
        $params = array_merge($params, [$search_param, $search_param, $search_param, $search_param, $search_param]);
    }
    
    if ($date_from) {
        $conditions[] = "p.payment_date >= ?";
        $params[] = $date_from;
    }
    
    if ($date_to) {
        $conditions[] = "p.payment_date <= ?";
        $params[] = $date_to;
    }
    
    if ($customer_filter) {
        $conditions[] = "b.customer_id = ?";
        $params[] = $customer_filter;
    }
    
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
    
    $sql .= " ORDER BY p.payment_date DESC, p.payment_id DESC";
    
    $payments = fetchAll($sql, $params);
}

// Get customers for filter
$customers = fetchAll("SELECT customer_id, account_number, first_name, last_name 
                      FROM customers WHERE is_active = 1 ORDER BY last_name, first_name");

// Get unpaid bills for payment processing
if ($action == 'add') {
    $bill_filter = $_GET['bill_id'] ?? '';
    $customer_filter = $_GET['customer_id'] ?? '';
    
    $sql = "SELECT b.*, c.account_number, c.first_name, c.last_name, c.meter_number
            FROM bills b
            JOIN customers c ON b.customer_id = c.customer_id
            WHERE b.status IN ('pending', 'overdue')";
    $params = [];
    
    if ($bill_filter) {
        $sql .= " AND b.bill_id = ?";
        $params[] = $bill_filter;
    }
    
    if ($customer_filter) {
        $sql .= " AND b.customer_id = ?";
        $params[] = $customer_filter;
    }
    
    $sql .= " ORDER BY b.due_date ASC";
    
    $unpaid_bills = fetchAll($sql, $params);
}
?>

<?php if ($message): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i><?php echo $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($action == 'list'): ?>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-credit-card me-2"></i>Payment Management
            </h5>
            <a href="?action=add" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Process Payment
            </a>
        </div>
        <div class="card-body">
            <!-- Search and Filter -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <form method="GET" class="d-flex">
                        <input type="hidden" name="action" value="list">
                        <input type="text" class="form-control me-2" name="search" 
                               placeholder="Search payments..." 
                               value="<?php echo htmlspecialchars($search ?? ''); ?>">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" name="date_from" 
                           placeholder="From Date" value="<?php echo $date_from ?? ''; ?>">
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" name="date_to" 
                           placeholder="To Date" value="<?php echo $date_to ?? ''; ?>">
                </div>
                <div class="col-md-2">
                    <select class="form-select" onchange="filterPayments()">
                        <option value="">All Customers</option>
                        <?php foreach ($customers as $customer): ?>
                            <option value="<?php echo $customer['customer_id']; ?>" 
                                    <?php echo $customer_filter == $customer['customer_id'] ? 'selected' : ''; ?>>
                                <?php echo $customer['account_number'] . ' - ' . $customer['last_name'] . ', ' . $customer['first_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-secondary w-100" onclick="filterPayments()">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </div>
            
            <!-- Payments Table -->
            <div class="table-responsive">
                <table class="table table-striped data-table">
                    <thead>
                        <tr>
                            <th>OR #</th>
                            <th>Bill #</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Method</th>
                            <th>Cashier</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td><strong><?php echo $payment['or_number']; ?></strong></td>
                            <td><?php echo $payment['bill_number']; ?></td>
                            <td>
                                <strong><?php echo $payment['account_number']; ?></strong><br>
                                <small><?php echo $payment['last_name'] . ', ' . $payment['first_name']; ?></small>
                            </td>
                            <td><strong><?php echo formatCurrency($payment['amount_paid']); ?></strong></td>
                            <td><?php echo formatDate($payment['payment_date'], 'M d, Y'); ?></td>
                            <td>
                                <span class="badge bg-info">
                                    <?php echo ucfirst($payment['payment_method']); ?>
                                </span>
                            </td>
                            <td><?php echo $payment['cashier_name']; ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="payment_receipt.php?id=<?php echo $payment['payment_id']; ?>" 
                                       class="btn btn-sm btn-outline-primary" target="_blank">
                                        <i class="fas fa-print"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php elseif ($action == 'add'): ?>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-plus me-2"></i>Process Payment
            </h5>
        </div>
        <div class="card-body">
            <!-- Bill Selection -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">Search Bill</label>
                    <select class="form-select" id="billSelect" onchange="loadBillDetails()">
                        <option value="">Select a bill to pay</option>
                        <?php foreach ($unpaid_bills as $bill): ?>
                            <option value="<?php echo $bill['bill_id']; ?>" 
                                    data-customer="<?php echo $bill['customer_id']; ?>"
                                    data-amount="<?php echo $bill['total_amount']; ?>"
                                    data-bill-number="<?php echo $bill['bill_number']; ?>">
                                <?php echo $bill['bill_number'] . ' - ' . $bill['account_number'] . ' - ' . $bill['last_name'] . ', ' . $bill['first_name']; ?>
                                (<?php echo formatCurrency($bill['total_amount']); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Filter by Customer</label>
                    <select class="form-select" onchange="filterBills()">
                        <option value="">All Customers</option>
                        <?php foreach ($customers as $customer): ?>
                            <option value="<?php echo $customer['customer_id']; ?>" 
                                    <?php echo $customer_filter == $customer['customer_id'] ? 'selected' : ''; ?>>
                                <?php echo $customer['account_number'] . ' - ' . $customer['last_name'] . ', ' . $customer['first_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <!-- Bill Details Display -->
            <div id="billDetails" class="alert alert-info" style="display: none;">
                <h6>Bill Details</h6>
                <div id="billInfo"></div>
            </div>
            
            <!-- Payment Form -->
            <form method="POST" id="paymentForm" style="display: none;">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <input type="hidden" name="bill_id" id="bill_id">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="payment_date" class="form-label">Payment Date *</label>
                            <input type="date" class="form-control" id="payment_date" name="payment_date" 
                                   value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method *</label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="cash">Cash</option>
                                <option value="check">Check</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="online">Online Payment</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="amount_paid" class="form-label">Amount Paid *</label>
                            <input type="number" class="form-control" id="amount_paid" name="amount_paid" 
                                   step="0.01" min="0.01" required>
                            <div class="form-text">Maximum: <span id="maxAmount">₱0.00</span></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end">
                    <a href="payments.php" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-credit-card me-2"></i>Process Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<script>
function filterPayments() {
    const dateFrom = document.querySelector('input[name="date_from"]').value;
    const dateTo = document.querySelector('input[name="date_to"]').value;
    const customer = document.querySelector('select[onchange="filterPayments()"]').value;
    const search = document.querySelector('input[name="search"]').value;
    
    let url = 'payments.php?action=list';
    if (search) url += '&search=' + encodeURIComponent(search);
    if (dateFrom) url += '&date_from=' + dateFrom;
    if (dateTo) url += '&date_to=' + dateTo;
    if (customer) url += '&customer_id=' + customer;
    
    window.location.href = url;
}

function filterBills() {
    const customer = document.querySelector('select[onchange="filterBills()"]').value;
    let url = 'payments.php?action=add';
    if (customer) url += '&customer_id=' + customer;
    window.location.href = url;
}

function loadBillDetails() {
    const billSelect = document.getElementById('billSelect');
    const selectedOption = billSelect.options[billSelect.selectedIndex];
    
    if (selectedOption.value) {
        const billId = selectedOption.value;
        const billNumber = selectedOption.dataset.billNumber;
        const amount = selectedOption.dataset.amount;
        
        // Load bill details via AJAX
        fetch('ajax/get_bill_details.php?bill_id=' + billId)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('billInfo').innerHTML = `
                        <strong>Bill #:</strong> ${billNumber}<br>
                        <strong>Customer:</strong> ${data.customer.account_number} - ${data.customer.name}<br>
                        <strong>Total Amount:</strong> ₱${parseFloat(data.bill.total_amount).toLocaleString()}<br>
                        <strong>Amount Paid:</strong> ₱${parseFloat(data.total_paid).toLocaleString()}<br>
                        <strong>Remaining Balance:</strong> ₱${parseFloat(data.remaining_balance).toLocaleString()}
                    `;
                    
                    document.getElementById('billDetails').style.display = 'block';
                    document.getElementById('paymentForm').style.display = 'block';
                    document.getElementById('bill_id').value = billId;
                    document.getElementById('amount_paid').max = data.remaining_balance;
                    document.getElementById('maxAmount').textContent = '₱' + parseFloat(data.remaining_balance).toLocaleString();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    } else {
        document.getElementById('billDetails').style.display = 'none';
        document.getElementById('paymentForm').style.display = 'none';
    }
}
</script>

<?php require_once 'includes/footer.php'; ?>
