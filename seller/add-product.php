<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireRole('Seller');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $category_id = $_POST['category_id'];
    $description = trim($_POST['description']);
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $condition = $_POST['condition'];
    $location = trim($_POST['location']);
    $seller_id = $_SESSION['user_id'];

    if (empty($name) || empty($category_id) || empty($description) || empty($price) || empty($quantity) || empty($condition) || empty($location)) {
        $error = "All fields are required.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO products (seller_id, category_id, name, description, price, quantity, product_condition, location, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
        if ($stmt->execute([$seller_id, $category_id, $name, $description, $price, $quantity, $condition, $location])) {
            $success = "Product listed successfully and is pending approval!";
        } else {
            $error = "Failed to list product. Please try again.";
        }
    }
}

$categories = getCategories($pdo);
$pageTitle = 'Add New Product';
include '../includes/header.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center mb-4">
                <a href="dashboard.php" class="btn btn-light rounded-circle me-3"><i class="fas fa-arrow-left"></i></a>
                <h2 class="fw-bold mb-0">List a New Product</h2>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger rounded-3"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success rounded-3"><?php echo $success; ?></div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                <form action="add-product.php" method="POST" enctype="multipart/form-data">
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label fw-bold">Product Name</label>
                            <input type="text" name="name" class="form-control rounded-3" placeholder="e.g. iPhone 12 Pro" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Category</label>
                            <select name="category_id" class="form-select rounded-3" required>
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>"><?php echo e($cat['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Condition</label>
                            <select name="condition" class="form-select rounded-3" required>
                                <option value="New">New</option>
                                <option value="Used">Used</option>
                                <option value="Refurbished">Refurbished</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Price (ZAR)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light rounded-start-3">R</span>
                                <input type="number" step="0.01" name="price" class="form-control rounded-end-3" placeholder="0.00" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Quantity Available</label>
                            <input type="number" name="quantity" class="form-control rounded-3" value="1" min="1" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Location</label>
                            <input type="text" name="location" class="form-control rounded-3" placeholder="e.g. Soweto, Johannesburg" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description" class="form-control rounded-3" rows="5" placeholder="Describe your product in detail..." required></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Product Image</label>
                            <div class="border-dashed p-4 text-center rounded-4 bg-light">
                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                <p class="mb-2 small">Click to upload or drag and drop</p>
                                <p class="text-muted extra-small">PNG, JPG, JPEG (Max 2MB)</p>
                                <input type="file" name="image" class="form-control form-control-sm d-none" id="productImage">
                                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-4" onclick="document.getElementById('productImage').click()">Select File</button>
                            </div>
                        </div>

                        <div class="col-12 mt-5">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold" style="background-color: #00695c; border: none;">Submit for Approval</button>
                            </div>
                            <p class="text-center text-muted small mt-3">By listing this product, you agree to Zula's Seller Terms & Conditions.</p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.border-dashed {
    border: 2px dashed #dee2e6;
}
.extra-small {
    font-size: 0.75rem;
}
</style>

<?php include '../includes/footer.php'; ?>
