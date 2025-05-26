<?php
$page_title = "Manage Categories";
include_once '../../includes/header.php';

// Require admin privileges
requireAdmin();

// Get all categories
$category = new Category();
$categories = $category->getAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Manage Categories</h1>
    <a href="<?php echo SITE_URL; ?>/admin/categories/add.php" class="btn btn-success">
        <i class="fas fa-plus"></i> Add New Category
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
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $categories->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td>
                                <?php if ($row['image']): ?>
                                    <img src="<?php echo SITE_URL . '/' . CATEGORY_IMG_PATH . $row['image']; ?>" alt="<?php echo $row['name']; ?>" class="img-thumbnail" style="width: 50px;">
                                <?php else: ?>
                                    <img src="<?php echo SITE_URL; ?>/assets/images/category-placeholder.jpg" alt="<?php echo $row['name']; ?>" class="img-thumbnail" style="width: 50px;">
                                <?php endif; ?>
                            </td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo substr($row['description'], 0, 100) . (strlen($row['description']) > 100 ? '...' : ''); ?></td>
                            <td>
                                <a href="<?php echo SITE_URL; ?>/products/category.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo SITE_URL; ?>/admin/categories/edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?php echo SITE_URL; ?>/admin/categories/delete.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this category? This will affect all associated products.');">
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
