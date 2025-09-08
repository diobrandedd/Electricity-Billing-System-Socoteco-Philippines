<?php
require_once __DIR__ . '/../config/config.php';

header('Content-Type: application/json');

// Helpers
function requireAdmin() {
    if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? null) !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
}

function requireCustomer() {
    if (!isset($_SESSION['customer_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'ensure_session':
            // Customer ensures there is an open chat session; create if none
            requireCustomer();
            $customer_id = (int) $_SESSION['customer_id'];
            $session = fetchOne("SELECT * FROM chat_sessions WHERE customer_id = ? AND status = 'open' ORDER BY created_at DESC LIMIT 1", [$customer_id]);
            if (!$session) {
                executeQuery("INSERT INTO chat_sessions (customer_id) VALUES (?)", [$customer_id]);
                $session_id = getLastInsertId();
                $session = fetchOne("SELECT * FROM chat_sessions WHERE session_id = ?", [$session_id]);
            }
            echo json_encode(['session' => $session]);
            break;

        case 'send_message':
            // Sender can be customer or admin
            $session_id = (int) ($_POST['session_id'] ?? 0);
            $message = trim($_POST['message'] ?? '');
            $explicit_sender = $_POST['sender'] ?? '';
            if ($session_id <= 0 || $message === '') {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid parameters']);
                exit;
            }

            $session = fetchOne("SELECT * FROM chat_sessions WHERE session_id = ?", [$session_id]);
            if (!$session || $session['status'] !== 'open') {
                http_response_code(404);
                echo json_encode(['error' => 'Session not found']);
                exit;
            }

            // Determine sender with explicit hint first
            if ($explicit_sender === 'customer' && isset($_SESSION['customer_id'])) {
                if ((int)$session['customer_id'] !== (int)$_SESSION['customer_id']) {
                    http_response_code(403);
                    echo json_encode(['error' => 'Forbidden']);
                    exit;
                }
                executeQuery(
                    "INSERT INTO chat_messages (session_id, sender_type, sender_customer_id, message) VALUES (?, 'customer', ?, ?)",
                    [$session_id, $_SESSION['customer_id'], $message]
                );
            } elseif ($explicit_sender === 'admin' && isset($_SESSION['user_id']) && ($_SESSION['role'] ?? null) === 'admin') {
                executeQuery(
                    "INSERT INTO chat_messages (session_id, sender_type, sender_user_id, message) VALUES (?, 'admin', ?, ?)",
                    [$session_id, $_SESSION['user_id'], $message]
                );
            } elseif (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? null) === 'admin') {
                executeQuery(
                    "INSERT INTO chat_messages (session_id, sender_type, sender_user_id, message) VALUES (?, 'admin', ?, ?)",
                    [$session_id, $_SESSION['user_id'], $message]
                );
            } elseif (isset($_SESSION['customer_id'])) {
                if ((int)$session['customer_id'] !== (int)$_SESSION['customer_id']) {
                    http_response_code(403);
                    echo json_encode(['error' => 'Forbidden']);
                    exit;
                }
                executeQuery(
                    "INSERT INTO chat_messages (session_id, sender_type, sender_customer_id, message) VALUES (?, 'customer', ?, ?)",
                    [$session_id, $_SESSION['customer_id'], $message]
                );
            } else {
                http_response_code(401);
                echo json_encode(['error' => 'Unauthorized']);
                exit;
            }

            echo json_encode(['success' => true]);
            break;

        case 'fetch_messages':
            $session_id = (int) ($_GET['session_id'] ?? 0);
            $since_id = (int) ($_GET['since_id'] ?? 0);
            if ($session_id <= 0) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid parameters']);
                exit;
            }
            $session = fetchOne("SELECT * FROM chat_sessions WHERE session_id = ?", [$session_id]);
            if (!$session) {
                http_response_code(404);
                echo json_encode(['error' => 'Session not found']);
                exit;
            }
            // Authorization: customer must own the session; admin can access any
            if (isset($_SESSION['customer_id'])) {
                if ((int)$session['customer_id'] !== (int)$_SESSION['customer_id']) {
                    http_response_code(403);
                    echo json_encode(['error' => 'Forbidden']);
                    exit;
                }
            } elseif (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? null) !== 'admin') {
                http_response_code(401);
                echo json_encode(['error' => 'Unauthorized']);
                exit;
            }

            if ($since_id > 0) {
                $messages = fetchAll("SELECT * FROM chat_messages WHERE session_id = ? AND message_id > ? ORDER BY message_id ASC", [$session_id, $since_id]);
            } else {
                $messages = fetchAll("SELECT * FROM chat_messages WHERE session_id = ? ORDER BY message_id ASC LIMIT 200", [$session_id]);
            }
            echo json_encode(['messages' => $messages]);
            break;

        case 'mark_read':
            $session_id = (int) ($_POST['session_id'] ?? 0);
            if ($session_id <= 0) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid parameters']);
                exit;
            }
            // Only admin marks customer messages read; customer marks admin messages read
            if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? null) === 'admin') {
                executeQuery("UPDATE chat_messages SET is_read = 1 WHERE session_id = ? AND sender_type = 'customer'", [$session_id]);
            } elseif (isset($_SESSION['customer_id'])) {
                // Ensure ownership
                $session = fetchOne("SELECT * FROM chat_sessions WHERE session_id = ?", [$session_id]);
                if (!$session || (int)$session['customer_id'] !== (int)$_SESSION['customer_id']) {
                    http_response_code(403);
                    echo json_encode(['error' => 'Forbidden']);
                    exit;
                }
                executeQuery("UPDATE chat_messages SET is_read = 1 WHERE session_id = ? AND sender_type = 'admin'", [$session_id]);
            } else {
                http_response_code(401);
                echo json_encode(['error' => 'Unauthorized']);
                exit;
            }
            echo json_encode(['success' => true]);
            break;

        case 'admin_list_sessions':
            requireAdmin();
            $sessions = fetchAll("SELECT cs.*, c.first_name, c.last_name, c.account_number,
                (SELECT COUNT(*) FROM chat_messages cm WHERE cm.session_id = cs.session_id AND cm.sender_type = 'customer' AND cm.is_read = 0) as unread_count,
                (SELECT MAX(created_at) FROM chat_messages cm2 WHERE cm2.session_id = cs.session_id) as last_message_at
                FROM chat_sessions cs
                JOIN customers c ON cs.customer_id = c.customer_id
                WHERE cs.status = 'open'
                ORDER BY COALESCE(last_message_at, cs.last_activity) DESC
                LIMIT 100");
            echo json_encode(['sessions' => $sessions]);
            break;

        case 'close_session':
            requireAdmin();
            $session_id = (int) ($_POST['session_id'] ?? 0);
            if ($session_id <= 0) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid parameters']);
                exit;
            }
            executeQuery("UPDATE chat_sessions SET status = 'closed' WHERE session_id = ?", [$session_id]);
            echo json_encode(['success' => true]);
            break;

        default:
            http_response_code(400);
            echo json_encode(['error' => 'Unknown action']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}
?>


