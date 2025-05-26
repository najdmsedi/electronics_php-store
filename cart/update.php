<?php
require_once '../config/config.php';

// Check if cart exists
if (!isset($_SESSION['cart'])) {
    redirect(SITE_URL . '/cart');
}

// Check if product ID and quantity are provided
if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
    setMessage("Invalid request", "error");
    redirect(SITE_URL . '/cart');
}

$product_id = $_POST['product_id'];
$quantity = (int)$_POST['quantity'];

// Validate product exists in cart
if (!isset($_SESSION['cart'][$product_id])) {
    setMessage("Product not found in cart", "error");
    redirect(SITE_URL . '/cart');
}

// Validate quantity
if ($quantity < 1) {
    // Remove item if quantity is 0 or negative
    unset($_SESSION['cart'][$product_id]);
    setMessage("Product removed from cart");
} else {
    // Check stock availability
    $product = new Product();
    if ($product->getById($product_id)) {
        if ($quantity > $product->stock_quantity) {
            $quantity = $product->stock_quantity;
            setMessage("Quantity adjusted to available stock", "warning");
        }
    }
    
    // Update quantity
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    setMessage("Cart updated");
}

redirect(SITE_URL . '/cart');
?>
