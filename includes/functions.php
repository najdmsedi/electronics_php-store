<?php
/**
 * Helper functions for the Electronics Store
 */

/**
 * Generate a random string
 * 
 * @param int $length Length of the random string
 * @return string Random string
 */
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/**
 * Validate email address
 * 
 * @param string $email Email address to validate
 * @return bool True if valid, false otherwise
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Sanitize input data
 * 
 * @param string $data Data to sanitize
 * @return string Sanitized data
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Format date
 * 
 * @param string $date Date to format
 * @param string $format Format string
 * @return string Formatted date
 */
function formatDate($date, $format = 'M d, Y') {
    return date($format, strtotime($date));
}

/**
 * Get pagination links
 * 
 * @param int $current_page Current page number
 * @param int $total_pages Total number of pages
 * @param string $url_pattern URL pattern with %d placeholder for page number
 * @return string HTML pagination links
 */
function getPaginationLinks($current_page, $total_pages, $url_pattern) {
    $links = '';
    
    if ($total_pages <= 1) {
        return $links;
    }
    
    $links .= '<nav aria-label="Page navigation"><ul class="pagination">';
    
    // Previous link
    if ($current_page > 1) {
        $links .= '<li class="page-item"><a class="page-link" href="' . sprintf($url_pattern, $current_page - 1) . '">&laquo; Previous</a></li>';
    } else {
        $links .= '<li class="page-item disabled"><a class="page-link" href="#">&laquo; Previous</a></li>';
    }
    
    // Page links
    $range = 2;
    for ($i = max(1, $current_page - $range); $i <= min($total_pages, $current_page + $range); $i++) {
        if ($i == $current_page) {
            $links .= '<li class="page-item active"><a class="page-link" href="#">' . $i . '</a></li>';
        } else {
            $links .= '<li class="page-item"><a class="page-link" href="' . sprintf($url_pattern, $i) . '">' . $i . '</a></li>';
        }
    }
    
    // Next link
    if ($current_page < $total_pages) {
        $links .= '<li class="page-item"><a class="page-link" href="' . sprintf($url_pattern, $current_page + 1) . '">Next &raquo;</a></li>';
    } else {
        $links .= '<li class="page-item disabled"><a class="page-link" href="#">Next &raquo;</a></li>';
    }
    
    $links .= '</ul></nav>';
    
    return $links;
}

/**
 * Get order status badge
 * 
 * @param string $status Order status
 * @return string HTML badge with appropriate color
 */
function getOrderStatusBadge($status) {
    $badge_class = '';
    switch ($status) {
        case 'pending':
            $badge_class = 'bg-warning text-dark';
            break;
        case 'processing':
            $badge_class = 'bg-info text-white';
            break;
        case 'shipped':
            $badge_class = 'bg-primary text-white';
            break;
        case 'delivered':
            $badge_class = 'bg-success text-white';
            break;
        case 'cancelled':
            $badge_class = 'bg-danger text-white';
            break;
        default:
            $badge_class = 'bg-secondary text-white';
    }
    
    return '<span class="badge ' . $badge_class . '">' . ucfirst($status) . '</span>';
}

/**
 * Calculate cart total
 * 
 * @param array $cart Cart items
 * @return float Cart total
 */
function calculateCartTotal($cart) {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

/**
 * Get related products
 * 
 * @param int $product_id Current product ID
 * @param int $category_id Category ID
 * @param int $limit Number of products to return
 * @return array Related products
 */
function getRelatedProducts($product_id, $category_id, $limit = 4) {
    $db = new Database();
    $conn = $db->getConnection();
    
    $query = "SELECT * FROM products 
              WHERE category_id = :category_id 
              AND id != :product_id 
              ORDER BY RAND() 
              LIMIT :limit";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
