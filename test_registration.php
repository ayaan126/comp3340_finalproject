<?php
require_once 'config/db.php';

echo "<h2>Registration System Test</h2>";

// Test database connection
if ($conn->connect_error) {
    echo "<p style='color: red;'>❌ Database connection failed: " . $conn->connect_error . "</p>";
    exit;
} else {
    echo "<p style='color: green;'>✅ Database connection successful</p>";
}

// Check if users table exists
$result = $conn->query("SHOW TABLES LIKE 'users'");
if ($result->num_rows > 0) {
    echo "<p style='color: green;'>✅ Users table exists</p>";
    
    // Check table structure
    $structure = $conn->query("DESCRIBE users");
    echo "<h3>Users Table Structure:</h3>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    
    while ($row = $structure->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['Field']}</td>";
        echo "<td>{$row['Type']}</td>";
        echo "<td>{$row['Null']}</td>";
        echo "<td>{$row['Key']}</td>";
        echo "<td>{$row['Default']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Count existing users
    $count_result = $conn->query("SELECT COUNT(*) as count FROM users");
    $count = $count_result->fetch_assoc()['count'];
    echo "<p><strong>Current users in database:</strong> {$count}</p>";
    
} else {
    echo "<p style='color: red;'>❌ Users table does not exist!</p>";
    echo "<p>Creating users table...</p>";
    
    $create_table = "
    CREATE TABLE users (
        user_id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($create_table)) {
        echo "<p style='color: green;'>✅ Users table created successfully!</p>";
    } else {
        echo "<p style='color: red;'>❌ Error creating users table: " . $conn->error . "</p>";
    }
}

echo "<h3>Test Registration</h3>";
echo "<p><a href='pages/register.php' target='_blank'>Go to Registration Page</a></p>";
echo "<p><a href='pages/login.php' target='_blank'>Go to Login Page</a></p>";

?>
