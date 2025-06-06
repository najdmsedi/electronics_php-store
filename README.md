# Electronics Store - PHP/MySQL E-commerce Website

A complete e-commerce solution for an electronics store built with PHP and MySQL.

## Features

### Front-office (Public)
- User registration and login with session management
- Dynamic product listings with categories
- Product search functionality
- Shopping cart management
- Checkout and order placement
- Order history for registered users

### Back-office (Admin)
- Secure admin login
- Dashboard with sales statistics
- Product management (CRUD operations)
- Category management
- Order management

## Technical Details

- PHP 7.4+ for server-side logic
- MySQL database for data storage
- Bootstrap 5 for responsive design
- HTML5 and CSS3 for markup and styling
- JavaScript for client-side functionality

## Installation

1. **Database Setup**
   - Create a MySQL database
   - Import the `database.sql` file to set up the tables and sample data

2. **Configuration**
   - Edit `config/database.php` with your database credentials
   - Edit `config/config.php` to set your site URL and other settings

3. **Server Requirements**
   - PHP 7.4 or higher
   - MySQL 5.7 or higher
   - PDO PHP Extension
   - GD PHP Extension (for image processing)

4. **File Permissions**
   - Ensure the `assets/images/products` and `assets/images/categories` directories are writable

## Usage

### Admin Access
- URL: `/admin/dashboard.php`
- Default admin credentials:
  - Username: admin
  - Password: admin123

### Customer Access
- Register a new account at `/user/register.php`
- Browse products at `/products`
- Add items to cart and checkout

## Project Structure

- `assets/` - CSS, JavaScript, and images
- `classes/` - PHP classes for database models
- `config/` - Configuration files
- `includes/` - Reusable PHP components
- `admin/` - Admin panel files
- `user/` - User account management
- `products/` - Product listing and details
- `cart/` - Shopping cart functionality

## UML Class Diagram

See `uml_class_diagram.txt` for a PlantUML representation of the application's class structure.

## Credits

Developed as a PHP/MySQL project following the specifications:

"Réaliser un site Web dynamique en langage PHP contenant une base de données MySQL, avec gestion de sessions, front-office pour les visiteurs (inscription, consultation des produits, commande) et back-office pour l'administrateur (authentification, CRUD des produits)"
#   e l e c t r o n i c s _ p h p - s t o r e  
 #   e l e c t r o n i c s _ p h p - s t o r e  
 #   e l e c t r o n i c s _ s t o r e  
 #   e l e c t r o n i c s _ s t o r e  
 #   e l e c t r o n i c s _ p h p - s t o r e  
 "# electronics_php-store" 
"# electronics_php-store" 
