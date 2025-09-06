<?php
/**
 * Test script to verify password hashing
 */

echo "<h2>Password Hash Test</h2>";

$password = 'admin123';
$stored_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

echo "<p><strong>Testing password:</strong> $password</p>";
echo "<p><strong>Stored hash:</strong> $stored_hash</p>";

$verify_result = password_verify($password, $stored_hash);
echo "<p><strong>Verification result:</strong> " . ($verify_result ? 'SUCCESS' : 'FAILED') . "</p>";

if (!$verify_result) {
    echo "<p style='color: red;'>The stored hash doesn't match the password!</p>";
    echo "<p>Let's create a new hash:</p>";
    
    $new_hash = password_hash($password, PASSWORD_DEFAULT);
    echo "<p><strong>New hash:</strong> $new_hash</p>";
    
    $new_verify = password_verify($password, $new_hash);
    echo "<p><strong>New verification:</strong> " . ($new_verify ? 'SUCCESS' : 'FAILED') . "</p>";
    
    if ($new_verify) {
        echo "<p style='color: green;'>✓ New hash works! You can use this in the database.</p>";
        echo "<p>SQL to update admin password:</p>";
        echo "<code>UPDATE users SET password = '$new_hash' WHERE username = 'admin';</code>";
    }
} else {
    echo "<p style='color: green;'>✓ Password hash is correct!</p>";
}
?>
