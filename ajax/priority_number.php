<?php
/**
 * AJAX endpoint for priority number operations
 * Handles priority number generation, status updates, and queue management
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/PriorityNumberGenerator.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'error' => 'Authentication required']);
    exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    $priorityGenerator = new PriorityNumberGenerator();
    
    switch ($action) {
        case 'generate':
            handleGeneratePriorityNumber($priorityGenerator);
            break;
            
        case 'get_current':
            handleGetCurrentPriorityNumber($priorityGenerator);
            break;
            
        case 'update_current':
            handleUpdateCurrentPriorityNumber($priorityGenerator);
            break;
            
        case 'get_details':
            handleGetPriorityDetails($priorityGenerator);
            break;
            
        case 'get_queue_stats':
            handleGetQueueStatistics($priorityGenerator);
            break;
            
        case 'get_upcoming':
            handleGetUpcomingPriorityNumbers($priorityGenerator);
            break;
            
        case 'get_current_with_customer':
            handleGetCurrentWithCustomer($priorityGenerator);
            break;
            
        case 'cancel':
            handleCancelPriorityNumber($priorityGenerator);
            break;
            
        case 'get_customer_history':
            handleGetCustomerHistory($priorityGenerator);
            break;
            
        case 'clear_expired':
            handleClearExpiredNumbers($priorityGenerator);
            break;
            
        case 'reset_queue':
            handleResetQueue($priorityGenerator);
            break;
            
        default:
            echo json_encode(['success' => false, 'error' => 'Invalid action']);
            break;
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

/**
 * Handle priority number generation
 */
function handleGeneratePriorityNumber($priorityGenerator) {
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
        logActivity('Priority number generated', 'priority_numbers', $result['priority_id']);
    }
    
    echo json_encode($result);
}

/**
 * Handle getting current priority number
 */
function handleGetCurrentPriorityNumber($priorityGenerator) {
    $result = $priorityGenerator->getCurrentPriorityNumber();
    echo json_encode(['success' => true, 'data' => $result]);
}

/**
 * Handle updating current priority number
 */
function handleUpdateCurrentPriorityNumber($priorityGenerator) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'error' => 'POST method required']);
        return;
    }
    
    // Check if user has admin or cashier role
    if (!in_array($_SESSION['role'], ['admin', 'cashier'])) {
        echo json_encode(['success' => false, 'error' => 'Insufficient permissions']);
        return;
    }
    
    $newNumber = $_POST['priority_number'] ?? null;
    
    if (!$newNumber) {
        echo json_encode(['success' => false, 'error' => 'Priority number is required']);
        return;
    }
    
    // Validate CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        echo json_encode(['success' => false, 'error' => 'Invalid security token']);
        return;
    }
    
    $result = $priorityGenerator->updateCurrentPriorityNumber($newNumber, $_SESSION['user_id']);
    
    if ($result['success']) {
        // Log activity
        logActivity('Priority number served', 'priority_numbers', null, null, ['priority_number' => $newNumber]);
    }
    
    echo json_encode($result);
}

/**
 * Handle getting priority number details
 */
function handleGetPriorityDetails($priorityGenerator) {
    $priorityNumber = $_GET['priority_number'] ?? null;
    
    if (!$priorityNumber) {
        echo json_encode(['success' => false, 'error' => 'Priority number is required']);
        return;
    }
    
    $result = $priorityGenerator->getPriorityNumberDetails($priorityNumber);
    
    if ($result) {
        echo json_encode(['success' => true, 'data' => $result]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Priority number not found']);
    }
}

/**
 * Handle getting queue statistics
 */
function handleGetQueueStatistics($priorityGenerator) {
    $stats = $priorityGenerator->getQueueStatistics();
    echo json_encode(['success' => true, 'data' => $stats]);
}

/**
 * Handle getting upcoming priority numbers
 */
function handleGetUpcomingPriorityNumbers($priorityGenerator) {
    $limit = $_GET['limit'] ?? 10;
    $upcoming = $priorityGenerator->getUpcomingPriorityNumbers($limit);
    echo json_encode(['success' => true, 'data' => $upcoming]);
}

/**
 * Handle getting current priority with customer information
 */
function handleGetCurrentWithCustomer($priorityGenerator) {
    $current = $priorityGenerator->getCurrentPriorityWithCustomer();
    
    if ($current) {
        echo json_encode(['success' => true, 'data' => $current]);
    } else {
        echo json_encode(['success' => false, 'error' => 'No current customer found']);
    }
}

/**
 * Handle cancelling priority number
 */
function handleCancelPriorityNumber($priorityGenerator) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'error' => 'POST method required']);
        return;
    }
    
    $priorityNumber = $_POST['priority_number'] ?? null;
    $reason = $_POST['reason'] ?? null;
    
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
        logActivity('Priority number cancelled', 'priority_numbers', null, null, ['priority_number' => $priorityNumber, 'reason' => $reason]);
    }
    
    echo json_encode($result);
}

