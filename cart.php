<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

requireLogin();

// Simple session-based cart for the prototype
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action === 'add') {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
    header("Location: cart.php");
    exit();
}

if ($action === 'remove') {
    $product_id = (int)$_POST['product_id'];
    unset($_SESSION['cart'][$product_id]);
    header("Location: cart.php");
    exit();
}

if ($action === 'update') {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    if ($quantity <= 0) {
        unset($_SESSION['cart'][$product_id]);
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
    header("Location: cart.php");
    exit();
}

$cart_products = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $placeholders = str_repeat('?,', count($ids) - 1) . '?';
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $db_products = $stmt->fetchAll();

    foreach ($db_products as $p) {
        $qty = $_SESSION['cart'][$p['id']];
        $subtotal = $p['price'] * $qty;
        $total += $subtotal;
        $p['cart_quantity'] = $qty;
        $p['subtotal'] = $subtotal;
        $cart_products[] = $p;
    }
}

$pageTitle = 'Shopping Cart';
include 'includes/header.php';
?>

<div class="container">
    <h2 class="fw-bold mb-4">Your Shopping Cart</h2>

    <div class="row g-4">
        <div class="col-lg-8">
            <?php if (empty($cart_products)): ?>
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                    <div class="mb-4 text-muted"><i class="fas fa-shopping-basket fa-5x"></i></div>
                    <h4 class="fw-bold">Your cart is empty</h4>
                    <p class="text-muted mb-4">Looks like you haven't added anything to your cart yet.</p>
                    <a href="browse.php" class="btn btn-primary rounded-pill px-5">Start Shopping</a>
                </div>
            <?php else: ?>
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 border-0">Product</th>
                                    <th class="py-3 border-0">Price</th>
                                    <th class="py-3 border-0">Quantity</th>
                                    <th class="py-3 border-0">Subtotal</th>
                                    <th class="py-3 border-0 pe-4"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart_products as $p): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center py-2">
                                                <img src="https://via.placeholder.com/80?text=<?php echo urlencode($p['name']); ?>" class="rounded-3 me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                <div>
                                                    <h6 class="fw-bold mb-0"><?php echo e($p['name']); ?></h6>
                                                    <small class="text-muted"><?php echo e($p['location']); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo formatPrice($p['price']); ?></td>
                                        <td>
                                            <form action="cart.php" method="POST" class="d-flex align-items-center" style="width: 120px;">
                                                <input type="hidden" name="action" value="update">
                                                <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                                                <input type="number" name="quantity" class="form-control form-control-sm text-center rounded-3" value="<?php echo $p['cart_quantity']; ?>" min="1" onchange="this.form.submit()">
                                            </form>
                                        </td>
                                        <td class="fw-bold text-primary"><?php echo formatPrice($p['subtotal']); ?></td>
                                        <td class="pe-4 text-end">
                                            <form action="cart.php" method="POST" class="d-inline">
                                                <input type="hidden" name="action" value="remove">
                                                <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                                                <button type="submit" class="btn btn-link text-danger p-0"><i class="fas fa-trash-alt"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold mb-4">Order Summary</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal</span>
                    <span><?php echo formatPrice($total); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Delivery</span>
                    <span class="text-success">Calculated at checkout</span>
                </div>
                <hr class="my-3">
                <div class="d-flex justify-content-between mb-4">
                    <span class="fw-bold fs-5">Total</span>
                    <span class="fw-bold fs-5 text-primary"><?php echo formatPrice($total); ?></span>
                </div>
                <div class="d-grid gap-2">
                    <a href="checkout.php" class="btn btn-primary btn-lg rounded-pill <?php echo empty($cart_products) ? 'disabled' : ''; ?>" style="background-color: #00695c; border: none;">Proceed to Checkout</a>
                    <a href="browse.php" class="btn btn-light rounded-pill">Continue Shopping</a>
                </div>
                
                <div class="mt-4 p-3 bg-light rounded-3 small text-muted">
                    <i class="fas fa-info-circle me-2"></i>
                    All transactions on Zula are protected by our dispute management system.
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
