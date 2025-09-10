<?php
/**
 * Real-time priority number display endpoint
 * Provides current priority number and queue status for public display
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/PriorityNumberGenerator.php';

header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

try {
    $priorityGenerator = new PriorityNumberGenerator();
    
    // Get current priority number and queue status
    $current = $priorityGenerator->getCurrentPriorityNumber();
    $stats = $priorityGenerator->getQueueStatistics();
    
    // Get current customer being served
    $currentCustomer = $priorityGenerator->getCurrentPriorityWithCustomer();
    
    // Get next few priority numbers to be served
    $upcoming = $priorityGenerator->getUpcomingPriorityNumbers(5);
    
    $response = [
        'success' => true,
        'data' => [
            'current_serving' => $current['current_priority_number'] ?? 0,
            'current_customer' => $currentCustomer ? [
                'name' => $currentCustomer['first_name'] . ' ' . $currentCustomer['last_name'],
                'account_number' => $currentCustomer['account_number']
            ] : null,
            'last_served' => $current['last_served_number'] ?? 0,
            'served_today' => $current['served_count'] ?? 0,
            'daily_capacity' => $current['daily_capacity'] ?? 1000,
            'total_pending' => $stats['total_pending'],
            'today_pending' => $stats['today_pending'],
            'upcoming_numbers' => $upcoming,
            'timestamp' => date('Y-m-d H:i:s'),
            'queue_date' => date('Y-m-d')
        ]
    ];
    
    echo json_encode($response);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ]);
}
?>
