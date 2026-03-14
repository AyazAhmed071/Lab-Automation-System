<?php
require_once 'config/db.php';

// Check if admin already exists
$check = $conn->query("SELECT * FROM admins WHERE username = 'admin'");
if ($check->num_rows == 0) {
    $username = 'admin';
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    $full_name = 'System Administrator';
    
    $stmt = $conn->prepare("INSERT INTO admins (username, password, full_name) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $full_name);
    
    if ($stmt->execute()) {
        echo "Admin user created successfully.<br>";
    } else {
        echo "Error creating admin user: " . $stmt->error . "<br>";
    }
    $stmt->close();
} else {
    echo "Admin user already exists.<br>";
}
?>
