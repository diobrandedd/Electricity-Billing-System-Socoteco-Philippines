<?php
require_once 'config/config.php';
requireLogin();

$payment_id = $_GET['id'] ?? null;

if (!$payment_id) {
    die('Payment ID required');
}

// Get payment details
$payment = fetchOne("
    SELECT p.*, b.bill_number, b.total_amount, c.*, u.full_name as cashier_name
    FROM payments p
    JOIN bills b ON p.bill_id = b.bill_id
    JOIN customers c ON b.customer_id = c.customer_id
    JOIN users u ON p.cashier_id = u.user_id
    WHERE p.payment_id = ?
", [$payment_id]);

if (!$payment) {
    die('Payment not found');
}

// Get total paid amount for this bill
$total_paid = fetchOne("
    SELECT COALESCE(SUM(amount_paid), 0) as total 
    FROM payments 
    WHERE bill_id = ?
", [$payment['bill_id']])['total'];

$remaining_balance = $payment['total_amount'] - $total_paid;

// Get company settings
$company_name = getSystemSetting('company_name', 'SOCOTECO II');
$company_address = getSystemSetting('company_address', 'Koronadal City, South Cotabato');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Receipt - <?php echo $payment['or_number']; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .receipt-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #667eea;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }
        .company-address {
            color: #666;
            font-size: 14px;
        }
        .receipt-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .receipt-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .receipt-info-left, .receipt-info-right {
            flex: 1;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-section h4 {
            color: #667eea;
            font-size: 16px;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .info-row {
            display: flex;
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            width: 120px;
            color: #555;
        }
        .info-value {
            flex: 1;
            color: #333;
        }
        .payment-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .payment-amount {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
            margin: 20px 0;
            padding: 15px;
            background-color: #d4edda;
            border-radius: 8px;
        }
        .balance-info {
            background-color: #fff3cd;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #ffc107;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #667eea;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .print-button:hover {
            background: #5a6fd8;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .receipt-container {
                box-shadow: none;
                border-radius: 0;
                padding: 0;
            }
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">
        <i class="fas fa-print"></i> Print Receipt
    </button>
    
    <div class="receipt-container">
        <!-- Header -->
        <div class="header">
            <div class="company-name"><?php echo $company_name; ?></div>
            <div class="company-address"><?php echo $company_address; ?></div>
            <div class="receipt-title">OFFICIAL RECEIPT</div>
        </div>
        
        <!-- Receipt Information -->
        <div class="receipt-info">
            <div class="receipt-info-left">
                <div class="info-section">
                    <h4>Customer Information</h4>
                    <div class="info-row">
                        <div class="info-label">Account #:</div>
                        <div class="info-value"><?php echo $payment['account_number']; ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Name:</div>
                        <div class="info-value">
                            <?php echo $payment['last_name'] . ', ' . $payment['first_name']; ?>
                            <?php if ($payment['middle_name']): ?>
                                <?php echo ' ' . $payment['middle_name']; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Address:</div>
                        <div class="info-value">
                            <?php echo $payment['address']; ?><br>
                            <?php echo $payment['barangay'] . ', ' . $payment['municipality']; ?><br>
                            <?php echo $payment['province']; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="receipt-info-right">
                <div class="info-section">
                    <h4>Receipt Information</h4>
                    <div class="info-row">
                        <div class="info-label">OR #:</div>
                        <div class="info-value"><?php echo $payment['or_number']; ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Bill #:</div>
                        <div class="info-value"><?php echo $payment['bill_number']; ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Date:</div>
                        <div class="info-value"><?php echo formatDate($payment['payment_date'], 'M d, Y'); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Time:</div>
                        <div class="info-value"><?php echo formatDate($payment['created_at'], 'g:i A'); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Cashier:</div>
                        <div class="info-value"><?php echo $payment['cashier_name']; ?></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Payment Details -->
        <div class="payment-details">
            <h4 style="color: #667eea; margin-bottom: 15px;">Payment Details</h4>
            <div class="info-row">
                <div class="info-label">Payment Method:</div>
                <div class="info-value"><?php echo ucfirst($payment['payment_method']); ?></div>
            </div>
            <?php if ($payment['notes']): ?>
            <div class="info-row">
                <div class="info-label">Notes:</div>
                <div class="info-value"><?php echo $payment['notes']; ?></div>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Payment Amount -->
        <div class="payment-amount">
            Amount Paid: <?php echo formatCurrency($payment['amount_paid']); ?>
        </div>
        
        <!-- Balance Information -->
        <?php if ($remaining_balance > 0): ?>
        <div class="balance-info">
            <strong>Remaining Balance:</strong> <?php echo formatCurrency($remaining_balance); ?>
            <br><small>Please pay the remaining balance to avoid penalties.</small>
        </div>
        <?php else: ?>
        <div class="balance-info" style="background-color: #d4edda; border-left-color: #28a745;">
            <strong>Bill Status:</strong> Fully Paid
            <br><small>Thank you for your payment!</small>
        </div>
        <?php endif; ?>
        
        <!-- Footer -->
        <div class="footer">
            <p>This is an official receipt. Please keep this for your records.</p>
            <p>For inquiries, please contact our customer service.</p>
            <p>Generated on: <?php echo date('M d, Y g:i A'); ?></p>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>
