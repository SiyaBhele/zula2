<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireRole('Seller');

$seller_id = $_SESSION['user_id'];

// Get stats
$stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE seller_id = ?");
$stmt->execute([$seller_id]);
$total_products = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE seller_id = ? AND status = 'Approved'");
$stmt->execute([$seller_id]);
$approved_products = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE seller_id = ? AND status = 'Pending'");
$stmt->execute([$seller_id]);
$pending_products = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM order_items WHERE seller_id = ?");
$stmt->execute([$seller_id]);
$total_orders = $stmt->fetchColumn();

$pageTitle = 'Seller Dashboard';
include '../includes/header.php';
?>

<div class="container">
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold mb-1">Welcome back, <?php echo e($_SESSION['first_name']); ?>!</h2>
                        <p class="mb-0 opacity-75">Here's what's happening with your shop today.</p>
                    </div>
                    <a href="add-product.php" class="btn btn-light rounded-pill fw-bold px-4">
                        <i class="fas fa-plus me-2"></i>Add New Product
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="p-3 bg-light rounded-3"><i class="fas fa-box text-primary fs-4"></i></div>
                    <span class="badge bg-light text-dark border rounded-pill">Total</span>
                </div>
                <h3 class="fw-bold mb-1"><?php echo $total_products; ?></h3>
                <p class="text-muted small mb-0">Products Listed</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="p-3 bg-success-subtle rounded-3"><i class="fas fa-check-circle text-success fs-4"></i></div>
                    <span class="badge bg-success-subtle text-success border-success-subtle rounded-pill">Live</span>
                </div>
                <h3 class="fw-bold mb-1"><?php echo $approved_products; ?></h3>
                <p class="text-muted small mb-0">Approved Products</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="p-3 bg-warning-subtle rounded-3"><i class="fas fa-clock text-warning fs-4"></i></div>
                    <span class="badge bg-warning-subtle text-warning border-warning-subtle rounded-pill">Review</span>
                </div>
                <h3 class="fw-bold mb-1"><?php echo $pending_products; ?></h3>
                <p class="text-muted small mb-0">Pending Approval</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="p-3 bg-info-subtle rounded-3"><i class="fas fa-shopping-bag text-info fs-4"></i></div>
                    <span class="badge bg-info-subtle text-info border-info-subtle rounded-pill">Sales</span>
                </div>
                <h3 class="fw-bold mb-1"><?php echo $total_orders; ?></h3>
                <p class="text-muted small mb-0">Total Orders</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Recent Products</h5>
                    <a href="manage-products.php" class="btn btn-link text-decoration-none small">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 border-0">Product</th>
                                <th class="border-0">Price</th>
                                <th class="border-0">Status</th>
                                <th class="pe-4 border-0 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $pdo->prepare("SELECT * FROM products WHERE seller_id = ? ORDER BY created_at DESC LIMIT 5");
                            $stmt->execute([$seller_id]);
                            $recent_products = $stmt->fetchAll();
                            foreach ($recent_products as $p):
                            ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center py-2">
                                            <img src="https://via.placeholder.com/50?text=P" class="rounded-3 me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                            <h6 class="fw-bold mb-0 small"><?php echo e($p['name']); ?></h6>
                                        </div>
                                    </td>
                                    <td><?php echo formatPrice($p['price']); ?></td>
                                    <td><span class="badge <?php echo getStatusBadgeClass($p['status']); ?> rounded-pill small"><?php echo $p['status']; ?></span></td>
                                    <td class="pe-4 text-end">
                                        <a href="edit-product.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-light rounded-circle"><i class="fas fa-edit text-primary"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h5 class="fw-bold mb-4">Quick Actions</h5>
                <div class="d-grid gap-2">
                    <a href="add-product.php" class="btn btn-outline-primary rounded-pill text-start px-3 py-2">
                        <i class="fas fa-plus-circle me-2"></i>List a new item
                    </a>
                    <a href="seller-orders.php" class="btn btn-outline-primary rounded-pill text-start px-3 py-2">
                        <i class="fas fa-clipboard-list me-2"></i>View recent orders
                    </a>
                    <a href="../profile.php" class="btn btn-outline-primary rounded-pill text-start px-3 py-2">
                        <i class="fas fa-user-cog me-2"></i>Update shop profile
                    </a>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-light">
                <h6 class="fw-bold mb-3">Seller Tip</h6>
                <p class="small text-muted mb-0">Make sure your product photos are clear and taken in good lighting to attract more buyers!</p>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
