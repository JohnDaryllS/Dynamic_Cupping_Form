<?php
include 'db.php';

echo "<h2>Database Update Script</h2>";

// Check if is_approved column exists
$check_column = $conn->query("SHOW COLUMNS FROM users LIKE 'is_approved'");
if ($check_column->num_rows == 0) {
    echo "<p>Adding 'is_approved' column...</p>";
    $sql = "ALTER TABLE users ADD COLUMN is_approved TINYINT(1) DEFAULT 0";
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>✓ 'is_approved' column added successfully</p>";
    } else {
        echo "<p style='color: red;'>✗ Error adding 'is_approved' column: " . $conn->error . "</p>";
    }
} else {
    echo "<p style='color: green;'>✓ 'is_approved' column already exists</p>";
}

// Check if created_at column exists
$check_created = $conn->query("SHOW COLUMNS FROM users LIKE 'created_at'");
if ($check_created->num_rows == 0) {
    echo "<p>Adding 'created_at' column...</p>";
    $sql = "ALTER TABLE users ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>✓ 'created_at' column added successfully</p>";
    } else {
        echo "<p style='color: red;'>✗ Error adding 'created_at' column: " . $conn->error . "</p>";
    }
} else {
    echo "<p style='color: green;'>✓ 'created_at' column already exists</p>";
}

// Update existing users to be approved (except admin)
echo "<p>Updating existing users to approved status...</p>";
$sql = "UPDATE users SET is_approved = 1 WHERE role != 'admin'";
if ($conn->query($sql) === TRUE) {
    echo "<p style='color: green;'>✓ Existing users updated to approved status</p>";
} else {
    echo "<p style='color: red;'>✗ Error updating users: " . $conn->error . "</p>";
}

// Ensure admin is approved
echo "<p>Ensuring admin is approved...</p>";
$sql = "UPDATE users SET is_approved = 1 WHERE role = 'admin'";
if ($conn->query($sql) === TRUE) {
    echo "<p style='color: green;'>✓ Admin users updated to approved status</p>";
} else {
    echo "<p style='color: red;'>✗ Error updating admin: " . $conn->error . "</p>";
}

echo "<h3>Database Update Complete!</h3>";
echo "<p><a href='login.php'>Go to Login</a> | <a href='register.php'>Go to Registration</a></p>";

$conn->close();
?>
