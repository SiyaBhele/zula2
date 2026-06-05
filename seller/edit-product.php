<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireRole('Seller');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$seller_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND seller_id = ?");
$stmt->execute([$id, $seller_id]);
$product = $stmt->fetch();

if (!$product) {
    header("Location: manage-products.php");
    exit();
}

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

    if (empty($name) || empty($category_id) || empty($description) || empty($price) || empty($quantity) || empty($condition) || empty($location)) {
        $error = "All fields are required.";
    } else {
        $stmt = $pdo->prepare("UPDATE products SET category_id = ?, name = ?, description = ?, price = ?, quantity = ?, product_condition = ?, location = ? WHERE id = ? AND seller_id = ?");
        if ($stmt->execute([$category_id, $name, $description, $price, $quantity, $condition, $location, $id, $seller_id])) {
            $success = "Product updated successfully!";
            // Refresh product data
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND seller_id = ?");
            $stmt->execute([$id, $seller_id]);
            $product = $stmt->fetch();
        } else {
            $error = "Failed to update product. Please try again.";
        }
    }
}

$categories = getCategories($pdo);
$pageTitle = 'Edit Product';
include '../includes/header.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center mb-4">
                <a href="manage-products.php" class="btn btn-light rounded-circle me-3"><i class="fas fa-arrow-left"></i></a>
                <h2 class="fw-bold mb-0">Edit Product</h2>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger rounded-3"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success rounded-3"><?php echo $success; ?></div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                <form action="edit-product.php?id=<?php echo $id; ?>" method="POST">
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label fw-bold">Product Name</label>
                            <input type="text" name="name" class="form-control rounded-3" value="<?php echo e($product['name']); ?>" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Category</label>
                            <select name="category_id" class="form-select rounded-3" required>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>" <?php echo $product['category_id'] == $cat['id'] ? 'selected' : ''; ?>><?php echo e($cat['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Condition</label>
                            <select name="condition" class="form-select rounded-3" required>
                                <option value="New" <?php echo $product['product_condition'] === 'New' ? 'selected' : ''; ?>>New</option>
                                <option value="Used" <?php echo $product['product_condition'] === 'Used' ? 'selected' : ''; ?>>Used</option>
                                <option value="Refurbished" <?php echo $product['product_condition'] === 'Refurbished' ? 'selected' : ''; ?>>Refurbished</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Price (ZAR)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light rounded-start-3">R</span>
                                <input type="number" step="0.01" name="price" class="form-control rounded-end-3" value="<?php echo $product['price']; ?>" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Quantity Available</label>
                            <input type="number" name="quantity" class="form-control rounded-3" value="<?php echo $product['quantity']; ?>" min="0" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Location</label>
                            <input type="text" name="location" class="form-control rounded-3" value="<?php echo e($product['location']); ?>" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description" class="form-control rounded-3" rows="5" required><?php echo e($product['description']); ?></textarea>
                        </div>

                        <div class="col-12 mt-5">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold" style="background-color: #00695c; border: none;">Update Product</button>
                                <a href="manage-products.php" class="btn btn-light btn-lg rounded-pill">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
