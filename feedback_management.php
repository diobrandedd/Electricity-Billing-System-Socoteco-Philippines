<?php
require_once __DIR__ . '/config/config.php';
requireRole(['admin']);

$page_title = 'Feedback Management';
include 'includes/header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Feedback Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button id="refreshFeedback" class="btn btn-outline-secondary">
            <i class="fas fa-sync-alt me-2"></i>Refresh
        </button>
    </div>
</div>

<!-- Feedback List -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-comments me-2"></i>Customer Feedback
                </h5>
            </div>
            <div class="card-body">
                <div id="feedbackList" class="d-flex flex-column gap-3" style="max-height: 70vh; overflow-y: auto;">
                    <!-- Feedback items will be loaded here -->
                </div>
                <div id="feedbackEmpty" class="text-muted text-center py-4" style="display:none;">
                    <i class="fas fa-comments fa-3x mb-3"></i>
                    <p>No feedback available yet.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.feedback-item {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 1rem;
    background: #fff;
    transition: box-shadow 0.2s;
}

.feedback-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.reply-box {
    background: #f8f9fa;
    border-left: 3px solid var(--primary-color);
    margin-top: 0.75rem;
    padding: 0.75rem;
    border-radius: 4px;
}

.reply-form {
    margin-top: 0.75rem;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 4px;
}

