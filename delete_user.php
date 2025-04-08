<?php
session_start();
include 'db.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    
    // Prevent deleting the main admin account
    if ($user_id == 1) {
        $_SESSION['error'] = "Cannot delete the main admin account";
        header("Location: dashboard.php");
        exit();
    }
    
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "User deleted successfully";
    } else {
        $_SESSION['error'] = "Failed to delete user";
    }
}

header("Location: dashboard.php");
exit();
?>