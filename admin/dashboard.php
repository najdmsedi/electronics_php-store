<?php
$page_title = "Admin Dashboard";
include_once '../includes/header.php';

// Require admin privileges
requireAdmin();

// Get statistics
$db = new Database();
$conn = $db->getConnection();

// Count users
$stmt = $conn->query("SELECT COUNT(*) as total_users FROM users WHERE is_admin = 0");
$total_users = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];

// Count products
$stmt = $conn->query("SELECT COUNT(*) as total_products FROM products");
$total_products = $stmt->fetch(PDO::FETCH_ASSOC)['total_products'];

// Count categories
$stmt = $conn->query("SELECT COUNT(*) as total_categories FROM categories");
$total_categories = $stmt->fetch(PDO::FETCH_ASSOC)['total_categories'];

// Count orders
$stmt = $conn->query("SELECT COUNT(*) as total_orders FROM orders");
$total_orders = $stmt->fetch(PDO::FETCH_ASSOC)['total_orders'];

// Get recent orders
$stmt = $conn->query("SELECT o.*, u.username, u.email 
                      FROM orders o 
                      LEFT JOIN users u ON o.user_id = u.id 
                      ORDER BY o.created_at DESC 
                      LIMIT 5");
$recent_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get low stock products
$stmt = $conn->query("SELECT * FROM products WHERE stock_quantity < 10 ORDER BY stock_quantity ASC LIMIT 5");
$low_stock = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Admin Dashboard</h1>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Total Users</h5>
                <h2 class="card-text"><?php echo $total_users; ?></h2>
                <a href="<?php echo SITE_URL; ?>/admin/users" class="text-white">View Details <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Total Products</h5>
                <h2 class="card-text"><?php echo $total_products; ?></h2>
                <a href="<?php echo SITE_URL; ?>/admin/products" class="text-white">View Details <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Total Categories</h5>
                <h2 class="card-text"><?php echo $total_categories; ?></h2>
                <a href="<?php echo SITE_URL; ?>/admin/categories" class="text-white">View Details <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h5 class="card-title">Total Orders</h5>
                <h2 class="card-text"><?php echo $total_orders; ?></h2>
                <a href="<?php echo SITE_URL; ?>/admin/orders" class="text-dark">View Details <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Orders -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Recent Orders</h5>
            </div>
            <div class="card-body">
                <?php if (empty($recent_orders)): ?>
                    <p class="text-muted">No orders found.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_orders as $order): ?>
                                    <tr>
                                        <td>#<?php echo $order['id']; ?></td>
                                        <td><?php echo $order['username']; ?></td>
                                        <td><?php echo formatPrice($order['total_amount']); ?></td>
                                        <td>
                                            <?php
                                            $status_class = '';
                                            switch ($order['status']) {
                                                case 'pending':
                                                    $status_class = 'bg-warning text-dark';
                                                    break;
                                                case 'processing':
                                                    $status_class = 'bg-info text-white';
                                                    break;
                                                case 'shipped':
                                                    $status_class = 'bg-primary text-white';
                                                    break;
                                                case 'delivered':
                                                    $status_class = 'bg-success text-white';
                                                    break;
                                                case 'cancelled':
                                                    $status_class = 'bg-danger text-white';
                                                    break;
                                            }
                                            ?>
                                            <span class="badge <?php echo $status_class; ?>"><?php echo ucfirst($order['status']); ?></span>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                        <td>
                                            <a href="<?php echo SITE_URL; ?>/admin/orders/view.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <a href="<?php echo SITE_URL; ?>/admin/orders" class="btn btn-outline-primary">View All Orders</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Low Stock Products -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Low Stock Products</h5>
            </div>
            <div class="card-body">
                <?php if (empty($low_stock)): ?>
                    <p class="text-muted">No low stock products found.</p>
                <?php else: ?>
                    <ul class="list-group">
                        <?php foreach ($low_stock as $product): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="<?php echo SITE_URL; ?>/products/view.php?id=<?php echo $product['id']; ?>"><?php echo $product['name']; ?></a>
                                </div>
                                <span class="badge bg-danger rounded-pill"><?php echo $product['stock_quantity']; ?> left</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="<?php echo SITE_URL; ?>/admin/products" class="btn btn-outline-danger mt-3">Manage Products</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
