<?php
require_once __DIR__ . '/config/database.php';

try {
    $db = Database::getInstance();
    $db->exec("ALTER TABLE users MODIFY COLUMN role ENUM('admin','staff','manager','customer') NOT NULL DEFAULT 'customer'");
    
    // Let's also insert a staff and a manager for testing
    $hash = password_hash('Admin@1234', PASSWORD_BCRYPT, ['cost' => 12]);
    
    // Check if staff exists
    $stmt = $db->query("SELECT 1 FROM users WHERE email = 'staff@arcnebupen.com'");
    if (!$stmt->fetch()) {
        $db->exec("INSERT INTO users (fullname, email, password, role) VALUES ('ARC Staff', 'staff@arcnebupen.com', '$hash', 'staff')");
    }
    
    // Check if manager exists
    $stmt = $db->query("SELECT 1 FROM users WHERE email = 'manager@arcnebupen.com'");
    if (!$stmt->fetch()) {
        $db->exec("INSERT INTO users (fullname, email, password, role) VALUES ('ARC Manager', 'manager@arcnebupen.com', '$hash', 'manager')");
    }
    
    echo "Database updated successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
