<?php
$page_title = "My Orders";
include_once '../includes/header.php';

// Require login
requireLogin();

// Get user orders
$order = new Order();
$orders = $order->getByUser($_SESSION['user_id']);
?>

<div class="row">
    <div class="col-md-3">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">My Account</h5>
            </div>
            <div class="list-group list-group-flush">
                <a href="<?php echo SITE_URL; ?>/user/profile.php" class="list-group-item list-group-item-action">My Profile</a>
                <a href="<?php echo SITE_URL; ?>/user/orders.php" class="list-group-item list-group-item-action active">My Orders</a>
                <a href="<?php echo SITE_URL; ?>/user/logout.php" class="list-group-item list-group-item-action text-danger">Logout</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">My Orders</h4>
            </div>
            <div class="card-body">
                <?php if ($orders->rowCount() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $orders->fetch(PDO::FETCH_ASSOC)): ?>
                                    <?php
                                    // Determine badge class
                                    switch ($row['status']) {
                                        case 'pending':    $status_class = 'bg-warning text-dark'; break;
                                        case 'processing': $status_class = 'bg-info text-white';   break;
                                        case 'shipped':    $status_class = 'bg-primary text-white';break;
                                        case 'delivered':  $status_class = 'bg-success text-white';break;
                                        case 'cancelled':  $status_class = 'bg-danger text-white'; break;
                                        default:           $status_class = 'badge-secondary';      break;
                                    }
                                    ?>
                                    <tr>
                                        <td>#<?php echo $row['id']; ?></td>
                                        <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                                        <td><?php echo formatPrice($row['total_amount']); ?></td>
                                        <td>
                                            <span class="badge <?php echo $status_class; ?>">
                                                <?php echo ucfirst($row['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-info"
                                                data-bs-toggle="modal"
                                                data-bs-target="#orderModal<?php echo $row['id']; ?>">
                                                View Details
                                            </button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        You haven't placed any orders yet. <a href="<?php echo SITE_URL; ?>/products">Start shopping</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
// Re-fetch orders for the modals
$orders = $order->getByUser($_SESSION['user_id']);
while ($row = $orders->fetch(PDO::FETCH_ASSOC)):
    // Determine badge class again
    switch ($row['status']) {
        case 'pending':    $status_class = 'bg-warning text-dark'; break;
        case 'processing': $status_class = 'bg-info text-white';   break;
        case 'shipped':    $status_class = 'bg-primary text-white';break;
        case 'delivered':  $status_class = 'bg-success text-white';break;
        case 'cancelled':  $status_class = 'bg-danger text-white'; break;
        default:           $status_class = 'badge-secondary';      break;
    }
    $order_items = $order->getOrderItems($row['id']);
?>
<!-- Modal -->
<div class="modal fade" id="orderModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="orderModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderModalLabel<?php echo $row['id']; ?>">
                    Order #<?php echo $row['id']; ?> Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Order Date:</strong> <?php echo date('M d, Y', strtotime($row['created_at'])); ?></p>
                        <p>
                            <strong>Status:</strong>
                            <span class="badge <?php echo $status_class; ?>">
                                <?php echo ucfirst($row['status']); ?>
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Total Amount:</strong> <?php echo formatPrice($row['total_amount']); ?></p>
                    </div>
                </div>

                <h6>Order Items</h6>
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
                                                <img src="<?php echo SITE_URL . '/' . PRODUCT_IMG_PATH . $item['image']; ?>"
                                                     alt="<?php echo htmlspecialchars($item['name']); ?>"
                                                     class="img-thumbnail me-2" style="width: 50px;">
                                            <?php else: ?>
                                                <img src="<?php echo SITE_URL; ?>/assets/images/product-placeholder.jpg"
                                                     alt="<?php echo htmlspecialchars($item['name']); ?>"
                                                     class="img-thumbnail me-2" style="width: 50px;">
                                            <?php endif; ?>
                                            <?php echo htmlspecialchars($item['name']); ?>
                                        </div>
                                    </td>
                                    <td><?php echo formatPrice($item['price']); ?></td>
                                    <td><?php echo (int)$item['quantity']; ?></td>
                                    <td><?php echo formatPrice($item['price'] * $item['quantity']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php endwhile; ?>

<?php include_once '../includes/footer.php'; ?>
