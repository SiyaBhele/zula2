<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireRole('Admin');

// Get statistics
$stats = [
    'users' => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
    'sellers' => $pdo->query("SELECT COUNT(*) FROM users WHERE role_id = 3")->fetchColumn(),
    'products' => $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn(),
    'pending_products' => $pdo->query("SELECT COUNT(*) FROM products WHERE status = 'Pending'")->fetchColumn(),
    'orders' => $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
    'disputes' => $pdo->query("SELECT COUNT(*) FROM disputes WHERE status = 'Open'")->fetchColumn(),
    'revenue' => $pdo->query("SELECT SUM(total_amount) FROM orders WHERE payment_status = 'Paid'")->fetchColumn() ?: 0
];

$pageTitle = 'Admin Dashboard';
include '../includes/header.php';
?>

<div class="container-fluid px-4">
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 p-4" style="background-color: #004d40; color: white;">
                <h2 class="fw-bold mb-1">Admin Control Center</h2>
                <p class="mb-0 opacity-75">Platform overview and management.</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="p-3 bg-primary-subtle rounded-3"><i class="fas fa-users text-primary fs-4"></i></div>
                    <span class="badge bg-primary-subtle text-primary border rounded-pill">Users</span>
                </div>
                <h3 class="fw-bold mb-1"><?php echo $stats['users']; ?></h3>
                <p class="text-muted small mb-0"><?php echo $stats['sellers']; ?> Sellers</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="p-3 bg-warning-subtle rounded-3"><i class="fas fa-box text-warning fs-4"></i></div>
                    <span class="badge bg-warning-subtle text-warning border rounded-pill">Pending</span>
                </div>
                <h3 class="fw-bold mb-1"><?php echo $stats['pending_products']; ?></h3>
                <p class="text-muted small mb-0">Products for Review</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="p-3 bg-success-subtle rounded-3"><i class="fas fa-shopping-cart text-success fs-4"></i></div>
                    <span class="badge bg-success-subtle text-success border rounded-pill">Sales</span>
                </div>
                <h3 class="fw-bold mb-1"><?php echo $stats['orders']; ?></h3>
                <p class="text-muted small mb-0">Total Orders</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="p-3 bg-danger-subtle rounded-3"><i class="fas fa-exclamation-triangle text-danger fs-4"></i></div>
                    <span class="badge bg-danger-subtle text-danger border rounded-pill">Active</span>
                </div>
                <h3 class="fw-bold mb-1"><?php echo $stats['disputes']; ?></h3>
                <p class="text-muted small mb-0">Open Disputes</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 sticky-top" style="top: 100px;">
                <h6 class="fw-bold text-muted small text-uppercase mb-3 px-3">Management</h6>
                <div class="list-group list-group-flush">
                    <a href="dashboard.php" class="list-group-item list-group-item-action border-0 py-3 active rounded-3"><i class="fas fa-tachometer-alt me-3"></i>Dashboard</a>
                    <a href="manage-users.php" class="list-group-item list-group-item-action border-0 py-3 rounded-3"><i class="fas fa-users-cog me-3"></i>Users & Roles</a>
                    <a href="manage-products.php" class="list-group-item list-group-item-action border-0 py-3 rounded-3"><i class="fas fa-boxes me-3"></i>Products Approval</a>
                    <a href="manage-categories.php" class="list-group-item list-group-item-action border-0 py-3 rounded-3"><i class="fas fa-tags me-3"></i>Categories</a>
                    <a href="manage-orders.php" class="list-group-item list-group-item-action border-0 py-3 rounded-3"><i class="fas fa-shopping-bag me-3"></i>All Orders</a>
                    <a href="disputes.php" class="list-group-item list-group-item-action border-0 py-3 rounded-3"><i class="fas fa-balance-scale me-3"></i>Disputes</a>
                    <a href="reports.php" class="list-group-item list-group-item-action border-0 py-3 rounded-3"><i class="fas fa-chart-bar me-3"></i>Reports</a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Pending Products -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Pending Product Approvals</h5>
                    <a href="manage-products.php" class="btn btn-sm btn-outline-primary rounded-pill px-3">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 border-0">Product</th>
                                <th class="border-0">Seller</th>
                                <th class="border-0">Price</th>
                                <th class="pe-4 border-0 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $pdo->query("SELECT p.*, u.first_name, u.last_name FROM products p JOIN users u ON p.seller_id = u.id WHERE p.status = 'Pending' ORDER BY p.created_at ASC LIMIT 5");
                            $pending_list = $stmt->fetchAll();
                            if (empty($pending_list)):
                            ?>
                                <tr><td colspan="4" class="text-center py-4 text-muted">No pending products to review.</td></tr>
                            <?php else: ?>
                                <?php foreach ($pending_list as $p): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center py-2">
                                                <img src="https://via.placeholder.com/40?text=P" class="rounded-2 me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                                <h6 class="fw-bold mb-0 small"><?php echo e($p['name']); ?></h6>
                                            </div>
                                        </td>
                                        <td><?php echo e($p['first_name'] . ' ' . $p['last_name']); ?></td>
                                        <td class="fw-bold"><?php echo formatPrice($p['price']); ?></td>
                                        <td class="pe-4 text-end">
                                            <a href="manage-products.php" class="btn btn-sm btn-primary rounded-pill px-3">Review</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Users -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Recent User Registrations</h5>
                    <a href="manage-users.php" class="btn btn-sm btn-outline-primary rounded-pill px-3">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 border-0">User</th>
                                <th class="border-0">Role</th>
                                <th class="border-0">Status</th>
                                <th class="pe-4 border-0 text-end">Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $pdo->query("SELECT u.*, r.role_name FROM users u JOIN roles r ON u.role_id = r.id ORDER BY u.created_at DESC LIMIT 5");
                            $recent_users = $stmt->fetchAll();
                            foreach ($recent_users as $u):
                            ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center py-2">
                                            <div class="bg-light rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-user text-secondary"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-0 small"><?php echo e($u['first_name'] . ' ' . $u['last_name']); ?></h6>
                                                <small class="text-muted"><?php echo e($u['email']); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light text-dark border rounded-pill small"><?php echo $u['role_name']; ?></span></td>
                                    <td><span class="badge bg-success-subtle text-success rounded-pill small">Active</span></td>
                                    <td class="pe-4 text-end small text-muted"><?php echo date('d M Y', strtotime($u['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
