<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireRole('Admin');

// Handle status update
if (isset($_POST['update_status'])) {
    $product_id = (int)$_POST['product_id'];
    $status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE products SET status = ? WHERE id = ?");
    $stmt->execute([$status, $product_id]);
    header("Location: manage-products.php?updated=1");
    exit();
}

$stmt = $pdo->query("SELECT p.*, u.first_name, u.last_name, c.name as category_name FROM products p JOIN users u ON p.seller_id = u.id JOIN categories c ON p.category_id = c.id ORDER BY p.status = 'Pending' DESC, p.created_at DESC");
$products = $stmt->fetchAll();

$pageTitle = 'Manage Products';
include '../includes/header.php';
?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <a href="dashboard.php" class="btn btn-light rounded-circle me-3"><i class="fas fa-arrow-left"></i></a>
            <h2 class="fw-bold mb-0">Product Moderation</h2>
        </div>
    </div>

    <?php if (isset($_GET['updated'])): ?>
        <div class="alert alert-success rounded-3">Product status updated successfully.</div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 border-0">Product</th>
                        <th class="py-3 border-0">Seller</th>
                        <th class="py-3 border-0">Category</th>
                        <th class="py-3 border-0">Price</th>
                        <th class="py-3 border-0">Status</th>
                        <th class="pe-4 py-3 border-0 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
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
                            <td><span class="small"><?php echo e($p['first_name'] . ' ' . $p['last_name']); ?></span></td>
                            <td><span class="small"><?php echo e($p['category_name']); ?></span></td>
                            <td class="fw-bold"><?php echo formatPrice($p['price']); ?></td>
                            <td><span class="badge <?php echo getStatusBadgeClass($p['status']); ?> rounded-pill small"><?php echo $p['status']; ?></span></td>
                            <td class="pe-4 text-end">
                                <form action="manage-products.php" method="POST" class="d-flex gap-2 justify-content-end">
                                    <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                                    <input type="hidden" name="update_status" value="1">
                                    <select name="status" class="form-select form-select-sm rounded-pill" style="width: 120px;">
                                        <option value="Pending" <?php echo $p['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="Approved" <?php echo $p['status'] === 'Approved' ? 'selected' : ''; ?>>Approve</option>
                                        <option value="Rejected" <?php echo $p['status'] === 'Rejected' ? 'selected' : ''; ?>>Reject</option>
                                        <option value="Sold" <?php echo $p['status'] === 'Sold' ? 'selected' : ''; ?>>Sold</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3">Set</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
