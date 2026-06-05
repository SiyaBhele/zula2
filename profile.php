<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

requireLogin();

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$pageTitle = 'My Profile';
include 'includes/header.php';
?>

<div class="container">
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
                <div class="mb-4 position-relative d-inline-block mx-auto">
                    <i class="fas fa-user-circle fa-6x text-secondary"></i>
                    <button class="btn btn-primary btn-sm rounded-circle position-absolute bottom-0 end-0"><i class="fas fa-camera"></i></button>
                </div>
                <h4 class="fw-bold mb-1"><?php echo e($user['first_name'] . ' ' . $user['last_name']); ?></h4>
                <p class="text-muted mb-3"><?php echo e($user['email']); ?></p>
                <span class="badge bg-light text-dark border rounded-pill px-3 py-2 mb-4"><?php echo $_SESSION['role']; ?></span>
                
                <div class="list-group list-group-flush text-start border-top pt-3">
                    <a href="profile.php" class="list-group-item list-group-item-action border-0 py-3 active rounded-3"><i class="fas fa-user me-3"></i>Personal Info</a>
                    <a href="orders.php" class="list-group-item list-group-item-action border-0 py-3 rounded-3"><i class="fas fa-shopping-bag me-3"></i>My Orders</a>
                    <?php if ($_SESSION['role'] === 'Seller'): ?>
                        <a href="seller/dashboard.php" class="list-group-item list-group-item-action border-0 py-3 rounded-3"><i class="fas fa-store me-3"></i>Seller Dashboard</a>
                    <?php endif; ?>
                    <a href="logout.php" class="list-group-item list-group-item-action border-0 py-3 text-danger rounded-3"><i class="fas fa-sign-out-alt me-3"></i>Logout</a>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold mb-4">Account Settings</h5>
                <form>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">First Name</label>
                            <input type="text" class="form-control rounded-3" value="<?php echo e($user['first_name']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Last Name</label>
                            <input type="text" class="form-control rounded-3" value="<?php echo e($user['last_name']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Email Address</label>
                            <input type="email" class="form-control rounded-3" value="<?php echo e($user['email']); ?>" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Phone Number</label>
                            <input type="text" class="form-control rounded-3" value="<?php echo e($user['phone']); ?>" placeholder="081 234 5678">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Street Address</label>
                            <textarea class="form-control rounded-3" rows="2"><?php echo e($user['address']); ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">City</label>
                            <input type="text" class="form-control rounded-3" value="<?php echo e($user['city']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Province</label>
                            <select class="form-select rounded-3">
                                <option value="Gauteng" <?php echo $user['province'] === 'Gauteng' ? 'selected' : ''; ?>>Gauteng</option>
                                <option value="Western Cape" <?php echo $user['province'] === 'Western Cape' ? 'selected' : ''; ?>>Western Cape</option>
                                <option value="KwaZulu-Natal" <?php echo $user['province'] === 'KwaZulu-Natal' ? 'selected' : ''; ?>>KwaZulu-Natal</option>
                                <option value="Eastern Cape" <?php echo $user['province'] === 'Eastern Cape' ? 'selected' : ''; ?>>Eastern Cape</option>
                                <option value="Free State" <?php echo $user['province'] === 'Free State' ? 'selected' : ''; ?>>Free State</option>
                                <option value="Limpopo" <?php echo $user['province'] === 'Limpopo' ? 'selected' : ''; ?>>Limpopo</option>
                                <option value="Mpumalanga" <?php echo $user['province'] === 'Mpumalanga' ? 'selected' : ''; ?>>Mpumalanga</option>
                                <option value="North West" <?php echo $user['province'] === 'North West' ? 'selected' : ''; ?>>North West</option>
                                <option value="Northern Cape" <?php echo $user['province'] === 'Northern Cape' ? 'selected' : ''; ?>>Northern Cape</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary rounded-pill px-4" style="background-color: #00695c; border: none;">Save Changes</button>
                        <button type="button" class="btn btn-light rounded-pill px-4">Cancel</button>
                    </div>
                </form>
            </div>
            
            <div class="card border-0 shadow-sm rounded-4 p-4 mt-4">
                <h5 class="fw-bold mb-4 text-danger">Security</h5>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-1">Change Password</h6>
                        <p class="text-muted small mb-0">It's a good idea to use a strong password that you don't use elsewhere.</p>
                    </div>
                    <button class="btn btn-outline-danger btn-sm rounded-pill px-4">Update</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
