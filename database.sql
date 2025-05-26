-- Database creation
CREATE DATABASE electronics_store;
USE electronics_store;

-- Users table for customer registration
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    address TEXT,
    phone VARCHAR(20),
    is_admin BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories table for product organization
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255)
);

-- Products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock_quantity INT NOT NULL DEFAULT 0,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Order items table (for items in each order)
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Insert admin user (password: admin123)
INSERT INTO users (username, email, password, first_name, last_name, is_admin) 
VALUES ('admin', 'admin@electronics.com', '$2y$10$8WxmVFNDrgEYDtGNQDyIpOXWk.7btCJlLXZQGGZVl9UUY9bFpmsWa', 'Admin', 'User', 1);

-- Insert sample categories
INSERT INTO categories (name, description, image) VALUES 
('Smartphones', 'Latest smartphones from top brands', 'smartphone.jpg'),
('Laptops', 'Powerful laptops for work and gaming', 'laptop.jpg'),
('Accessories', 'Essential accessories for your devices', 'accessories.jpg');

-- Insert sample products
INSERT INTO products (category_id, name, description, price, stock_quantity, image) VALUES
(1, 'iPhone 14 Pro', 'Latest iPhone with advanced features', 999.99, 50, 'iphone14.jpg'),
(1, 'Samsung Galaxy S23', 'Flagship Android smartphone', 899.99, 45, 'galaxy_s23.jpg'),
(2, 'MacBook Pro 16"', 'Powerful laptop for professionals', 2499.99, 20, 'macbook_pro.jpg'),
(2, 'Dell XPS 15', 'Premium Windows laptop', 1899.99, 25, 'dell_xps.jpg'),
(3, 'AirPods Pro', 'Wireless earbuds with noise cancellation', 249.99, 100, 'airpods.jpg'),
(3, 'Wireless Charger', 'Fast wireless charging pad', 49.99, 200, 'wireless_charger.jpg');
