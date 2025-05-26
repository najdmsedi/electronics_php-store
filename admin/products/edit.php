<?php
$page_title = "Edit Product";
include_once '../../includes/header.php';

// Require admin privileges
requireAdmin();

// Check if ID is set
if (!isset($_GET['id']) || empty($_GET['id'])) {
    setMessage("Product ID is required", "error");
    redirect(SITE_URL . '/admin/products');
}

// Get product details
$product = new Product();
if (!$product->getById($_GET['id'])) {
    setMessage("Product not found", "error");
    redirect(SITE_URL . '/admin/products');
}

// Get all categories for dropdown
$category = new Category();
$categories = $category->getAll();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set product properties
    $product->category_id = $_POST['category_id'];
    $product->name = $_POST['name'];
    $product->description = $_POST['description'];
    $product->price = $_POST['price'];
    $product->stock_quantity = $_POST['stock_quantity'];
    
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../../' . PRODUCT_IMG_PATH;
        $file_name = time() . '_' . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $file_name;
        
        // Check if image file is a actual image
        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check !== false) {
            // Check file size (limit to 5MB)
            if ($_FILES['image']['size'] <= 5000000) {
                // Allow certain file formats
                $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                if (in_array($file_type, ['jpg', 'jpeg', 'png', 'gif'])) {
                    // Upload file
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                        // Delete old image if exists
                        if (!empty($product->image)) {
                            $old_file = $upload_dir . $product->image;
                            if (file_exists($old_file)) {
                                unlink($old_file);
                            }
                        }
                        $product->image = $file_name;
                    } else {
                        $error = "Error uploading file.";
                    }
                } else {
                    $error = "Only JPG, JPEG, PNG & GIF files are allowed.";
                }
            } else {
                $error = "File is too large. Maximum size is 5MB.";
            }
        } else {
            $error = "File is not an image.";
        }
    }
    
    // Update product
    if (!isset($error) && $product->update()) {
        setMessage("Product updated successfully");
        redirect(SITE_URL . '/admin/products');
    } else {
        $error = isset($error) ? $error : "Error updating product";
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Edit Product</h1>
    <a href="<?php echo SITE_URL; ?>/admin/products" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Products
    </a>
</div>

<div class="card">
    <div class="card-body">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $product->id; ?>" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select class="form-select" id="category_id" name="category_id" required>
                    <option value="">Select Category</option>
                    <?php 
                    // Reset the pointer to the beginning
                    $categories->execute();
                    while ($row = $categories->fetch(PDO::FETCH_ASSOC)): 
                    ?>
                        <option value="<?php echo $row['id']; ?>" <?php echo ($row['id'] == $product->category_id) ? 'selected' : ''; ?>>
                            <?php echo $row['name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $product->name; ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="5" required><?php echo $product->description; ?></textarea>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="price" class="form-label">Price</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" value="<?php echo $product->price; ?>" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <label for="stock_quantity" class="form-label">Stock Quantity</label>
                    <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" min="0" value="<?php echo $product->stock_quantity; ?>" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="image" class="form-label">Product Image</label>
                <?php if ($product->image): ?>
                    <div class="mb-2">
                        <img src="<?php echo SITE_URL . '/' . PRODUCT_IMG_PATH . $product->image; ?>" alt="<?php echo $product->name; ?>" class="img-thumbnail" style="max-width: 200px;">
                    </div>
                <?php endif; ?>
                <input type="file" class="form-control" id="image" name="image">
                <div class="form-text">Leave empty to keep current image. Recommended size: 800x600 pixels. Max size: 5MB.</div>
            </div>
            
            <button type="submit" class="btn btn-primary">Update Product</button>
        </form>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>
