<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    header("Location: browse.php");
    exit();
}

$stmt = $pdo->prepare("SELECT p.*, c.name as category_name, u.first_name, u.last_name, u.seller_status, u.city, u.province 
                       FROM products p 
                       JOIN categories c ON p.category_id = c.id 
                       JOIN users u ON p.seller_id = u.id 
                       WHERE p.id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    header("Location: browse.php");
    exit();
}

$pageTitle = $product['name'];
include 'includes/header.php';
?>

<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="browse.php">Browse</a></li>
            <li class="breadcrumb-item active"><?php echo e($product['name']); ?></li>
        </ol>
    </nav>

    <div class="row g-5">
        <!-- Product Image -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <img src="/assets/images/products/<?php echo e($product['image'] ?: 'product_default.png'); ?>" class="img-fluid" alt="<?php echo e($product['name']); ?>">
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-lg-6">
            <div class="product-info-card p-2">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="badge bg-primary rounded-pill px-3"><?php echo e($product['category_name']); ?></span>
                    <span class="badge bg-light text-dark border rounded-pill px-3"><?php echo e($product['product_condition']); ?></span>
                </div>
                <h1 class="fw-bold mb-3"><?php echo e($product['name']); ?></h1>
                <div class="d-flex align-items-center mb-4">
                    <h2 class="fw-bold text-primary mb-0 me-3"><?php echo formatPrice($product['price']); ?></h2>
                    <span class="text-muted">| <?php echo $product['quantity']; ?> available</span>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <h5 class="fw-bold mb-3">Description</h5>
                    <p class="text-muted mb-0"><?php echo nl2br(e($product['description'])); ?></p>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="p-3 bg-white rounded-3 shadow-sm border-start border-primary border-4">
                            <small class="text-muted d-block">Location</small>
                            <span class="fw-bold"><?php echo e($product['location']); ?></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-white rounded-3 shadow-sm border-start border-warning border-4">
                            <small class="text-muted d-block">Condition</small>
                            <span class="fw-bold"><?php echo e($product['product_condition']); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Seller Info -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-light">
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <i class="fas fa-user-circle fa-3x text-secondary"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">
                                <?php echo e($product['first_name'] . ' ' . $product['last_name']); ?>
                                <?php if ($product['seller_status'] === 'Verified'): ?>
                                    <span class="badge bg-info ms-2 small" title="Verified Seller">
                                        <i class="fas fa-check-circle me-1"></i>Verified
                                    </span>
                                <?php endif; ?>
                            </h6>
                            <p class="small text-muted mb-0">Seller since <?php echo date('M Y'); ?></p>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-primary btn-sm rounded-pill flex-grow-1">Message Seller</a>
                        <a href="#" class="btn btn-outline-secondary btn-sm rounded-pill flex-grow-1">View Profile</a>
                    </div>
                </div>

                <div class="d-grid gap-3">
                    <form action="cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="action" value="add">
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-white border-end-0 rounded-start-pill ps-3">Qty</span>
                            <input type="number" name="quantity" class="form-control border-start-0 text-center" value="1" min="1" max="<?php echo $product['quantity']; ?>">
                            <button type="submit" class="btn btn-primary btn-lg rounded-end-pill px-5 fw-bold" style="background-color: #00695c; border: none;">Add to Cart</button>
                        </div>
                    </form>
                    <button class="btn btn-link text-danger text-decoration-none small"><i class="fas fa-flag me-2"></i>Report this product</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
