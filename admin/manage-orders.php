<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireRole('Admin');

$stmt = $pdo->query("SELECT o.*, u.first_name, u.last_name FROM orders o JOIN users u ON o.buyer_id = u.id ORDER BY o.created_at DESC");
$orders = $stmt->fetchAll();

$pageTitle = 'Manage All Orders';
include '../includes/header.php';
?>

<div class="container-fluid px-4">
    <div class="d-flex align-items-center mb-4">
        <a href="dashboard.php" class="btn btn-light rounded-circle me-3"><i class="fas fa-arrow-left"></i></a>
        <h2 class="fw-bold mb-0">Platform Orders</h2>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 border-0">Order ID</th>
                        <th class="py-3 border-0">Customer</th>
                        <th class="py-3 border-0">Total</th>
                        <th class="py-3 border-0">Status</th>
                        <th class="py-3 border-0">Payment</th>
                        <th class="py-3 border-0">Date</th>
                        <th class="pe-4 py-3 border-0 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $o): ?>
                        <tr>
                            <td class="ps-4 fw-bold">#ZULA-<?php echo str_pad($o['id'], 5, '0', STR_PAD_LEFT); ?></td>
                            <td><?php echo e($o['first_name'] . ' ' . $o['last_name']); ?></td>
                            <td class="fw-bold text-primary"><?php echo formatPrice($o['total_amount']); ?></td>
                            <td><span class="badge <?php echo getOrderStatusBadgeClass($o['order_status']); ?> rounded-pill small"><?php echo $o['order_status']; ?></span></td>
                            <td><span class="badge bg-success-subtle text-success rounded-pill small"><?php echo $o['payment_status']; ?></span></td>
                            <td class="small text-muted"><?php echo date('d M Y', strtotime($o['created_at'])); ?></td>
                            <td class="pe-4 text-end">
                                <button class="btn btn-sm btn-light rounded-pill px-3">Details</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
