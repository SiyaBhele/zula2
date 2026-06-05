<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

requireLogin();

$stmt = $pdo->prepare("SELECT * FROM orders WHERE buyer_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();

$pageTitle = 'My Orders';
include 'includes/header.php';
?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">My Orders</h2>
        <a href="browse.php" class="btn btn-outline-primary rounded-pill">Continue Shopping</a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success rounded-4 shadow-sm border-0 p-4 mb-4">
            <div class="d-flex">
                <div class="me-3 fs-3"><i class="fas fa-check-circle"></i></div>
                <div>
                    <h5 class="fw-bold mb-1">Order Placed Successfully!</h5>
                    <p class="mb-0">Thank you for your purchase. The seller has been notified and will process your order soon.</p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (empty($orders)): ?>
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
            <div class="mb-4 text-muted"><i class="fas fa-box-open fa-5x"></i></div>
            <h4 class="fw-bold">No orders yet</h4>
            <p class="text-muted mb-4">You haven't placed any orders on Zula yet.</p>
            <a href="browse.php" class="btn btn-primary rounded-pill px-5">Browse Products</a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($orders as $order): ?>
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="d-flex gap-4">
                                <div>
                                    <small class="text-muted d-block text-uppercase fw-bold">Order Placed</small>
                                    <span class="fw-bold"><?php echo date('d M Y', strtotime($order['created_at'])); ?></span>
                                </div>
                                <div>
                                    <small class="text-muted d-block text-uppercase fw-bold">Total Amount</small>
                                    <span class="fw-bold text-primary"><?php echo formatPrice($order['total_amount']); ?></span>
                                </div>
                                <div>
                                    <small class="text-muted d-block text-uppercase fw-bold">Order ID</small>
                                    <span class="fw-bold text-muted">#ZULA-<?php echo str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?></span>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <span class="badge <?php echo getOrderStatusBadgeClass($order['order_status']); ?> px-3 py-2 rounded-pill">
                                    <?php echo $order['order_status']; ?>
                                </span>
                                <span class="badge bg-success px-3 py-2 rounded-pill">
                                    <?php echo $order['payment_status']; ?>
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-4 border-top">
                            <div class="row g-4">
                                <div class="col-md-8">
                                    <?php
                                    $stmt_items = $pdo->prepare("SELECT oi.*, p.name, p.image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
                                    $stmt_items->execute([$order['id']]);
                                    $items = $stmt_items->fetchAll();
                                    foreach ($items as $item):
                                    ?>
                                        <div class="d-flex align-items-center mb-3">
                                            <img src="https://via.placeholder.com/60?text=<?php echo urlencode($item['name']); ?>" class="rounded-3 me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                            <div>
                                                <h6 class="fw-bold mb-0"><?php echo e($item['name']); ?></h6>
                                                <small class="text-muted">Quantity: <?php echo $item['quantity']; ?> | Price: <?php echo formatPrice($item['price_at_purchase']); ?></small>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <div class="d-grid gap-2 d-md-block">
                                        <button class="btn btn-primary rounded-pill px-4">Track Order</button>
                                        <button class="btn btn-outline-secondary rounded-pill px-4">Help</button>
                                    </div>
                                    <?php if ($order['order_status'] === 'Completed'): ?>
                                        <button class="btn btn-warning rounded-pill px-4 mt-2">Rate Seller</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
