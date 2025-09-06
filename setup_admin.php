<?php
/**
 * Setup script to create admin user and check database connection
 * Run this once to set up the system
 */

require_once 'config/config.php';

echo "<h2>SOCOTECO II Billing System Setup</h2>";

try {
    // Test database connection
    $db = getDB();
    if ($db) {
        echo "<p style='color: green;'>✓ Database connection successful!</p>";
        
        // Check if users table exists
        $sql = "SHOW TABLES LIKE 'users'";
        $result = fetchOne($sql);
        
        if ($result) {
            echo "<p style='color: green;'>✓ Users table exists!</p>";
            
            // Check if admin user exists
            $sql = "SELECT * FROM users WHERE username = 'admin'";
            $admin = fetchOne($sql);
            
            if ($admin) {
                echo "<p style='color: green;'>✓ Admin user already exists!</p>";
                echo "<p><strong>Username:</strong> admin</p>";
                echo "<p><strong>Password:</strong> admin123</p>";
                echo "<p><strong>Role:</strong> " . $admin['role'] . "</p>";
                echo "<p><strong>Status:</strong> " . ($admin['is_active'] ? 'Active' : 'Inactive') . "</p>";
            } else {
                echo "<p style='color: orange;'>⚠ Admin user not found. Creating...</p>";
                
                // Create admin user with hashed password
                $hashed_password = password_hash('admin123', PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (username, password, full_name, email, role, is_active) VALUES (?, ?, ?, ?, ?, ?)";
                executeQuery($sql, ['admin', $hashed_password, 'System Administrator', 'admin@socoteco2.com', 'admin', 1]);
                
                echo "<p style='color: green;'>✓ Admin user created successfully!</p>";
                echo "<p><strong>Username:</strong> admin</p>";
                echo "<p><strong>Password:</strong> admin123</p>";
            }
        } else {
            echo "<p style='color: red;'>✗ Users table does not exist!</p>";
            echo "<p>Please run the database schema first:</p>";
            echo "<ol>";
            echo "<li>Open phpMyAdmin or MySQL command line</li>";
            echo "<li>Create database: <code>socoteco_billing</code></li>";
            echo "<li>Import the file: <code>database/schema.sql</code></li>";
            echo "</ol>";
        }
        
    } else {
        echo "<p style='color: red;'>✗ Database connection failed!</p>";
        echo "<p>Please check your database configuration in <code>config/database.php</code></p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
    echo "<p>Please check your database configuration and make sure MySQL is running.</p>";
}

echo "<hr>";
echo "<p><a href='auth/login.php'>Go to Login Page</a></p>";
echo "<p><a href='index.php'>Go to Home Page</a></p>";
?>
