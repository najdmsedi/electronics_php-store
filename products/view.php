<?php
include_once '../includes/header.php';

// Check if ID is set
if (!isset($_GET['id']) || empty($_GET['id'])) {
    setMessage("Product ID is required", "error");
    redirect(SITE_URL . '/products');
}

// Get product details
$product = new Product();
if (!$product->getById($_GET['id'])) {
    setMessage("Product not found", "error");
    redirect(SITE_URL . '/products');
}

$page_title = $product->name;
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>/products">Products</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo $product->name; ?></li>
    </ol>
</nav>

<div class="row">
    <!-- Product Image -->
    <div class="col-md-5">
        <div class="card mb-4">
            <?php if ($product->image): ?>
                <img src="<?php echo SITE_URL . '/' . PRODUCT_IMG_PATH . $product->image; ?>" class="card-img-top img-fluid" alt="<?php echo $product->name; ?>">
            <?php else: ?>
                <img src="<?php echo SITE_URL; ?>/assets/images/product-placeholder.jpg" class="card-img-top img-fluid" alt="<?php echo $product->name; ?>">
            <?php endif; ?>
        </div>
    </div>

    <!-- Product Details -->
    <div class="col-md-7">
        <h1><?php echo $product->name; ?></h1>
        <p class="text-muted">Category: <?php echo $product->category_name; ?></p>

        <div class="mb-3">
            <h3 class="text-primary"><?php echo formatPrice($product->price); ?></h3>

            <?php if ($product->stock_quantity > 0): ?>
                <span class="badge bg-success">In Stock (<?php echo $product->stock_quantity; ?>)</span>
            <?php else: ?>
                <span class="badge bg-danger">Out of Stock</span>
            <?php endif; ?>
        </div>

        <div class="mb-4">
            <h4>Description</h4>
            <p><?php echo nl2br($product->description); ?></p>
        </div>

        <?php if ($product->stock_quantity > 0): ?>
            <form action="<?php echo SITE_URL; ?>/cart/add.php" method="post">
                <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" max="<?php echo $product->stock_quantity; ?>">
                    </div>
                </div>

                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-shopping-cart"></i> Add to Cart
                </button>
            </form>
        <?php else: ?>
            <button class="btn btn-secondary btn-lg" disabled>
                <i class="fas fa-shopping-cart"></i> Out of Stock
            </button>
        <?php endif; ?>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
