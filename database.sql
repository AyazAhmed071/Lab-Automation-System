-- Lab Automation System Database Schema
CREATE DATABASE IF NOT EXISTS ayaz_ahmed;
USE ayaz_ahmed;

-- Admins table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id VARCHAR(10) NOT NULL UNIQUE, -- 10-digit Product ID
    product_name VARCHAR(100) NOT NULL,
    product_code VARCHAR(50) NOT NULL,
    revision VARCHAR(20) NOT NULL,
    manufacturing_number VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Testing Records table
CREATE TABLE IF NOT EXISTS testing_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    test_id VARCHAR(12) NOT NULL UNIQUE, -- 12-digit Test ID
    product_id VARCHAR(10) NOT NULL,
    test_type VARCHAR(100) NOT NULL,
    testing_department VARCHAR(100) NOT NULL,
    testing_criteria TEXT NOT NULL,
    result ENUM('Pass', 'Fail') NOT NULL,
    remarks TEXT,
    tester_name VARCHAR(100) NOT NULL,
    testing_date DATE NOT NULL,
    status VARCHAR(50) NOT NULL, -- e.g., 'CPRI approval' or 're-manufacturing'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);

-- Insert sample admin (password: admin123)
INSERT INTO admins (username, password) VALUES 
('admin', '$2y$10$8W3Y6u7G6H6v8Y8y7u8Y7u8Y7u8Y7u8Y7u8Y7u8Y7u8Y7u8Y7u8Y7', 'System Administrator');
-- Note: You can also run create_admin.php to create/update the admin account.
