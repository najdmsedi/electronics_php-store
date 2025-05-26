<?php
require_once '../config/config.php';

// Check if cart exists
if (!isset($_SESSION['cart'])) {
    redirect(SITE_URL . '/cart');
}

// Check if product ID is provided
if (!isset($_GET['id'])) {
    setMessage("Product ID is required", "error");
    redirect(SITE_URL . '/cart');
}

$product_id = $_GET['id'];

// Remove product from cart
if (isset($_SESSION['cart'][$product_id])) {
    $product_name = $_SESSION['cart'][$product_id]['name'];
    unset($_SESSION['cart'][$product_id]);
    setMessage("{$product_name} removed from cart");
} else {
    setMessage("Product not found in cart", "error");
}

redirect(SITE_URL . '/cart');
?>
