<?php
// Site configuration
define('SITE_NAME', 'Electronics Store');
define('SITE_URL', 'http://localhost/electronics_store');

// File upload paths
define('PRODUCT_IMG_PATH', 'assets/images/products/');
define('CATEGORY_IMG_PATH', 'assets/images/categories/');

// Session timeout (in seconds)
define('SESSION_TIMEOUT', 1800); // 30 minutes

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database configuration
require_once 'database.php';

// Start session
session_start();

// Auto-load classes
spl_autoload_register(function($class_name) {
    $file_path = __DIR__ . '/../classes/' . $class_name . '.php';
    if(file_exists($file_path)) {
        require_once $file_path;
    }
});

// Helper functions
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

function formatPrice($price) {
    return number_format($price, 2) . ' TND';
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
}

function requireLogin() {
    if(!isLoggedIn()) {
        $_SESSION['message'] = 'Please log in to access this page';
        redirect(SITE_URL . '/user/login.php');
    }
}

function requireAdmin() {
    if(!isAdmin()) {
        $_SESSION['error'] = 'You do not have permission to access this page';
        redirect(SITE_URL . '/index.php');
    }
}

// Flash messages
function setMessage($message, $type = 'success') {
    $_SESSION[$type] = $message;
}

function displayMessage() {
    if(isset($_SESSION['success'])) {
        echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
        unset($_SESSION['success']);
    }
    
    if(isset($_SESSION['error'])) {
        echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
    
    if(isset($_SESSION['warning'])) {
        echo '<div class="alert alert-warning">' . $_SESSION['warning'] . '</div>';
        unset($_SESSION['warning']);
    }
    
    if(isset($_SESSION['info'])) {
        echo '<div class="alert alert-info">' . $_SESSION['info'] . '</div>';
        unset($_SESSION['info']);
    }
}
?>
