<?php
session_start();
require 'db.php';

// Verify user access
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "user") {
    header("Location: login.php");
    exit();
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize an array to store all form data
    $formData = [
        'user_id' => $_SESSION["user_id"],
        'user_name' => $_SESSION["email"],
        'submission_date' => date('Y-m-d H:i:s'),
        'form_date' => $_POST['date'] ?? '',
        'table_no' => $_POST['table_no'] ?? '',
        'batch_number' => $_POST['batch_number'] ?? 1,
        // Main attributes
        'fragrance_aroma' => $_POST['fragrance_aroma'] ?? 0,
        'dry' => $_POST['dry'] ?? 3,
        'break_value' => $_POST['break'] ?? 3,
        'quality1' => $_POST['quality1'] ?? '',
        'quality2' => $_POST['quality2'] ?? '',
        'fragrance_notes' => $_POST['fragrance_notes'] ?? '',
        'flavor' => $_POST['flavor'] ?? 0,
        'flavor_notes' => $_POST['flavor_notes'] ?? '',
        'aftertaste' => $_POST['aftertaste'] ?? 0,
        'aftertaste_notes' => $_POST['aftertaste_notes'] ?? '',
        'acidity' => $_POST['acidity'] ?? 0,
        'acidity_intensity' => $_POST['acidity_intensity'] ?? 3,
        'acidity_notes' => $_POST['acidity_notes'] ?? '',
        'body' => $_POST['body'] ?? 0,
        'body_level' => $_POST['body_level'] ?? 3,
        'body_notes' => $_POST['body_notes'] ?? '',
        'uniformity' => $_POST['uniformity'] ?? 10,
        'uniformity_notes' => $_POST['uniformity_notes'] ?? '',
        'clean_cup' => $_POST['clean_cup'] ?? 10,
        'clean_cup_notes' => $_POST['clean_cup_notes'] ?? '',
        'overall' => $_POST['overall'] ?? 0,
        'overall_notes' => $_POST['overall_notes'] ?? '',
        'balance' => $_POST['balance'] ?? 0,
        'balance_notes' => $_POST['balance_notes'] ?? '',
        'sweetness' => $_POST['sweetness'] ?? 10,
        'sweetness_notes' => $_POST['sweetness_notes'] ?? '',
        'defective_cups' => $_POST['defective_cups'] ?? 0,
        'defect_intensity' => $_POST['defect_intensity'] ?? 0,
        'defect_points' => $_POST['defect_points'] ?? 0,
        'total_score' => $_POST['total_score'] ?? 0,
        'final_score' => $_POST['final_score'] ?? 0,
        'comments' => $_POST['comments'] ?? ''
    ];

    // Prepare SQL statement
    $columns = implode(', ', array_keys($formData));
    $placeholders = implode(', ', array_fill(0, count($formData), '?'));
    
    $sql = "INSERT INTO cupping_forms ($columns) VALUES ($placeholders)";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $_SESSION['form_error'] = "Database error: " . $conn->error;
        header("Location: user_dashboard.php");
        exit();
    }
    
    // Bind parameters - we need to specify types for each parameter
    $types = str_repeat('s', count($formData)); // All as strings for simplicity
    $stmt->bind_param($types, ...array_values($formData));
    
    if ($stmt->execute()) {
        $_SESSION['form_success'] = "Cupping form submitted successfully!";
        header("Location: user_dashboard.php");
        exit();
    } else {
        $_SESSION['form_error'] = "Error submitting form: " . $stmt->error;
        header("Location: user_dashboard.php");
        exit();
    }
} else {
    // Not a POST request
    $_SESSION['form_error'] = "Invalid request method";
    header("Location: user_dashboard.php");
    exit();
}
?>