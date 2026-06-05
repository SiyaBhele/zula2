<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category_id = isset($_GET['category']) ? $_GET['category'] : '';
$condition = isset($_GET['condition']) ? $_GET['condition'] : '';
$min_price = isset($_GET['min_price']) ? $_GET['min_price'] : '';
$max_price = isset($_GET['max_price']) ? $_GET['max_price'] : '';

$query = "SELECT p.*, u.first_name, u.last_name, u.seller_status FROM products p JOIN users u ON p.seller_id = u.id WHERE p.status = 'Approved'";
$params = [];

if ($search) {
    $query .= " AND (p.name LIKE ? OR p.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($category_id) {
    $query .= " AND p.category_id = ?";
    $params[] = $category_id;
}

if ($condition) {
    $query .= " AND p.product_condition = ?";
    $params[] = $condition;
}

if ($min_price) {
    $query .= " AND p.price >= ?";
    $params[] = $min_price;
}

if ($max_price) {
    $query .= " AND p.price <= ?";
    $params[] = $max_price;
}

$query .= " ORDER BY p.created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();

$categories = getCategories($pdo);

$pageTitle = 'Browse Products';
include 'includes/header.php';
?>

<div class="container">
    <div class="row g-4">
        <!-- Filters Sidebar -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 100px;">
                <h5 class="fw-bold mb-4">Filters</h5>
                <form action="browse.php" method="GET">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Search</label>
                        <input type="text" name="search" class="form-control rounded-3" value="<?php echo e($search); ?>" placeholder="Keywords...">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Category</label>
                        <select name="category" class="form-select rounded-3">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo $category_id == $cat['id'] ? 'selected' : ''; ?>><?php echo e($cat['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Condition</label>
                        <select name="condition" class="form-select rounded-3">
                            <option value="">Any Condition</option>
                            <option value="New" <?php echo $condition == 'New' ? 'selected' : ''; ?>>New</option>
                            <option value="Used" <?php echo $condition == 'Used' ? 'selected' : ''; ?>>Used</option>
                            <option value="Refurbished" <?php echo $condition == 'Refurbished' ? 'selected' : ''; ?>>Refurbished</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">Price Range</label>
                        <div class="d-flex gap-2">
                            <input type="number" name="min_price" class="form-control rounded-3" value="<?php echo e($min_price); ?>" placeholder="Min">
                            <input type="number" name="max_price" class="form-control rounded-3" value="<?php echo e($max_price); ?>" placeholder="Max">
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill">Apply Filters</button>
                        <a href="browse.php" class="btn btn-light rounded-pill">Clear All</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0"><?php echo count($products); ?> Products Found</h4>
                <div class="d-flex align-items-center">
                    <span class="text-muted small me-2">Sort by:</span>
                    <select class="form-select form-select-sm rounded-3 border-0 shadow-sm" style="width: auto;">
                        <option>Newest First</option>
                        <option>Price: Low to High</option>
                        <option>Price: High to Low</option>
                    </select>
                </div>
            </div>

            <?php if (empty($products)): ?>
                <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                    <i class="fas fa-search fa-4x text-muted mb-3"></i>
                    <h5 class="fw-bold">No products found</h5>
                    <p class="text-muted">Try adjusting your filters or search keywords.</p>
                    <a href="browse.php" class="btn btn-primary rounded-pill px-4">Browse All Products</a>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($products as $product): ?>
                        <div class="col-md-6 col-xl-4">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden product-card">
                                <div class="position-relative">
                                    <img src="/assets/images/products/<?php echo e($product['image'] ?: 'product_default.png'); ?>" class="card-img-top" alt="<?php echo e($product['name']); ?>" style="height: 200px; object-fit: cover;">
                                    <span class="position-absolute top-0 end-0 m-2 badge bg-white text-dark shadow-sm rounded-pill">
                                        <?php echo e($product['product_condition']); ?>
                                    </span>
                                </div>
                                <div class="card-body p-3">
                                    <p class="text-muted small mb-1"><?php echo e($product['location']); ?></p>
                                    <h6 class="fw-bold mb-2 text-truncate"><?php echo e($product['name']); ?></h6>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold text-primary fs-5"><?php echo formatPrice($product['price']); ?></span>
                                        <span class="small text-muted"><?php echo $product['quantity']; ?> available</span>
                                    </div>
                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center small text-muted">
                                            <i class="fas fa-user-circle me-1"></i>
                                            <span class="text-truncate" style="max-width: 80px;"><?php echo e($product['first_name']); ?></span>
                                            <?php if ($product['seller_status'] === 'Verified'): ?>
                                                <i class="fas fa-check-circle text-info ms-1" title="Verified Seller"></i>
                                            <?php endif; ?>
                                        </div>
                                        <a href="product-details.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-primary rounded-pill px-3">View Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.product-card:hover {
    transform: translateY(-5px);
    transition: transform 0.3s ease;
}
</style>

<?php include 'includes/footer.php'; ?>
