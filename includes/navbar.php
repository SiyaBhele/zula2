<nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background-color: #00695c;">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/index.php">
            <img src="/assets/images/logo.png" alt="Zula" height="40" class="me-2">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/browse.php">Browse</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/support.php">Support</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto align-items-center">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item me-3">
                        <a class="nav-link position-relative" href="/cart.php">
                            <i class="fas fa-shopping-cart fa-lg"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark">
                                0
                            </span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle fa-lg me-2"></i>
                            <?php echo e($_SESSION['first_name']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <?php if ($_SESSION['role'] === 'Admin'): ?>
                                <li><a class="dropdown-item" href="/admin/dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard</a></li>
                            <?php elseif ($_SESSION['role'] === 'Seller'): ?>
                                <li><a class="dropdown-item" href="/seller/dashboard.php"><i class="fas fa-store me-2"></i>Seller Dashboard</a></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="/profile.php"><i class="fas fa-user me-2"></i>My Profile</a></li>
                            <li><a class="dropdown-item" href="/orders.php"><i class="fas fa-box me-2"></i>My Orders</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/login.php">Login</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-warning fw-bold px-4 rounded-pill" href="/register.php">Join Zula</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
