<?php
$page_title = "View User";
include_once '../../includes/header.php';

// Require admin privileges
requireAdmin();

// Check if ID is set
if (!isset($_GET['id']) || empty($_GET['id'])) {
    setMessage("User ID is required", "error");
    redirect(SITE_URL . '/admin/users');
}

// Get user details
$user = new User();
if (!$user->getById($_GET['id'])) {
    setMessage("User not found", "error");
    redirect(SITE_URL . '/admin/users');
}

// Get user orders
$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC");
$stmt->bindParam(':user_id', $user->id);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="mb-4">
    <a href="<?php echo SITE_URL; ?>/admin/users" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Users
    </a>
</div>

<div class="row">
    <!-- User Details -->
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">User Details</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Username:</strong> <?php echo $user->username; ?>
                </div>
                <div class="mb-3">
                    <strong>Name:</strong> <?php echo $user->first_name . ' ' . $user->last_name; ?>
                </div>
                <div class="mb-3">
                    <strong>Email:</strong> <?php echo $user->email; ?>
                </div>
                <div class="mb-3">
                    <strong>Phone:</strong> <?php echo $user->phone ? $user->phone : 'N/A'; ?>
                </div>
                <div class="mb-3">
                    <strong>Address:</strong> <?php echo $user->address ? $user->address : 'N/A'; ?>
                </div>
                <div class="mb-3">
                    <strong>Registered:</strong> <?php echo date('F d, Y', strtotime($user->created_at)); ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- User Orders -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Order History</h5>
            </div>
            <div class="card-body">
                <?php if (empty($orders)): ?>
                    <p class="text-muted">No orders found for this user.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td>#<?php echo $order['id']; ?></td>
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
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>
