<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireRole('Admin');

// Handle deletion
if (isset($_POST['delete_id'])) {
    $delete_id = (int)$_POST['delete_id'];
    if ($delete_id != $_SESSION['user_id']) { // Cannot delete self
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$delete_id]);
        header("Location: manage-users.php?deleted=1");
        exit();
    }
}

// Handle status update
if (isset($_POST['update_status'])) {
    $user_id = (int)$_POST['user_id'];
    $status = $_POST['seller_status'];
    $stmt = $pdo->prepare("UPDATE users SET seller_status = ? WHERE id = ?");
    $stmt->execute([$status, $user_id]);
    header("Location: manage-users.php?updated=1");
    exit();
}

$stmt = $pdo->query("SELECT u.*, r.role_name FROM users u JOIN roles r ON u.role_id = r.id ORDER BY u.created_at DESC");
$users = $stmt->fetchAll();

$pageTitle = 'Manage Users';
include '../includes/header.php';
?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <a href="dashboard.php" class="btn btn-light rounded-circle me-3"><i class="fas fa-arrow-left"></i></a>
            <h2 class="fw-bold mb-0">Users Management</h2>
        </div>
        <a href="add-user.php" class="btn btn-primary rounded-pill px-4"><i class="fas fa-plus me-2"></i>Add User</a>
    </div>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success rounded-3">User deleted successfully.</div>
    <?php endif; ?>
    <?php if (isset($_GET['updated'])): ?>
        <div class="alert alert-success rounded-3">User status updated successfully.</div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 border-0">User</th>
                        <th class="py-3 border-0">Role</th>
                        <th class="py-3 border-0">Seller Status</th>
                        <th class="py-3 border-0">Joined Date</th>
                        <th class="pe-4 py-3 border-0 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center py-2">
                                    <div class="bg-light rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                        <i class="fas fa-user text-secondary"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0 small"><?php echo e($u['first_name'] . ' ' . $u['last_name']); ?></h6>
                                        <small class="text-muted"><?php echo e($u['email']); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-light text-dark border rounded-pill small"><?php echo $u['role_name']; ?></span></td>
                            <td>
                                <?php if ($u['role_name'] === 'Seller'): ?>
                                    <form action="manage-users.php" method="POST" class="d-flex gap-2">
                                        <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                        <input type="hidden" name="update_status" value="1">
                                        <select name="seller_status" class="form-select form-select-sm rounded-pill" onchange="this.form.submit()" style="width: 120px;">
                                            <option value="Pending" <?php echo $u['seller_status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="Verified" <?php echo $u['seller_status'] === 'Verified' ? 'selected' : ''; ?>>Verified</option>
                                            <option value="Suspended" <?php echo $u['seller_status'] === 'Suspended' ? 'selected' : ''; ?>>Suspended</option>
                                        </select>
                                    </form>
                                <?php else: ?>
                                    <span class="text-muted small">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td class="small text-muted"><?php echo date('d M Y', strtotime($u['created_at'])); ?></td>
                            <td class="pe-4 text-end">
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                        <li><a class="dropdown-item" href="edit-user.php?id=<?php echo $u['id']; ?>"><i class="fas fa-edit me-2 text-primary"></i>Edit</a></li>
                                        <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="manage-users.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                    <input type="hidden" name="delete_id" value="<?php echo $u['id']; ?>">
                                                    <button type="submit" class="dropdown-item text-danger"><i class="fas fa-trash-alt me-2"></i>Delete</button>
                                                </form>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
