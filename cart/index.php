<?php
$page_title = "Shopping Cart";
include_once '../includes/header.php';

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Calculate cart total
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<h1>Shopping Cart</h1>

<?php if (empty($_SESSION['cart'])): ?>
    <div class="alert alert-info">
        Your cart is empty. <a href="<?php echo SITE_URL; ?>/products">Continue shopping</a>
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <?php if ($item['image']): ?>
                                    <img src="<?php echo SITE_URL . '/' . PRODUCT_IMG_PATH . $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="img-thumbnail me-3" style="width: 50px;">
                                <?php else: ?>
                                    <img src="<?php echo SITE_URL; ?>/assets/images/product-placeholder.jpg" alt="<?php echo $item['name']; ?>" class="img-thumbnail me-3" style="width: 50px;">
                                <?php endif; ?>
                                <a href="<?php echo SITE_URL; ?>/products/view.php?id=<?php echo $item['id']; ?>"><?php echo $item['name']; ?></a>
                            </div>
                        </td>
                        <td><?php echo formatPrice($item['price']); ?></td>
                        <td>
                            <form action="<?php echo SITE_URL; ?>/cart/update.php" method="post" class="d-flex align-items-center">
                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="form-control form-control-sm" style="width: 70px;">
                                <button type="submit" class="btn btn-sm btn-outline-secondary ms-2">Update</button>
                            </form>
                        </td>
                        <td><?php echo formatPrice($item['price'] * $item['quantity']); ?></td>
                        <td>
                            <a href="<?php echo SITE_URL; ?>/cart/remove.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> Remove
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                    <td><strong><?php echo formatPrice($total); ?></strong></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
    
    <div class="d-flex justify-content-between mt-4">
        <a href="<?php echo SITE_URL; ?>/products" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left"></i> Continue Shopping
        </a>
        
        <div>
            <a href="<?php echo SITE_URL; ?>/cart/checkout.php" class="btn btn-success">
                <i class="fas fa-shopping-cart"></i> Proceed to Checkout
            </a>
        </div>
    </div>
<?php endif; ?>

<?php include_once '../includes/footer.php'; ?>
