<?php
$page_title = "Manage Products";
include_once '../../includes/header.php';

// Require admin privileges
requireAdmin();

// Get all products
$product = new Product();
$products = $product->getAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Manage Products</h1>
    <a href="<?php echo SITE_URL; ?>/admin/products/add.php" class="btn btn-success">
        <i class="fas fa-plus"></i> Add New Product
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $products->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td>
                                <?php if ($row['image']): ?>
                                    <img src="<?php echo SITE_URL . '/' . PRODUCT_IMG_PATH . $row['image']; ?>" alt="<?php echo $row['name']; ?>" class="img-thumbnail" style="width: 50px;">
                                <?php else: ?>
                                    <img src="<?php echo SITE_URL; ?>/assets/images/product-placeholder.jpg" alt="<?php echo $row['name']; ?>" class="img-thumbnail" style="width: 50px;">
                                <?php endif; ?>
                            </td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['category_name']; ?></td>
                            <td><?php echo formatPrice($row['price']); ?></td>
                            <td>
                                <?php if ($row['stock_quantity'] < 10): ?>
                                    <span class="badge bg-danger"><?php echo $row['stock_quantity']; ?></span>
                                <?php else: ?>
                                    <span class="badge bg-success"><?php echo $row['stock_quantity']; ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo SITE_URL; ?>/products/view.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo SITE_URL; ?>/admin/products/edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?php echo SITE_URL; ?>/admin/products/delete.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this product?');">
                                    <i class="fas fa-trash"></i>
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
