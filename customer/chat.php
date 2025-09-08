<?php
require_once __DIR__ . '/../config/config.php';

if (!isset($_SESSION['customer_id'])) {
    redirect('auth/customer_login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Chat - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .chat-box { height: 60vh; overflow-y: auto; background: #f8f9fa; padding: 1rem; border-radius: .5rem; }
        .msg-row { display: flex; align-items: flex-end; gap: .5rem; margin-bottom: .5rem; width: 100%; }
        .msg-row.left { flex-direction: row; }
        .msg-row.right { flex-direction: row-reverse; }
        .avatar {
            width: 28px; height: 28px; border-radius: 50%; background: #6c757d; color: #fff;
            display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 700;
        }
        .avatar.you { background: #0d6efd; }
        .avatar.admin { background: #198754; }
        .content { max-width: 85%; display: flex; flex-direction: column; }
        .bubble { display: inline-block; max-width: 100%; padding: .5rem .75rem; border-radius: .5rem; font-size: 0.95rem; width: auto; overflow-wrap: break-word; word-break: normal; white-space: pre-wrap; hyphens: auto; }
        .bubble.you { background: #e7f1ff; }
        .bubble.admin { background: #ffffff; }
        .bubble.you.long { background: #d7e8ff; }
        .bubble.admin.long { background: #f2f2f2; }
        @media (max-width: 576px) {
            .content { max-width: 95%; }
        }
        .meta { font-size: 0.75rem; color: #6c757d; margin-top: 2px; }
        .meta.you { text-align: right; }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>Support Chat</strong>
                    <a href="<?php echo url('customer/home.php'); ?>" class="btn btn-sm btn-outline-secondary">Back</a>
                </div>
                <div class="card-body">
                    <div id="chatBox" class="chat-box"></div>
                    <form id="chatForm" class="d-flex gap-2 mt-3 align-items-end">
                        <textarea id="messageInput" class="form-control chat-input" placeholder="Type your message..." rows="1" required style="resize: none; overflow: hidden;"></textarea>
                        <button class="btn btn-primary" type="submit">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
let sessionId = null;
let lastMessageId = 0;
const chatBox = document.getElementById('chatBox');
const form = document.getElementById('chatForm');
const input = document.getElementById('messageInput');

function ensureSession() {
    return fetch('<?php echo url('ajax/chat.php?action=ensure_session'); ?>')
        .then(r => r.json())
        .then(data => { sessionId = data.session.session_id; });
}

function formatTime(ts) {
    try { return new Date(ts.replace(' ', 'T')).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }); } catch(e) { return ''; }
}

function ensureMessageElement(m) {
    let row = document.getElementById('msg-' + m.message_id);
    const isYou = m.sender_type === 'customer';
    if (!row) {
        row = document.createElement('div');
        row.id = 'msg-' + m.message_id;
        row.className = 'msg-row ' + (isYou ? 'right' : 'left');

        const avatar = document.createElement('div');
        avatar.className = 'avatar ' + (isYou ? 'you' : 'admin');
        avatar.textContent = isYou ? 'YOU' : 'ADMIN';

        const contentWrap = document.createElement('div');
        contentWrap.className = 'content';
        const bubble = document.createElement('div');
        const isLong = (m.message || '').length > 120;
        bubble.className = 'bubble ' + (isYou ? 'you' : 'admin') + (isLong ? ' long' : '');
        bubble.textContent = m.message;

        const meta = document.createElement('div');
        meta.className = 'meta ' + (isYou ? 'you' : '');
        meta.dataset.role = isYou ? 'you' : 'them';

        contentWrap.appendChild(bubble);
        contentWrap.appendChild(meta);

        row.appendChild(avatar);
        row.appendChild(contentWrap);
        chatBox.appendChild(row);
    }
    return row;
}

function updateMeta(m) {
    const row = document.getElementById('msg-' + m.message_id);
    if (!row) return;
    const meta = row.querySelector('.meta');
    if (!meta) return;
    const time = formatTime(m.created_at || m.createdAt || m.timestamp || '');
    const isYou = m.sender_type === 'customer';
    if (isYou) {
        meta.textContent = `${time} • ${m.is_read == 1 ? 'Seen' : 'Sent'}`;
    } else {
        meta.textContent = `${time}`;
    }
}

function renderOrUpdateMessage(m) {
    ensureMessageElement(m);
    updateMeta(m);
}

function fetchMessages() {
    if (!sessionId) return;
    const url = '<?php echo url('ajax/chat.php'); ?>' + `?action=fetch_messages&session_id=${sessionId}&since_id=${lastMessageId}`;
    fetch(url)
        .then(r => r.json())
        .then(data => {
            (data.messages || []).forEach(m => {
                renderOrUpdateMessage(m);
                lastMessageId = Math.max(lastMessageId, parseInt(m.message_id));
            });
            if ((data.messages || []).length) {
                chatBox.scrollTop = chatBox.scrollHeight;
                // mark admin messages as read
                fetch('<?php echo url('ajax/chat.php'); ?>', { method: 'POST', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: new URLSearchParams({ action: 'mark_read', session_id: sessionId }) });
            }
        })
        .catch(() => {});
}

form.addEventListener('submit', function(e) {
    e.preventDefault();
    const message = input.value.trim();
    if (!message || !sessionId) return;
    const body = new URLSearchParams({ action: 'send_message', session_id: sessionId, message, sender: 'customer' });
    fetch('<?php echo url('ajax/chat.php'); ?>', { method: 'POST', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body })
        .then(r => r.json())
        .then(() => {
            input.value = '';
            input.style.height = 'auto';
            fetchMessages();
        });
});

ensureSession().then(() => {
    fetchMessages();
    setInterval(fetchMessages, 2000);
});

// Auto-grow textarea
function autoResize() {
    input.style.height = 'auto';
    input.style.height = (input.scrollHeight) + 'px';
}
input.addEventListener('input', autoResize);
window.addEventListener('load', autoResize);
</script>
</body>
</html>


