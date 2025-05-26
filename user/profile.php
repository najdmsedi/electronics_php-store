<?php
$page_title = "My Profile";
include_once '../includes/header.php';

// Require login
requireLogin();

// Get user details
$user = new User();
$user->getById($_SESSION['user_id']);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set user properties
    $user->first_name = $_POST['first_name'];
    $user->last_name = $_POST['last_name'];
    $user->email = $_POST['email'];
    $user->address = $_POST['address'];
    $user->phone = $_POST['phone'];
    
    // Validate input
    $errors = [];
    
    // Email validation
    if (empty($user->email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    // First name validation
    if (empty($user->first_name)) {
        $errors[] = "First name is required";
    }
    
    // Last name validation
    if (empty($user->last_name)) {
        $errors[] = "Last name is required";
    }
    
    // If no errors, update user
    if (empty($errors)) {
        if ($user->update()) {
            setMessage("Profile updated successfully");
            redirect(SITE_URL . '/user/profile.php');
        } else {
            $errors[] = "Update failed. Please try again.";
        }
    }
}
?>

<div class="row">
    <div class="col-md-3">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">My Account</h5>
            </div>
            <div class="list-group list-group-flush">
                <a href="<?php echo SITE_URL; ?>/user/profile.php" class="list-group-item list-group-item-action active">My Profile</a>
                <a href="<?php echo SITE_URL; ?>/user/orders.php" class="list-group-item list-group-item-action">My Orders</a>
                <a href="<?php echo SITE_URL; ?>/user/logout.php" class="list-group-item list-group-item-action text-danger">Logout</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">My Profile</h4>
            </div>
            <div class="card-body">
                <?php if (isset($errors) && !empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" value="<?php echo $user->username; ?>" disabled>
                            <div class="form-text">Username cannot be changed</div>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $user->email; ?>" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $user->first_name; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $user->last_name; ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3"><?php echo $user->address; ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $user->phone; ?>">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
