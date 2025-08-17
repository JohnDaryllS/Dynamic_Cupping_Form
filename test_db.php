<?php
session_start();
require 'db.php';

// Check if user is logged in
if (!isset($_SESSION['role'])) {
    echo "Not logged in";
    exit();
}

echo "<h1>Database Test</h1>";
echo "<p>User Role: " . $_SESSION['role'] . "</p>";
echo "<p>User Email: " . ($_SESSION['email'] ?? 'N/A') . "</p>";

// Test database connection
if ($conn->ping()) {
    echo "<p style='color: green;'>Database connection: OK</p>";
} else {
    echo "<p style='color: red;'>Database connection: FAILED</p>";
    exit();
}

// Check if cupping_forms table exists
$result = $conn->query("SHOW TABLES LIKE 'cupping_forms'");
if ($result->num_rows > 0) {
    echo "<p style='color: green;'>Table 'cupping_forms': EXISTS</p>";
} else {
    echo "<p style='color: red;'>Table 'cupping_forms': NOT FOUND</p>";
    exit();
}

// Check table structure
echo "<h2>Table Structure</h2>";
$result = $conn->query("DESCRIBE cupping_forms");
if ($result) {
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>Could not describe table</p>";
}

// Check if there are any records
echo "<h2>Records in cupping_forms</h2>";
$result = $conn->query("SELECT COUNT(*) as count FROM cupping_forms");
if ($result) {
    $row = $result->fetch_assoc();
    echo "<p>Total records: " . $row['count'] . "</p>";
    
    if ($row['count'] > 0) {
        echo "<h3>Sample Records</h3>";
        $result2 = $conn->query("SELECT * FROM cupping_forms ORDER BY id DESC LIMIT 5");
        if ($result2) {
            echo "<table border='1'>";
            $first = true;
            while ($row = $result2->fetch_assoc()) {
                if ($first) {
                    echo "<tr>";
                    foreach (array_keys($row) as $key) {
                        echo "<th>" . $key . "</th>";
                    }
                    echo "</tr>";
                    $first = false;
                }
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        }
    } else {
        echo "<p style='color: orange;'>No records found in cupping_forms table</p>";
    }
} else {
    echo "<p style='color: red;'>Could not count records</p>";
}

// Test the specific query used in get_cupping_details.php
echo "<h2>Testing get_cupping_details Query</h2>";
if (isset($_GET['test_id'])) {
    $test_id = (int)$_GET['test_id'];
    echo "<p>Testing with ID: $test_id</p>";
    
    $query = "SELECT 
                cf.*,
                u.full_name,
                DATE_FORMAT(cf.submission_date, '%M %d, %Y %H:%i') as submission_date_formatted,
                DATE_FORMAT(cf.form_date, '%M %d, %Y') as form_date_formatted
              FROM cupping_forms cf
              LEFT JOIN users u ON cf.user_id = u.id
              WHERE cf.id = ?";
    
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $test_id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $form = $result->fetch_assoc();
                echo "<p style='color: green;'>Query successful</p>";
                echo "<pre>" . print_r($form, true) . "</pre>";
            } else {
                echo "<p style='color: orange;'>No record found with ID: $test_id</p>";
            }
        } else {
            echo "<p style='color: red;'>Query execution failed: " . $stmt->error . "</p>";
        }
    } else {
        echo "<p style='color: red;'>Query preparation failed: " . $conn->error . "</p>";
    }
} else {
    echo "<p>Add ?test_id=X to test a specific record</p>";
}

echo "<hr>";
echo "<p><a href='database_form.php'>Back to Database Form</a></p>";
?>
