<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    redirectBasedOnRole($_SESSION['role']);
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $stmt = $pdo->prepare("SELECT u.*, r.role_name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['role'] = $user['role_name'];
            
            redirectBasedOnRole($user['role_name']);
        } else {
            $error = "Invalid email or password.";
        }
    }
}

$pageTitle = 'Login';
include 'includes/header.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow border-0 rounded-4 overflow-hidden">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">Welcome Back</h2>
                        <p class="text-muted">Login to your Zula account</p>
                    </div>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form action="login.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control form-control-lg rounded-3" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control form-control-lg rounded-3" required>
                        </div>
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill" style="background-color: #00695c; border: none;">Login</button>
                        </div>
                        <div class="text-center">
                            <p class="mb-0 text-muted">Don't have an account? <a href="register.php" class="text-warning text-decoration-none fw-bold">Register here</a></p>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="mt-4 p-3 bg-white rounded-3 shadow-sm border text-center">
                <h6 class="fw-bold mb-2">Test Credentials:</h6>
                <div class="small">
                    <strong>Admin:</strong> admin@zula.co.za / Admin@123<br>
                    <strong>Seller:</strong> seller@zula.co.za / Seller@123<br>
                    <strong>Buyer:</strong> buyer@zula.co.za / Buyer@123
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
