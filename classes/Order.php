<?php
require_once __DIR__ . '/../config/database.php';

class Order {
    private $conn;
    private $table = 'orders';
    
    // Order properties
    public $id;
    public $user_id;
    public $total_amount;
    public $status;
    public $created_at;
    public $updated_at;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    // Create order
    public function create() {
        // Begin transaction
        $this->conn->beginTransaction();
        
        try {
            // Insert order
            $query = "INSERT INTO " . $this->table . "
                      SET user_id = :user_id,
                          total_amount = :total_amount,
                          status = :status";
            
            $stmt = $this->conn->prepare($query);
            
            // Sanitize input
            $this->user_id = htmlspecialchars(strip_tags($this->user_id));
            $this->total_amount = htmlspecialchars(strip_tags($this->total_amount));
            $this->status = htmlspecialchars(strip_tags($this->status));
            
            // Bind parameters
            $stmt->bindParam(':user_id', $this->user_id);
            $stmt->bindParam(':total_amount', $this->total_amount);
            $stmt->bindParam(':status', $this->status);
            
            // Execute query
            $stmt->execute();
            
            // Get last inserted ID
            $this->id = $this->conn->lastInsertId();
            
            // Commit transaction
            $this->conn->commit();
            
            return true;
        } catch(PDOException $e) {
            // Rollback transaction on error
            $this->conn->rollBack();
            return false;
        }
    }
    
    // Add order item
    public function addOrderItem($product_id, $quantity, $price) {
        $query = "INSERT INTO order_items
                  SET order_id = :order_id,
                      product_id = :product_id,
                      quantity = :quantity,
                      price = :price";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize input
        $order_id = htmlspecialchars(strip_tags($this->id));
        $product_id = htmlspecialchars(strip_tags($product_id));
        $quantity = htmlspecialchars(strip_tags($quantity));
        $price = htmlspecialchars(strip_tags($price));
        
        // Bind parameters
        $stmt->bindParam(':order_id', $order_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':price', $price);
        
        // Execute query
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    // Get all orders
    public function getAll() {
        $query = "SELECT o.*, u.username, u.email
                  FROM " . $this->table . " o
                  LEFT JOIN users u ON o.user_id = u.id
                  ORDER BY o.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    // Get orders by user
    public function getByUser($user_id) {
        $query = "SELECT * FROM " . $this->table . "
                  WHERE user_id = :user_id
                  ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        
        return $stmt;
    }
    
    // Get single order
    public function getById($id) {
        $query = "SELECT o.*, u.username, u.email, u.first_name, u.last_name, u.address, u.phone
                  FROM " . $this->table . " o
                  LEFT JOIN users u ON o.user_id = u.id
                  WHERE o.id = :id
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            // Set properties
            $this->id = $row['id'];
            $this->user_id = $row['user_id'];
            $this->total_amount = $row['total_amount'];
            $this->status = $row['status'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            
            return $row;
        }
        
        return false;
    }
    
    // Get order items
    public function getOrderItems($order_id) {
        $query = "SELECT oi.*, p.name, p.image
                  FROM order_items oi
                  LEFT JOIN products p ON oi.product_id = p.id
                  WHERE oi.order_id = :order_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
        
        return $stmt;
    }
    
    // Update order status
    public function updateStatus() {
        $query = "UPDATE " . $this->table . "
                  SET status = :status
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize input
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->status = htmlspecialchars(strip_tags($this->status));
        
        // Bind parameters
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':status', $this->status);
        
        // Execute query
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
}
?>
