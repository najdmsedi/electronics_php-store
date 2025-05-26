<?php
$page_title = "Checkout";
include_once '../includes/header.php';

// Require login to checkout
requireLogin();

// Check if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    setMessage("Your cart is empty", "error");
    redirect(SITE_URL . '/cart');
}

// Get user information
$user = new User();
$user->getById($_SESSION['user_id']);

// Calculate cart total
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Process checkout form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create order
    $order = new Order();
    $order->user_id = $_SESSION['user_id'];
    $order->total_amount = $total;
    $order->status = 'pending';
    
    if ($order->create()) {
        // Add order items
        $success = true;
        foreach ($_SESSION['cart'] as $item) {
            if (!$order->addOrderItem($item['id'], $item['quantity'], $item['price'])) {
                $success = false;
                break;
            }
            
            // Update product stock
            $product = new Product();
            if ($product->getById($item['id'])) {
                $product->stock_quantity -= $item['quantity'];
                $product->update();
            }
        }
        
        if ($success) {
            // Clear cart
            $_SESSION['cart'] = [];
            
            setMessage("Order placed successfully! Your order number is #{$order->id}");
            redirect(SITE_URL . '/user/orders.php');
        } else {
            setMessage("Error processing order items", "error");
        }
    } else {
        setMessage("Error creating order", "error");
    }
}
?>

<h1>Checkout</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Shipping Information</h4>
            </div>
            <div class="card-body">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" id="checkout-form">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $user->first_name; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $user->last_name; ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $user->email; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $user->phone; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Shipping Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required><?php echo $user->address; ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select class="form-select" id="payment_method" name="payment_method" required>
                            <option value="">Select Payment Method</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="paypal">PayPal</option>
                            <option value="cash_on_delivery">Cash on Delivery</option>
                        </select>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">
                            I agree to the terms and conditions
                        </label>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Order Summary</h4>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span><?php echo formatPrice($total); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Shipping:</span>
                    <span>Free</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <strong>Total:</strong>
                    <strong><?php echo formatPrice($total); ?></strong>
                </div>
                
                <button type="submit" form="checkout-form" class="btn btn-success w-100">
                    <i class="fas fa-lock"></i> Place Order
                </button>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Order Items</h4>
            </div>
            <div class="card-body">
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <span><?php echo $item['name']; ?></span>
                            <small class="d-block text-muted">Qty: <?php echo $item['quantity']; ?></small>
                        </div>
                        <span><?php echo formatPrice($item['price'] * $item['quantity']); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
