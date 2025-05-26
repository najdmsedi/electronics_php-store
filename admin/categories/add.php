<?php
$page_title = "Add Category";
include_once '../../includes/header.php';

// Require admin privileges
requireAdmin();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = new Category();
    
    // Set category properties
    $category->name = $_POST['name'];
    $category->description = $_POST['description'];
    
    // Handle image upload
    $category->image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../../' . CATEGORY_IMG_PATH;
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
                        $category->image = $file_name;
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
    
    // Create category
    if (!isset($error) && $category->create()) {
        setMessage("Category added successfully");
        redirect(SITE_URL . '/admin/categories');
    } else {
        $error = isset($error) ? $error : "Error adding category";
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Add New Category</h1>
    <a href="<?php echo SITE_URL; ?>/admin/categories" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Categories
    </a>
</div>

<div class="card">
    <div class="card-body">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Category Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            
            <div class="mb-3">
                <label for="image" class="form-label">Category Image</label>
                <input type="file" class="form-control" id="image" name="image">
                <div class="form-text">Recommended size: 800x600 pixels. Max size: 5MB.</div>
            </div>
            
            <button type="submit" class="btn btn-primary">Add Category</button>
        </form>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>
