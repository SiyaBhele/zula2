<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

requireLogin();

if (empty($_SESSION['cart'])) {
    header("Location: browse.php");
    exit();
}

$cart_products = [];
$total = 0;
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shipping_address = trim($_POST['address']);
    $delivery_option = $_POST['delivery_option'];
    
    // Begin transaction
    $pdo->beginTransaction();
    try {
        // Create order
        $stmt = $pdo->prepare("INSERT INTO orders (buyer_id, total_amount, shipping_address, delivery_option, order_status, payment_status) VALUES (?, ?, ?, ?, 'Pending', 'Paid')");
        $stmt->execute([$_SESSION['user_id'], $total, $shipping_address, $delivery_option]);
        $order_id = $pdo->lastInsertId();
        
        // Create order items
        $stmt_item = $pdo->prepare("INSERT INTO order_items (order_id, product_id, seller_id, quantity, price_at_purchase) VALUES (?, ?, ?, ?, ?)");
        foreach ($cart_products as $p) {
            $stmt_item->execute([$order_id, $p['id'], $p['seller_id'], $p['cart_quantity'], $p['price']]);
            
            // Update product quantity
            $stmt_update = $pdo->prepare("UPDATE products SET quantity = quantity - ? WHERE id = ?");
            $stmt_update->execute([$p['cart_quantity'], $p['id']]);
        }
        
        $pdo->commit();
        $_SESSION['cart'] = []; // Clear cart
        header("Location: orders.php?success=1");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Checkout failed: " . $e->getMessage();
    }
}

$pageTitle = 'Checkout';
include 'includes/header.php';
?>

<div class="container">
    <h2 class="fw-bold mb-4">Checkout</h2>

    <form action="checkout.php" method="POST">
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <h5 class="fw-bold mb-4">Shipping Information</h5>
                    <div class="mb-3">
                        <label class="form-label">Full Address</label>
                        <textarea name="address" class="form-control rounded-3" rows="3" required placeholder="Street address, Township/Suburb, City, Province"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Delivery Option</label>
                        <select name="delivery_option" class="form-select rounded-3" required>
                            <option value="Local Pickup">Local Pickup (Free)</option>
                            <option value="Door Delivery">Door Delivery (R 100.00)</option>
                            <option value="Paxi/PostNet">Paxi/PostNet (R 60.00)</option>
                        </select>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h5 class="fw-bold mb-4">Simulated Payment</h5>
                    <p class="text-muted small mb-4">For this academic prototype, we are using a simulated payment gateway. Your transaction will be processed instantly.</p>
                    <div class="mb-3">
                        <label class="form-label">Card Holder Name</label>
                        <input type="text" class="form-control rounded-3" value="<?php echo e($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?>">
                    </div>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Card Number</label>
                            <input type="text" class="form-control rounded-3" placeholder="4242 4242 4242 4242">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">CVV</label>
                            <input type="text" class="form-control rounded-3" placeholder="123">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 100px;">
                    <h5 class="fw-bold mb-4">Order Summary</h5>
                    <?php foreach ($cart_products as $p): ?>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <div class="position-relative me-3">
                                    <img src="https://via.placeholder.com/50?text=<?php echo urlencode($p['name']); ?>" class="rounded-2" style="width: 50px; height: 50px; object-fit: cover;">
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary">
                                        <?php echo $p['cart_quantity']; ?>
                                    </span>
                                </div>
                                <h6 class="mb-0 small fw-bold"><?php echo e($p['name']); ?></h6>
                            </div>
                            <span class="small"><?php echo formatPrice($p['subtotal']); ?></span>
                        </div>
                    <?php endforeach; ?>
                    <hr class="my-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span><?php echo formatPrice($total); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold fs-5">Total</span>
                        <span class="fw-bold fs-5 text-primary"><?php echo formatPrice($total); ?></span>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold" style="background-color: #00695c; border: none;">Pay & Place Order</button>
                    <p class="text-center mt-3 small text-muted">Securely processed by Zula Pay</p>
                </div>
            </div>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
