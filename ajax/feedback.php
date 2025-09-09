<?php
require_once __DIR__ . '/../config/config.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? $_POST['action'] ?? '';

function json_ok($data = []) { echo json_encode(['ok' => true] + $data); exit; }
function json_err($msg, $code = 400) { http_response_code($code); echo json_encode(['ok' => false, 'error' => $msg]); exit; }

if ($method === 'GET' && $action === 'list') {
    $sinceId = (int)($_GET['since_id'] ?? 0);
    $limit = min(max((int)($_GET['limit'] ?? 20), 1), 100);
    $sql = "SELECT f.feedback_id, f.customer_id, COALESCE(f.customer_name, c.first_name) AS customer_name, f.message, f.status, f.created_at
            FROM feedback f
            LEFT JOIN customers c ON f.customer_id = c.customer_id
            WHERE f.feedback_id > ?
            ORDER BY f.feedback_id DESC
            LIMIT ?";
    $rows = fetchAll($sql, [$sinceId, $limit]);
    // Optionally attach replies for returned feedback
    $ids = array_column($rows, 'feedback_id');
    $repliesByFeedback = [];
    if (!empty($ids)) {
        $in = implode(',', array_fill(0, count($ids), '?'));
        $rws = fetchAll(
            "SELECT r.reply_id, r.feedback_id, r.admin_user_id, u.full_name AS admin_name, r.message, r.created_at
             FROM feedback_replies r
             LEFT JOIN users u ON u.user_id = r.admin_user_id
             WHERE r.feedback_id IN ($in)
             ORDER BY r.created_at ASC",
            $ids
        );
        foreach ($rws as $r) { $repliesByFeedback[$r['feedback_id']][] = $r; }
    }
    json_ok(['feedback' => $rows, 'replies' => $repliesByFeedback]);
}

if ($method === 'POST' && $action === 'create') {
    // Only customers can post
    $customerId = $_SESSION['customer_id'] ?? null;
    if (!$customerId) {
        json_err('Not authenticated', 401);
    }

    $raw = trim($_POST['message'] ?? '');
    if ($raw === '') { json_err('Message is required'); }
    if (mb_strlen($raw) > 2000) { json_err('Message too long'); }

    $message = $raw; // DB is parameterized; output will be escaped in UI

    $sql = "INSERT INTO feedback (customer_id, message) VALUES (?, ?)";
    executeQuery($sql, [$customerId, $message]);
    $id = getLastInsertId();

    $row = fetchOne("SELECT f.feedback_id, f.customer_id, COALESCE(f.customer_name, c.first_name) AS customer_name, f.message, f.status, f.created_at
                     FROM feedback f LEFT JOIN customers c ON f.customer_id = c.customer_id WHERE f.feedback_id = ?", [$id]);
    json_ok(['feedback' => $row]);
}

if ($method === 'POST' && $action === 'reply') {
    // Only admins can reply (case-insensitive role check)
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'admin') {
        json_err('Forbidden', 403);
    }
    $feedbackId = (int)($_POST['feedback_id'] ?? 0);
    $raw = trim($_POST['message'] ?? '');
    if ($feedbackId <= 0) { json_err('Invalid feedback'); }
    if ($raw === '') { json_err('Message is required'); }
    if (mb_strlen($raw) > 4000) { json_err('Message too long'); }

    // Ensure feedback exists
    $exists = fetchOne('SELECT feedback_id FROM feedback WHERE feedback_id = ?', [$feedbackId]);
    if (!$exists) { json_err('Feedback not found', 404); }

    $id = executeQueryWithId('INSERT INTO feedback_replies (feedback_id, admin_user_id, message) VALUES (?, ?, ?)', [$feedbackId, $_SESSION['user_id'], $raw]);
    
    // Debug: Check if we got a valid ID
    if (!$id || $id <= 0) {
        json_err('Failed to get reply ID', 500);
    }
    
    $reply = fetchOne('SELECT r.reply_id, r.feedback_id, r.admin_user_id, u.full_name AS admin_name, r.message, r.created_at
                       FROM feedback_replies r LEFT JOIN users u ON u.user_id = r.admin_user_id WHERE r.reply_id = ?', [$id]);
    
    // Debug: Log what we found
    error_log("Reply ID: $id, Reply data: " . json_encode($reply));
    
    if (!$reply) {
        json_err('Failed to fetch created reply', 500);
    }
    json_ok(['reply' => $reply]);
}

json_err('Unsupported action', 404);
?>


