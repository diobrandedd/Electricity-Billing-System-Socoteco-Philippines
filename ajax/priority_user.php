<?php
/**
 * Priority Number AJAX endpoint for User Index Page
 * Handles priority number operations for the public user interface
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/PriorityNumberGenerator.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    $priorityGenerator = new PriorityNumberGenerator();
    
    switch ($action) {
        case 'get_queue_status':
            handleGetQueueStatus($priorityGenerator);
            break;
            
        case 'generate_priority':
            handleGeneratePriority($priorityGenerator);
            break;
            
        case 'check_existing':
            handleCheckExisting($priorityGenerator);
            break;
            
        case 'cancel_priority':
            handleCancelPriority($priorityGenerator);
            break;
            
        default:
            echo json_encode(['success' => false, 'error' => 'Invalid action']);
            break;
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

/**
 * Handle getting queue status
 */
function handleGetQueueStatus($priorityGenerator) {
    $current = $priorityGenerator->getCurrentPriorityNumber();
    $stats = $priorityGenerator->getQueueStatistics();
    
    // Get the next priority number that will be assigned
    $nextPriorityNumber = $priorityGenerator->getNextPriorityNumber();
    
    // Calculate estimated service day for next priority number
    $dailyCapacity = $current['daily_capacity'] ?? 1000;
    $dayNumber = ceil($nextPriorityNumber / $dailyCapacity);
    $estimatedServiceDate = date('Y-m-d', strtotime("+{$dayNumber} days"));
    
    echo json_encode([
        'success' => true,
        'data' => [
            'next_priority_number' => $nextPriorityNumber,
            'estimated_service_date' => $estimatedServiceDate,
            'estimated_day_number' => $dayNumber,
            'current_serving' => $current['current_priority_number'] ?? 0,
            'served_today' => $current['served_count'] ?? 0,
            'today_pending' => $stats['today_pending'],
            'daily_capacity' => $dailyCapacity,
            'total_pending' => $stats['total_pending']
        ]
    ]);
}

/**
 * Handle priority number generation
 */
function handleGeneratePriority($priorityGenerator) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'error' => 'POST method required']);
        return;
    }
    
    $customerId = $_POST['customer_id'] ?? null;
    $preferredDate = $_POST['preferred_date'] ?? null;
    
    if (!$customerId) {
        echo json_encode(['success' => false, 'error' => 'Customer ID is required']);
        return;
    }
    
    // Validate CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        echo json_encode(['success' => false, 'error' => 'Invalid security token']);
        return;
    }
    
    $result = $priorityGenerator->generatePriorityNumber($customerId, $preferredDate);
    
    if ($result['success']) {
        // Log activity
        logActivity('Priority number generated via user interface', 'priority_numbers', $result['priority_id']);
    }
    
    echo json_encode($result);
}

/**
 * Handle checking existing priority number
 */
function handleCheckExisting($priorityGenerator) {
    $customerId = $_GET['customer_id'] ?? null;
    
    if (!$customerId) {
        echo json_encode(['success' => false, 'error' => 'Customer ID is required']);
        return;
    }
    
    $existing = $priorityGenerator->getCustomerPendingPriority($customerId);
    
    if ($existing) {
        echo json_encode([
            'success' => true,
            'has_existing' => true,
            'data' => $existing
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'has_existing' => false
        ]);
    }
}

/**
 * Handle cancelling priority number
 */
function handleCancelPriority($priorityGenerator) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'error' => 'POST method required']);
        return;
    }
    
    $priorityNumber = $_POST['priority_number'] ?? null;
    $reason = $_POST['reason'] ?? 'Cancelled by customer';
    
    if (!$priorityNumber) {
        echo json_encode(['success' => false, 'error' => 'Priority number is required']);
        return;
    }
    
    // Validate CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        echo json_encode(['success' => false, 'error' => 'Invalid security token']);
        return;
    }
    
    $result = $priorityGenerator->cancelPriorityNumber($priorityNumber, $reason);
    
    if ($result['success']) {
        // Log activity
        logActivity('Priority number cancelled via user interface', 'priority_numbers', null, null, ['priority_number' => $priorityNumber, 'reason' => $reason]);
    }
    
    echo json_encode($result);
}
?>
