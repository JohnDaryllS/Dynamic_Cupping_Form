<?php
session_start();
require 'db.php';

// Verify admin access
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if (!isset($_POST['id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'No ID provided']);
    exit();
}

$form_id = (int)$_POST['id'];

try {
    // First check if the form exists
    $check = $conn->prepare("SELECT id FROM cupping_forms WHERE id = ?");
    $check->bind_param("i", $form_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Form not found']);
        exit();
    }
    $check->close();

    // Delete the form
    $stmt = $conn->prepare("DELETE FROM cupping_forms WHERE id = ?");
    $stmt->bind_param("i", $form_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Form deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
exit();
?>