.status-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.truncate {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.expand-toggle {
    cursor: pointer;
    color: var(--primary-color);
    font-weight: 600;
    font-size: 0.9rem;
}

.expand-toggle:hover {
    text-decoration: underline;
}
</style>

<script>
(function(){
    const listEl = document.getElementById('feedbackList');
    const emptyEl = document.getElementById('feedbackEmpty');
    const refreshBtn = document.getElementById('refreshFeedback');
    const endpoint = '<?php echo url('ajax/feedback.php'); ?>';
    let lastId = 0;
    const rendered = new Set();
    const replyRendered = new Map();

    function formatTime(ts) {
        try { 
            return new Date(ts.replace(' ', 'T')).toLocaleString(); 
        } catch(e) { 
            return ts || ''; 
        }
    }

    function createFeedbackEl(f) {
        const wrap = document.createElement('div');
        wrap.className = 'feedback-item';
        wrap.dataset.feedbackId = f.feedback_id;

        // Header with customer name, time, and status
        const header = document.createElement('div');
        header.className = 'd-flex justify-content-between align-items-center mb-2';
        
        const left = document.createElement('div');
        left.className = 'd-flex align-items-center gap-2';
        
        const name = document.createElement('strong');
        name.textContent = f.customer_name || 'Anonymous';
        
        const status = document.createElement('span');
        status.className = `badge status-badge ${f.status === 'reviewed' ? 'bg-success' : 'bg-warning'}`;
        status.textContent = f.status === 'reviewed' ? 'Reviewed' : 'Pending';
        
        left.appendChild(name);
        left.appendChild(status);
        
        const time = document.createElement('small');
        time.className = 'text-muted';
        time.textContent = formatTime(f.created_at);
        
        header.appendChild(left);
        header.appendChild(time);

        // Message content
        const message = document.createElement('div');
        message.className = 'text-dark mb-2';
        message.textContent = f.message || '';

        // Expand/collapse for long messages
        if (f.message && f.message.length > 150) {
            message.classList.add('truncate');
            const toggle = document.createElement('div');
            toggle.className = 'expand-toggle mt-1';
            toggle.textContent = 'Read more';
            let expanded = false;
            toggle.addEventListener('click', function() {
                expanded = !expanded;
                if (expanded) {
                    message.classList.remove('truncate');
                    toggle.textContent = 'Show less';
                } else {
                    message.classList.add('truncate');
                    toggle.textContent = 'Read more';
                }
            });
            message.parentNode.appendChild(toggle);
        }

        // Replies container
        const replies = document.createElement('div');
        replies.className = 'replies-container';
        replies.dataset.feedbackId = f.feedback_id;

        // Reply form
        const replyForm = document.createElement('form');
        replyForm.className = 'reply-form d-flex gap-2';
        replyForm.innerHTML = `
            <textarea class="form-control" placeholder="Write a reply..." rows="2" required></textarea>
            <button class="btn btn-primary btn-sm" type="submit">Reply</button>
            <span class="small text-muted align-self-center" data-role="status"></span>
        `;
        
        const replyInput = replyForm.querySelector('textarea');
        const replyStatus = replyForm.querySelector('[data-role="status"]');
        
        replyForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const text = (replyInput.value || '').trim();
            if (!text) return;
            
            replyStatus.textContent = 'Sending...';
            const body = new URLSearchParams({ 
                action: 'reply', 
                feedback_id: f.feedback_id, 
                message: text 
            });
            
            fetch(endpoint, { 
                method: 'POST', 
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, 
                body 
            })
            .then(r => r.json())
            .then(data => {
                console.log('Reply response:', data); // Debug log
                if (data && data.ok && data.reply) {
                    renderReplies([data.reply]);
                    replyInput.value = '';
                    replyStatus.textContent = 'Replied';
                    setTimeout(() => replyStatus.textContent = '', 1500);
                } else {
                    replyStatus.textContent = (data && data.error) ? data.error : 'Failed to reply';
                    console.error('Reply failed:', data); // Debug log
                }
            })
            .catch(() => { 
                replyStatus.textContent = 'Network error'; 
            });
        });

        // Assemble the feedback item
        wrap.appendChild(header);
        wrap.appendChild(message);
        wrap.appendChild(replies);
        wrap.appendChild(replyForm);

        return wrap;
    }

    function createReplyEl(r) {
        const box = document.createElement('div');
        box.className = 'reply-box';
        
        const header = document.createElement('div');
        header.className = 'd-flex justify-content-between align-items-center mb-1';
        
        const who = document.createElement('strong');
        who.textContent = r.admin_name || 'Admin';
        
        const time = document.createElement('small');
        time.className = 'text-muted';
        time.textContent = formatTime(r.created_at);
        
        header.appendChild(who);
        header.appendChild(time);
        
        const body = document.createElement('div');
        body.className = 'text-dark';
        body.textContent = r.message || '';
        
        box.appendChild(header);
        box.appendChild(body);
        return box;
    }

    function renderReplies(replies) {
        if (!Array.isArray(replies) || !replies.length) return;
        
        replies.forEach(r => {
            const fid = parseInt(r.feedback_id);
            const rid = parseInt(r.reply_id);
            let set = replyRendered.get(fid);
            if (!set) { 
                set = new Set(); 
                replyRendered.set(fid, set); 
            }
            if (set.has(rid)) return;
            set.add(rid);
            
            const container = listEl.querySelector(`[data-feedback-id="${fid}"] .replies-container`);
            if (container) {
                container.appendChild(createReplyEl(r));
            }
        });
    }

    function renderRepliesMap(map) {
        if (!map) return;
        Object.keys(map).forEach(fid => {
            const replies = map[fid] || [];
            renderReplies(replies);
        });
    }

    function renderList(items) {
        if (!Array.isArray(items) || !items.length) { 
            updateEmptyState(); 
            return; 
        }
        
        // Sort by feedback_id ascending, then insert newest first
        items.sort((a,b) => (parseInt(a.feedback_id) - parseInt(b.feedback_id)));
        items.forEach(f => {
            const id = parseInt(f.feedback_id);
            if (rendered.has(id)) return;
            lastId = Math.max(lastId, id);
            rendered.add(id);
            listEl.insertBefore(createFeedbackEl(f), listEl.firstChild);
        });
        updateEmptyState();
    }

    function updateEmptyState() {
        emptyEl.style.display = listEl.children.length ? 'none' : 'block';
    }

    function fetchList(since = 0) {
        const url = endpoint + `?action=list&since_id=${since}`;
        return fetch(url)
            .then(r => r.json())
            .then(data => {
                renderList(data.feedback || []);
                renderRepliesMap(data.replies || {});
            })
            .catch(() => {});
    }

    function renderRepliesMap(map) {
        if (!map) return;
        Object.keys(map).forEach(fid => {
            const replies = map[fid] || [];
            renderReplies(replies);
        });
    }

    // Event listeners
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            listEl.innerHTML = '';
            rendered.clear();
            replyRendered.clear();
            lastId = 0;
            fetchList(0);
        });
    }

    // Initial load and polling
    fetchList(0);
    setInterval(function() {
        fetchList(lastId || 0);
    }, 5000);
})();
</script>

<?php include 'includes/footer.php'; ?>
