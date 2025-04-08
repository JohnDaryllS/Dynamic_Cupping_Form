<?php
session_start();
require 'db.php';

// Verify admin access
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

// Validate form submission
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $_SESSION['form_error'] = "Invalid form submission method";
    header("Location: user_dashboard.php");
    exit();
}

// Ensure we have the full_name in session
if (empty($_SESSION['full_name'])) {
    $stmt = $conn->prepare("SELECT full_name FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($_SESSION['full_name']);
    $stmt->fetch();
    $stmt->close();
    
    if (empty($_SESSION['full_name'])) {
        $_SESSION['full_name'] = $_SESSION['email']; // Fallback to email
    }
}

// Define all fields with validation
$fields = [
    // System fields
    'user_id' => ['type' => 'i', 'value' => $_SESSION['user_id']],
    'user_name' => ['type' => 's', 'value' => $_SESSION['email']],
    'full_name' => ['type' => 's', 'value' => $_POST['name'] ?? ''],
    'submission_date' => ['type' => 's', 'value' => date('Y-m-d H:i:s')],
    
    // Form fields
    'form_date' => ['type' => 's', 'value' => $_POST['date'] ?? '', 'required' => true],
    'table_no' => ['type' => 's', 'value' => $_POST['table_no'] ?? '', 'required' => true],
    'batch_number' => ['type' => 'i', 'value' => $_POST['batch_number'] ?? 1, 'required' => true],
    
    // Coffee attributes
    'fragrance_aroma' => ['type' => 'd', 'value' => (float)($_POST['fragrance_aroma'] ?? 0), 'required' => true],
    'dry' => ['type' => 'i', 'value' => (int)($_POST['dry'] ?? 3)],
    'break_value' => ['type' => 'i', 'value' => (int)($_POST['break'] ?? 3)],
    'quality1' => ['type' => 's', 'value' => $_POST['quality1'] ?? ''],
    'quality2' => ['type' => 's', 'value' => $_POST['quality2'] ?? ''],
    'fragrance_notes' => ['type' => 's', 'value' => $_POST['fragrance_notes'] ?? ''],
    
    // ... include all other form fields following the same pattern ...
    
    // Defects and scoring
    'defective_cups' => ['type' => 'i', 'value' => (int)($_POST['defective_cups'] ?? 0)],
    'defect_intensity' => ['type' => 'i', 'value' => (int)($_POST['defect_intensity'] ?? 0)],
    'defect_points' => ['type' => 'i', 'value' => (int)($_POST['defect_points'] ?? 0)],
    'total_score' => ['type' => 'd', 'value' => (float)($_POST['total_score'] ?? 0), 'required' => true],
    'final_score' => ['type' => 'd', 'value' => (float)($_POST['final_score'] ?? 0), 'required' => true],
    'comments' => ['type' => 's', 'value' => $_POST['comments'] ?? '']
];

// Validate required fields
foreach ($fields as $name => $field) {
    if (!empty($field['required']) && empty($field['value'])) {
        $_SESSION['form_error'] = "Required field missing: $name";
        header("Location: user_dashboard.php");
        exit();
    }
}

// Prepare SQL statement
$columns = implode(', ', array_keys($fields));
$placeholders = implode(', ', array_fill(0, count($fields), '?'));
$types = implode('', array_column($fields, 'type'));
$values = array_column($fields, 'value');

$sql = "INSERT INTO cupping_forms ($columns) VALUES ($placeholders)";

// Debug output (remove in production)
error_log("Executing query: $sql");
error_log("Types: $types");
error_log("Values: " . print_r($values, true));

$stmt = $conn->prepare($sql);
if (!$stmt) {
    $_SESSION['form_error'] = "Database error: " . $conn->error;
    header("Location: user_dashboard.php");
    exit();
}

// Bind parameters
$stmt->bind_param($types, ...$values);

if ($stmt->execute()) {
    $_SESSION['form_success'] = "Cupping form submitted successfully!";
    header("Location: database_form.php");
    exit();
} else {
    $_SESSION['form_error'] = "Submission failed: " . $stmt->error;
    header("Location: user_dashboard.php");
    exit();
}
?>