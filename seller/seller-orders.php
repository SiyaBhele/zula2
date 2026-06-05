<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireRole('Seller');

$seller_id = $_SESSION['user_id'];

// Handle status update
if (isset($_POST['update_status'])) {
    $order_id = (int)$_POST['order_id'];
    $new_status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE orders SET order_status = ? WHERE id = ?");
    $stmt->execute([$new_status, $order_id]);
    header("Location: seller-orders.php?updated=1");
    exit();
}

$stmt = $pdo->prepare("SELECT DISTINCT o.*, u.first_name, u.last_name 
                       FROM orders o 
                       JOIN order_items oi ON o.id = oi.order_id 
                       JOIN users u ON o.buyer_id = u.id 
                       WHERE oi.seller_id = ? 
                       ORDER BY o.created_at DESC");
$stmt->execute([$seller_id]);
$orders = $stmt->fetchAll();

$pageTitle = 'Manage Shop Orders';
include '../includes/header.php';
?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <a href="dashboard.php" class="btn btn-light rounded-circle me-3"><i class="fas fa-arrow-left"></i></a>
            <h2 class="fw-bold mb-0">Shop Orders</h2>
        </div>
    </div>

    <?php if (isset($_GET['updated'])): ?>
        <div class="alert alert-success rounded-3">Order status updated successfully.</div>
    <?php endif; ?>

    <?php if (empty($orders)): ?>
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
            <div class="mb-4 text-muted"><i class="fas fa-clipboard-list fa-5x"></i></div>
            <h4 class="fw-bold">No orders received yet</h4>
            <p class="text-muted">When customers buy your products, they will appear here.</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($orders as $order): ?>
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <h6 class="fw-bold mb-1">Order #ZULA-<?php echo str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?></h6>
                                <p class="small text-muted mb-0">Customer: <?php echo e($order['first_name'] . ' ' . $order['last_name']); ?> | <?php echo date('d M Y, H:i', strtotime($order['created_at'])); ?></p>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <span class="badge <?php echo getOrderStatusBadgeClass($order['order_status']); ?> px-3 py-2 rounded-pill"><?php echo $order['order_status']; ?></span>
                                <form action="seller-orders.php" method="POST" class="d-flex gap-2">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    <input type="hidden" name="update_status" value="1">
                                    <select name="status" class="form-select form-select-sm rounded-pill" style="width: 150px;">
                                        <option value="Pending" <?php echo $order['order_status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="Processing" <?php echo $order['order_status'] === 'Processing' ? 'selected' : ''; ?>>Processing</option>
                                        <option value="Out for Delivery" <?php echo $order['order_status'] === 'Out for Delivery' ? 'selected' : ''; ?>>Out for Delivery</option>
                                        <option value="Completed" <?php echo $order['order_status'] === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                        <option value="Cancelled" <?php echo $order['order_status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3">Update</button>
                                </form>
                            </div>
                        </div>
                        <div class="card-body p-4 border-top">
                            <div class="row g-4">
                                <div class="col-md-7">
                                    <h6 class="fw-bold mb-3 small text-uppercase text-muted">Items Ordered</h6>
                                    <?php
                                    $stmt_items = $pdo->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ? AND oi.seller_id = ?");
                                    $stmt_items->execute([$order['id'], $seller_id]);
                                    $items = $stmt_items->fetchAll();
                                    foreach ($items as $item):
                                    ?>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="small"><?php echo e($item['name']); ?> <span class="text-muted">x<?php echo $item['quantity']; ?></span></span>
                                            <span class="fw-bold small"><?php echo formatPrice($item['price_at_purchase'] * $item['quantity']); ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="col-md-5 border-start">
                                    <h6 class="fw-bold mb-3 small text-uppercase text-muted">Shipping Details</h6>
                                    <p class="small mb-1"><strong>Method:</strong> <?php echo e($order['delivery_option']); ?></p>
                                    <p class="small mb-0"><strong>Address:</strong><br><?php echo nl2br(e($order['shipping_address'])); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
