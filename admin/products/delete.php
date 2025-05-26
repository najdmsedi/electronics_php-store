<?php
require_once '../../config/config.php';

// Require admin privileges
requireAdmin();

// Check if ID is set
if (!isset($_GET['id']) || empty($_GET['id'])) {
    setMessage("Product ID is required", "error");
    redirect(SITE_URL . '/admin/products');
}

// Get product details
$product = new Product();
if (!$product->getById($_GET['id'])) {
    setMessage("Product not found", "error");
    redirect(SITE_URL . '/admin/products');
}

// Delete product image if exists
if (!empty($product->image)) {
    $image_path = __DIR__ . '/../../' . PRODUCT_IMG_PATH . $product->image;
    if (file_exists($image_path)) {
        unlink($image_path);
    }
}

// Delete product
if ($product->delete()) {
    setMessage("Product deleted successfully");
} else {
    setMessage("Error deleting product", "error");
}

redirect(SITE_URL . '/admin/products');
?>
