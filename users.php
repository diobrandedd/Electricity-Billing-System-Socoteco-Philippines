<?php
$page_title = 'User Management';
require_once 'includes/header.php';

requireRole(['admin']);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action == 'add') {
        $username = sanitizeInput($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $full_name = sanitizeInput($_POST['full_name'] ?? '');
        $email = sanitizeInput($_POST['email'] ?? '');
        $role = sanitizeInput($_POST['role'] ?? '');
        
        if (empty($username) || empty($password) || empty($full_name) || empty($role)) {
            $error = 'Please fill in all required fields.';
        } else {
            // Check if username already exists
            $existing = fetchOne("SELECT user_id FROM users WHERE username = ?", [$username]);
            if ($existing) {
                $error = 'Username already exists.';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (username, password, full_name, email, role, is_active) VALUES (?, ?, ?, ?, ?, ?)";
                executeQuery($sql, [$username, $hashed_password, $full_name, $email, $role, 1]);
                
                logActivity('User created', 'users', getLastInsertId());
                $success = 'User created successfully.';
            }
        }
    } elseif ($action == 'edit') {
        $user_id = $_POST['user_id'] ?? '';
        $username = sanitizeInput($_POST['username'] ?? '');
        $full_name = sanitizeInput($_POST['full_name'] ?? '');
        $email = sanitizeInput($_POST['email'] ?? '');
        $role = sanitizeInput($_POST['role'] ?? '');
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        if (empty($username) || empty($full_name) || empty($role)) {
            $error = 'Please fill in all required fields.';
        } else {
            // Check if username already exists (excluding current user)
            $existing = fetchOne("SELECT user_id FROM users WHERE username = ? AND user_id != ?", [$username, $user_id]);
            if ($existing) {
                $error = 'Username already exists.';
            } else {
                $sql = "UPDATE users SET username = ?, full_name = ?, email = ?, role = ?, is_active = ? WHERE user_id = ?";
                executeQuery($sql, [$username, $full_name, $email, $role, $is_active, $user_id]);
                
                logActivity('User updated', 'users', $user_id);
                $success = 'User updated successfully.';
            }
        }
    } elseif ($action == 'delete') {
        $user_id = $_POST['user_id'] ?? '';
        
        if ($user_id == $_SESSION['user_id']) {
            $error = 'You cannot delete your own account.';
        } else {
            executeQuery("DELETE FROM users WHERE user_id = ?", [$user_id]);
            logActivity('User deleted', 'users', $user_id);
            $success = 'User deleted successfully.';
        }
    }
}

// Get all users
$users = fetchAll("SELECT * FROM users ORDER BY created_at DESC");
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-users me-2"></i>User Management</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal">
                    <i class="fas fa-plus me-2"></i>Add New User
                </button>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $user['role'] == 'admin' ? 'danger' : ($user['role'] == 'cashier' ? 'warning' : 'info'); ?>">
                                            <?php echo ucfirst($user['role']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $user['is_active'] ? 'success' : 'secondary'; ?>">
                                            <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?>
                                        </span>
                                    </td>
                                    <td><?php echo formatDate($user['created_at'], 'M d, Y'); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="editUser(<?php echo htmlspecialchars(json_encode($user)); ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <?php if ($user['user_id'] != $_SESSION['user_id']): ?>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteUser(<?php echo $user['user_id']; ?>, '<?php echo htmlspecialchars($user['username']); ?>')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Modal -->
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalTitle">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" id="userAction" value="add">
                    <input type="hidden" name="user_id" id="userId">
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">Username *</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    
                    <div class="mb-3" id="passwordField">
                        <label for="password" class="form-label">Password *</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name *</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    
                    <div class="mb-3">
                        <label for="role" class="form-label">Role *</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="cashier">Cashier</option>
                            <option value="meter_reader">Meter Reader</option>
                        </select>
                    </div>
                    
                    <div class="mb-3" id="statusField" style="display: none;">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete user <strong id="deleteUserName"></strong>?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="user_id" id="deleteUserId">
                    <button type="submit" class="btn btn-danger">Delete User</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function editUser(user) {
    document.getElementById('userModalTitle').textContent = 'Edit User';
    document.getElementById('userAction').value = 'edit';
    document.getElementById('userId').value = user.user_id;
    document.getElementById('username').value = user.username;
    document.getElementById('full_name').value = user.full_name;
    document.getElementById('email').value = user.email || '';
    document.getElementById('role').value = user.role;
    document.getElementById('is_active').checked = user.is_active == 1;
    
    // Hide password field for edit
    document.getElementById('passwordField').style.display = 'none';
    document.getElementById('password').required = false;
    
    // Show status field for edit
    document.getElementById('statusField').style.display = 'block';
    
    new bootstrap.Modal(document.getElementById('userModal')).show();
}

function deleteUser(userId, username) {
    document.getElementById('deleteUserId').value = userId;
    document.getElementById('deleteUserName').textContent = username;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// Reset modal when closed
document.getElementById('userModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('userModalTitle').textContent = 'Add New User';
    document.getElementById('userAction').value = 'add';
    document.getElementById('userId').value = '';
    document.getElementById('username').value = '';
    document.getElementById('password').value = '';
    document.getElementById('full_name').value = '';
    document.getElementById('email').value = '';
    document.getElementById('role').value = '';
    document.getElementById('is_active').checked = true;
    
    // Show password field for add
    document.getElementById('passwordField').style.display = 'block';
    document.getElementById('password').required = true;
    
    // Hide status field for add
    document.getElementById('statusField').style.display = 'none';
});
</script>

<?php require_once 'includes/footer.php'; ?>
