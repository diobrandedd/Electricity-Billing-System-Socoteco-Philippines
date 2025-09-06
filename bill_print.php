<?php
require_once 'config/config.php';
requireLogin();

$bill_id = $_GET['id'] ?? null;

if (!$bill_id) {
    die('Bill ID required');
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
    die('Bill not found');
}

// Get company settings
$company_name = getSystemSetting('company_name', 'SOCOTECO II');
$company_address = getSystemSetting('company_address', 'Koronadal City, South Cotabato');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill - <?php echo $bill['bill_number']; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .bill-container {
            max-width: 800px;
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
        .bill-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .bill-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .bill-info-left, .bill-info-right {
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
        .bill-breakdown {
            margin: 30px 0;
        }
        .breakdown-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .breakdown-table th,
        .breakdown-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .breakdown-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        .breakdown-table .text-right {
            text-align: right;
        }
        .breakdown-table .total-row {
            background-color: #e8f5e8;
            font-weight: bold;
        }
        .breakdown-table .subtotal-row {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }
        .status-overdue {
            background-color: #f8d7da;
            color: #721c24;
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
            .bill-container {
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
        <i class="fas fa-print"></i> Print Bill
    </button>
    
    <div class="bill-container">
        <!-- Header -->
        <div class="header">
            <div class="company-name"><?php echo $company_name; ?></div>
            <div class="company-address"><?php echo $company_address; ?></div>
            <div class="bill-title">ELECTRIC BILL</div>
        </div>
        
        <!-- Bill Information -->
        <div class="bill-info">
            <div class="bill-info-left">
                <div class="info-section">
                    <h4>Customer Information</h4>
                    <div class="info-row">
                        <div class="info-label">Account #:</div>
                        <div class="info-value"><?php echo $bill['account_number']; ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Name:</div>
                        <div class="info-value">
                            <?php echo $bill['last_name'] . ', ' . $bill['first_name']; ?>
                            <?php if ($bill['middle_name']): ?>
                                <?php echo ' ' . $bill['middle_name']; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Address:</div>
                        <div class="info-value">
                            <?php echo $bill['address']; ?><br>
                            <?php echo $bill['barangay'] . ', ' . $bill['municipality']; ?><br>
                            <?php echo $bill['province']; ?>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Meter #:</div>
                        <div class="info-value"><?php echo $bill['meter_number']; ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Category:</div>
                        <div class="info-value"><?php echo $bill['category_name']; ?></div>
                    </div>
                </div>
            </div>
            
            <div class="bill-info-right">
                <div class="info-section">
                    <h4>Billing Information</h4>
                    <div class="info-row">
                        <div class="info-label">Bill #:</div>
                        <div class="info-value"><?php echo $bill['bill_number']; ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Billing Period:</div>
                        <div class="info-value">
                            <?php echo formatDate($bill['billing_period_start'], 'M d, Y') . ' - ' . formatDate($bill['billing_period_end'], 'M d, Y'); ?>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Reading Date:</div>
                        <div class="info-value"><?php echo formatDate($bill['reading_date'], 'M d, Y'); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Consumption:</div>
                        <div class="info-value"><?php echo number_format($bill['consumption'], 2); ?> kWh</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Due Date:</div>
                        <div class="info-value"><?php echo formatDate($bill['due_date'], 'M d, Y'); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Status:</div>
                        <div class="info-value">
                            <span class="status-badge status-<?php echo $bill['status']; ?>">
                                <?php echo ucfirst($bill['status']); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bill Breakdown -->
        <div class="bill-breakdown">
            <h4 style="color: #667eea; margin-bottom: 15px;">Bill Breakdown</h4>
            <table class="breakdown-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Generation Charge (<?php echo number_format($bill['consumption'], 2); ?> kWh)</td>
                        <td class="text-right"><?php echo formatCurrency($bill['generation_charge']); ?></td>
                    </tr>
                    <tr>
                        <td>Distribution Charge</td>
                        <td class="text-right"><?php echo formatCurrency($bill['distribution_charge']); ?></td>
                    </tr>
                    <tr>
                        <td>Transmission Charge</td>
                        <td class="text-right"><?php echo formatCurrency($bill['transmission_charge']); ?></td>
                    </tr>
                    <tr>
                        <td>System Loss Charge</td>
                        <td class="text-right"><?php echo formatCurrency($bill['system_loss_charge']); ?></td>
                    </tr>
                    <tr class="subtotal-row">
                        <td><strong>Subtotal</strong></td>
                        <td class="text-right">
                            <strong><?php echo formatCurrency($bill['generation_charge'] + $bill['distribution_charge'] + $bill['transmission_charge'] + $bill['system_loss_charge']); ?></strong>
                        </td>
                    </tr>
                    <tr>
                        <td>VAT (12%)</td>
                        <td class="text-right"><?php echo formatCurrency($bill['vat']); ?></td>
                    </tr>
                    <tr class="total-row">
                        <td><strong>TOTAL AMOUNT</strong></td>
                        <td class="text-right">
                            <strong><?php echo formatCurrency($bill['total_amount']); ?></strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>This is a computer-generated bill. No signature required.</p>
            <p>For inquiries, please contact our customer service.</p>
            <p>Generated on: <?php echo date('M d, Y g:i A'); ?></p>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>
