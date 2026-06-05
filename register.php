<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    redirectBasedOnRole($_SESSION['role']);
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role_id = $_POST['role_id'];

    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($role_id)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (role_id, first_name, last_name, email, password) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$role_id, $first_name, $last_name, $email, $hashed_password])) {
                $success = "Registration successful! You can now login.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}

$pageTitle = 'Register';
include 'includes/header.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0 rounded-4 overflow-hidden">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">Join Zula</h2>
                        <p class="text-muted">Create your account to start trading</p>
                    </div>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <form action="register.php" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control rounded-3" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control rounded-3" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control rounded-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control rounded-3" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">I want to:</label>
                            <div class="d-flex gap-3">
                                <div class="form-check card p-3 flex-grow-1 text-center border-secondary-subtle">
                                    <input class="form-check-input d-none" type="radio" name="role_id" id="roleBuyer" value="4" checked>
                                    <label class="form-check-label stretched-link cursor-pointer" for="roleBuyer">
                                        <i class="fas fa-shopping-cart d-block fs-3 mb-2 text-primary"></i>
                                        Buy Items
                                    </label>
                                </div>
                                <div class="form-check card p-3 flex-grow-1 text-center border-secondary-subtle">
                                    <input class="form-check-input d-none" type="radio" name="role_id" id="roleSeller" value="3">
                                    <label class="form-check-label stretched-link cursor-pointer" for="roleSeller">
                                        <i class="fas fa-store d-block fs-3 mb-2 text-warning"></i>
                                        Sell Items
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill" style="background-color: #00695c; border: none;">Create Account</button>
                        </div>
                        <div class="text-center">
                            <p class="mb-0 text-muted">Already have an account? <a href="login.php" class="text-warning text-decoration-none fw-bold">Login here</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.cursor-pointer { cursor: pointer; }
.form-check-input:checked + .form-check-label {
    font-weight: bold;
}
.form-check:has(.form-check-input:checked) {
    border-color: #00695c !important;
    background-color: #e0f2f1;
}
</style>

<?php include 'includes/footer.php'; ?>
