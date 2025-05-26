<?php
include_once '../includes/header.php';

// Check if ID is set
if (!isset($_GET['id']) || empty($_GET['id'])) {
    setMessage("Category ID is required", "error");
    redirect(SITE_URL . '/products');
}

// Get category details
$category = new Category();
if (!$category->getById($_GET['id'])) {
    setMessage("Category not found", "error");
    redirect(SITE_URL . '/products');
}

$page_title = $category->name;

// Get products in this category
$product = new Product();
$products = $product->getByCategory($category->id);

// Get all categories for sidebar
$all_categories = $category->getAll();
?>

<div class="row">
    <!-- Sidebar with categories -->
    <div class="col-md-3">
        <div class="card mb-4">
            <div class="card-header text-white" style="background-color: #e19713;">
                <h5 class="mb-0">Categories</h5>
            </div>
            <div class="list-group list-group-flush">
                <a href="<?php echo SITE_URL; ?>/products" class="list-group-item list-group-item-action">All Products</a>
                <?php while ($cat = $all_categories->fetch(PDO::FETCH_ASSOC)): ?>
                    <a href="<?php echo SITE_URL; ?>/products/category.php?id=<?php echo $cat['id']; ?>" class="list-group-item list-group-item-action <?php echo ($cat['id'] == $category->id) ? 'active' : ''; ?>">
                        <?php echo $cat['name']; ?>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>

        <!-- Search form -->
        <div class="card">
            <div class="card-header text-white" style="background-color: #e19713;">
                <h5 class="mb-0">Search Products</h5>
            </div>
            <div class="card-body">
                <form action="<?php echo SITE_URL; ?>/products" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Search...">
                        <button class="btn" style="background-color: #e19713; color: white;" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Main content with products -->
    <div class="col-md-9">
        <h2><?php echo $category->name; ?></h2>
        <p><?php echo $category->description; ?></p>

        <?php if ($products->rowCount() > 0): ?>
            <div class="row">
                <?php while ($row = $products->fetch(PDO::FETCH_ASSOC)): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <?php if ($row['image']): ?>
                                <img src="<?php echo SITE_URL . '/' . PRODUCT_IMG_PATH . $row['image']; ?>" class="card-img-top" alt="<?php echo $row['name']; ?>">
                            <?php else: ?>
                                <img src="<?php echo SITE_URL; ?>/assets/images/product-placeholder.jpg" class="card-img-top" alt="<?php echo $row['name']; ?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row['name']; ?></h5>
                                <p class="card-text"><?php echo substr($row['description'], 0, 100) . '...'; ?></p>
                                <p class="card-text text-primary fw-bold"><?php echo formatPrice($row['price']); ?></p>
                            </div>
                            <div class="card-footer bg-white">
                                <div class="d-flex justify-content-between">
                                    <a href="<?php echo SITE_URL; ?>/products/view.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-secondary">
                                        <i class="fas fa-eye me-1"></i> Details
                                    </a>
                                    <a href="<?php echo SITE_URL; ?>/cart/add.php?id=<?php echo $row['id']; ?>" class="btn" style="background-color: #e19713; color: white;">
                                        <i class="fas fa-shopping-cart me-1"></i> Add to Cart
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                No products available in this category at this time.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
