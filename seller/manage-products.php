<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireRole('Seller');

$seller_id = $_SESSION['user_id'];

// Handle deletion
if (isset($_POST['delete_id'])) {
    $delete_id = (int)$_POST['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ? AND seller_id = ?");
    $stmt->execute([$delete_id, $seller_id]);
    header("Location: manage-products.php?deleted=1");
    exit();
}

$stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.seller_id = ? ORDER BY p.created_at DESC");
$stmt->execute([$seller_id]);
$products = $stmt->fetchAll();

$pageTitle = 'Manage My Products';
include '../includes/header.php';
?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <a href="dashboard.php" class="btn btn-light rounded-circle me-3"><i class="fas fa-arrow-left"></i></a>
            <h2 class="fw-bold mb-0">My Products</h2>
        </div>
        <a href="add-product.php" class="btn btn-primary rounded-pill px-4">
            <i class="fas fa-plus me-2"></i>Add New
        </a>
    </div>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success rounded-3">Product deleted successfully.</div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 border-0">Product</th>
                        <th class="py-3 border-0">Category</th>
                        <th class="py-3 border-0">Price</th>
                        <th class="py-3 border-0">Qty</th>
                        <th class="py-3 border-0">Status</th>
                        <th class="py-3 border-0">Listed On</th>
                        <th class="pe-4 py-3 border-0 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">You haven't listed any products yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $p): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center py-2">
                                        <img src="https://via.placeholder.com/50?text=P" class="rounded-3 me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                        <div>
                                            <h6 class="fw-bold mb-0 small"><?php echo e($p['name']); ?></h6>
                                            <small class="text-muted"><?php echo e($p['product_condition']); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="small"><?php echo e($p['category_name']); ?></span></td>
                                <td class="fw-bold"><?php echo formatPrice($p['price']); ?></td>
                                <td><?php echo $p['quantity']; ?></td>
                                <td><span class="badge <?php echo getStatusBadgeClass($p['status']); ?> rounded-pill small"><?php echo $p['status']; ?></span></td>
                                <td class="small text-muted"><?php echo date('d M Y', strtotime($p['created_at'])); ?></td>
                                <td class="pe-4 text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                            <li><a class="dropdown-item" href="edit-product.php?id=<?php echo $p['id']; ?>"><i class="fas fa-edit me-2 text-primary"></i>Edit</a></li>
                                            <li><a class="dropdown-item" href="../product-details.php?id=<?php echo $p['id']; ?>" target="_blank"><i class="fas fa-eye me-2 text-info"></i>View Live</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="manage-products.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?')">
                                                    <input type="hidden" name="delete_id" value="<?php echo $p['id']; ?>">
                                                    <button type="submit" class="dropdown-item text-danger"><i class="fas fa-trash-alt me-2"></i>Delete</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
