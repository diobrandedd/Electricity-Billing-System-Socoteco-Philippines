<?php
require_once __DIR__ . '/../config/config.php';
requireLogin();

header('Content-Type: application/json');

$bill_id = $_GET['bill_id'] ?? null;

if (!$bill_id) {
    echo json_encode(['success' => false, 'message' => 'Bill ID required']);
    exit;
}

try {
    // Get bill details
    $bill = fetchOne("
        SELECT b.*, c.account_number, c.first_name, c.last_name, c.middle_name
        FROM bills b
        JOIN customers c ON b.customer_id = c.customer_id
        WHERE b.bill_id = ?
    ", [$bill_id]);
    
    if (!$bill) {
        echo json_encode(['success' => false, 'message' => 'Bill not found']);
        exit;
    }
    
    // Get total paid amount
    $total_paid = fetchOne("
        SELECT COALESCE(SUM(amount_paid), 0) as total 
        FROM payments 
        WHERE bill_id = ?
    ", [$bill_id])['total'];
    
    $remaining_balance = $bill['total_amount'] - $total_paid;
    
    echo json_encode([
        'success' => true,
        'bill' => $bill,
        'customer' => [
            'account_number' => $bill['account_number'],
            'name' => $bill['last_name'] . ', ' . $bill['first_name'] . ($bill['middle_name'] ? ' ' . $bill['middle_name'] : '')
        ],
        'total_paid' => $total_paid,
        'remaining_balance' => $remaining_balance
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>
