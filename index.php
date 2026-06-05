<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

// Get featured categories
$categories = getCategories($pdo);

// Get featured approved products
$stmt = $pdo->query("SELECT p.*, u.first_name, u.last_name, u.seller_status FROM products p JOIN users u ON p.seller_id = u.id WHERE p.status = 'Approved' ORDER BY p.created_at DESC LIMIT 8");
$featured_products = $stmt->fetchAll();

$pageTitle = 'Home';
include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section text-white py-5 mb-5" style="background: linear-gradient(rgba(0, 105, 92, 0.9), rgba(0, 105, 92, 0.9)), url('https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?auto=format&fit=crop&w=1200&q=80'); background-size: cover; background-position: center;">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3">Buy. Sell. Trust Local.</h1>
                <p class="lead mb-4">Zula is South Africa's trusted marketplace for informal traders, students, and township entrepreneurs.</p>
                <div class="d-flex gap-3">
                    <a href="browse.php" class="btn btn-warning btn-lg px-4 rounded-pill fw-bold">Start Buying</a>
                    <a href="register.php" class="btn btn-outline-light btn-lg px-4 rounded-pill fw-bold">Become a Seller</a>
                </div>
            </div>
            <div class="col-lg-5 offset-lg-1 d-none d-lg-block">
                <div class="card bg-white text-dark p-4 rounded-4 shadow-lg border-0">
                    <h4 class="fw-bold mb-3">Search for anything</h4>
                    <form action="browse.php" method="GET">
                        <div class="mb-3">
                            <input type="text" name="search" class="form-control form-control-lg rounded-3" placeholder="What are you looking for?">
                        </div>
                        <div class="mb-3">
                            <select name="category" class="form-select form-select-lg rounded-3">
                                <option value="">All Categories</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>"><?php echo e($cat['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill" style="background-color: #00695c; border: none;">Search Zula</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container">
    <!-- Featured Categories -->
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="fw-bold mb-0">Browse by Category</h2>
                <p class="text-muted">Find exactly what you need in our local marketplace</p>
            </div>
        </div>
        <div class="row g-4">
            <?php foreach (array_slice($categories, 0, 6) as $cat): ?>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="browse.php?category=<?php echo $cat['id']; ?>" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm text-center p-3 rounded-4 category-card">
<?php 
                                    $cat_img = 'category_default.png';
                                    switch($cat['name']) {
                                        case 'Electronics': $cat_img = 'cat_electronics.jpg'; break;
                                        case 'Fashion': $cat_img = 'cat_fashion.jpg'; break;
                                        case 'Home & Kitchen': $cat_img = 'cat_home.jpg'; break;
                                        case 'Services': $cat_img = 'cat_services.jpg'; break;
                                        case 'Food & Groceries': $cat_img = 'cat_food.jpg'; break;
                                        case 'Books & Media': $cat_img = 'cat_books.jpg'; break;
                                        case 'Sports & Outdoors': $cat_img = 'cat_sports.jpg'; break;
                                    }
                                ?>
                                <div class="category-icon mb-2 mx-auto overflow-hidden" style="width: 80px; height: 80px; border-radius: 50%;">
                                    <img src="/assets/images/<?php echo $cat_img; ?>" alt="<?php echo e($cat['name']); ?>" class="img-fluid h-100 w-100" style="object-fit: cover;">
                                </div>
                            <h6 class="fw-bold text-dark mb-0"><?php echo e($cat['name']); ?></h6>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="fw-bold mb-0">Recently Added</h2>
                <p class="text-muted">Fresh listings from sellers in your area</p>
            </div>
            <a href="browse.php" class="btn btn-outline-primary rounded-pill">View All</a>
        </div>
        <div class="row g-4">
            <?php foreach ($featured_products as $product): ?>
                <div class="col-6 col-md-4 col-lg-3">
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
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-primary fs-5"><?php echo formatPrice($product['price']); ?></span>
                                <a href="product-details.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">View</a>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex align-items-center small text-muted">
                                <i class="fas fa-user-circle me-1"></i>
                                <span class="text-truncate"><?php echo e($product['first_name']); ?></span>
                                <?php if ($product['seller_status'] === 'Verified'): ?>
                                    <i class="fas fa-check-circle text-info ms-1" title="Verified Seller"></i>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- How it Works -->
    <section class="py-5 bg-white rounded-4 shadow-sm mb-5 px-4">
        <div class="text-center mb-5">
            <h2 class="fw-bold">How Zula Works</h2>
            <p class="text-muted">Simple, safe, and local trading in four easy steps</p>
        </div>
        <div class="row g-4 text-center">
            <div class="col-md-3">
                <div class="mb-3 text-primary"><i class="fas fa-user-plus fa-3x"></i></div>
                <h5 class="fw-bold">1. Register</h5>
                <p class="small text-muted">Create your free account as a buyer or seller in seconds.</p>
            </div>
            <div class="col-md-3">
                <div class="mb-3 text-warning"><i class="fas fa-list fa-3x"></i></div>
                <h5 class="fw-bold">2. List or Browse</h5>
                <p class="small text-muted">Sellers list items; buyers browse local deals with ease.</p>
            </div>
            <div class="col-md-3">
                <div class="mb-3 text-success"><i class="fas fa-handshake fa-3x"></i></div>
                <h5 class="fw-bold">3. Buy/Sell Securely</h5>
                <p class="small text-muted">Connect and complete transactions with built-in trust.</p>
            </div>
            <div class="col-md-3">
                <div class="mb-3 text-info"><i class="fas fa-star fa-3x"></i></div>
                <h5 class="fw-bold">4. Rate & Build Trust</h5>
                <p class="small text-muted">Rate your experience to help our community grow safely.</p>
            </div>
        </div>
    </section>

    <!-- Trust Features -->
    <section class="mb-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1556740734-7f9a2b7a0f4d?auto=format&fit=crop&w=800&q=80" class="img-fluid rounded-4 shadow" alt="Trust Local">
            </div>
            <div class="col-lg-6">
                <h2 class="fw-bold mb-4">Why Choose Zula?</h2>
                <div class="d-flex mb-4">
                    <div class="me-3 text-success"><i class="fas fa-shield-alt fa-2x"></i></div>
                    <div>
                        <h5 class="fw-bold mb-1">Verified Sellers</h5>
                        <p class="text-muted">We verify local entrepreneurs to ensure a safer trading environment for everyone.</p>
                    </div>
                </div>
                <div class="d-flex mb-4">
                    <div class="me-3 text-warning"><i class="fas fa-comments fa-2x"></i></div>
                    <div>
                        <h5 class="fw-bold mb-1">Reviews & Ratings</h5>
                        <p class="text-muted">Real feedback from local buyers helps you make informed decisions.</p>
                    </div>
                </div>
                <div class="d-flex mb-4">
                    <div class="me-3 text-primary"><i class="fas fa-headset fa-2x"></i></div>
                    <div>
                        <h5 class="fw-bold mb-1">Dispute Support</h5>
                        <p class="text-muted">Our moderation team is here to help resolve any issues that may arise.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.category-card:hover {
    transform: translateY(-5px);
    transition: transform 0.3s ease;
    background-color: #f8f9fa !important;
}
.product-card:hover {
    transform: translateY(-5px);
    transition: transform 0.3s ease;
}
</style>

<?php include 'includes/footer.php'; ?>