/**
 * Handle getting customer priority history
 */
function handleGetCustomerHistory($priorityGenerator) {
    $customerId = $_GET['customer_id'] ?? null;
    $limit = $_GET['limit'] ?? 10;
    
    if (!$customerId) {
        echo json_encode(['success' => false, 'error' => 'Customer ID is required']);
        return;
    }
    
    // Check if user can access this customer's data
    if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'cashier') {
        // For customers, only allow access to their own data
        if ($_SESSION['role'] === 'customer' && $_SESSION['customer_id'] != $customerId) {
            echo json_encode(['success' => false, 'error' => 'Access denied']);
            return;
        }
    }
    
    $history = $priorityGenerator->getCustomerPriorityHistory($customerId, $limit);
    echo json_encode(['success' => true, 'data' => $history]);
}

/**
 * Handle clearing expired priority numbers
 */
function handleClearExpiredNumbers($priorityGenerator) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'error' => 'POST method required']);
        return;
    }
    
    // Check if user has admin role
    if ($_SESSION['role'] !== 'admin') {
        echo json_encode(['success' => false, 'error' => 'Admin access required']);
        return;
    }
    
    // Validate CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        echo json_encode(['success' => false, 'error' => 'Invalid security token']);
        return;
    }
    
    try {
        $db = getDB();
        $expiryHours = (int)getSystemSetting('priority_expiry_hours', 24);
        
        // Mark expired priority numbers as expired
        $sql = "UPDATE priority_numbers 
                SET status = 'expired' 
                WHERE status = 'pending' 
                AND generated_at < DATE_SUB(NOW(), INTERVAL ? HOUR)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$expiryHours]);
        
        $affectedRows = $stmt->rowCount();
        
        // Log activity
        logActivity('Expired priority numbers cleared', 'priority_numbers', null, null, ['expired_count' => $affectedRows]);
        
        echo json_encode([
            'success' => true,
            'message' => "Cleared {$affectedRows} expired priority numbers"
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

/**
 * Handle resetting queue
 */
function handleResetQueue($priorityGenerator) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'error' => 'POST method required']);
        return;
    }
    
    // Check if user has admin role
    if ($_SESSION['role'] !== 'admin') {
        echo json_encode(['success' => false, 'error' => 'Admin access required']);
        return;
    }
    
    // Validate CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        echo json_encode(['success' => false, 'error' => 'Invalid security token']);
        return;
    }
    
    try {
        $db = getDB();
        $db->beginTransaction();
        
        // Reset queue status for today
        $sql = "UPDATE priority_queue_status 
                SET current_priority_number = 0, last_served_number = 0, served_count = 0 
                WHERE queue_date = CURDATE()";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        
        // If no record exists for today, create one
        if ($stmt->rowCount() === 0) {
            $sql = "INSERT INTO priority_queue_status (current_priority_number, last_served_number, queue_date, daily_capacity, served_count) 
                    VALUES (0, 0, CURDATE(), 1000, 0)";
            $stmt = $db->prepare($sql);
            $stmt->execute();
        }
        
        $db->commit();
        
        // Log activity
        logActivity('Queue status reset', 'priority_queue_status', null, null, ['reset_date' => date('Y-m-d')]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Queue status has been reset successfully'
        ]);
        
    } catch (Exception $e) {
        $db->rollBack();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>
