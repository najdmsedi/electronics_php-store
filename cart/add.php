<?php
require_once '../config/config.php';

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Check if product ID is provided
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Simple add from product listing
    $product_id = $_GET['id'];
    $quantity = 1;
} elseif (isset($_POST['product_id']) && !empty($_POST['product_id'])) {
    // Add from product detail page with quantity
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
} else {
    setMessage("Product ID is required", "error");
    redirect(SITE_URL . '/products');
}

// Validate product exists
$product = new Product();
if (!$product->getById($product_id)) {
    setMessage("Product not found", "error");
    redirect(SITE_URL . '/products');
}

// Check if product is in stock
if ($product->stock_quantity < 1) {
    setMessage("Sorry, this product is out of stock", "error");
    redirect(SITE_URL . '/products/view.php?id=' . $product_id);
}

// Limit quantity to available stock
if ($quantity > $product->stock_quantity) {
    $quantity = $product->stock_quantity;
    setMessage("Quantity adjusted to available stock", "warning");
}

// Check if product already in cart
if (isset($_SESSION['cart'][$product_id])) {
    // Update quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    
    // Ensure quantity doesn't exceed stock
    if ($_SESSION['cart'][$product_id]['quantity'] > $product->stock_quantity) {
        $_SESSION['cart'][$product_id]['quantity'] = $product->stock_quantity;
        setMessage("Quantity adjusted to available stock", "warning");
    }
} else {
    // Add new product to cart
    $_SESSION['cart'][$product_id] = [
        'id' => $product_id,
        'name' => $product->name,
        'price' => $product->price,
        'quantity' => $quantity,
        'image' => $product->image
    ];
}

setMessage("{$product->name} added to cart");
redirect(SITE_URL . '/cart');
?>
