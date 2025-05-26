<?php
$page_title = "Page Not Found";
include_once 'includes/header.php';
?>

<div class="text-center py-5">
    <h1 class="display-1">404</h1>
    <h2 class="mb-4">Page Not Found</h2>
    <p class="lead">The page you are looking for does not exist or has been moved.</p>
    <a href="<?php echo SITE_URL; ?>" class="btn btn-primary mt-3">
        <i class="fas fa-home"></i> Go to Homepage
    </a>
</div>

<?php include_once 'includes/footer.php'; ?>
