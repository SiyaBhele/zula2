<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireRole('Admin');

$pageTitle = 'Platform Reports';
include '../includes/header.php';
?>

<div class="container-fluid px-4">
    <div class="d-flex align-items-center mb-4">
        <a href="dashboard.php" class="btn btn-light rounded-circle me-3"><i class="fas fa-arrow-left"></i></a>
        <h2 class="fw-bold mb-0">Platform Reports</h2>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold mb-4">Sales Overview</h5>
                <div class="bg-light rounded-4 p-5 text-center">
                    <i class="fas fa-chart-line fa-4x text-muted mb-3"></i>
                    <p class="text-muted">Sales chart will be rendered here in the live version.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold mb-4">User Growth</h5>
                <div class="bg-light rounded-4 p-5 text-center">
                    <i class="fas fa-chart-area fa-4x text-muted mb-3"></i>
                    <p class="text-muted">Growth analytics will be rendered here in the live version.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-4">
        <h5 class="fw-bold mb-4">Recent Platform Activity</h5>
        <div class="list-group list-group-flush">
            <div class="list-group-item border-0 py-3 d-flex align-items-center">
                <div class="p-2 bg-success-subtle rounded-circle me-3"><i class="fas fa-shopping-cart text-success"></i></div>
                <div>
                    <h6 class="mb-0 fw-bold">New Order Placed</h6>
                    <p class="small text-muted mb-0">Order #ZULA-00124 by Thabo Mbeki</p>
                </div>
                <span class="ms-auto small text-muted">2 mins ago</span>
            </div>
            <div class="list-group-item border-0 py-3 d-flex align-items-center">
                <div class="p-2 bg-primary-subtle rounded-circle me-3"><i class="fas fa-user-plus text-primary"></i></div>
                <div>
                    <h6 class="mb-0 fw-bold">New Seller Registered</h6>
                    <p class="small text-muted mb-0">Lerato Khumalo joined as a seller</p>
                </div>
                <span class="ms-auto small text-muted">45 mins ago</span>
            </div>
            <div class="list-group-item border-0 py-3 d-flex align-items-center">
                <div class="p-2 bg-warning-subtle rounded-circle me-3"><i class="fas fa-box text-warning"></i></div>
                <div>
                    <h6 class="mb-0 fw-bold">Product Approval Requested</h6>
                    <p class="small text-muted mb-0">"African Print Dress" listed by Sipho Dlamini</p>
                </div>
                <span class="ms-auto small text-muted">2 hours ago</span>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
