<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireRole('Admin');

$categories = getCategories($pdo);

$pageTitle = 'Manage Categories';
include '../includes/header.php';
?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <a href="dashboard.php" class="btn btn-light rounded-circle me-3"><i class="fas fa-arrow-left"></i></a>
            <h2 class="fw-bold mb-0">Categories</h2>
        </div>
        <button class="btn btn-primary rounded-pill px-4"><i class="fas fa-plus me-2"></i>New Category</button>
    </div>

    <div class="row g-4">
        <?php foreach ($categories as $cat): ?>
            <div class="col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100">
                    <div class="p-3 bg-light rounded-circle mx-auto mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-tag text-primary fs-4"></i>
                    </div>
                    <h5 class="fw-bold mb-2"><?php echo e($cat['name']); ?></h5>
                    <p class="small text-muted mb-4"><?php echo e($cat['description']); ?></p>
                    <div class="mt-auto">
                        <button class="btn btn-sm btn-outline-primary rounded-pill px-3 me-1">Edit</button>
                        <button class="btn btn-sm btn-outline-danger rounded-pill px-3">Delete</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
