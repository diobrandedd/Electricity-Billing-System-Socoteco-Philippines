<?php
/**
 * Database Setup Script for SOCOTECO II Billing Management System
 * This script will create the database and all required tables
 */

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'socoteco_billing';

echo "<h2>SOCOTECO II Database Setup</h2>";

try {
    // Connect to MySQL without selecting a database
    $pdo = new PDO("mysql:host=$host;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color: green;'>✓ Connected to MySQL server</p>";
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$database`");
    echo "<p style='color: green;'>✓ Database '$database' created/verified</p>";
    
    // Select the database
    $pdo->exec("USE `$database`");
    
    // Read and execute the schema file
    $schema_file = 'database/schema.sql';
    if (file_exists($schema_file)) {
        $sql = file_get_contents($schema_file);
        
        // Split the SQL into individual statements
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        
        foreach ($statements as $statement) {
            if (!empty($statement) && !preg_match('/^(CREATE DATABASE|USE)/i', $statement)) {
                try {
                    $pdo->exec($statement);
                } catch (PDOException $e) {
                    // Ignore errors for existing tables/data
                    if (strpos($e->getMessage(), 'already exists') === false && 
                        strpos($e->getMessage(), 'Duplicate entry') === false) {
                        echo "<p style='color: orange;'>⚠ Warning: " . $e->getMessage() . "</p>";
                    }
                }
            }
        }
        
        echo "<p style='color: green;'>✓ Database schema imported successfully</p>";
        
        // Verify admin user
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = 'admin'");
        $stmt->execute();
        $admin = $stmt->fetch();
        
        if ($admin) {
            echo "<p style='color: green;'>✓ Admin user verified</p>";
            echo "<p><strong>Username:</strong> admin</p>";
            echo "<p><strong>Password:</strong> admin123</p>";
            echo "<p><strong>Role:</strong> " . $admin['role'] . "</p>";
        } else {
            echo "<p style='color: red;'>✗ Admin user not found</p>";
        }
        
    } else {
        echo "<p style='color: red;'>✗ Schema file not found: $schema_file</p>";
    }
    
    echo "<hr>";
    echo "<p style='color: green;'><strong>Setup completed successfully!</strong></p>";
    echo "<p>You can now:</p>";
    echo "<ul>";
    echo "<li><a href='auth/login.php'>Login to the system</a></li>";
    echo "<li><a href='setup_admin.php'>Check admin user status</a></li>";
    echo "</ul>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Database error: " . $e->getMessage() . "</p>";
    echo "<p>Please check your MySQL configuration and make sure the server is running.</p>";
}
?>
