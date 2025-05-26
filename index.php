<?php
$page_title = "Home";
include_once 'includes/header.php';

// Get featured products
$product = new Product();
$featured_products = $product->getAll();

// Get all categories
$category = new Category();
$categories = $category->getAll();
?>

<!-- Hero Section -->
<div class="hero-section position-relative mb-5 rounded  shadow-lg" style="background-image: url('<?php echo SITE_URL; ?>/assets/images/bg.jpg'); background-size: cover; background-position: center; height: 500px;">
    <div class="position-absolute w-100 h-100" ></div>
    <div class="container position-relative h-100 d-flex flex-column justify-content-center text-white">
        <div class="row justify-content-center">
            <div class="col-lg-7 text-center">
               <h1 class="display-4 fw-bold mb-3">Welcome to Electronics Store</h1>
                <p class="lead fs-4 mb-4">Your one-stop shop for all electronics needs.</p>
                <hr class="my-4 opacity-50" style="border-color: #e19713; border-width: 2px;">
                <p class="fs-5 mb-4">Explore our wide range of products from top brands at competitive prices.</p>
                <div class="d-flex gap-3 justify-content-center">
                    <a class="btn btn-lg px-4 py-3" href="<?php echo SITE_URL; ?>/products" role="button" style="background-color: #e19713; color: white;">
                        <i class="fas fa-shopping-bag me-2"></i> Shop Now
                    </a>
                    <a class="btn btn-outline-light btn-lg px-4 py-3" href="<?php echo SITE_URL; ?>/products/category.php?id=1" role="button">
                        <i class="fas fa-th-large me-2"></i> Browse Categories
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Categories Section -->
<h2 class="mb-4">Browse Categories</h2>
<div class="row">
    <?php while ($row = $categories->fetch(PDO::FETCH_ASSOC)): ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <?php if ($row['image']): ?>
            <img src="<?php echo SITE_URL . '/' . CATEGORY_IMG_PATH . $row['image']; ?>" class="card-img-top" alt="<?php echo $row['name']; ?>">
            <?php else: ?>
            <img src="<?php echo SITE_URL; ?>/assets/images/category-placeholder.jpg" class="card-img-top" alt="<?php echo $row['name']; ?>">
            <?php endif; ?>
            <div class="card-body">
                <h5 class="card-title"><?php echo $row['name']; ?></h5>
                <p class="card-text"><?php echo substr($row['description'], 0, 100) . '...'; ?></p>
                <a href="<?php echo SITE_URL; ?>/products/category.php?id=<?php echo $row['id']; ?>" class="btn" style="background-color: #e19713; color: white;">
                    <i class="fas fa-th-list me-2"></i> View Products
                </a>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<!-- Featured Products Section -->
<h2 class="mt-5 mb-4">Featured Products</h2>
<div class="row">
    <?php
    $count = 0;
    while ($row = $featured_products->fetch(PDO::FETCH_ASSOC)):
        if ($count >= 6) break; // Limit to 6 products
        $count++;
    ?>
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

<?php include_once 'includes/footer.php'; ?>