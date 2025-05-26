<?php
$page_title = "Manage Orders";
include_once '../../includes/header.php';

// Require admin privileges
requireAdmin();

// Get all orders
$order = new Order();
$orders = $order->getAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Manage Orders</h1>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $orders->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo formatPrice($row['total_amount']); ?></td>
                            <td>
                                <?php
                                $status_class = '';
                                switch ($row['status']) {
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
                                <span class="badge <?php echo $status_class; ?>"><?php echo ucfirst($row['status']); ?></span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                            <td>
                                <a href="<?php echo SITE_URL; ?>/admin/orders/view.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>
