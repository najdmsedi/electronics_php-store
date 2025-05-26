<nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top" style="background-color: #e19713;">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?php echo SITE_URL; ?>">
            <i class="fas fa-bolt me-2"></i><span class="d-none d-sm-inline">Electronics</span> Store
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link px-3 fw-medium" href="<?php echo SITE_URL; ?>">
                        <i class="fas fa-home me-1"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 fw-medium" href="<?php echo SITE_URL; ?>/products">
                        <i class="fas fa-mobile-alt me-1"></i> Products
                    </a>
                </li>
                <?php if(isAdmin()): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle px-3 fw-medium" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-shield me-1"></i> Admin
                    </a>
                    <ul class="dropdown-menu shadow border-0" aria-labelledby="adminDropdown">
                        <li><a class="dropdown-item py-2" href="<?php echo SITE_URL; ?>/admin/dashboard.php"><i class="fas fa-tachometer-alt me-2" style="color: #687281;"></i> Dashboard</a></li>
                        <li><a class="dropdown-item py-2" href="<?php echo SITE_URL; ?>/admin/users"><i class="fas fa-users me-2" style="color: #687281;"></i> Manage Users</a></li>
                        <li><a class="dropdown-item py-2" href="<?php echo SITE_URL; ?>/admin/products"><i class="fas fa-box me-2" style="color: #687281;"></i> Manage Products</a></li>
                        <li><a class="dropdown-item py-2" href="<?php echo SITE_URL; ?>/admin/categories"><i class="fas fa-tags me-2" style="color: #687281;"></i> Manage Categories</a></li>
                        <li><a class="dropdown-item py-2" href="<?php echo SITE_URL; ?>/admin/orders"><i class="fas fa-shopping-bag me-2" style="color: #687281;"></i> Manage Orders</a></li>
                    </ul>
                </li>
                <?php endif; ?>
            </ul>

            <!-- Search Form -->
            <form class="d-flex mx-auto my-2 my-lg-0 col-12 col-md-4" action="<?php echo SITE_URL; ?>/products" method="GET">
                <div class="input-group">
                    <input class="form-control border-0 bg-light" type="search" name="search" placeholder="Search products..." aria-label="Search">
                    <button class="btn btn-light border-0" type="submit">
                        <i class="fas fa-search" style="color: #687281;"></i>
                    </button>
                </div>
            </form>

            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item me-2">
                    <a class="nav-link position-relative px-3" href="<?php echo SITE_URL; ?>/cart">
                        <i class="fas fa-shopping-cart"></i>
                        <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?php echo count($_SESSION['cart']); ?>
                                <span class="visually-hidden">items in cart</span>
                            </span>
                        <?php endif; ?>
                    </a>
                </li>
                <?php if(isLoggedIn()): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px; color: #687281;">
                            <i class="fas fa-user"></i>
                        </div>
                        <span class="d-none d-md-inline"><?php echo $_SESSION['username']; ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item py-2" href="<?php echo SITE_URL; ?>/user/profile.php"><i class="fas fa-user-circle me-2" style="color: #687281;"></i> My Profile</a></li>
                        <li><a class="dropdown-item py-2" href="<?php echo SITE_URL; ?>/user/orders.php"><i class="fas fa-clipboard-list me-2" style="color: #687281;"></i> My Orders</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2" href="<?php echo SITE_URL; ?>/user/logout.php"><i class="fas fa-sign-out-alt me-2 text-danger"></i> Logout</a></li>
                    </ul>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-light btn-sm px-3 me-2" href="<?php echo SITE_URL; ?>/user/login.php">
                        <i class="fas fa-sign-in-alt me-1" style="color: #ffffff;"></i> Login
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-light btn-sm px-3" href="<?php echo SITE_URL; ?>/user/register.php" style="color: #e19713;">
                        <i class="fas fa-user-plus me-1"></i> Register
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
