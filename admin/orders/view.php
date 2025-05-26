<?php
$page_title = "View Order";
include_once '../../includes/header.php';

// Require admin privileges
requireAdmin();

// Check if ID is set
if (!isset($_GET['id']) || empty($_GET['id'])) {
    setMessage("Order ID is required", "error");
    redirect(SITE_URL . '/admin/orders');
}

// Get order details
$order = new Order();
$order_details = $order->getById($_GET['id']);

if (!$order_details) {
    setMessage("Order not found", "error");
    redirect(SITE_URL . '/admin/orders');
}

// Process status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $order->id = $order_details['id'];
    $order->status = $_POST['status'];
    
    if ($order->updateStatus()) {
        setMessage("Order status updated successfully");
        redirect(SITE_URL . '/admin/orders/view.php?id=' . $order->id);
    } else {
        $error = "Error updating order status";
    }
}

// Get order items
$order_items = $order->getOrderItems($order_details['id']);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Order #<?php echo $order_details['id']; ?></h1>
    <a href="<?php echo SITE_URL; ?>/admin/orders" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Orders
    </a>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Order Items</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($item = $order_items->fetch(PDO::FETCH_ASSOC)): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if ($item['image']): ?>
                                                <img src="<?php echo SITE_URL . '/' . PRODUCT_IMG_PATH . $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="img-thumbnail me-2" style="width: 50px;">
                                            <?php else: ?>
                                                <img src="<?php echo SITE_URL; ?>/assets/images/product-placeholder.jpg" alt="<?php echo $item['name']; ?>" class="img-thumbnail me-2" style="width: 50px;">
                                            <?php endif; ?>
                                            <a href="<?php echo SITE_URL; ?>/products/view.php?id=<?php echo $item['product_id']; ?>"><?php echo $item['name']; ?></a>
                                        </div>
                                    </td>
                                    <td><?php echo formatPrice($item['price']); ?></td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td><?php echo formatPrice($item['price'] * $item['quantity']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td><strong><?php echo formatPrice($order_details['total_amount']); ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Order Details</h5>
            </div>
            <div class="card-body">
                <p><strong>Order Date:</strong> <?php echo date('M d, Y', strtotime($order_details['created_at'])); ?></p>
                <p>
                    <strong>Status:</strong>
                    <?php
                    $status_class = '';
                    switch ($order_details['status']) {
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
                    <span class="badge <?php echo $status_class; ?>"><?php echo ucfirst($order_details['status']); ?></span>
                </p>
                
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $order_details['id']; ?>" method="post" class="mt-3">
                    <div class="mb-3">
                        <label for="status" class="form-label">Update Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="pending" <?php echo ($order_details['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="processing" <?php echo ($order_details['status'] == 'processing') ? 'selected' : ''; ?>>Processing</option>
                            <option value="shipped" <?php echo ($order_details['status'] == 'shipped') ? 'selected' : ''; ?>>Shipped</option>
                            <option value="delivered" <?php echo ($order_details['status'] == 'delivered') ? 'selected' : ''; ?>>Delivered</option>
                            <option value="cancelled" <?php echo ($order_details['status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </form>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Customer Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> <?php echo $order_details['first_name'] . ' ' . $order_details['last_name']; ?></p>
                <p><strong>Email:</strong> <?php echo $order_details['email']; ?></p>
                <p><strong>Phone:</strong> <?php echo $order_details['phone']; ?></p>
                <p><strong>Address:</strong> <?php echo nl2br($order_details['address']); ?></p>
            </div>
        </div>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>
