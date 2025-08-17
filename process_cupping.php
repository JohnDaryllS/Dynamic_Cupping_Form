<?php
session_start();
require 'db.php';

// Verify user access
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "user") {
    header("Location: login.php");
    exit();
}

date_default_timezone_set('Asia/Manila');

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Log the POST data for debugging
    error_log("Form submission received: " . print_r($_POST, true));
    
    // Process single form submission
    $form_number = $_POST['form_number'] ?? 1;
    $formData = extractFormData($form_number, $_POST);
    
    // Log the extracted form data for debugging
    error_log("Extracted form data: " . print_r($formData, true));
    
    if ($formData) {
        // Prepare SQL statement
        $columns = implode(', ', array_keys($formData));
        $placeholders = implode(', ', array_fill(0, count($formData), '?'));
        
        $sql = "INSERT INTO cupping_forms ($columns) VALUES ($placeholders)";
        
        // Log the SQL for debugging
        error_log("SQL Query: " . $sql);
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $error_msg = "Database error: " . $conn->error;
            error_log($error_msg);
            $_SESSION['form_error'] = $error_msg;
            echo json_encode(['success' => false, 'message' => $error_msg]);
            exit();
        }
        
        // Bind parameters
        $types = str_repeat('s', count($formData));
        $stmt->bind_param($types, ...array_values($formData));
        
        if ($stmt->execute()) {
            $success_msg = "Form $form_number submitted successfully!";
            error_log($success_msg);
            $_SESSION['form_success'] = $success_msg;
            echo json_encode(['success' => true, 'message' => $success_msg]);
        } else {
            $error_msg = "Error submitting form: " . $stmt->error;
            error_log($error_msg);
            $_SESSION['form_error'] = $error_msg;
            echo json_encode(['success' => false, 'message' => $error_msg]);
        }
        $stmt->close();
    } else {
        $error_msg = "Invalid form data";
        error_log($error_msg);
        $_SESSION['form_error'] = $error_msg;
        echo json_encode(['success' => false, 'message' => $error_msg]);
    }
} else {
    // Not a POST request
    $_SESSION['form_error'] = "Invalid request method";
    header("Location: user_dashboard.php");
    exit();
}

// Function to extract form data for a specific form number
function extractFormData($form_number, $post_data) {
    // Process checkbox arrays to JSON strings
    $fragrance_attributes = isset($post_data["fragrance_attributes"]) ? 
        (is_array($post_data["fragrance_attributes"]) ? 
            json_encode($post_data["fragrance_attributes"]) : 
            json_encode([$post_data["fragrance_attributes"]])) : null;
    
    $flavor_attributes = isset($post_data["flavor_attributes"]) ? 
        (is_array($post_data["flavor_attributes"]) ? 
            json_encode($post_data["flavor_attributes"]) : 
            json_encode([$post_data["flavor_attributes"]])) : null;
    
    $body_type = isset($post_data["body_type"]) ? 
        (is_array($post_data["body_type"]) ? 
            json_encode($post_data["body_type"]) : 
            json_encode([$post_data["body_type"]])) : null;
    
    $acidity_type = isset($post_data["acidity_type"]) ? 
        (is_array($post_data["acidity_type"]) ? 
            json_encode($post_data["acidity_type"]) : 
            json_encode([$post_data["acidity_type"]])) : null;
    
    $sweetness_type = isset($post_data["sweetness_type"]) ? 
        (is_array($post_data["sweetness_type"]) ? 
            json_encode($post_data["sweetness_type"]) : 
            json_encode([$post_data["sweetness_type"]])) : null;
    
    // Initialize an array to store all form data
    $formData = [
        'user_id' => $_SESSION["user_id"],
        'user_name' => $_SESSION["email"],
        'submission_date' => date('Y-m-d H:i:s'),
        'form_date' => $post_data["date"] ?? '',
        'form_number' => $form_number,
        'table_no' => $post_data["table_no"] ?? '',
        'batch_number' => $post_data["batch_number"] ?? 1,
        'sample_id' => $post_data["sample_id"] ?? '',
        'fragrance_intensity' => $post_data["fragrance_intensity"] ?? 3,
        'fragrance_attributes' => $fragrance_attributes,
        'fragrance_others_text' => $post_data["fragrance_others_text"] ?? '',
        'flavor_intensity' => $post_data["flavor_intensity"] ?? 3,
        'flavor_attributes' => $flavor_attributes,
        'flavor_others_text' => $post_data["flavor_others_text"] ?? '',
        'body_intensity' => $post_data["body_intensity"] ?? 3,
        'body_type' => $body_type,
        'body_others_text' => $post_data["body_others_text"] ?? '',
        'acidity_intensity' => $post_data["acidity_intensity"] ?? 3,
        'acidity_type' => $acidity_type,
        'acidity_others_text' => $post_data["acidity_others_text"] ?? '',
        'sweetness_intensity' => $post_data["sweetness_intensity"] ?? 3,
        'sweetness_type' => $sweetness_type,
        'sweetness_others_text' => $post_data["sweetness_others_text"] ?? '',
        'general_notes' => $post_data["general_notes"] ?? ''
    ];
    
    return $formData;
}
?>