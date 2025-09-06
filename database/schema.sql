-- SOCOTECO II Billing Management System Database Schema
-- Created for Philippine Electric Cooperative

CREATE DATABASE IF NOT EXISTS socoteco_billing;
USE socoteco_billing;

-- Users table for authentication and role management
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    role ENUM('admin', 'cashier', 'meter_reader', 'customer') NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Customer categories
CREATE TABLE customer_categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(50) NOT NULL,
    description TEXT,
    base_rate DECIMAL(10,4) DEFAULT 0.0000,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Customers table
CREATE TABLE customers (
    customer_id INT PRIMARY KEY AUTO_INCREMENT,
    account_number VARCHAR(20) UNIQUE NOT NULL,
    meter_number VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50),
    address TEXT NOT NULL,
    barangay VARCHAR(100) NOT NULL,
    municipality VARCHAR(100) NOT NULL,
    province VARCHAR(100) DEFAULT 'South Cotabato',
    contact_number VARCHAR(20),
    email VARCHAR(100),
    category_id INT NOT NULL,
    connection_date DATE NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES customer_categories(category_id)
);

-- Meter readings table
CREATE TABLE meter_readings (
    reading_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    reading_date DATE NOT NULL,
    previous_reading DECIMAL(10,2) NOT NULL,
    current_reading DECIMAL(10,2) NOT NULL,
    consumption DECIMAL(10,2) NOT NULL,
    reading_type ENUM('actual', 'estimated', 'adjusted') DEFAULT 'actual',
    meter_reader_id INT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id),
    FOREIGN KEY (meter_reader_id) REFERENCES users(user_id)
);

-- Billing rates table
CREATE TABLE billing_rates (
    rate_id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT NOT NULL,
    min_kwh INT NOT NULL,
    max_kwh INT,
    rate_per_kwh DECIMAL(10,4) NOT NULL,
    effective_date DATE NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES customer_categories(category_id)
);

-- Bills table
CREATE TABLE bills (
    bill_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    reading_id INT NOT NULL,
    bill_number VARCHAR(20) UNIQUE NOT NULL,
    billing_period_start DATE NOT NULL,
    billing_period_end DATE NOT NULL,
    consumption DECIMAL(10,2) NOT NULL,
    generation_charge DECIMAL(10,2) NOT NULL,
    distribution_charge DECIMAL(10,2) NOT NULL,
    transmission_charge DECIMAL(10,2) DEFAULT 0.00,
    system_loss_charge DECIMAL(10,2) DEFAULT 0.00,
    vat DECIMAL(10,2) NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    due_date DATE NOT NULL,
    status ENUM('pending', 'paid', 'overdue', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id),
    FOREIGN KEY (reading_id) REFERENCES meter_readings(reading_id)
);

-- Payments table
CREATE TABLE payments (
    payment_id INT PRIMARY KEY AUTO_INCREMENT,
    bill_id INT NOT NULL,
    payment_date DATE NOT NULL,
    amount_paid DECIMAL(10,2) NOT NULL,
    payment_method ENUM('cash', 'check', 'bank_transfer', 'online') DEFAULT 'cash',
    or_number VARCHAR(20) UNIQUE NOT NULL,
    cashier_id INT NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (bill_id) REFERENCES bills(bill_id),
    FOREIGN KEY (cashier_id) REFERENCES users(user_id)
);

-- Payment history for partial payments
CREATE TABLE payment_history (
    history_id INT PRIMARY KEY AUTO_INCREMENT,
    bill_id INT NOT NULL,
    payment_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (bill_id) REFERENCES bills(bill_id),
    FOREIGN KEY (payment_id) REFERENCES payments(payment_id)
);

-- System settings
CREATE TABLE system_settings (
    setting_id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Audit trail for tracking user activities
CREATE TABLE audit_trail (
    audit_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    action VARCHAR(100) NOT NULL,
    table_name VARCHAR(50),
    record_id INT,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Insert default customer categories
INSERT INTO customer_categories (category_name, description, base_rate) VALUES
('Residential', 'Residential customers', 8.5000),
('Commercial', 'Commercial establishments', 9.2000),
('Industrial', 'Industrial customers', 8.8000),
('Government', 'Government offices and facilities', 8.0000);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, password, full_name, email, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin@socoteco2.com', 'admin');

-- Insert default system settings
INSERT INTO system_settings (setting_key, setting_value, description) VALUES
('company_name', 'SOCOTECO II', 'Electric Cooperative Name'),
('company_address', 'Koronadal City, South Cotabato', 'Company Address'),
('vat_rate', '12', 'VAT Percentage'),
('penalty_rate', '2', 'Monthly Penalty Rate'),
('due_days', '15', 'Days from bill date to due date'),
('generation_rate', '4.5000', 'Generation charge per kWh'),
('distribution_rate', '1.2000', 'Distribution charge per kWh'),
('transmission_rate', '0.8000', 'Transmission charge per kWh'),
('system_loss_rate', '0.5000', 'System loss charge per kWh');
