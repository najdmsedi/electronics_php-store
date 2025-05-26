<?php
require_once '../config/config.php';

// Destroy session
session_destroy();

// Redirect to home page
setMessage("You have been logged out successfully", "info");
redirect(SITE_URL);
?>
