    </div><!-- /.container -->
    
    <footer class="py-4 mt-auto text-white" style="background-color: #242423;">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Electronics Store</h5>
                    <p>Your one-stop shop for all electronics needs.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo SITE_URL; ?>" class="text-white">Home</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/products" class="text-white">Products</a></li>
                        <?php if(isLoggedIn()): ?>
                            <li><a href="<?php echo SITE_URL; ?>/user/profile.php" class="text-white">My Account</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/user/orders.php" class="text-white">My Orders</a></li>
                        <?php else: ?>
                            <li><a href="<?php echo SITE_URL; ?>/user/login.php" class="text-white">Login</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/user/register.php" class="text-white">Register</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact Us</h5>
                    <address>
                        <i class="fas fa-map-marker-alt" style="color: #687281;"></i> 123 Electronics Street<br>
                        <i class="fas fa-phone" style="color: #687281;"></i> (123) 456-7890<br>
                        <i class="fas fa-envelope" style="color: #687281;"></i> info@electronics-store.com
                    </address>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; <?php echo date('Y'); ?> Electronics Store. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="<?php echo SITE_URL; ?>/assets/js/script.js"></script>
</body>
</html>
