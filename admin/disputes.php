<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireRole('Admin');

$stmt = $pdo->query("SELECT d.*, u.first_name, u.last_name FROM disputes d JOIN users u ON d.user_id = u.id ORDER BY d.created_at DESC");
$disputes = $stmt->fetchAll();

$pageTitle = 'Manage Disputes';
include '../includes/header.php';
?>

<div class="container-fluid px-4">
    <div class="d-flex align-items-center mb-4">
        <a href="dashboard.php" class="btn btn-light rounded-circle me-3"><i class="fas fa-arrow-left"></i></a>
        <h2 class="fw-bold mb-0">Dispute Management</h2>
    </div>

    <div class="row g-4">
        <?php if (empty($disputes)): ?>
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                    <h4 class="fw-bold">No active disputes</h4>
                    <p class="text-muted">Great job! All customer issues have been resolved.</p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($disputes as $d): ?>
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="fw-bold mb-1"><?php echo e($d['reason']); ?></h5>
                                <p class="small text-muted mb-0">Raised by: <?php echo e($d['first_name'] . ' ' . $d['last_name']); ?> | Order #ZULA-<?php echo str_pad($d['order_id'], 5, '0', STR_PAD_LEFT); ?></p>
                            </div>
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><?php echo $d['status']; ?></span>
                        </div>
                        <p class="text-muted mb-4"><?php echo e($d['description']); ?></p>
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary btn-sm rounded-pill px-4">Resolve</button>
                            <button class="btn btn-outline-secondary btn-sm rounded-pill px-4">Contact User</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
