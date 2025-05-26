<?php
require_once '../../config/config.php';

// Require admin privileges
requireAdmin();

// Check if ID is set
if (!isset($_GET['id']) || empty($_GET['id'])) {
    setMessage("Category ID is required", "error");
    redirect(SITE_URL . '/admin/categories');
}

// Get category details
$category = new Category();
if (!$category->getById($_GET['id'])) {
    setMessage("Category not found", "error");
    redirect(SITE_URL . '/admin/categories');
}

// Delete category image if exists
if (!empty($category->image)) {
    $image_path = __DIR__ . '/../../' . CATEGORY_IMG_PATH . $category->image;
    if (file_exists($image_path)) {
        unlink($image_path);
    }
}

// Delete category
if ($category->delete()) {
    setMessage("Category deleted successfully");
} else {
    setMessage("Error deleting category", "error");
}

redirect(SITE_URL . '/admin/categories');
?>
