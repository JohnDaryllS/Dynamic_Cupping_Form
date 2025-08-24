<?php
require 'autologin.php';
include 'db.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "user") {
    header("Location: login.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get all form data
    $user_id = $_SESSION["user_id"];
    $user_name = $_SESSION["full_name"];
    $submission_date = date('Y-m-d H:i:s');
    $form_date = $_POST['date'];
    $table_no = $_POST['table_no'];
    $batch_number = $_POST['batch_number'];
    
    // Main attributes
    $fragrance_aroma = $_POST['fragrance_aroma'];
    $dry = $_POST['dry'];
    $break_value = $_POST['break'];
    $quality1 = $_POST['quality1'] ?? '';
    $quality2 = $_POST['quality2'] ?? '';
    $fragrance_notes = $_POST['fragrance_notes'] ?? '';
    
    $flavor = $_POST['flavor'];
    $flavor_notes = $_POST['flavor_notes'] ?? '';
    
    $aftertaste = $_POST['aftertaste'];
    $aftertaste_notes = $_POST['aftertaste_notes'] ?? '';
    
    $acidity = $_POST['acidity'];
    $acidity_intensity = $_POST['acidity_intensity'];
    $acidity_notes = $_POST['acidity_notes'] ?? '';
    
    $body = $_POST['body'];
    $body_level = $_POST['body_level'];
    $body_notes = $_POST['body_notes'] ?? '';
    
    $uniformity = $_POST['uniformity'];
    $uniformity_notes = $_POST['uniformity_notes'] ?? '';
    
    $clean_cup = $_POST['clean_cup'];
    $clean_cup_notes = $_POST['clean_cup_notes'] ?? '';
    
    $overall = $_POST['overall'];
    $overall_notes = $_POST['overall_notes'] ?? '';
    
    $balance = $_POST['balance'];
    $balance_notes = $_POST['balance_notes'] ?? '';
    
    $sweetness = $_POST['sweetness'];
    $sweetness_notes = $_POST['sweetness_notes'] ?? '';
    
    // Defects and scoring
    $defective_cups = $_POST['defective_cups'] ?? 0;
    $defect_intensity = $_POST['defect_intensity'] ?? 0;
    $defect_points = $_POST['defect_points'] ?? 0;
    $total_score = $_POST['total_score'];
    $final_score = $_POST['final_score'];
    
    $comments = $_POST['comments'] ?? '';
    
    // Prepare and execute the insert statement
    $stmt = $conn->prepare("INSERT INTO cupping_forms (
        user_id, user_name, submission_date, form_date, table_no, batch_number,
        fragrance_aroma, dry, break_value, quality1, quality2, fragrance_notes,
        flavor, flavor_notes, aftertaste, aftertaste_notes,
        acidity, acidity_intensity, acidity_notes,
        body, body_level, body_notes,
        uniformity, uniformity_notes, clean_cup, clean_cup_notes,
        overall, overall_notes, balance, balance_notes,
        sweetness, sweetness_notes,
        defective_cups, defect_intensity, defect_points,
        total_score, final_score, comments
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("issssiiddisssdssdssdssisssdssdssisssiidds",
        $user_id, $user_name, $submission_date, $form_date, $table_no, $batch_number,
        $fragrance_aroma, $dry, $break_value, $quality1, $quality2, $fragrance_notes,
        $flavor, $flavor_notes, $aftertaste, $aftertaste_notes,
        $acidity, $acidity_intensity, $acidity_notes,
        $body, $body_level, $body_notes,
        $uniformity, $uniformity_notes, $clean_cup, $clean_cup_notes,
        $overall, $overall_notes, $balance, $balance_notes,
        $sweetness, $sweetness_notes,
        $defective_cups, $defect_intensity, $defect_points,
        $total_score, $final_score, $comments
    );
    
    if ($stmt->execute()) {
        $_SESSION['form_success'] = "Cupping form submitted successfully!";
        header("Location: user_dashboard.php"); // Redirect back to the form
        exit();
    } else {
        $_SESSION['form_error'] = "Error submitting form: " . $conn->error;
        header("Location: user_dashboard.php"); // Redirect back to the form
        exit();
    }
}

// Get user data from database
$user_id = $_SESSION["user_id"];
$stmt = $conn->prepare("SELECT full_name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$full_name = $user['full_name'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard | SCA Cupping Form</title>
    <link rel="shortcut icon" href="img/fci.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        .header-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }
        .header-info > div {
            flex: 1;
            min-width: 200px;
        }
        .header-info label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .header-info input, .header-info select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .quality-scale table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .quality-scale th, .quality-scale td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        .quality-scale th {
            background-color: #f5f5f5;
        }
        .range-container {
            margin-bottom: 30px;
        }
        .range-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .score-box {
            width: 60px;
            text-align: center;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f8f9fa;
            font-weight: 600;
        }
        input[type="range"] {
            width: 100%;
            margin: 10px 0;
        }
        .range-labels {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #666;
        }
        .fragrance-sub-attributes {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            flex-wrap: wrap;
            gap: 20px;
        }
        .sub-attribute {
            flex: 1;
            min-width: 120px;
        }
        .vertical-range {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .vertical-range input[type="range"] {
            width: 30px;
            height: 150px;
            writing-mode: vertical-lr;
            direction: rtl;
        }
        .vertical-labels {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 150px;
            font-size: 12px;
        }
        .qualities-container {
            flex: 2;
            min-width: 200px;
        }
        
        .intensity-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }
        .intensity-label {
            font-weight: bold;
        }
        .intensity-range {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .intensity-range input[type="range"] {
            width: 30px;
            height: 150px;
            writing-mode: vertical-lr;
            direction: rtl;
        }
        .intensity-labels {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 150px;
            font-size: 12px;
        }
        .intensity-value {
            width: 30px;
            text-align: center;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #f8f8f8;
            font-weight: bold;
        }

        .level-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }
        .level-label {
            font-weight: bold;
        }
        .level-range {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .level-range input[type="range"] {
            width: 30px;
            height: 150px;
            writing-mode: vertical-lr;
            direction: rtl;
        }
        .level-labels {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 150px;
            font-size: 12px;
        }
        .level-value {
            width: 30px;
            text-align: center;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #f8f8f8;
            font-weight: bold;
        }

        .small-input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        .attribute-notes input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .notes-section {
            margin-top: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }
        .notes-columns {
            display: flex;
            gap: 20px;
            margin: 15px 0;
        }
        .notes-column {
            flex: 1;
        }
        .scoring-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .scoring-row input {
            width: 80px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        textarea {
            width: 100%;
            min-height: 100px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: vertical;
        }
        .hidden {
            display: none;
        }
        #successMessage {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
            text-align: center;
        }

        /* Your existing CSS styles here */
        .form-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 25px;
            margin-bottom: 30px;
        }
        .header-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }
        .header-info > div {
            flex: 1;
            min-width: 200px;
        }
        .header-info label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .header-info input, .header-info select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .quality-scale table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .quality-scale th, .quality-scale td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        .quality-scale th {
            background-color: #f5f5f5;
        }
        .range-container {
            margin-bottom: 30px;
        }
        .range-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .score-box {
            width: 60px;
            text-align: center;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f8f9fa;
            font-weight: 600;
        }

        /* Vertical Form Styles */
        .vertical-form {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }
        
        .form-section {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .form-section h4 {
            background: #2c5530;
            color: white;
            padding: 10px 15px;
            margin: -20px -20px 20px -20px;
            border-radius: 8px 8px 0 0;
            font-size: 16px;
            font-weight: 600;
        }
        
        .sample-input input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .intensity-scale {
            margin-bottom: 20px;
            text-align: center;
        }
        
        .scale-labels {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-weight: 600;
            color: #333;
        }
        
        .intensity-scale input[type="range"] {
            width: 100%;
            height: 8px;
            border-radius: 4px;
            background: #ddd;
            outline: none;
            -webkit-appearance: none;
        }
        
        .intensity-scale input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #2c5530;
            cursor: pointer;
        }
        
        .attributes-grid {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .attribute-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .attribute-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 6px;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.2s;
            min-width: 120px;
            position: relative;
            user-select: none;
            outline: none;
        }

        .attribute-item:focus {
            outline: 2px solid #2c5530;
            outline-offset: 2px;
        }
        
        .attribute-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            border-color: #2c5530 !important;
        }

        .attribute-item:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        /* Click feedback */
        .attribute-item.clicking {
            transform: scale(0.98);
            transition: transform 0.1s ease;
        }

        /* Validation Error Styling */
        .alert-danger {
            border-left: 4px solid #dc3545;
            background: linear-gradient(135deg, #fff5f5 0%, #ffe6e6 100%);
            border-color: #dc3545;
            color: #721c24;
            font-weight: 500;
        }

        .alert-danger .btn-close {
            color: #dc3545;
        }

        .alert-danger .error-message {
            white-space: pre-line;
            line-height: 1.5;
        }

        /* Validation Warning Banner Styling */
        .validation-warning-banner {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border: 2px solid #ffc107;
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.2);
            animation: slideInDown 0.5s ease-out;
        }

        .validation-warning-banner .warning-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .validation-warning-banner .warning-text {
            color: #856404;
            font-weight: 600;
            font-size: 14px;
            flex: 1;
        }

        .validation-warning-banner .btn-outline-warning {
            border-color: #ffc107;
            color: #856404;
            font-weight: 500;
            white-space: nowrap;
        }

        .validation-warning-banner .btn-outline-warning:hover {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Validation Status Modal Styling */
        .validation-status-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
        }

        .validation-status-modal .modal-content {
            background: white;
            border-radius: 12px;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease-out;
        }

        .validation-status-modal .modal-header {
            background: linear-gradient(135deg, #2c5530, #4a7c59);
            color: white;
            padding: 20px;
            border-radius: 12px 12px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .validation-status-modal .modal-header h3 {
            margin: 0;
            font-size: 1.3rem;
        }

        .validation-status-modal .close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background-color 0.2s;
        }

        .validation-status-modal .close-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .validation-status-modal .modal-body {
            padding: 20px;
        }

        .validation-status-summary h4 {
            color: #2c5530;
            margin-bottom: 20px;
            text-align: center;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
        }

        .validation-status-item {
            margin-bottom: 15px;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid;
        }

        .validation-status-item.status-complete {
            background: #d4edda;
            border-left-color: #28a745;
            color: #155724;
        }

        .validation-status-item.status-incomplete {
            background: #f8d7da;
            border-left-color: #dc3545;
            color: #721c24;
        }

        .validation-status-item .status-header {
            font-weight: 600;
            margin-bottom: 8px;
        }

        .validation-status-item .missing-items {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Form validation visual indicators */
        .form-section:has(input[type="checkbox"]:not(:checked)) .form-section-header {
            position: relative;
        }

        .form-section:has(input[type="checkbox"]:not(:checked)) .form-section-header::after {
            content: '⚠️ Required';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            font-size: 12px;
            color: #dc3545;
            background: #fff;
            padding: 2px 6px;
            border-radius: 4px;
            border: 1px solid #dc3545;
        }
        
        .attribute-item input[type="checkbox"] {
            margin: 0;
            width: 16px;
            height: 16px;
        }
        
        .attribute-item label {
            margin: 0;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            user-select: none;
        }
        
        /* Color schemes for attributes */
        .attribute-item.green {
            background-color: #90EE90;
            border-color: #32CD32;
        }
        
        .attribute-item.orange {
            background-color: #FF8C00;
            border-color: #FF8C00;
        }
        
        .attribute-item.pink {
            background-color: #FFB6C1;
            border-color: #FF69B4;
        }
        
        .attribute-item.purple {
            background-color: #DDA0DD;
            border-color: #9932CC;
        }

        .attribute-item.brown {
            background-color:rgb(168, 101, 53);
        }
        
        .attribute-item.dark-red {
            background-color: #CD5C5C;
            border-color: #B22222;
            color: white;
        }

        .attribute-item.white {
            background-color: #FFFFFF;
            border-color: #FFFFFF;
        }
        
        .attribute-item.dark-red[id*="spices"] {
            background-color: #8B0000;
            border-color: #660000;
        }
        
        .attribute-item.dark-purple {
            background-color: #9370DB;
            border-color: #4B0082;
            color: white;
        }
        
        .attribute-item.light-green {
            background-color: #98FB98;
            border-color: #90EE90;
        }
        
        .attribute-item.light-blue {
            background-color: #87CEEB;
            border-color: #4682B4;
        }
        
        .attribute-item.yellow {
            background-color: #FFFFE0;
            border-color: #FFD700;
        }
        
        .attribute-item.orange-yellow {
            background-color: #FFE4B5;
            border-color: #FFA500;
        }
        
        .attribute-item.white {
            background-color: #F8F8FF;
            border-color: #C0C0C0;
        }
        
        .attribute-item input[type="checkbox"]:checked + label {
            color: #2c5530;
            font-weight: 700;
        }
        
        .attribute-item input[type="checkbox"]:checked {
            accent-color: #2c5530;
        }

        /* Visual indicator for clickable area */
        .attribute-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 6px;
            background: transparent;
            transition: background-color 0.2s;
            pointer-events: none;
        }

        .attribute-item:hover::before {
            background: rgba(44, 85, 48, 0.1);
        }

        /* Additional visual cues for clickability */
        .attribute-item::after {
            content: '👆';
            position: absolute;
            top: -8px;
            right: -8px;
            font-size: 12px;
            opacity: 0;
            transition: opacity 0.2s;
            pointer-events: none;
        }

        .attribute-item:hover::after {
            opacity: 0.7;
        }
        
        .form-section textarea {
            width: 100%;
            min-height: 80px;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: vertical;
            font-family: inherit;
        }
        
        /* Others input styling */
        .others-input {
            margin-top: 10px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 6px;
            border: 1px solid #e9ecef;
        }
        
        .others-text-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            background-color: white;
        }
        
        .others-text-input:focus {
            outline: none;
            border-color: #2c5530;
            box-shadow: 0 0 0 2px rgba(44, 85, 48, 0.2);
        }

        /* New styles for flavor categories */
        .flavor-categories {
            display: flex;
            flex-direction: row;
            gap: 15px;
            margin-top: 20px;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        
        .flavor-category {
            flex: 1;
            min-width: 150px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .flavor-category .category-header {
            font-size: 16px;
            font-weight: bold;
            color: white;
            margin-bottom: 15px;
            padding: 8px 12px;
            border-radius: 6px;
            text-align: center;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }
        
        .flavor-category .category-attributes {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .flavor-category .attribute-item {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            padding: 6px 10px;
            margin: 0;
            min-width: auto;
        }
        
        .flavor-category .attribute-item:hover {
            transform: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .flavor-category .attribute-item input[type="checkbox"] {
            margin-right: 8px;
        }
        
        .flavor-category .attribute-item label {
            font-size: 13px;
            font-weight: 500;
        }
        
        .flavor-category .category-header.spices {
            background-color: #8B0000;
        }
        .flavor-category .category-header.nutty {
            background-color: #8B4513;
        }
        .flavor-category .category-header.sweet {
            background-color: #FF8C00;
        }
        .flavor-category .category-header.floral {
            background-color: #FF1493;
        }
        .flavor-category .category-header.fruity {
            background-color: #FF69B4;
        }
        .flavor-category .category-header.sour {
            background-color: #FFD700;
            color: #333;
            text-shadow: none;
        }

        /* Form Navigation Styles */
        .form-navigation {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
            padding: 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .nav-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .nav-btn {
            background: #e9ecef;
            color: #6c757d;
            border: 2px solid #dee2e6;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 18px;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .nav-btn:hover {
            background: #2c5530;
            color: white;
            border-color: #2c5530;
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(44, 85, 48, 0.4);
        }

        .nav-btn:hover::before {
            content: 'Form ' attr(data-form);
            position: absolute;
            bottom: -35px;
            left: 50%;
            transform: translateX(-50%);
            background: #2c5530;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            white-space: nowrap;
            z-index: 1000;
            opacity: 0;
            animation: tooltipFadeIn 0.3s ease-in-out forwards;
        }

        @keyframes tooltipFadeIn {
            to {
                opacity: 1;
            }
        }

        .nav-btn.active {
            background: #2c5530;
            color: white;
            border-color: #2c5530;
            box-shadow: 0 4px 12px rgba(44, 85, 48, 0.4);
            position: relative;
        }

        .nav-btn.active::after {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            background: linear-gradient(45deg, #28a745, #20c997);
            border-radius: 50%;
            z-index: -1;
            animation: activeGlow 2s ease-in-out infinite alternate;
        }

        @keyframes activeGlow {
            from {
                opacity: 0.6;
                transform: scale(1);
            }
            to {
                opacity: 1;
                transform: scale(1.05);
            }
        }

        /* Mobile responsive styles */
        @media (max-width: 768px) {
            .nav-buttons {
                gap: 8px;
            }
            
            .nav-btn {
                width: 45px;
                height: 45px;
                font-size: 16px;
            }
            
            .form-navigation {
                padding: 15px;
                gap: 12px;
            }

            /* Make attribute items more touch-friendly on mobile */
            .attribute-item {
                padding: 12px 16px;
                min-width: 140px;
                min-height: 44px; /* Minimum touch target size */
            }

            .attribute-item input[type="checkbox"] {
                width: 18px;
                height: 18px;
            }
        }

        @media (max-width: 480px) {
            .nav-buttons {
                gap: 6px;
            }
            
            .nav-btn {
                width: 40px;
                height: 40px;
                font-size: 14px;
            }
            
            .form-navigation.bottom-navigation {
                margin-top: 20px;
                padding: 15px;
            }
        }

        .form-counter {
            font-size: 18px;
            font-weight: 600;
            color: #2c5530;
            background: white;
            padding: 10px 20px;
            border-radius: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        /* Bottom Navigation Styles */
        .form-navigation.bottom-navigation {
            margin-top: 30px;
            margin-bottom: 0;
            border-top: 3px solid #2c5530;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            position: relative;
        }

        .form-navigation.bottom-navigation::before {
            content: '';
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: #2c5530;
            border-radius: 2px;
        }

        .form-navigation.bottom-navigation .nav-btn {
            background: #f8f9fa;
            border-color: #2c5530;
            color: #2c5530;
        }

        .form-navigation.bottom-navigation .nav-btn:hover {
            background: #2c5530;
            color: white;
        }

        .form-navigation.bottom-navigation .nav-btn.active {
            background: #2c5530;
            color: white;
        }

        .navigation-separator {
            height: 2px;
            background: linear-gradient(90deg, transparent, #2c5530, transparent);
            margin: 20px 0;
            border-radius: 1px;
        }

        /* Form Pages */
        .form-page {
            display: none;
            animation: fadeIn 0.5s ease-in-out;
        }

        .form-page.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes buttonPulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }

        .nav-btn:active {
            animation: buttonPulse 0.2s ease-in-out;
        }

        /* Progress Indicator */
        .progress-indicator {
            margin: 20px 0;
            text-align: center;
        }
        
        .progress-text {
            font-size: 16px;
            font-weight: 600;
            color: #2c5530;
            margin-bottom: 10px;
        }
        
        .progress-bar {
            width: 100%;
            height: 20px;
            background-color: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #28a745, #20c997);
            border-radius: 10px;
            transition: width 0.5s ease;
            width: 0%;
        }
        
        /* Review Modal Styles */
        .review-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }
        
        .review-modal-content {
            background-color: #fff;
            margin: 2% auto;
            padding: 0;
            border-radius: 15px;
            width: 90%;
            max-width: 1200px;
            max-height: 90vh;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease-out;
        }
        
        @keyframes modalSlideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .review-modal-header {
            background: linear-gradient(135deg, #2c5530, #4a7c59);
            color: white;
            padding: 20px 25px;
            border-radius: 15px 15px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .review-modal-header h3 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .close-review-modal {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 5px;
            border-radius: 50%;
            transition: background-color 0.3s;
        }
        
        .close-review-modal:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        .review-modal-body {
            padding: 25px;
            max-height: 60vh;
            overflow-y: auto;
        }
        
        .review-modal-footer {
            padding: 20px 25px;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
            border-radius: 0 0 15px 15px;
            display: flex;
            justify-content: space-between;
            gap: 15px;
        }
        
        .review-modal-footer .btn {
            padding: 12px 24px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .review-modal-footer .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        /* Review Form Styles */
        .review-form-section {
            margin-bottom: 30px;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .review-form-header {
            background: linear-gradient(135deg, #6c757d, #495057);
            color: white;
            padding: 15px 20px;
            font-weight: 600;
            font-size: 1.2rem;
        }
        
        .review-form-content {
            padding: 20px;
            background-color: #f8f9fa;
        }
        
        .review-field-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 15px;
        }
        
        .review-field {
            background-color: white;
            padding: 12px 15px;
            border-radius: 8px;
            border-left: 4px solid #28a745;
        }
        
        .review-field-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 5px;
            font-size: 0.9rem;
        }
        
        .review-field-value {
            color: #212529;
            font-size: 1rem;
        }
        
        .review-field-value.empty {
            color: #6c757d;
            font-style: italic;
        }
        
        .review-attributes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }
        
        /* Enhanced Review Attribute Styles */
        .review-attribute-item {
            background-color: #28a745;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            display: inline-block;
            margin: 4px 6px 4px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.2s ease;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
        }
        
        .review-attribute-item:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .review-attribute-item.selected {
            background-color: #28a745;
            color: white;
        }
        
        /* Fix for attribute containers positioning */
        .review-field-value {
            color: #212529;
            font-size: 1rem;
            word-wrap: break-word;
            overflow-wrap: break-word;
            line-height: 1.4;
        }
        
        .review-field-value .review-attribute-item {
            margin: 4px 6px 4px 0;
            vertical-align: top;
            display: inline-block;
        }
        
        /* Ensure attributes are properly contained within their fields */
        .review-field-value {
            flex: 1;
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            gap: 4px;
        }
        
        /* Ensure proper spacing in review field groups */
        .review-field-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
            align-items: start;
        }
        
        /* Sample ID and Date container - full width */
        .review-field-group.sample-info {
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            width: 100%;
        }
        
        .review-field-group.sample-info .review-field {
            min-height: auto;
            flex: 1;
        }
        
        .review-field {
            background-color: white;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #28a745;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            min-height: 80px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .review-field-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
            font-size: 0.95rem;
            flex-shrink: 0;
        }
        
        .review-field-value.empty {
            color: #6c757d;
            font-style: italic;
        }
        
        .review-attributes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }
        
        .review-attribute-item.selected {
            background-color: #28a745;
            color: white;
        }
        
        /* Mobile Responsive Styles for Review Modal */
        @media (max-width: 768px) {
            .review-modal-content {
                margin: 2% auto;
                width: 95%;
                max-width: none;
                max-height: 90vh;
                border-radius: 8px;
            }

            .review-modal-header {
                padding: 15px;
                border-radius: 8px 8px 0 0;
            }

            .review-modal-header h3 {
                font-size: 1.2rem;
            }

            .close-review-modal {
                width: 28px;
                height: 28px;
                font-size: 20px;
            }

            .review-modal-body {
                padding: 15px;
                max-height: 70vh;
            }

            .review-modal-footer {
                padding: 15px;
                flex-direction: column;
                gap: 10px;
            }

            .review-modal-footer .btn {
                width: 100%;
                justify-content: center;
            }

            .review-field-group {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .review-field {
                padding: 12px;
                min-height: auto;
            }
            
            .review-field-value {
                flex-direction: column;
                align-items: flex-start;
            }

            .review-field-value {
                max-width: none;
                text-align: left;
                word-break: break-word;
            }

            .review-attribute-item {
                max-width: none;
                margin: 3px 4px 3px 0;
                padding: 6px 10px;
                font-size: 0.85rem;
            }
        }
        
        /* Badge styles */
        .badge {
            display: inline-block;
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.375rem;
        }
        
        .bg-success {
            background-color: #198754 !important;
            color: white !important;
        }
        
        .bg-secondary {
            background-color: #6c757d !important;
            color: white !important;
        }
        
        .me-2 {
            margin-right: 0.5rem !important;
        }
        
        .mt-2 {
            margin-top: 0.5rem !important;
        }
        

    </style>
</head>
<body>
    <div id="loading-screen">
        <div class="loading-spinner"></div>
        <div class="loading-text">Loading...</div>
    </div>

    <div class="user-container">
        <!-- Sidebar Navigation -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <div class="logo-container">
                    <img src="img/TRANSPARENT BG.png" alt="Logo" class="sidebar-logo">
                    <img src="img/fci.png" alt="Second Logo" class="sidebar-logo second-logo">
                </div>
                <h3>Filipino Coffee Institute</h3>
            </div>
            <ul class="sidebar-menu">
                <li class="active"><a href="#"><i class="fas fa-home"></i> Dashboard</a></li>
            </ul>
            <div class="sidebar-footer">
                <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <header class="main-header">
                <h1>Welcome, <?php echo htmlspecialchars($full_name); ?></h1>
                <div class="user-info">
                    <div class="avatar"><?php echo strtoupper(substr($_SESSION["email"], 0, 1)); ?></div>
                </div>
            </header>

            <!-- Add this near your error display section -->
            <?php if(isset($_SESSION['form_success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?php echo $_SESSION['form_success']; unset($_SESSION['form_success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="content-wrapper">
                <?php if(isset($_SESSION['form_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?php echo $_SESSION['form_error']; unset($_SESSION['form_error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Welcome to Filipino Coffee Institute Cupping Form!
                </div>
                
                <!-- Coffee Cupping Form Container -->
                <div class="form-container">
                    <!-- Navigation Buttons -->
                    <div class="form-navigation">
                        <div class="nav-buttons">
                            <button type="button" class="nav-btn active" data-form="1" aria-label="Go to Form 1" title="Form 1">1</button>
                            <button type="button" class="nav-btn" data-form="2" aria-label="Go to Form 2" title="Form 2">2</button>
                            <button type="button" class="nav-btn" data-form="3" aria-label="Go to Form 3" title="Form 3">3</button>
                            <button type="button" class="nav-btn" data-form="4" aria-label="Go to Form 4" title="Form 4">4</button>
                            <button type="button" class="nav-btn" data-form="5" aria-label="Go to Form 5" title="Form 5">5</button>
                            <button type="button" class="nav-btn" data-form="6" aria-label="Go to Form 6" title="Form 6">6</button>
                        </div>
                        <div class="form-counter">Form <span id="currentFormNum">1</span> of 6</div>
                    </div>
                    
                    <!-- Progress Indicator -->
                    <div class="progress-indicator">
                        <div class="progress-text">Forms Completed: <span id="completedForms">0</span>/6</div>
                        <div class="progress-bar">
                            <div class="progress-fill" id="progressFill"></div>
                        </div>
                    </div>
                    
                    <!-- Auto-save Status and Controls -->
                    <div class="auto-save-status">
                        <div class="status-info">
                            <i class="fas fa-save me-2"></i>
                            <span>Auto-save is <span id="autoSaveStatus" class="status-active">ACTIVE</span></span>
                        </div>
                        <div class="status-controls">
                            <button type="button" onclick="saveAllForms()" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-save me-2"></i> Save All Forms
                            </button>
                            <button type="button" onclick="clearAllData()" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash me-2"></i> Clear All Data
                            </button>
                        </div>
                    </div>
                    

                    
                    <!-- Review Modal -->
                    <div id="reviewModal" class="review-modal">
                        <div class="review-modal-content">
                            <div class="review-modal-header">
                                <h3><i class="fas fa-eye me-2"></i>Review All Forms Before Submission</h3>
                                <button type="button" class="close-review-modal" id="closeReviewModal">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="review-modal-body" id="reviewModalBody">
                                <!-- Review content will be populated here -->
                            </div>
                            <div class="review-modal-footer">
                                <button type="button" class="btn btn-success" id="confirmSubmitBtn">
                                    <i class="fas fa-check me-2"></i>Confirm & Submit All Forms
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Form 1 -->
                    <div class="form-page active" id="form1">
                        <!-- Validation Warning Banner -->
                        <div id="validationWarning1" class="validation-warning-banner" style="display: none;">
                            <div class="warning-content">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <span class="warning-text">This form requires completion of all sections before submission.</span>
                            </div>
                        </div>
                        
                        <form id="cuppingForm1" method="POST" action="process_cupping.php" enctype="multipart/form-data">
                            <input type="hidden" name="_subject" value="New Coffee Cupping Form Submission">
                            
                            <input type="hidden" name="form_number" value="1">
                            
                            <div class="header-info">
                                <div>
                                    <label for="name1">Name:</label>
                                    <input type="text" id="name1" name="name" value="<?php echo htmlspecialchars($full_name); ?>" required readonly>
                                </div>
                                <div>
                                    <label for="date1">Date:</label>
                                    <input type="date" id="date1" name="date" required>
                                </div>
                                <div>
                                    <label for="tableNo1">Table no:</label>
                                    <input type="text" id="tableNo1" name="table_no" required>
                                </div>
                            </div>
                        
                        <h3>Intensity Scale</h3>
                        <div class="quality-scale">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="quality-scale-header">SLOW</th>
                                        <th class="quality-scale-header">MEDIUM TO LOW</th>
                                        <th class="quality-scale-header">MEDIUM</th>
                                        <th class="quality-scale-header">MEDIUM TO HIGH</th>
                                        <th class="quality-scale-header">HIGH</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                        <td>5</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Vertical Form Layout -->
                        <div class="vertical-form">
                            <!-- Sample Section -->
                            <div class="form-section">
                                <h4>SAMPLE</h4>
                                <div class="sample-input">
                                    <input type="text" id="sampleId1" name="sample_id" placeholder="Enter sample identification">
                                </div>
                            </div>
                            
                            <!-- Fragrance/Aroma Section -->
                            <div class="form-section">
                                <h4>FRAGRANCE / AROMA</h4>
                                <div class="intensity-scale">
                                    <div class="scale-labels">
                                        <span>1</span>
                                        <span>2</span>
                                        <span>3</span>
                                        <span>4</span>
                                        <span>5</span>
                                    </div>
                                    <input type="range" id="fragranceIntensity1" name="fragrance_intensity" min="1" max="5" step="1" value="3" orient="horizontal">
                                </div>
                                <div class="attributes-grid">
                                    <div class="attribute-group">
                                        <div class="attribute-item green">
                                            <input type="checkbox" id="fragrance_green1" name="fragrance_attributes[]" value="green">
                                            <label for="fragrance_green1">GREEN</label>
                                        </div>
                                        <div class="attribute-item white">
                                            <input type="checkbox" id="fragrance_grain1" name="fragrance_attributes[]" value="grain">
                                            <label for="fragrance_grain1">GRAIN</label>
                                        </div>
                                        <div class="attribute-item pink">
                                            <input type="checkbox" id="fragrance_floral1" name="fragrance_attributes[]" value="floral">
                                            <label for="fragrance_floral1">FLORAL</label>
                                        </div>
                                        <div class="attribute-item purple">
                                            <input type="checkbox" id="fragrance_fruity1" name="fragrance_attributes[]" value="fruity">
                                            <label for="fragrance_fruity1">FRUITY</label>
                                        </div>
                                    </div>
                                    <div class="attribute-group">
                                        <div class="attribute-item orange">
                                            <input type="checkbox" id="fragrance_sweet1" name="fragrance_attributes[]" value="sweet">
                                            <label for="fragrance_sweet1">SWEET</label>
                                        </div>
                                        <div class="attribute-item brown">
                                            <input type="checkbox" id="fragrance_nutty1" name="fragrance_attributes[]" value="nutty">
                                            <label for="fragrance_nutty1">NUTTY/COCOA</label>
                                        </div>
                                        <div class="attribute-item dark-red">
                                            <input type="checkbox" id="fragrance_spices1" name="fragrance_attributes[]" value="spices">
                                            <label for="fragrance_spices1">SPICES</label>
                                        </div>
                                        <div class="attribute-item dark-purple">
                                            <input type="checkbox" id="fragrance_roasted1" name="fragrance_attributes[]" value="roasted">
                                            <label for="fragrance_roasted1">ROASTED</label>
                                        </div>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="fragrance_others1" name="fragrance_attributes[]" value="others">
                                        <label for="fragrance_others1">OTHERS</label>
                                    </div>
                                    <div class="others-input" id="fragrance_others_input1" style="display: none;">
                                        <input type="text" name="fragrance_others_text" placeholder="Specify other fragrance/aroma attributes..." class="others-text-input">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Flavor Section -->
                            <div class="form-section">
                                <h4>FLAVOR</h4>
                                <div class="intensity-scale">
                                    <div class="scale-labels">
                                        <span>1</span>
                                        <span>2</span>
                                        <span>3</span>
                                        <span>4</span>
                                        <span>5</span>
                                    </div>
                                    <input type="range" id="flavorIntensity1" name="flavor_intensity" min="1" max="5" step="1" value="3" orient="horizontal">
                                </div>
                                <div class="flavor-categories">
                                    <!-- SPICES Category -->
                                    <div class="flavor-category">
                                        <div class="category-header spices">SPICES</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_spices1" name="flavor_attributes[]" value="spices">
                                                <label for="flavor_spices1">SPICES</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_brown_spices1" name="flavor_attributes[]" value="brown_spices">
                                                <label for="flavor_brown_spices1">BROWN SPICES</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- NUTTY/COCOA Category -->
                                    <div class="flavor-category">
                                        <div class="category-header nutty">NUTTY/COCOA</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_nutty1" name="flavor_attributes[]" value="nutty">
                                                <label for="flavor_nutty1">NUTTY</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_chocolate1" name="flavor_attributes[]" value="chocolate">
                                                <label for="flavor_chocolate1">CHOCOLATE</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- SWEET Category -->
                                    <div class="flavor-category">
                                        <div class="category-header sweet">SWEET</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_sweet1" name="flavor_attributes[]" value="sweet">
                                                <label for="flavor_sweet1">SWEET</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_brown_sugar1" name="flavor_attributes[]" value="brown_sugar">
                                                <label for="flavor_brown_sugar1">BROWN SUGAR</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_caramel1" name="flavor_attributes[]" value="caramel">
                                                <label for="flavor_caramel1">CARAMEL</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_vanilla1" name="flavor_attributes[]" value="vanilla">
                                                <label for="flavor_vanilla1">VANILLA</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- FLORAL Category -->
                                    <div class="flavor-category">
                                        <div class="category-header floral">FLORAL</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_floral1" name="flavor_attributes[]" value="floral">
                                                <label for="flavor_floral1">FLORAL</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- FRUITY Category -->
                                    <div class="flavor-category">
                                        <div class="category-header fruity">FRUITY</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_fruity1" name="flavor_attributes[]" value="fruity">
                                                <label for="flavor_fruity1">FRUITY</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_dried_fruit1" name="flavor_attributes[]" value="dried_fruit">
                                                <label for="flavor_dried_fruit1">DRIED FRUIT</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_tropical1" name="flavor_attributes[]" value="tropical">
                                                <label for="flavor_tropical1">TROPICAL</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_berry1" name="flavor_attributes[]" value="berry">
                                                <label for="flavor_berry1">BERRY</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_drupe1" name="flavor_attributes[]" value="drupe">
                                                <label for="flavor_drupe1">DRUPE</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_pomme1" name="flavor_attributes[]" value="pomme">
                                                <label for="flavor_pomme1">POMME</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- SOUR Category -->
                                    <div class="flavor-category">
                                        <div class="category-header sour">SOUR</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_winey1" name="flavor_attributes[]" value="winey">
                                                <label for="flavor_winey1">WINEY</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_sour1" name="flavor_attributes[]" value="sour">
                                                <label for="flavor_sour1">SOUR</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- OTHERS option -->
                                <div class="attribute-item white" style="margin-top: 20px;">
                                    <input type="checkbox" id="flavor_others1" name="flavor_attributes[]" value="others">
                                    <label for="flavor_others1">OTHERS</label>
                                </div>
                                <div class="others-input" id="flavor_others_input1" style="display: none;">
                                    <input type="text" name="flavor_others_text" placeholder="Specify other flavor attributes..." class="others-text-input">
                                </div>
                            </div>

                            <!-- Mouthfeel/Body Section -->
                            <div class="form-section">
                                <h4>MOUTHFEEL / BODY</h4>
                                <div class="intensity-scale">
                                    <div class="scale-labels">
                                        <span>1</span>
                                        <span>2</span>
                                        <span>3</span>
                                        <span>4</span>
                                        <span>5</span>
                                    </div>
                                    <input type="range" id="bodyIntensity1" name="body_intensity" min="1" max="5" step="1" value="3" orient="horizontal">
                                </div>
                                <div class="attributes-grid">
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="body_rough1" name="body_type[]" value="rough">
                                        <label for="body_rough1">Rough</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="body_smooth1" name="body_type[]" value="smooth">
                                        <label for="body_smooth1">Smooth</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="body_others1" name="body_type[]" value="others">
                                        <label for="body_others1">OTHERS</label>
                                    </div>
                                    <div class="others-input" id="body_others_input1" style="display: none;">
                                        <input type="text" name="body_others_text" placeholder="Specify other body type attributes..." class="others-text-input">
                                    </div>
                                </div>
                            </div>

                            <!-- Acidity Section -->
                            <div class="form-section">
                                <h4>ACIDITY</h4>
                                <div class="intensity-scale">
                                    <div class="scale-labels">
                                        <span>1</span>
                                        <span>2</span>
                                        <span>3</span>
                                        <span>4</span>
                                        <span>5</span>
                                    </div>
                                    <input type="range" id="acidityIntensity1" name="acidity_intensity" min="1" max="5" step="1" value="3" orient="horizontal">
                                </div>
                                <div class="attributes-grid">
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="acidity_ripe_fruit1" name="acidity_type[]" value="ripe_fruit">
                                        <label for="acidity_ripe_fruit1">Ripe Fruit</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="acidity_winey1" name="acidity_type[]" value="winey">
                                        <label for="acidity_winey1">Winey</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="acidity_vinegar1" name="acidity_type[]" value="vinegar">
                                        <label for="acidity_vinegar1">Vinegar</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="acidity_others1" name="acidity_type[]" value="others">
                                        <label for="acidity_others1">OTHERS</label>
                                    </div>
                                    <div class="others-input" id="acidity_others_input1" style="display: none;">
                                        <input type="text" name="acidity_others_text" placeholder="Specify other acidity type attributes..." class="others-text-input">
                                    </div>
                                </div>
                            </div>

                            <!-- Sweetness Section -->
                            <div class="form-section">
                                <h4>SWEETNESS</h4>
                                <div class="intensity-scale">
                                    <div class="scale-labels">
                                        <span>1</span>
                                        <span>2</span>
                                        <span>3</span>
                                        <span>4</span>
                                        <span>5</span>
                                    </div>
                                    <input type="range" id="sweetnessIntensity1" name="sweetness_intensity" min="1" max="5" step="1" value="3" orient="horizontal">
                                </div>
                                <div class="attributes-grid">
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="sweetness_ripe_fruit1" name="sweetness_type[]" value="ripe_fruit">
                                        <label for="sweetness_ripe_fruit1">Ripe Fruit</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="sweetness_nutty1" name="sweetness_type[]" value="nutty">
                                        <label for="sweetness_nutty1">Nutty/Cocoa</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="sweetness_sweet1" name="sweetness_type[]" value="sweet">
                                        <label for="sweetness_sweet1">Sweet</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="sweetness_others1" name="sweetness_type[]" value="others">
                                        <label for="sweetness_others1">OTHERS</label>
                                    </div>
                                    <div class="others-input" id="sweetness_others_input1" style="display: none;">
                                        <input type="text" name="sweetness_others_text" placeholder="Specify other sweetness type attributes..." class="others-text-input">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Notes Section -->
                            <div class="form-section">
                                <h4>NOTES:</h4>
                                <textarea id="generalNotes1" name="general_notes" placeholder="Enter any additional notes here..." rows="4"></textarea>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <button type="submit" id="submitBtn1" class="btn btn-primary mt-4">
                            <i class="fas fa-save me-2"></i> Save Form 1
                        </button>
                        
                        
                        
                        <!-- Success Message -->
                        <div id="successMessage1" class="hidden">
                            <i class="fas fa-check-circle me-2"></i> Your cupping form has been submitted successfully!
                        </div>
                    </form>
                </div>

                <!-- Form 2 -->
                <div class="form-page" id="form2">
                    <!-- Validation Warning Banner -->
                    <div id="validationWarning2" class="validation-warning-banner" style="display: none;">
                        <div class="warning-content">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <span class="warning-text">This form requires completion of all sections before submission.</span>
                        </div>
                    </div>
                    
                    <form id="cuppingForm2" method="POST" action="process_cupping.php" enctype="multipart/form-data">
                        <input type="hidden" name="_subject" value="New Coffee Cupping Form Submission">
                        
                        <input type="hidden" name="form_number" value="2">
                        
                        <div class="header-info">
                            <div>
                                <label for="name2">Name:</label>
                                <input type="text" id="name2" name="name" value="<?php echo htmlspecialchars($full_name); ?>" required readonly>
                            </div>
                            <div>
                                <label for="date2">Date:</label>
                                <input type="date" id="date2" name="date" required>
                            </div>
                            <div>
                                <label for="tableNo2">Table no:</label>
                                <input type="text" id="tableNo2" name="table_no" required>
                            </div>
                        </div>
                        
                        <!-- Copy the same form structure for form 2 -->
                        <h3>Intensity Scale</h3>
                        <div class="quality-scale">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="quality-scale-header">SLOW</th>
                                        <th class="quality-scale-header">MEDIUM TO LOW</th>
                                        <th class="quality-scale-header">MEDIUM</th>
                                        <th class="quality-scale-header">MEDIUM TO HIGH</th>
                                        <th class="quality-scale-header">HIGH</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                        <td>5</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="vertical-form">
                            <div class="form-section">
                                <h4>SAMPLE</h4>
                                <div class="sample-input">
                                    <input type="text" id="sampleId2" name="sample_id" placeholder="Enter sample identification">
                                </div>
                            </div>
                            
                            <!-- Add similar sections for form 2 with updated IDs -->
                            <div class="form-section">
                                <h4>FRAGRANCE / AROMA</h4>
                                <div class="intensity-scale">
                                    <div class="scale-labels">
                                        <span>1</span>
                                        <span>2</span>
                                        <span>3</span>
                                        <span>4</span>
                                        <span>5</span>
                                    </div>
                                    <input type="range" id="fragranceIntensity2" name="fragrance_intensity" min="1" max="5" step="1" value="3" orient="horizontal">
                                </div>
                                <div class="attributes-grid">
                                    <div class="attribute-group">
                                        <div class="attribute-item green">
                                            <input type="checkbox" id="fragrance_green2" name="fragrance_attributes[]" value="green">
                                            <label for="fragrance_green2">GREEN</label>
                                        </div>
                                        <div class="attribute-item white">
                                            <input type="checkbox" id="fragrance_grain2" name="fragrance_attributes[]" value="grain">
                                            <label for="fragrance_grain2">GRAIN</label>
                                        </div>
                                        <div class="attribute-item pink">
                                            <input type="checkbox" id="fragrance_floral2" name="fragrance_attributes[]" value="floral">
                                            <label for="fragrance_floral2">FLORAL</label>
                                        </div>
                                        <div class="attribute-item purple">
                                            <input type="checkbox" id="fragrance_fruity2" name="fragrance_attributes[]" value="fruity">
                                            <label for="fragrance_fruity2">FRUITY</label>
                                        </div>
                                    </div>
                                    <div class="attribute-group">
                                        <div class="attribute-item orange">
                                            <input type="checkbox" id="fragrance_sweet2" name="fragrance_attributes[]" value="sweet">
                                            <label for="fragrance_sweet2">SWEET</label>
                                        </div>
                                        <div class="attribute-item brown">
                                            <input type="checkbox" id="fragrance_nutty2" name="fragrance_attributes[]" value="nutty">
                                            <label for="fragrance_nutty2">NUTTY/COCOA</label>
                                        </div>
                                        <div class="attribute-item dark-red">
                                            <input type="checkbox" id="fragrance_spices2" name="fragrance_attributes[]" value="spices">
                                            <label for="fragrance_spices2">SPICES</label>
                                        </div>
                                        <div class="attribute-item dark-purple">
                                            <input type="checkbox" id="fragrance_roasted2" name="fragrance_attributes[]" value="roasted">
                                            <label for="fragrance_roasted2">ROASTED</label>
                                        </div>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="fragrance_others2" name="fragrance_attributes[]" value="others">
                                        <label for="fragrance_others2">OTHERS</label>
                                    </div>
                                    <div class="others-input" id="fragrance_others_input2" style="display: none;">
                                        <input type="text" name="fragrance_others_text" placeholder="Specify other fragrance/aroma attributes..." class="others-text-input">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Flavor Section -->
                            <div class="form-section">
                                <h4>FLAVOR</h4>
                                <div class="intensity-scale">
                                    <div class="scale-labels">
                                        <span>1</span>
                                        <span>2</span>
                                        <span>3</span>
                                        <span>4</span>
                                        <span>5</span>
                                    </div>
                                    <input type="range" id="flavorIntensity2" name="flavor_intensity" min="1" max="5" step="1" value="3" orient="horizontal">
                                </div>
                                <div class="flavor-categories">
                                    <!-- SPICES Category -->
                                    <div class="flavor-category">
                                        <div class="category-header spices">SPICES</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_spices2" name="flavor_attributes[]" value="spices">
                                                <label for="flavor_spices2">SPICES</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_brown_spices2" name="flavor_attributes[]" value="brown_spices">
                                                <label for="flavor_brown_spices2">BROWN SPICES</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- NUTTY/COCOA Category -->
                                    <div class="flavor-category">
                                        <div class="category-header nutty">NUTTY/COCOA</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_nutty2" name="flavor_attributes[]" value="nutty">
                                                <label for="flavor_nutty2">NUTTY</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_chocolate2" name="flavor_attributes[]" value="chocolate">
                                                <label for="flavor_chocolate2">CHOCOLATE</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- SWEET Category -->
                                    <div class="flavor-category">
                                        <div class="category-header sweet">SWEET</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_sweet2" name="flavor_attributes[]" value="sweet">
                                                <label for="flavor_sweet2">SWEET</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_brown_sugar2" name="flavor_attributes[]" value="brown_sugar">
                                                <label for="flavor_brown_sugar2">BROWN SUGAR</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_caramel2" name="flavor_attributes[]" value="caramel">
                                                <label for="flavor_caramel2">CARAMEL</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_vanilla2" name="flavor_attributes[]" value="vanilla">
                                                <label for="flavor_vanilla2">VANILLA</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- FLORAL Category -->
                                    <div class="flavor-category">
                                        <div class="category-header floral">FLORAL</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_floral2" name="flavor_attributes[]" value="floral">
                                                <label for="flavor_floral2">FLORAL</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- FRUITY Category -->
                                    <div class="flavor-category">
                                        <div class="category-header fruity">FRUITY</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_fruity2" name="flavor_attributes[]" value="fruity">
                                                <label for="flavor_fruity2">FRUITY</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_dried_fruit2" name="flavor_attributes[]" value="dried_fruit">
                                                <label for="flavor_dried_fruit2">DRIED FRUIT</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_tropical2" name="flavor_attributes[]" value="tropical">
                                                <label for="flavor_tropical2">TROPICAL</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_berry2" name="flavor_attributes[]" value="berry">
                                                <label for="flavor_berry2">BERRY</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_drupe2" name="flavor_attributes[]" value="drupe">
                                                <label for="flavor_drupe2">DRUPE</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_pomme2" name="flavor_attributes[]" value="pomme">
                                                <label for="flavor_pomme2">POMME</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- SOUR Category -->
                                    <div class="flavor-category">
                                        <div class="category-header sour">SOUR</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_winey2" name="flavor_attributes[]" value="winey">
                                                <label for="flavor_winey2">WINEY</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_sour2" name="flavor_attributes[]" value="sour">
                                                <label for="flavor_sour2">SOUR</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- OTHERS option -->
                                <div class="attribute-item white" style="margin-top: 20px;">
                                    <input type="checkbox" id="flavor_others2" name="flavor_attributes[]" value="others">
                                    <label for="flavor_others2">OTHERS</label>
                                </div>
                                <div class="others-input" id="flavor_others_input2" style="display: none;">
                                    <input type="text" name="flavor_others_text" placeholder="Specify other flavor attributes..." class="others-text-input">
                                </div>
                            </div>

                            <!-- Mouthfeel/Body Section -->
                            <div class="form-section">
                                <h4>MOUTHFEEL / BODY</h4>
                                <div class="intensity-scale">
                                    <div class="scale-labels">
                                        <span>5</span>
                                        <span>4</span>
                                        <span>3</span>
                                        <span>2</span>
                                        <span>1</span>
                                    </div>
                                    <input type="range" id="bodyIntensity2" name="body_intensity" min="1" max="5" step="1" value="3" orient="horizontal">
                                </div>
                                <div class="attributes-grid">
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="body_rough2" name="body_type[]" value="rough">
                                        <label for="body_rough2">Rough</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="body_smooth2" name="body_type[]" value="smooth">
                                        <label for="body_smooth2">Smooth</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="body_others2" name="body_type[]" value="others">
                                        <label for="body_others2">OTHERS</label>
                                    </div>
                                    <div class="others-input" id="body_others_input2" style="display: none;">
                                        <input type="text" name="body_others_text" placeholder="Specify other body type attributes..." class="others-text-input">
                                    </div>
                                </div>
                            </div>

                            <!-- Acidity Section -->
                            <div class="form-section">
                                <h4>ACIDITY</h4>
                                <div class="intensity-scale">
                                    <div class="scale-labels">
                                        <span>1</span>
                                        <span>2</span>
                                        <span>3</span>
                                        <span>4</span>
                                        <span>5</span>
                                    </div>
                                    <input type="range" id="acidityIntensity2" name="acidity_intensity" min="1" max="5" step="1" value="3" orient="horizontal">
                                </div>
                                <div class="attributes-grid">
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="acidity_ripe_fruit2" name="acidity_type[]" value="ripe_fruit">
                                        <label for="acidity_ripe_fruit2">Ripe Fruit</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="acidity_winey2" name="acidity_type[]" value="winey">
                                        <label for="acidity_winey2">Winey</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="acidity_vinegar2" name="acidity_type[]" value="vinegar">
                                        <label for="acidity_vinegar2">Vinegar</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="acidity_others2" name="acidity_type[]" value="others">
                                        <label for="acidity_others2">OTHERS</label>
                                    </div>
                                    <div class="others-input" id="acidity_others_input2" style="display: none;">
                                        <input type="text" name="acidity_others_text" placeholder="Specify other acidity type attributes..." class="others-text-input">
                                    </div>
                                </div>
                            </div>

                            <!-- Sweetness Section -->
                            <div class="form-section">
                                <h4>SWEETNESS</h4>
                                <div class="intensity-scale">
                                    <div class="scale-labels">
                                        <span>5</span>
                                        <span>4</span>
                                        <span>3</span>
                                        <span>2</span>
                                        <span>1</span>
                                    </div>
                                    <input type="range" id="sweetnessIntensity2" name="sweetness_intensity" min="1" max="5" step="1" value="3" orient="horizontal">
                                </div>
                                <div class="attributes-grid">
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="sweetness_ripe_fruit2" name="sweetness_type[]" value="ripe_fruit">
                                        <label for="sweetness_ripe_fruit2">Ripe Fruit</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="sweetness_nutty2" name="sweetness_type[]" value="nutty">
                                        <label for="sweetness_nutty2">Nutty/Cocoa</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="sweetness_sweet2" name="sweetness_type[]" value="sweet">
                                        <label for="sweetness_sweet2">Sweet</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="sweetness_others2" name="sweetness_type[]" value="others">
                                        <label for="sweetness_others2">OTHERS</label>
                                    </div>
                                    <div class="others-input" id="sweetness_others_input2" style="display: none;">
                                        <input type="text" name="sweetness_others_text" placeholder="Specify other sweetness type attributes..." class="others-text-input">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Notes Section -->
                            <div class="form-section">
                                <h4>NOTES:</h4>
                                <textarea id="generalNotes2" name="general_notes" placeholder="Enter any additional notes here..." rows="4"></textarea>
                            </div>
                        </div>
                        
                        <button type="submit" id="submitBtn2" class="btn btn-primary mt-4">
                            <i class="fas fa-save me-2"></i> Save Form 2
                        </button>
                        
                        
                        
                        <div id="successMessage2" class="hidden">
                            <i class="fas fa-check-circle me-2"></i> Your cupping form has been submitted successfully!
                        </div>
                    </form>
                </div>

                <!-- Form 3 -->
                <div class="form-page" id="form3">
                    <!-- Validation Warning Banner -->
                    <div id="validationWarning3" class="validation-warning-banner" style="display: none;">
                        <div class="warning-content">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <span class="warning-text">This form requires completion of all sections before submission.</span>
                        </div>
                    </div>
                    
                    <form id="cuppingForm3" method="POST" action="process_cupping.php" enctype="multipart/form-data">
                        <input type="hidden" name="_subject" value="New Coffee Cupping Form Submission">
                        
                        <input type="hidden" name="form_number" value="3">
                        
                        <div class="header-info">
                            <div>
                                <label for="name3">Name:</label>
                                <input type="text" id="name3" name="name" value="<?php echo htmlspecialchars($full_name); ?>" required readonly>
                            </div>
                            <div>
                                <label for="date3">Date:</label>
                                <input type="date" id="date3" name="date" required>
                            </div>
                            <div>
                                <label for="tableNo3">Table no:</label>
                                <input type="text" id="tableNo3" name="table_no" required>
                            </div>
                        </div>
                        
                        <h3>Intensity Scale</h3>
                        <div class="quality-scale">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="quality-scale-header">SLOW</th>
                                        <th class="quality-scale-header">MEDIUM TO LOW</th>
                                        <th class="quality-scale-header">MEDIUM</th>
                                        <th class="quality-scale-header">MEDIUM TO HIGH</th>
                                        <th class="quality-scale-header">HIGH</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                        <td>5</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Vertical Form Layout -->
                        <div class="vertical-form">
                            <!-- Sample Section -->
                            <div class="form-section">
                                <h4>SAMPLE</h4>
                                <div class="sample-input">
                                    <input type="text" id="sampleId3" name="sample_id" placeholder="Enter sample identification">
                                </div>
                            </div>
                            
                            <!-- Fragrance/Aroma Section -->
                            <div class="form-section">
                                <h4>FRAGRANCE / AROMA</h4>
                                <div class="intensity-scale">
                                    <div class="scale-labels">
                                        <span>5</span>
                                        <span>4</span>
                                        <span>3</span>
                                        <span>2</span>
                                        <span>1</span>
                                    </div>
                                    <input type="range" id="fragranceIntensity3" name="fragrance_intensity" min="1" max="5" step="1" value="3" orient="horizontal">
                                </div>
                                <div class="attributes-grid">
                                    <div class="attribute-group">
                                        <div class="attribute-item green">
                                            <input type="checkbox" id="fragrance_green3" name="fragrance_attributes[]" value="green">
                                            <label for="fragrance_green3">GREEN</label>
                                        </div>
                                        <div class="attribute-item white">
                                            <input type="checkbox" id="fragrance_grain3" name="fragrance_attributes[]" value="grain">
                                            <label for="fragrance_grain3">GRAIN</label>
                                        </div>
                                        <div class="attribute-item pink">
                                            <input type="checkbox" id="fragrance_floral3" name="fragrance_attributes[]" value="floral">
                                            <label for="fragrance_floral3">FLORAL</label>
                                        </div>
                                        <div class="attribute-item purple">
                                            <input type="checkbox" id="fragrance_fruity3" name="fragrance_attributes[]" value="fruity">
                                            <label for="fragrance_fruity3">FRUITY</label>
                                        </div>
                                    </div>
                                    <div class="attribute-group">
                                        <div class="attribute-item orange">
                                            <input type="checkbox" id="fragrance_sweet3" name="fragrance_attributes[]" value="sweet">
                                            <label for="fragrance_sweet3">SWEET</label>
                                        </div>
                                        <div class="attribute-item brown">
                                            <input type="checkbox" id="fragrance_nutty3" name="fragrance_attributes[]" value="nutty">
                                            <label for="fragrance_nutty3">NUTTY/COCOA</label>
                                        </div>
                                        <div class="attribute-item dark-red">
                                            <input type="checkbox" id="fragrance_spices3" name="fragrance_attributes[]" value="spices">
                                            <label for="fragrance_spices3">SPICES</label>
                                        </div>
                                        <div class="attribute-item dark-purple">
                                            <input type="checkbox" id="fragrance_roasted3" name="fragrance_attributes[]" value="roasted">
                                            <label for="fragrance_roasted3">ROASTED</label>
                                        </div>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="fragrance_others3" name="fragrance_attributes[]" value="others">
                                        <label for="fragrance_others3">OTHERS</label>
                                    </div>
                                    <div class="others-input" id="fragrance_others_input3" style="display: none;">
                                        <input type="text" name="fragrance_others_text" placeholder="Specify other fragrance/aroma attributes..." class="others-text-input">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Flavor Section -->
                            <div class="form-section">
                                <h4>FLAVOR</h4>
                                <div class="intensity-scale">
                                    <div class="scale-labels">
                                        <span>5</span>
                                        <span>4</span>
                                        <span>3</span>
                                        <span>2</span>
                                        <span>1</span>
                                    </div>
                                    <input type="range" id="flavorIntensity3" name="flavor_intensity" min="1" max="5" step="1" value="3" orient="horizontal">
                                </div>
                                <div class="flavor-categories">
                                    <!-- SPICES Category -->
                                    <div class="flavor-category">
                                        <div class="category-header spices">SPICES</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_spices3" name="flavor_attributes[]" value="spices">
                                                <label for="flavor_spices3">SPICES</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_brown_spices3" name="flavor_attributes[]" value="brown_spices">
                                                <label for="flavor_brown_spices3">BROWN SPICES</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- NUTTY/COCOA Category -->
                                    <div class="flavor-category">
                                        <div class="category-header nutty">NUTTY/COCOA</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_nutty3" name="flavor_attributes[]" value="nutty">
                                                <label for="flavor_nutty3">NUTTY</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_chocolate3" name="flavor_attributes[]" value="chocolate">
                                                <label for="flavor_chocolate3">CHOCOLATE</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- SWEET Category -->
                                    <div class="flavor-category">
                                        <div class="category-header sweet">SWEET</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_sweet3" name="flavor_attributes[]" value="sweet">
                                                <label for="flavor_sweet3">SWEET</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_brown_sugar3" name="flavor_attributes[]" value="brown_sugar">
                                                <label for="flavor_brown_sugar3">BROWN SUGAR</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_caramel3" name="flavor_attributes[]" value="caramel">
                                                <label for="flavor_caramel3">CARAMEL</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_vanilla3" name="flavor_attributes[]" value="vanilla">
                                                <label for="flavor_vanilla3">VANILLA</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- FLORAL Category -->
                                    <div class="flavor-category">
                                        <div class="category-header floral">FLORAL</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_floral3" name="flavor_attributes[]" value="floral">
                                                <label for="flavor_floral3">FLORAL</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- FRUITY Category -->
                                    <div class="flavor-category">
                                        <div class="category-header fruity">FRUITY</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_fruity3" name="flavor_attributes[]" value="fruity">
                                                <label for="flavor_fruity3">FRUITY</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_dried_fruit3" name="flavor_attributes[]" value="dried_fruit">
                                                <label for="flavor_dried_fruit3">DRIED FRUIT</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_tropical3" name="flavor_attributes[]" value="tropical">
                                                <label for="flavor_tropical3">TROPICAL</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_berry3" name="flavor_attributes[]" value="berry">
                                                <label for="flavor_berry3">BERRY</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_drupe3" name="flavor_attributes[]" value="drupe">
                                                <label for="flavor_drupe3">DRUPE</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_pomme3" name="flavor_attributes[]" value="pomme">
                                                <label for="flavor_pomme3">POMME</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- SOUR Category -->
                                    <div class="flavor-category">
                                        <div class="category-header sour">SOUR</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_winey3" name="flavor_attributes[]" value="winey">
                                                <label for="flavor_winey3">WINEY</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_sour3" name="flavor_attributes[]" value="sour">
                                                <label for="flavor_sour3">SOUR</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- OTHERS option -->
                                <div class="attribute-item white" style="margin-top: 20px;">
                                    <input type="checkbox" id="flavor_others3" name="flavor_attributes[]" value="others">
                                    <label for="flavor_others3">OTHERS</label>
                                </div>
                                <div class="others-input" id="flavor_others_input3" style="display: none;">
                                    <input type="text" name="flavor_others_text" placeholder="Specify other flavor attributes..." class="others-text-input">
                                </div>
                            </div>

                            <!-- Mouthfeel/Body Section -->
                            <div class="form-section">
                                <h4>MOUTHFEEL / BODY</h4>
                                <div class="intensity-scale">
                                    <div class="scale-labels">
                                        <span>5</span>
                                        <span>4</span>
                                        <span>3</span>
                                        <span>2</span>
                                        <span>1</span>
                                    </div>
                                    <input type="range" id="bodyIntensity3" name="body_intensity" min="1" max="5" step="1" value="3" orient="horizontal">
                                </div>
                                <div class="attributes-grid">
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="body_rough3" name="body_type[]" value="rough">
                                        <label for="body_rough3">Rough</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="body_smooth3" name="body_type[]" value="smooth">
                                        <label for="body_smooth3">Smooth</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="body_others3" name="body_type[]" value="others">
                                        <label for="body_others3">OTHERS</label>
                                    </div>
                                    <div class="others-input" id="body_others_input3" style="display: none;">
                                        <input type="text" name="body_others_text" placeholder="Specify other body type attributes..." class="others-text-input">
                                    </div>
                                </div>
                            </div>

                            <!-- Acidity Section -->
                            <div class="form-section">
                                <h4>ACIDITY</h4>
                                <div class="intensity-scale">
                                    <div class="scale-labels">
                                        <span>1</span>
                                        <span>2</span>
                                        <span>3</span>
                                        <span>4</span>
                                        <span>5</span>
                                    </div>
                                    <input type="range" id="acidityIntensity3" name="acidity_intensity" min="1" max="5" step="1" value="3" orient="horizontal">
                                </div>
                                <div class="attributes-grid">
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="acidity_ripe_fruit3" name="acidity_type[]" value="ripe_fruit">
                                        <label for="acidity_ripe_fruit3">Ripe Fruit</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="acidity_winey3" name="acidity_type[]" value="winey">
                                        <label for="acidity_winey3">Winey</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="acidity_vinegar3" name="acidity_type[]" value="vinegar">
                                        <label for="acidity_vinegar3">Vinegar</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="acidity_others3" name="acidity_type[]" value="others">
                                        <label for="acidity_others3">OTHERS</label>
                                    </div>
                                    <div class="others-input" id="acidity_others_input3" style="display: none;">
                                        <input type="text" name="acidity_others_text" placeholder="Specify other acidity type attributes..." class="others-text-input">
                                    </div>
                                </div>
                            </div>

                            <!-- Sweetness Section -->
                            <div class="form-section">
                                <h4>SWEETNESS</h4>
                                <div class="intensity-scale">
                                    <div class="scale-labels">
                                        <span>5</span>
                                        <span>4</span>
                                        <span>3</span>
                                        <span>2</span>
                                        <span>1</span>
                                    </div>
                                    <input type="range" id="sweetnessIntensity3" name="sweetness_intensity" min="1" max="5" step="1" value="3" orient="horizontal">
                                </div>
                                <div class="attributes-grid">
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="sweetness_ripe_fruit3" name="sweetness_type[]" value="ripe_fruit">
                                        <label for="sweetness_ripe_fruit3">Ripe Fruit</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="sweetness_nutty3" name="sweetness_type[]" value="nutty">
                                        <label for="sweetness_nutty3">Nutty/Cocoa</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="sweetness_sweet3" name="sweetness_type[]" value="sweet">
                                        <label for="sweetness_sweet3">Sweet</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="sweetness_others3" name="sweetness_type[]" value="others">
                                        <label for="sweetness_others3">OTHERS</label>
                                    </div>
                                    <div class="others-input" id="sweetness_others_input3" style="display: none;">
                                        <input type="text" name="sweetness_others_text" placeholder="Specify other sweetness type attributes..." class="others-text-input">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Notes Section -->
                            <div class="form-section">
                                <h4>NOTES:</h4>
                                <textarea id="generalNotes3" name="general_notes" placeholder="Enter any additional notes here..." rows="4"></textarea>
                            </div>
                        </div>
                        
                        <button type="submit" id="submitBtn3" class="btn btn-primary mt-4">
                            <i class="fas fa-save me-2"></i> Save Form 3
                        </button>
                        
                        
                        
                        <div id="successMessage3" class="hidden">
                            <i class="fas fa-check-circle me-2"></i> Your cupping form has been submitted successfully!
                        </div>
                    </form>
                </div>

                <!-- Form 4 -->
                <div class="form-page" id="form4">
                    <!-- Validation Warning Banner -->
                    <div id="validationWarning4" class="validation-warning-banner" style="display: none;">
                        <div class="warning-content">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <span class="warning-text">This form requires completion of all sections before submission.</span>
                        </div>
                    </div>
                    
                    <form id="cuppingForm4" method="POST" action="process_cupping.php" enctype="multipart/form-data">
                        <input type="hidden" name="_subject" value="New Coffee Cupping Form Submission">
                        
                        <input type="hidden" name="form_number" value="4">
                        
                        <div class="header-info">
                            <div>
                                <label for="name4">Name:</label>
                                <input type="text" id="name4" name="name" value="<?php echo htmlspecialchars($full_name); ?>" required readonly>
                            </div>
                            <div>
                                <label for="date4">Date:</label>
                                <input type="date" id="date4" name="date" required>
                            </div>
                            <div>
                                <label for="tableNo4">Table no:</label>
                                <input type="text" id="tableNo4" name="table_no" required>
                            </div>
                        </div>
                        
                        <h3>Intensity Scale</h3>
                        <div class="quality-scale">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="quality-scale-header">SLOW</th>
                                        <th class="quality-scale-header">MEDIUM TO LOW</th>
                                        <th class="quality-scale-header">MEDIUM</th>
                                        <th class="quality-scale-header">MEDIUM TO HIGH</th>
                                        <th class="quality-scale-header">HIGH</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                        <td>5</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Vertical Form Layout -->
                        <div class="vertical-form">
                            <!-- Sample Section -->
                            <div class="form-section">
                                <h4>SAMPLE</h4>
                                <div class="sample-input">
                                    <input type="text" id="sampleId4" name="sample_id" placeholder="Enter sample identification">
                                </div>
                            </div>
                            
                            <!-- Fragrance/Aroma Section -->
                            <div class="form-section">
                                <h4>FRAGRANCE / AROMA</h4>
                                <div class="intensity-scale">
                                    <div class="scale-labels">
                                        <span>5</span>
                                        <span>4</span>
                                        <span>3</span>
                                        <span>2</span>
                                        <span>1</span>
                                    </div>
                                    <input type="range" id="fragranceIntensity4" name="fragrance_intensity" min="1" max="5" step="1" value="3" orient="horizontal">
                                </div>
                                <div class="attributes-grid">
                                    <div class="attribute-group">
                                        <div class="attribute-item green">
                                            <input type="checkbox" id="fragrance_green4" name="fragrance_attributes[]" value="green">
                                            <label for="fragrance_green4">GREEN</label>
                                        </div>
                                        <div class="attribute-item white">
                                            <input type="checkbox" id="fragrance_grain4" name="fragrance_attributes[]" value="grain">
                                            <label for="fragrance_grain4">GRAIN</label>
                                        </div>
                                        <div class="attribute-item pink">
                                            <input type="checkbox" id="fragrance_floral4" name="fragrance_attributes[]" value="floral">
                                            <label for="fragrance_floral4">FLORAL</label>
                                        </div>
                                        <div class="attribute-item purple">
                                            <input type="checkbox" id="fragrance_fruity4" name="fragrance_attributes[]" value="fruity">
                                            <label for="fragrance_fruity4">FRUITY</label>
                                        </div>
                                    </div>
                                    <div class="attribute-group">
                                        <div class="attribute-item orange">
                                            <input type="checkbox" id="fragrance_sweet4" name="fragrance_attributes[]" value="sweet">
                                            <label for="fragrance_sweet4">SWEET</label>
                                        </div>
                                        <div class="attribute-item brown">
                                            <input type="checkbox" id="fragrance_nutty4" name="fragrance_attributes[]" value="nutty">
                                            <label for="fragrance_nutty4">NUTTY/COCOA</label>
                                        </div>
                                        <div class="attribute-item dark-red">
                                            <input type="checkbox" id="fragrance_spices4" name="fragrance_attributes[]" value="spices">
                                            <label for="fragrance_spices4">SPICES</label>
                                        </div>
                                        <div class="attribute-item dark-purple">
                                            <input type="checkbox" id="fragrance_roasted4" name="fragrance_attributes[]" value="roasted">
                                            <label for="fragrance_roasted4">ROASTED</label>
                                        </div>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="fragrance_others4" name="fragrance_attributes[]" value="others">
                                        <label for="fragrance_others4">OTHERS</label>
                                    </div>
                                    <div class="others-input" id="fragrance_others_input4" style="display: none;">
                                        <input type="text" name="fragrance_others_text" placeholder="Specify other fragrance/aroma attributes..." class="others-text-input">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Flavor Section -->
                            <div class="form-section">
                                <h4>FLAVOR</h4>
                                <div class="intensity-scale">
                                    <div class="scale-labels">
                                        <span>5</span>
                                        <span>4</span>
                                        <span>3</span>
                                        <span>2</span>
                                        <span>1</span>
                                    </div>
                                    <input type="range" id="flavorIntensity4" name="flavor_intensity" min="1" max="5" step="1" value="3" orient="horizontal">
                                </div>
                                <div class="flavor-categories">
                                    <!-- SPICES Category -->
                                    <div class="flavor-category">
                                        <div class="category-header spices">SPICES</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_spices4" name="flavor_attributes[]" value="spices">
                                                <label for="flavor_spices4">SPICES</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_brown_spices4" name="flavor_attributes[]" value="brown_spices">
                                                <label for="flavor_brown_spices4">BROWN SPICES</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- NUTTY/COCOA Category -->
                                    <div class="flavor-category">
                                        <div class="category-header nutty">NUTTY/COCOA</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_nutty4" name="flavor_attributes[]" value="nutty">
                                                <label for="flavor_nutty4">NUTTY</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_chocolate4" name="flavor_attributes[]" value="chocolate">
                                                <label for="flavor_chocolate4">CHOCOLATE</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- SWEET Category -->
                                    <div class="flavor-category">
                                        <div class="category-header sweet">SWEET</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_sweet4" name="flavor_attributes[]" value="sweet">
                                                <label for="flavor_sweet4">SWEET</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_brown_sugar4" name="flavor_attributes[]" value="brown_sugar">
                                                <label for="flavor_brown_sugar4">BROWN SUGAR</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_caramel4" name="flavor_attributes[]" value="caramel">
                                                <label for="flavor_caramel4">CARAMEL</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_vanilla4" name="flavor_attributes[]" value="vanilla">
                                                <label for="flavor_vanilla4">VANILLA</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- FLORAL Category -->
                                    <div class="flavor-category">
                                        <div class="category-header floral">FLORAL</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_floral4" name="flavor_attributes[]" value="floral">
                                                <label for="flavor_floral4">FLORAL</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- FRUITY Category -->
                                    <div class="flavor-category">
                                        <div class="category-header fruity">FRUITY</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_fruity4" name="flavor_attributes[]" value="fruity">
                                                <label for="flavor_fruity4">FRUITY</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_dried_fruit4" name="flavor_attributes[]" value="dried_fruit">
                                                <label for="flavor_dried_fruit4">DRIED FRUIT</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_tropical4" name="flavor_attributes[]" value="tropical">
                                                <label for="flavor_tropical4">TROPICAL</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_berry4" name="flavor_attributes[]" value="berry">
                                                <label for="flavor_berry4">BERRY</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_drupe4" name="flavor_attributes[]" value="drupe">
                                                <label for="flavor_drupe4">DRUPE</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_pomme4" name="flavor_attributes[]" value="pomme">
                                                <label for="flavor_pomme4">POMME</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- SOUR Category -->
                                    <div class="flavor-category">
                                        <div class="category-header sour">SOUR</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_winey4" name="flavor_attributes[]" value="winey">
                                                <label for="flavor_winey4">WINEY</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_sour4" name="flavor_attributes[]" value="sour">
                                                <label for="flavor_sour4">SOUR</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- OTHERS option -->
                                <div class="attribute-item white" style="margin-top: 20px;">
                                    <input type="checkbox" id="flavor_others4" name="flavor_attributes[]" value="others">
                                    <label for="flavor_others4">OTHERS</label>
                                </div>
                                <div class="others-input" id="flavor_others_input4" style="display: none;">
                                    <input type="text" name="flavor_others_text" placeholder="Specify other flavor attributes..." class="others-text-input">
                                </div>
                            </div>

                            <!-- Mouthfeel/Body Section -->
                            <div class="form-section">
                                <h4>MOUTHFEEL / BODY</h4>
                                <div class="intensity-scale">
                                    <div class="scale-labels">
                                        <span>5</span>
                                        <span>4</span>
                                        <span>3</span>
                                        <span>2</span>
                                        <span>1</span>
                                    </div>
                                    <input type="range" id="bodyIntensity4" name="body_intensity" min="1" max="5" step="1" value="3" orient="horizontal">
                                </div>
                                <div class="attributes-grid">
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="body_rough4" name="body_type[]" value="rough">
                                        <label for="body_rough4">Rough</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="body_smooth4" name="body_type[]" value="smooth">
                                        <label for="body_smooth4">Smooth</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="body_others4" name="body_type[]" value="others">
                                        <label for="body_others4">OTHERS</label>
                                    </div>
                                    <div class="others-input" id="body_others_input4" style="display: none;">
                                        <input type="text" name="body_others_text" placeholder="Specify other body type attributes..." class="others-text-input">
                                    </div>
                                </div>
                            </div>

                            <!-- Acidity Section -->
                            <div class="form-section">
                                <h4>ACIDITY</h4>
                                <div class="intensity-scale">
                                    <div class="scale-labels">
                                        <span>1</span>
                                        <span>2</span>
                                        <span>3</span>
                                        <span>4</span>
                                        <span>5</span>
                                    </div>
                                    <input type="range" id="acidityIntensity4" name="acidity_intensity" min="1" max="5" step="1" value="3" orient="horizontal">
                                </div>
                                <div class="attributes-grid">
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="acidity_ripe_fruit4" name="acidity_type[]" value="ripe_fruit">
                                        <label for="acidity_ripe_fruit4">Ripe Fruit</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="acidity_winey4" name="acidity_type[]" value="winey">
                                        <label for="acidity_winey4">Winey</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="acidity_vinegar4" name="acidity_type[]" value="vinegar">
                                        <label for="acidity_vinegar4">Vinegar</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="acidity_others4" name="acidity_type[]" value="others">
                                        <label for="acidity_others4">OTHERS</label>
                                    </div>
                                    <div class="others-input" id="acidity_others_input4" style="display: none;">
                                        <input type="text" name="acidity_others_text" placeholder="Specify other acidity type attributes..." class="others-text-input">
                                    </div>
                                </div>
                            </div>

                            <!-- Sweetness Section -->
                            <div class="form-section">
                                <h4>SWEETNESS</h4>
                                <div class="intensity-scale">
                                    <div class="scale-labels">
                                        <span>5</span>
                                        <span>4</span>
                                        <span>3</span>
                                        <span>2</span>
                                        <span>1</span>
                                    </div>
                                    <input type="range" id="sweetnessIntensity4" name="sweetness_intensity" min="1" max="5" step="1" value="3" orient="horizontal">
                                </div>
                                <div class="attributes-grid">
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="sweetness_ripe_fruit4" name="sweetness_type[]" value="ripe_fruit">
                                        <label for="sweetness_ripe_fruit4">Ripe Fruit</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="sweetness_nutty4" name="sweetness_type[]" value="nutty">
                                        <label for="sweetness_nutty4">Nutty/Cocoa</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="sweetness_sweet4" name="sweetness_type[]" value="sweet">
                                        <label for="sweetness_sweet4">Sweet</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="sweetness_others4" name="sweetness_type[]" value="others">
                                        <label for="sweetness_others4">OTHERS</label>
                                    </div>
                                    <div class="others-input" id="sweetness_others_input4" style="display: none;">
                                        <input type="text" name="sweetness_others_text" placeholder="Specify other sweetness type attributes..." class="others-text-input">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Notes Section -->
                            <div class="form-section">
                                <h4>NOTES:</h4>
                                <textarea id="generalNotes4" name="general_notes" placeholder="Enter any additional notes here..." rows="4"></textarea>
                            </div>
                        </div>
                        
                        <button type="submit" id="submitBtn4" class="btn btn-primary mt-4">
                            <i class="fas fa-save me-2"></i> Save Form 4
                        </button>
                        

                        
                        <div id="successMessage4" class="hidden">
                            <i class="fas fa-check-circle me-2"></i> Your cupping form has been submitted successfully!
                        </div>
                    </form>
                </div>

                <!-- Form 5 -->
                <div class="form-page" id="form5">
                    <!-- Validation Warning Banner -->
                    <div id="validationWarning5" class="validation-warning-banner" style="display: none;">
                        <div class="warning-content">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <span class="warning-text">This form requires completion of all sections before submission.</span>
                        </div>
                    </div>
                    
                    <form id="cuppingForm5" method="POST" action="process_cupping.php" enctype="multipart/form-data">
                        <input type="hidden" name="_subject" value="New Coffee Cupping Form Submission">
                        
                        <input type="hidden" name="form_number" value="5">
                        
                        <div class="header-info">
                            <div>
                                <label for="name5">Name:</label>
                                <input type="text" id="name5" name="name" value="<?php echo htmlspecialchars($full_name); ?>" required readonly>
                            </div>
                            <div>
                                <label for="date5">Date:</label>
                                <input type="date" id="date5" name="date" required>
                            </div>
                            <div>
                                <label for="tableNo5">Table no:</label>
                                <input type="text" id="tableNo5" name="table_no" required>
                            </div>
                        </div>
                        
                        <h3>Intensity Scale</h3>
                        <div class="quality-scale">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="quality-scale-header">SLOW</th>
                                        <th class="quality-scale-header">MEDIUM TO LOW</th>
                                        <th class="quality-scale-header">MEDIUM</th>
                                        <th class="quality-scale-header">MEDIUM TO HIGH</th>
                                        <th class="quality-scale-header">HIGH</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                        <td>5</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Vertical Form Layout -->
                        <div class="vertical-form">
                            <!-- Sample Section -->
                            <div class="form-section">
                                <h4>SAMPLE</h4>
                                <div class="sample-input">
                                    <input type="text" id="sampleId5" name="sample_id" placeholder="Enter sample identification">
                                </div>
                            </div>
                            
                            <!-- Fragrance/Aroma Section -->
                            <div class="form-section">
                                <h4>FRAGRANCE / AROMA</h4>
                                <div class="intensity-scale">
                                    <div class="scale-labels">
                                        <span>5</span>
                                        <span>4</span>
                                        <span>3</span>
                                        <span>2</span>
                                        <span>1</span>
                                    </div>
                                    <input type="range" id="fragranceIntensity5" name="fragrance_intensity" min="1" max="5" step="1" value="3" orient="horizontal">
                                </div>
                                <div class="attributes-grid">
                                    <div class="attribute-group">
                                        <div class="attribute-item green">
                                            <input type="checkbox" id="fragrance_green5" name="fragrance_attributes[]" value="green">
                                            <label for="fragrance_green5">GREEN</label>
                                        </div>
                                        <div class="attribute-item white">
                                            <input type="checkbox" id="fragrance_grain5" name="fragrance_attributes[]" value="grain">
                                            <label for="fragrance_grain5">GRAIN</label>
                                        </div>
                                        <div class="attribute-item pink">
                                            <input type="checkbox" id="fragrance_floral5" name="fragrance_attributes[]" value="floral">
                                            <label for="fragrance_floral5">FLORAL</label>
                                        </div>
                                        <div class="attribute-item purple">
                                            <input type="checkbox" id="fragrance_fruity5" name="fragrance_attributes[]" value="fruity">
                                            <label for="fragrance_fruity5">FRUITY</label>
                                        </div>
                                    </div>
                                    <div class="attribute-group">
                                        <div class="attribute-item orange">
                                            <input type="checkbox" id="fragrance_sweet5" name="fragrance_attributes[]" value="sweet">
                                            <label for="fragrance_sweet5">SWEET</label>
                                        </div>
                                        <div class="attribute-item brown">
                                            <input type="checkbox" id="fragrance_nutty5" name="fragrance_attributes[]" value="nutty">
                                            <label for="fragrance_nutty5">NUTTY/COCOA</label>
                                        </div>
                                        <div class="attribute-item dark-red">
                                            <input type="checkbox" id="fragrance_spices5" name="fragrance_attributes[]" value="spices">
                                            <label for="fragrance_spices5">SPICES</label>
                                        </div>
                                        <div class="attribute-item dark-purple">
                                            <input type="checkbox" id="fragrance_roasted5" name="fragrance_attributes[]" value="roasted">
                                            <label for="fragrance_roasted5">ROASTED</label>
                                        </div>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="fragrance_others5" name="fragrance_attributes[]" value="others">
                                        <label for="fragrance_others5">OTHERS</label>
                                    </div>
                                    <div class="others-input" id="fragrance_others_input5" style="display: none;">
                                        <input type="text" name="fragrance_others_text" placeholder="Specify other fragrance/aroma attributes..." class="others-text-input">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Flavor Section -->
                            <div class="form-section">
                                <h4>FLAVOR</h4>
                                <div class="intensity-scale">
                                    <div class="scale-labels">
                                        <span>5</span>
                                        <span>4</span>
                                        <span>3</span>
                                        <span>2</span>
                                        <span>1</span>
                                    </div>
                                    <input type="range" id="flavorIntensity5" name="flavor_intensity" min="1" max="5" step="1" value="3" orient="horizontal">
                                </div>
                                <div class="flavor-categories">
                                    <!-- SPICES Category -->
                                    <div class="flavor-category">
                                        <div class="category-header spices">SPICES</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_spices5" name="flavor_attributes[]" value="spices">
                                                <label for="flavor_spices5">SPICES</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_brown_spices5" name="flavor_attributes[]" value="brown_spices">
                                                <label for="flavor_brown_spices5">BROWN SPICES</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- NUTTY/COCOA Category -->
                                    <div class="flavor-category">
                                        <div class="category-header nutty">NUTTY/COCOA</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_nutty5" name="flavor_attributes[]" value="nutty">
                                                <label for="flavor_nutty5">NUTTY</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_chocolate5" name="flavor_attributes[]" value="chocolate">
                                                <label for="flavor_chocolate5">CHOCOLATE</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- SWEET Category -->
                                    <div class="flavor-category">
                                        <div class="category-header sweet">SWEET</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_sweet5" name="flavor_attributes[]" value="sweet">
                                                <label for="flavor_sweet5">SWEET</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_brown_sugar5" name="flavor_attributes[]" value="brown_sugar">
                                                <label for="flavor_brown_sugar5">BROWN SUGAR</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_caramel5" name="flavor_attributes[]" value="caramel">
                                                <label for="flavor_caramel5">CARAMEL</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_vanilla5" name="flavor_attributes[]" value="vanilla">
                                                <label for="flavor_vanilla5">VANILLA</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- FLORAL Category -->
                                    <div class="flavor-category">
                                        <div class="category-header floral">FLORAL</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_floral5" name="flavor_attributes[]" value="floral">
                                                <label for="flavor_floral5">FLORAL</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- FRUITY Category -->
                                    <div class="flavor-category">
                                        <div class="category-header fruity">FRUITY</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_fruity5" name="flavor_attributes[]" value="fruity">
                                                <label for="flavor_fruity5">FRUITY</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_dried_fruit5" name="flavor_attributes[]" value="dried_fruit">
                                                <label for="flavor_dried_fruit5">DRIED FRUIT</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_tropical5" name="flavor_attributes[]" value="tropical">
                                                <label for="flavor_tropical5">TROPICAL</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_berry5" name="flavor_attributes[]" value="berry">
                                                <label for="flavor_berry5">BERRY</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_drupe5" name="flavor_attributes[]" value="drupe">
                                                <label for="flavor_drupe5">DRUPE</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_pomme5" name="flavor_attributes[]" value="pomme">
                                                <label for="flavor_pomme5">POMME</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- SOUR Category -->
                                    <div class="flavor-category">
                                        <div class="category-header sour">SOUR</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_winey5" name="flavor_attributes[]" value="winey">
                                                <label for="flavor_winey5">WINEY</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_sour5" name="flavor_attributes[]" value="sour">
                                                <label for="flavor_sour5">SOUR</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- OTHERS option -->
                                <div class="attribute-item white" style="margin-top: 20px;">
                                    <input type="checkbox" id="flavor_others5" name="flavor_attributes[]" value="others">
                                    <label for="flavor_others5">OTHERS</label>
                                </div>
                                <div class="others-input" id="flavor_others_input5" style="display: none;">
                                    <input type="text" name="flavor_others_text" placeholder="Specify other flavor attributes..." class="others-text-input">
                                </div>
                            </div>

                            <!-- Mouthfeel/Body Section -->
                            <div class="form-section">
                                <h4>MOUTHFEEL / BODY</h4>
                                <div class="intensity-scale">
                                    <div class="scale-labels">
                                        <span>5</span>
                                        <span>4</span>
                                        <span>3</span>
                                        <span>2</span>
                                        <span>1</span>
                                    </div>
                                    <input type="range" id="bodyIntensity5" name="body_intensity" min="1" max="5" step="1" value="3" orient="horizontal">
                                </div>
                                <div class="attributes-grid">
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="body_rough5" name="body_type[]" value="rough">
                                        <label for="body_rough5">Rough</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="body_smooth5" name="body_type[]" value="smooth">
                                        <label for="body_smooth5">Smooth</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="body_others5" name="body_type[]" value="others">
                                        <label for="body_others5">OTHERS</label>
                                    </div>
                                    <div class="others-input" id="body_others_input5" style="display: none;">
                                        <input type="text" name="body_others_text" placeholder="Specify other body type attributes..." class="others-text-input">
                                    </div>
                                </div>
                            </div>

                            <!-- Acidity Section -->
                            <div class="form-section">
                                <h4>ACIDITY</h4>
                                <div class="intensity-scale">
                                    <div class="scale-labels">
                                        <span>1</span>
                                        <span>2</span>
                                        <span>3</span>
                                        <span>4</span>
                                        <span>5</span>
                                    </div>
                                    <input type="range" id="acidityIntensity5" name="acidity_intensity" min="1" max="5" step="1" value="3" orient="horizontal">
                                </div>
                                <div class="attributes-grid">
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="acidity_ripe_fruit5" name="acidity_type[]" value="ripe_fruit">
                                        <label for="acidity_ripe_fruit5">Ripe Fruit</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="acidity_winey5" name="acidity_type[]" value="winey">
                                        <label for="acidity_winey5">Winey</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="acidity_vinegar5" name="acidity_type[]" value="vinegar">
                                        <label for="acidity_vinegar5">Vinegar</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="acidity_others5" name="acidity_type[]" value="others">
                                        <label for="acidity_others5">OTHERS</label>
                                    </div>
                                    <div class="others-input" id="acidity_others_input5" style="display: none;">
                                        <input type="text" name="acidity_others_text" placeholder="Specify other acidity type attributes..." class="others-text-input">
                                    </div>
                                </div>
                            </div>

                            <!-- Sweetness Section -->
                            <div class="form-section">
                                <h4>SWEETNESS</h4>
                                <div class="intensity-scale">
                                    <div class="scale-labels">
                                        <span>5</span>
                                        <span>4</span>
                                        <span>3</span>
                                        <span>2</span>
                                        <span>1</span>
                                    </div>
                                    <input type="range" id="sweetnessIntensity5" name="sweetness_intensity" min="1" max="5" step="1" value="3" orient="horizontal">
                                </div>
                                <div class="attributes-grid">
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="sweetness_ripe_fruit5" name="sweetness_type[]" value="ripe_fruit">
                                        <label for="sweetness_ripe_fruit5">Ripe Fruit</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="sweetness_nutty5" name="sweetness_type[]" value="nutty">
                                        <label for="sweetness_nutty5">Nutty/Cocoa</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="sweetness_sweet5" name="sweetness_type[]" value="sweet">
                                        <label for="sweetness_sweet5">Sweet</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="sweetness_others5" name="sweetness_type[]" value="others">
                                        <label for="sweetness_others5">OTHERS</label>
                                    </div>
                                    <div class="others-input" id="sweetness_others_input5" style="display: none;">
                                        <input type="text" name="sweetness_others_text" placeholder="Specify other sweetness type attributes..." class="others-text-input">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Notes Section -->
                            <div class="form-section">
                                <h4>NOTES:</h4>
                                <textarea id="generalNotes5" name="general_notes" placeholder="Enter any additional notes here..." rows="4"></textarea>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <button type="submit" id="submitBtn5" class="btn btn-primary mt-4">
                            <i class="fas fa-save me-2"></i> Save Form 5
                        </button>
                        

                        
                        <!-- Success Message -->
                        <div id="successMessage5" class="hidden">
                            <i class="fas fa-check-circle me-2"></i> Your cupping form has been submitted successfully!
                        </div>
                    </form>
                </div>

                <!-- Form 6 -->
                <div class="form-page" id="form6">
                    <!-- Validation Warning Banner -->
                    <div id="validationWarning6" class="validation-warning-banner" style="display: none;">
                        <div class="warning-content">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <span class="warning-text">This form requires completion of all sections before submission.</span>
                        </div>
                    </div>
                    
                    <form id="cuppingForm6" method="POST" action="process_cupping.php" enctype="multipart/form-data">
                        <input type="hidden" name="_subject" value="New Coffee Cupping Form Submission">
                        
                        <input type="hidden" name="form_number" value="6">
                        
                        <div class="header-info">
                            <div>
                                <label for="name6">Name:</label>
                                <input type="text" id="name6" name="name" value="<?php echo htmlspecialchars($full_name); ?>" required readonly>
                            </div>
                            <div>
                                <label for="date6">Date:</label>
                                <input type="date" id="date6" name="date" required>
                            </div>
                            <div>
                                <label for="tableNo6">Table no:</label>
                                <input type="text" id="tableNo6" name="table_no" required>
                            </div>
                        </div>
                        
                        <h3>Intensity Scale</h3>
                        <div class="quality-scale">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="quality-scale-header">SLOW</th>
                                        <th class="quality-scale-header">MEDIUM TO LOW</th>
                                        <th class="quality-scale-header">MEDIUM</th>
                                        <th class="quality-scale-header">MEDIUM TO HIGH</th>
                                        <th class="quality-scale-header">HIGH</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                        <td>5</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Vertical Form Layout -->
                        <div class="vertical-form">
                            <!-- Sample Section -->
                            <div class="form-section">
                                <h4>SAMPLE</h4>
                                <div class="sample-input">
                                    <input type="text" id="sampleId6" name="sample_id" placeholder="Enter sample identification">
                                </div>
                            </div>
                            
                            <!-- Fragrance/Aroma Section -->
                            <div class="form-section">
                                <h4>FRAGRANCE / AROMA</h4>
                                <div class="intensity-scale">
                                    <div class="scale-labels">
                                        <span>5</span>
                                        <span>4</span>
                                        <span>3</span>
                                        <span>2</span>
                                        <span>1</span>
                                    </div>
                                    <input type="range" id="fragranceIntensity6" name="fragrance_intensity" min="1" max="5" step="1" value="3" orient="horizontal">
                                </div>
                                <div class="attributes-grid">
                                    <div class="attribute-group">
                                        <div class="attribute-item green">
                                            <input type="checkbox" id="fragrance_green6" name="fragrance_attributes[]" value="green">
                                            <label for="fragrance_green6">GREEN</label>
                                        </div>
                                        <div class="attribute-item white">
                                            <input type="checkbox" id="fragrance_grain6" name="fragrance_attributes[]" value="grain">
                                            <label for="fragrance_grain6">GRAIN</label>
                                        </div>
                                        <div class="attribute-item pink">
                                            <input type="checkbox" id="fragrance_floral6" name="fragrance_attributes[]" value="floral">
                                            <label for="fragrance_floral6">FLORAL</label>
                                        </div>
                                        <div class="attribute-item purple">
                                            <input type="checkbox" id="fragrance_fruity6" name="fragrance_attributes[]" value="fruity">
                                            <label for="fragrance_fruity6">FRUITY</label>
                                        </div>
                                    </div>
                                    <div class="attribute-group">
                                        <div class="attribute-item orange">
                                            <input type="checkbox" id="fragrance_sweet6" name="fragrance_attributes[]" value="sweet">
                                            <label for="fragrance_sweet6">SWEET</label>
                                        </div>
                                        <div class="attribute-item brown">
                                            <input type="checkbox" id="fragrance_nutty6" name="fragrance_attributes[]" value="nutty">
                                            <label for="fragrance_nutty6">NUTTY/COCOA</label>
                                        </div>
                                        <div class="attribute-item dark-red">
                                            <input type="checkbox" id="fragrance_spices6" name="fragrance_attributes[]" value="spices">
                                            <label for="fragrance_spices6">SPICES</label>
                                        </div>
                                        <div class="attribute-item dark-purple">
                                            <input type="checkbox" id="fragrance_roasted6" name="fragrance_attributes[]" value="roasted">
                                            <label for="fragrance_roasted6">ROASTED</label>
                                        </div>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="fragrance_others6" name="fragrance_attributes[]" value="others">
                                        <label for="fragrance_others6">OTHERS</label>
                                    </div>
                                    <div class="others-input" id="fragrance_others_input6" style="display: none;">
                                        <input type="text" name="fragrance_others_text" placeholder="Specify other fragrance/aroma attributes..." class="others-text-input">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Flavor Section -->
                            <div class="form-section">
                                <h4>FLAVOR</h4>
                                <div class="intensity-scale">
                                    <div class="scale-labels">
                                        <span>5</span>
                                        <span>4</span>
                                        <span>3</span>
                                        <span>2</span>
                                        <span>1</span>
                                    </div>
                                    <input type="range" id="flavorIntensity6" name="flavor_intensity" min="1" max="5" step="1" value="3" orient="horizontal">
                                </div>
                                <div class="flavor-categories">
                                    <!-- SPICES Category -->
                                    <div class="flavor-category">
                                        <div class="category-header spices">SPICES</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_spices6" name="flavor_attributes[]" value="spices">
                                                <label for="flavor_spices6">SPICES</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_brown_spices6" name="flavor_attributes[]" value="brown_spices">
                                                <label for="flavor_brown_spices6">BROWN SPICES</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- NUTTY/COCOA Category -->
                                    <div class="flavor-category">
                                        <div class="category-header nutty">NUTTY/COCOA</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_nutty6" name="flavor_attributes[]" value="nutty">
                                                <label for="flavor_nutty6">NUTTY</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_chocolate6" name="flavor_attributes[]" value="chocolate">
                                                <label for="flavor_chocolate6">CHOCOLATE</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- SWEET Category -->
                                    <div class="flavor-category">
                                        <div class="category-header sweet">SWEET</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_sweet6" name="flavor_attributes[]" value="sweet">
                                                <label for="flavor_sweet6">SWEET</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_brown_sugar6" name="flavor_attributes[]" value="brown_sugar">
                                                <label for="flavor_brown_sugar6">BROWN SUGAR</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_caramel6" name="flavor_attributes[]" value="caramel">
                                                <label for="flavor_caramel6">CARAMEL</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_vanilla6" name="flavor_attributes[]" value="vanilla">
                                                <label for="flavor_vanilla6">VANILLA</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- FLORAL Category -->
                                    <div class="flavor-category">
                                        <div class="category-header floral">FLORAL</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_floral6" name="flavor_attributes[]" value="floral">
                                                <label for="flavor_floral6">FLORAL</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- FRUITY Category -->
                                    <div class="flavor-category">
                                        <div class="category-header fruity">FRUITY</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_fruity6" name="flavor_attributes[]" value="fruity">
                                                <label for="flavor_fruity6">FRUITY</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_dried_fruit6" name="flavor_attributes[]" value="dried_fruit">
                                                <label for="flavor_dried_fruit6">DRIED FRUIT</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_tropical6" name="flavor_attributes[]" value="tropical">
                                                <label for="flavor_tropical6">TROPICAL</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_berry6" name="flavor_attributes[]" value="berry">
                                                <label for="flavor_berry6">BERRY</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_drupe6" name="flavor_attributes[]" value="drupe">
                                                <label for="flavor_drupe6">DRUPE</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_pomme6" name="flavor_attributes[]" value="pomme">
                                                <label for="flavor_pomme6">POMME</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- SOUR Category -->
                                    <div class="flavor-category">
                                        <div class="category-header sour">SOUR</div>
                                        <div class="category-attributes">
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_winey6" name="flavor_attributes[]" value="winey">
                                                <label for="flavor_winey6">WINEY</label>
                                            </div>
                                            <div class="attribute-item">
                                                <input type="checkbox" id="flavor_sour6" name="flavor_attributes[]" value="sour">
                                                <label for="flavor_sour6">SOUR</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- OTHERS option -->
                                <div class="attribute-item white" style="margin-top: 20px;">
                                    <input type="checkbox" id="flavor_others6" name="flavor_attributes[]" value="others">
                                    <label for="flavor_others6">OTHERS</label>
                                </div>
                                <div class="others-input" id="flavor_others_input6" style="display: none;">
                                    <input type="text" name="flavor_others_text" placeholder="Specify other flavor attributes..." class="others-text-input">
                                </div>
                            </div>

                            <!-- Mouthfeel/Body Section -->
                            <div class="form-section">
                                <h4>MOUTHFEEL / BODY</h4>
                                <div class="intensity-scale">
                                    <div class="scale-labels">
                                        <span>5</span>
                                        <span>4</span>
                                        <span>3</span>
                                        <span>2</span>
                                        <span>1</span>
                                    </div>
                                    <input type="range" id="bodyIntensity6" name="body_intensity" min="1" max="5" step="1" value="3" orient="horizontal">
                                </div>
                                <div class="attributes-grid">
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="body_rough6" name="body_type[]" value="rough">
                                        <label for="body_rough6">Rough</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="body_smooth6" name="body_type[]" value="smooth">
                                        <label for="body_smooth6">Smooth</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="body_others6" name="body_type[]" value="others">
                                        <label for="body_others6">OTHERS</label>
                                    </div>
                                    <div class="others-input" id="body_others_input6" style="display: none;">
                                        <input type="text" name="body_others_text" placeholder="Specify other body type attributes..." class="others-text-input">
                                    </div>
                                </div>
                            </div>

                            <!-- Acidity Section -->
                            <div class="form-section">
                                <h4>ACIDITY</h4>
                                <div class="intensity-scale">
                                    <div class="scale-labels">
                                        <span>1</span>
                                        <span>2</span>
                                        <span>3</span>
                                        <span>4</span>
                                        <span>5</span>
                                    </div>
                                    <input type="range" id="acidityIntensity6" name="acidity_intensity" min="1" max="5" step="1" value="3" orient="horizontal">
                                </div>
                                <div class="attributes-grid">
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="acidity_ripe_fruit6" name="acidity_type[]" value="ripe_fruit">
                                        <label for="acidity_ripe_fruit6">Ripe Fruit</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="acidity_winey6" name="acidity_type[]" value="winey">
                                        <label for="acidity_winey6">Winey</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="acidity_vinegar6" name="acidity_type[]" value="vinegar">
                                        <label for="acidity_vinegar6">Vinegar</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="acidity_others6" name="acidity_type[]" value="others">
                                        <label for="acidity_others6">OTHERS</label>
                                    </div>
                                    <div class="others-input" id="acidity_others_input6" style="display: none;">
                                        <input type="text" name="acidity_others_text" placeholder="Specify other acidity type attributes..." class="others-text-input">
                                    </div>
                                </div>
                            </div>

                            <!-- Sweetness Section -->
                            <div class="form-section">
                                <h4>SWEETNESS</h4>
                                <div class="intensity-scale">
                                    <div class="scale-labels">
                                        <span>5</span>
                                        <span>4</span>
                                        <span>3</span>
                                        <span>2</span>
                                        <span>1</span>
                                    </div>
                                    <input type="range" id="sweetnessIntensity6" name="sweetness_intensity" min="1" max="5" step="1" value="3" orient="horizontal">
                                </div>
                                <div class="attributes-grid">
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="sweetness_ripe_fruit6" name="sweetness_type[]" value="ripe_fruit">
                                        <label for="sweetness_ripe_fruit6">Ripe Fruit</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="sweetness_nutty6" name="sweetness_type[]" value="nutty">
                                        <label for="sweetness_nutty6">Nutty/Cocoa</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="sweetness_sweet6" name="sweetness_type[]" value="sweet">
                                        <label for="sweetness_sweet6">Sweet</label>
                                    </div>
                                    <div class="attribute-item white">
                                        <input type="checkbox" id="sweetness_others6" name="sweetness_type[]" value="others">
                                        <label for="sweetness_others6">OTHERS</label>
                                    </div>
                                    <div class="others-input" id="sweetness_others_input6" style="display: none;">
                                        <input type="text" name="sweetness_others_text" placeholder="Specify other sweetness type attributes..." class="others-text-input">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Notes Section -->
                            <div class="form-section">
                                <h4>NOTES:</h4>
                                <textarea id="generalNotes6" name="general_notes" placeholder="Enter any additional notes here..." rows="4"></textarea>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <button type="submit" id="submitBtn6" class="btn btn-success mt-4">
                            <i class="fas fa-check me-2"></i> Finish & Submit All Forms
                        </button>
                        

                        
                        <!-- Success Message -->
                        <div id="successMessage6" class="hidden">
                            <i class="fas fa-check-circle me-2"></i> All cupping forms have been submitted successfully!
                        </div>
                    </form>
                </div>
                
                <!-- Navigation Separator -->
                <div class="navigation-separator"></div>
                
                <!-- Bottom Navigation Buttons -->
                <div class="form-navigation bottom-navigation">
                    <div class="nav-buttons">
                        <button type="button" class="nav-btn" data-form="1" aria-label="Go to Form 1" title="Form 1">1</button>
                        <button type="button" class="nav-btn" data-form="2" aria-label="Go to Form 2" title="Form 2">2</button>
                        <button type="button" class="nav-btn" data-form="3" aria-label="Go to Form 3" title="Form 3">3</button>
                        <button type="button" class="nav-btn" data-form="4" aria-label="Go to Form 4" title="Form 4">4</button>
                        <button type="button" class="nav-btn" data-form="5" aria-label="Go to Form 5" title="Form 5">5</button>
                        <button type="button" class="nav-btn" data-form="6" aria-label="Go to Form 6" title="Form 6">6</button>
                    </div>
                    <div class="form-counter">Form <span id="currentFormNumBottom">1</span> of 6</div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="loading.js"></script>
    <script>
         document.addEventListener('DOMContentLoaded', function() {
        // Form navigation variables
        let currentForm = 1;
        const totalForms = 6;
        
        // Initialize all forms with current date
        for (let i = 1; i <= totalForms; i++) {
            document.getElementById(`date${i}`).valueAsDate = new Date();
        }
        

        
        // Setup range inputs for intensity scales for all forms
        for (let i = 1; i <= totalForms; i++) {
            setupIntensityRange(`fragranceIntensity${i}`);
            setupIntensityRange(`flavorIntensity${i}`);
            setupIntensityRange(`bodyIntensity${i}`);
            setupIntensityRange(`acidityIntensity${i}`);
            setupIntensityRange(`sweetnessIntensity${i}`);
        }

        // Setup sample ID validation
        for (let i = 1; i <= totalForms; i++) {
            const sampleInput = document.getElementById(`sampleId${i}`);
            if (sampleInput) {
                sampleInput.addEventListener('input', function() {
                    // Clear validation errors when user starts typing
                    clearValidationErrors(i);
                    // Update validation in real-time
                    updateFormValidationRealTime(i);
                });
            }
        }

        // Setup table number validation
        for (let i = 1; i <= totalForms; i++) {
            const tableInput = document.getElementById(`tableNo${i}`);
            if (tableInput) {
                tableInput.addEventListener('input', function() {
                    // Clear validation errors when user starts typing
                    clearValidationErrors(i);
                    // Update validation in real-time
                    updateFormValidationRealTime(i);
                });
            }
        }

        // Setup attribute selection for all forms
        setupAttributeSelection();
        
        // Setup form navigation
        setupFormNavigation();
        
        // Setup range input function
        function setupIntensityRange(inputId) {
            const input = document.getElementById(inputId);
            if (input) {
                input.addEventListener('input', function() {
                    console.log(`${inputId}: ${this.value}`);
                });
            }
        }
        
        // Setup attribute selection
        function setupAttributeSelection() {
            // Handle checkbox selections for all forms
            document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const attributeItem = this.closest('.attribute-item');
                    if (this.checked) {
                        attributeItem.style.borderColor = '#2c5530';
                        attributeItem.style.boxShadow = '0 2px 8px rgba(44, 85, 48, 0.3)';
                    } else {
                        attributeItem.style.borderColor = '';
                        attributeItem.style.boxShadow = '';
                    }
                    
                    // Handle "others" input fields for checkboxes
                    if (this.id.includes('others')) {
                        const othersInput = document.getElementById(this.id.replace('others', 'others_input'));
                        if (othersInput) {
                            othersInput.style.display = this.checked ? 'block' : 'none';
                        }
                    } else {
                        // Hide "others" input when selecting other options
                        const section = this.closest('.form-section');
                        const othersInput = section.querySelector('.others-input');
                        if (othersInput) {
                            othersInput.style.display = 'none';
                        }
                        // Uncheck the "others" checkbox
                        const othersCheckbox = section.querySelector('input[id*="others"]');
                        if (othersCheckbox) {
                            othersCheckbox.checked = false;
                            const othersItem = othersCheckbox.closest('.attribute-item');
                            if (othersItem) {
                                othersItem.style.borderColor = '';
                                othersItem.style.boxShadow = '';
                            }
                        }
                    }
                    
                    // Clear validation errors when user starts filling out the form
                    const form = this.closest('form');
                    if (form) {
                        const formId = form.id.replace('cuppingForm', '');
                        clearValidationErrors(formId);
                        // Update validation in real-time
                        updateFormValidationRealTime(formId);
                    }
                });
            });

            // Make attribute-item containers clickable
            document.querySelectorAll('.attribute-item').forEach(container => {
                container.addEventListener('click', function(e) {
                    // Don't trigger if clicking directly on checkbox or label
                    if (e.target.tagName === 'INPUT' || e.target.tagName === 'LABEL') {
                        return;
                    }
                    
                    // Add click feedback
                    this.classList.add('clicking');
                    setTimeout(() => {
                        this.classList.remove('clicking');
                    }, 100);
                    
                    // Find the checkbox within this container
                    const checkbox = this.querySelector('input[type="checkbox"]');
                    if (checkbox) {
                        // Toggle the checkbox
                        checkbox.checked = !checkbox.checked;
                        
                        // Trigger the change event to update styling
                        const changeEvent = new Event('change', { bubbles: true });
                        checkbox.dispatchEvent(changeEvent);
                        
                        // Update validation in real-time
                        const form = this.closest('form');
                        if (form) {
                            const formId = form.id.replace('cuppingForm', '');
                            updateFormValidationRealTime(formId);
                        }
                    }
                });
            });
        }
        
        // Setup form navigation
        function setupFormNavigation() {
            const currentFormNum = document.getElementById('currentFormNum');
            const currentFormNumBottom = document.getElementById('currentFormNumBottom');
            const navButtons = document.querySelectorAll('.nav-btn');
            
            // Add click event listeners to all navigation buttons
            navButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const formNumber = parseInt(this.getAttribute('data-form'));
                    showForm(formNumber);
                });
            });
            
            // Update navigation state
            function updateNavigationState() {
                // Update active button in both top and bottom navigation
                navButtons.forEach(btn => {
                    btn.classList.remove('active');
                });
                document.querySelectorAll(`[data-form="${currentForm}"]`).forEach(btn => {
                    btn.classList.add('active');
                });
                
                // Update both counters
                currentFormNum.textContent = currentForm;
                currentFormNumBottom.textContent = currentForm;
            }
            
            // Show specific form
            function showForm(formNumber) {
                // Hide current form
                document.getElementById(`form${currentForm}`).classList.remove('active');
                
                // Show new form
                document.getElementById(`form${formNumber}`).classList.add('active');
                
                // Update current form
                currentForm = formNumber;
                
                // Update navigation state
                updateNavigationState();
                
                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
            
            // Initialize navigation state
            updateNavigationState();
        }
        
        // Auto-save functionality for all forms
        let autoSaveData = {};
        let autoSaveTimer = null;
        
        // Initialize auto-save data from localStorage if available
        function initializeAutoSave() {
            const savedData = localStorage.getItem('cuppingFormAutoSave');
            if (savedData) {
                try {
                    autoSaveData = JSON.parse(savedData);
                    console.log('Restored auto-save data:', autoSaveData);
                    // Restore form data
                    restoreFormData();
                    
                    // After restoring data, update validation warnings
                    setTimeout(() => {
                        for (let i = 1; i <= totalForms; i++) {
                            updateFormValidationRealTime(i);
                        }
                    }, 100);
                } catch (e) {
                    console.error('Error parsing auto-save data:', e);
                    autoSaveData = {};
                    
                    // If no saved data, show validation warnings
                    for (let i = 1; i <= totalForms; i++) {
                        updateFormValidationRealTime(i);
                    }
                }
            } else {
                // If no saved data, show validation warnings
                for (let i = 1; i <= totalForms; i++) {
                    updateFormValidationRealTime(i);
                }
            }
        }
        
        // Enhanced auto-save function
        function autoSaveForm(formNumber) {
            const form = document.getElementById(`cuppingForm${formNumber}`);
            if (form) {
                const formData = {};
                
                // Capture all form elements
                const inputs = form.querySelectorAll('input, textarea, select');
                inputs.forEach(input => {
                    if (input.name) {
                        if (input.type === 'checkbox') {
                            if (!formData[input.name]) {
                                formData[input.name] = [];
                            }
                            if (input.checked) {
                                formData[input.name].push(input.value);
                            }
                        } else if (input.type === 'radio') {
                            if (input.checked) {
                                formData[input.name] = input.value;
                            }
                        } else {
                            formData[input.name] = input.value;
                        }
                    }
                });
                
                // Handle empty arrays for checkboxes (none selected)
                Object.keys(formData).forEach(key => {
                    if (Array.isArray(formData[key]) && formData[key].length === 0) {
                        formData[key] = [];
                    }
                });
                
                // Store in auto-save data
                autoSaveData[`form${formNumber}`] = formData;
                
                // Debug logging
                console.log(`Form ${formNumber} auto-saved:`, formData);
                console.log('All auto-save data:', autoSaveData);
                
                // Save to localStorage
                localStorage.setItem('cuppingFormAutoSave', JSON.stringify(autoSaveData));
                
                // Show auto-save indicator
                showAutoSaveIndicator(formNumber);
                
                // Update progress indicator
                updateProgressIndicator();
            }
        }
        
        // Show auto-save indicator
        function showAutoSaveIndicator(formNumber) {
            const submitBtn = document.getElementById(`submitBtn${formNumber}`);
            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-save me-2"></i> Auto-saved';
                submitBtn.style.backgroundColor = '#28a745';
                
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.style.backgroundColor = '';
                }, 2000);
            }
            
            // Also show a floating notification
            showFloatingNotification(`Form ${formNumber} auto-saved!`, 'success');
        }
        
        // Show floating notification
        function showFloatingNotification(message, type = 'info') {
            // Remove existing notifications
            const existingNotifications = document.querySelectorAll('.floating-notification');
            existingNotifications.forEach(notification => notification.remove());
            
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `floating-notification ${type}`;
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
                ${message}
            `;
            
            // Add styles
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? '#28a745' : '#17a2b8'};
                color: white;
                padding: 12px 20px;
                border-radius: 5px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 10000;
                font-size: 14px;
                font-weight: 500;
                animation: slideInRight 0.3s ease;
            `;
            
            // Add animation CSS
            if (!document.getElementById('notification-styles')) {
                const style = document.createElement('style');
                style.id = 'notification-styles';
                style.textContent = `
                    @keyframes slideInRight {
                        from { transform: translateX(100%); opacity: 0; }
                        to { transform: translateX(0); opacity: 1; }
                    }
                    @keyframes slideOutRight {
                        from { transform: translateX(0); opacity: 1; }
                        to { transform: translateX(100%); opacity: 0; }
                    }
                `;
                document.head.appendChild(style);
            }
            
            // Add to page
            document.body.appendChild(notification);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }
        
        // Manual save function

        
        // Enhanced restore form data function
        function restoreFormData() {
            for (let i = 1; i <= totalForms; i++) {
                const form = document.getElementById(`cuppingForm${i}`);
                if (form && autoSaveData[`form${i}`]) {
                    const formData = autoSaveData[`form${i}`];
                    console.log(`Restoring form ${i}:`, formData);
                    
                    // Restore form fields
                    Object.keys(formData).forEach(key => {
                        const fields = form.querySelectorAll(`[name="${key}"]`);
                        if (fields.length > 0) {
                            fields.forEach(field => {
                                if (field.type === 'checkbox') {
                                    if (Array.isArray(formData[key])) {
                                        field.checked = formData[key].includes(field.value);
                                    } else {
                                        field.checked = formData[key] === field.value;
                                    }
                                } else if (field.type === 'radio') {
                                    field.checked = field.value === formData[key];
                                } else {
                                    field.value = formData[key];
                                }
                            });
                        }
                    });
                    
                    // Restore attribute selections visual state
                    restoreAttributeSelections(i);
                    
                    // Restore "others" input fields visibility
                    restoreOthersInputs(i);
                }
            }
        }
        
        // Restore attribute selections visual state
        function restoreAttributeSelections(formNumber) {
            const formPage = document.getElementById(`form${formNumber}`);
            if (formPage) {
                formPage.querySelectorAll('input[type="checkbox"]:checked').forEach(checkbox => {
                    const attributeItem = checkbox.closest('.attribute-item');
                    if (attributeItem) {
                        attributeItem.style.borderColor = '#2c5530';
                        attributeItem.style.boxShadow = '0 2px 8px rgba(44, 85, 48, 0.3)';
                    }
                });
            }
        }
        
        // Restore "others" input fields visibility
        function restoreOthersInputs(formNumber) {
            const formPage = document.getElementById(`form${formNumber}`);
            if (formPage) {
                formPage.querySelectorAll('input[type="checkbox"]:checked').forEach(checkbox => {
                    if (checkbox.id.includes('others')) {
                        const othersInput = document.getElementById(checkbox.id.replace('others', 'others_input'));
                        if (othersInput) {
                            othersInput.style.display = 'block';
                        }
                    }
                });
            }
        }
        
        // Setup comprehensive auto-save for all forms
        for (let i = 1; i <= totalForms; i++) {
            const form = document.getElementById(`cuppingForm${i}`);
            if (form) {
                // Auto-save on any input change
                form.addEventListener('input', function() {
                    clearTimeout(autoSaveTimer);
                    autoSaveTimer = setTimeout(() => {
                        autoSaveForm(i);
                    }, 500); // Auto-save after 0.5 seconds of inactivity
                });
                
                // Auto-save on checkbox/radio change
                form.addEventListener('change', function() {
                    clearTimeout(autoSaveTimer);
                    autoSaveTimer = setTimeout(() => {
                        autoSaveForm(i);
                    }, 300); // Auto-save after 0.3 seconds for checkboxes/radios
                });
                
                // Auto-save on select change
                form.addEventListener('change', function(e) {
                    if (e.target.tagName === 'SELECT') {
                        clearTimeout(autoSaveTimer);
                        autoSaveTimer = setTimeout(() => {
                            autoSaveForm(i);
                        }, 300);
                    }
                });
                
                // Auto-save on textarea input
                form.addEventListener('input', function(e) {
                    if (e.target.tagName === 'TEXTAREA') {
                        clearTimeout(autoSaveTimer);
                        autoSaveTimer = setTimeout(() => {
                            autoSaveForm(i);
                        }, 1000); // Auto-save after 1 second for text areas
                    }
                });
            }
        }
        
        // Initialize auto-save on page load
        initializeAutoSave();
        
        // Update progress indicator
        updateProgressIndicator();
        
        // Fix all scale labels to show 1 on left, 5 on right
        fixAllScaleLabels();
        
        // Save data when page is unloaded (refresh, close, navigate away)
        window.addEventListener('beforeunload', function() {
            // Save all forms before leaving
            for (let i = 1; i <= totalForms; i++) {
                if (document.getElementById(`cuppingForm${i}`)) {
                    autoSaveForm(i);
                }
            }
        });
        
        // Also save data when page becomes hidden (mobile apps, tab switching)
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                // Save all forms when page becomes hidden
                for (let i = 1; i <= totalForms; i++) {
                    if (document.getElementById(`cuppingForm${i}`)) {
                        autoSaveForm(i);
                    }
                }
            }
        });
        
        // Function to fix all scale labels
        function fixAllScaleLabels() {
            const scaleLabels = document.querySelectorAll('.scale-labels');
            scaleLabels.forEach(scaleLabel => {
                const spans = scaleLabel.querySelectorAll('span');
                if (spans.length === 5) {
                    // For 5-value scales (1,2,3,4,5)
                    spans[0].textContent = '1';
                    spans[1].textContent = '2';
                    spans[2].textContent = '3';
                    spans[3].textContent = '4';
                    spans[4].textContent = '5';
                } else if (spans.length === 3) {
                    // For 3-value scales (1,3,5) - like acidity
                    spans[0].textContent = '1';
                    spans[1].textContent = '3';
                    spans[2].textContent = '5';
                }
            });
            console.log('All scale labels fixed to show 1 on left, 5 on right');
        }
        
        // Form submission handlers for all forms - ALL FORMS ARE RESTRICTED
        for (let i = 1; i <= totalForms; i++) {
            const form = document.getElementById(`cuppingForm${i}`);
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Validate form before proceeding - ALL FORMS REQUIRE VALIDATION
                    if (!validateForm(i)) {
                        return false;
                    }
                    
                    if (i === 6) {
                        // Form 6 - Show review modal
                        showReviewModal();
                    } else {
                        // Forms 1-5 - Auto-save after validation
                        autoSaveForm(i);
                        showAutoSaveIndicator(i);
                        showFloatingNotification(`Form ${i} completed and saved successfully!`, 'success');
                    }
                });
            }
        }
        
        // Function to update progress indicator
        function updateProgressIndicator() {
            let completedCount = 0;
            
            // Check each form for data
            for (let i = 1; i <= totalForms; i++) {
                if (autoSaveData[`form${i}`]) {
                    const formData = autoSaveData[`form${i}`];
                    // Check if form has meaningful data (not just empty fields)
                    if (hasFormData(formData)) {
                        completedCount++;
                    }
                }
            }
            
            // Update progress display
            document.getElementById('completedForms').textContent = completedCount;
            const progressFill = document.getElementById('progressFill');
            const progressPercentage = (completedCount / totalForms) * 100;
            progressFill.style.width = progressPercentage + '%';
        }
        
        // Function to check if form has meaningful data
        function hasFormData(formData) {
            // Check if any important fields have data
            const importantFields = ['sample_id', 'fragrance_intensity', 'flavor_intensity', 'body_intensity', 'acidity_intensity', 'sweetness_intensity'];
            
            for (let field of importantFields) {
                if (formData[field] && formData[field] !== '' && formData[field] !== '3') {
                    return true;
                }
            }
            
            // Check if any checkboxes are selected
            const checkboxFields = ['fragrance_attributes', 'flavor_attributes', 'body_type', 'acidity_type', 'sweetness_type'];
            for (let field of checkboxFields) {
                if (formData[field] && Array.isArray(formData[field]) && formData[field].length > 0) {
                    return true;
                }
            }
            
            return false;
        }

        // Function to validate form before submission
        function validateForm(formNumber) {
            const form = document.getElementById(`cuppingForm${formNumber}`);
            if (!form) return false;
            
            // Check if at least one checkbox is selected in each major section
            const sections = [
                { name: 'Fragrance/Aroma', selector: 'input[name="fragrance_attributes[]"]:checked' },
                { name: 'Flavor', selector: 'input[name="flavor_attributes[]"]:checked' },
                { name: 'Body', selector: 'input[name="body_type[]"]:checked' },
                { name: 'Acidity', selector: 'input[name="acidity_type[]"]:checked' },
                { name: 'Sweetness', selector: 'input[name="sweetness_type[]"]:checked' }
            ];
            
            let missingSections = [];
            
            sections.forEach(section => {
                const checkboxes = form.querySelectorAll(section.selector);
                if (checkboxes.length === 0) {
                    missingSections.push(section.name);
                }
            });
            
            // Check if sample ID is filled
            const sampleId = form.querySelector('input[name="sample_id"]');
            if (sampleId && (!sampleId.value || sampleId.value.trim() === '')) {
                missingSections.push('Sample ID');
            }
            
            // Check if table number is filled
            const tableNo = form.querySelector('input[name="table_no"]');
            if (tableNo && (!tableNo.value || tableNo.value.trim() === '')) {
                missingSections.push('Table No');
            }
            
            // Update warning banner visibility
            updateValidationWarningBanner(formNumber, missingSections);
            
            if (missingSections.length > 0) {
                const sectionList = missingSections.join(', ');
                const message = `Please fill out the following sections in Form ${formNumber}:\n\n${sectionList}\n\nYou must select at least one attribute in each section and fill in all required fields to proceed.`;
                
                // Show validation error
                showValidationError(formNumber, message);
                return false;
            }
            
            return true;
        }

        // Function to show validation error
        function showValidationError(formNumber, message) {
            // Create or update validation error message
            let errorDiv = document.getElementById(`validationError${formNumber}`);
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.id = `validationError${formNumber}`;
                errorDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
                errorDiv.innerHTML = `
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span class="error-message">${message}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                
                // Insert after the form
                const form = document.getElementById(`cuppingForm${formNumber}`);
                if (form) {
                    form.parentNode.insertBefore(errorDiv, form.nextSibling);
                }
            } else {
                // Update existing error message
                errorDiv.querySelector('.error-message').textContent = message;
                errorDiv.style.display = 'block';
            }
            
            // Scroll to the error message
            errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Auto-hide after 8 seconds
            setTimeout(() => {
                if (errorDiv) {
                    errorDiv.style.display = 'none';
                }
            }, 8000);
        }

        // Function to clear validation errors
        function clearValidationErrors(formNumber) {
            const errorDiv = document.getElementById(`validationError${formNumber}`);
            if (errorDiv) {
                errorDiv.remove();
            }
        }

        // Function to update validation warning banner
        function updateValidationWarningBanner(formNumber, missingSections) {
            const warningBanner = document.getElementById(`validationWarning${formNumber}`);
            if (!warningBanner) return;
            
            if (missingSections.length > 0) {
                // Show warning banner
                warningBanner.style.display = 'block';
                
                // Update warning text with specific missing sections
                const warningText = warningBanner.querySelector('.warning-text');
                if (warningText) {
                    const sectionList = missingSections.join(', ');
                    warningText.textContent = `This form requires completion of: ${sectionList}`;
                }
            } else {
                // Hide warning banner when form is complete
                warningBanner.style.display = 'none';
            }
        }



        // Function to update validation in real-time
        function updateFormValidationRealTime(formNumber) {
            const form = document.getElementById(`cuppingForm${formNumber}`);
            if (!form) return;
            
            // Check if this form has saved data - if it does, don't show warnings
            if (autoSaveData[`form${formNumber}`] && hasFormData(autoSaveData[`form${formNumber}`])) {
                // Form has data, hide warning banner
                updateValidationWarningBanner(formNumber, []);
                clearValidationErrors(formNumber);
                return;
            }
            
            // Also check if the form is currently complete (user might have filled it after page load)
            const sections = [
                { name: 'Fragrance/Aroma', selector: 'input[name="fragrance_attributes[]"]:checked' },
                { name: 'Flavor', selector: 'input[name="flavor_attributes[]"]:checked' },
                { name: 'Body', selector: 'input[name="body_type[]"]:checked' },
                { name: 'Acidity', selector: 'input[name="acidity_type[]"]:checked' },
                { name: 'Sweetness', selector: 'input[name="sweetness_type[]"]:checked' }
            ];
            
            let missingSections = [];
            sections.forEach(section => {
                const checkboxes = form.querySelectorAll(section.selector);
                if (checkboxes.length === 0) {
                    missingSections.push(section.name);
                }
            });
            
            // Check sample ID
            const sampleId = form.querySelector('input[name="sample_id"]');
            if (sampleId && (!sampleId.value || sampleId.value.trim() === '')) {
                missingSections.push('Sample ID');
            }
            
            // Check table number
            const tableNo = form.querySelector('input[name="table_no"]');
            if (tableNo && (!tableNo.value || tableNo.value.trim() === '')) {
                missingSections.push('Table No');
            }
            
            // Update warning banner
            updateValidationWarningBanner(formNumber, missingSections);
            
            // Clear validation errors if form is now complete
            if (missingSections.length === 0) {
                clearValidationErrors(formNumber);
            }
        }

        // Function to get form completion status
        function getFormCompletionStatus(formNumber) {
            const form = document.getElementById(`cuppingForm${formNumber}`);
            if (!form) return { completed: false, missing: [] };
            
            const sections = [
                { name: 'Fragrance/Aroma', selector: 'input[name="fragrance_attributes[]"]:checked' },
                { name: 'Flavor', selector: 'input[name="flavor_attributes[]"]:checked' },
                { name: 'Body', selector: 'input[name="body_type[]"]:checked' },
                { name: 'Acidity', selector: 'input[name="acidity_type[]"]:checked' },
                { name: 'Sweetness', selector: 'input[name="sweetness_type[]"]:checked' }
            ];
            
            let missing = [];
            sections.forEach(section => {
                const checkboxes = form.querySelectorAll(section.selector);
                if (checkboxes.length === 0) {
                    missing.push(section.name);
                }
            });
            
            // Check sample ID
            const sampleId = form.querySelector('input[name="sample_id"]');
            if (sampleId && (!sampleId.value || sampleId.value.trim() === '')) {
                missing.push('Sample ID');
            }
            
            // Check table number
            const tableNo = form.querySelector('input[name="table_no"]');
            if (tableNo && (!tableNo.value || tableNo.value.trim() === '')) {
                missing.push('Table No');
            }
            
            return {
                completed: missing.length === 0,
                missing: missing
            };
        }

        // Function to show completion summary
        function showCompletionSummary() {
            let summary = 'Form Completion Summary:\n\n';
            let incompleteForms = 0;
            
            for (let i = 1; i <= totalForms; i++) {
                const status = getFormCompletionStatus(i);
                if (!status.completed) {
                    incompleteForms++;
                    summary += `Form ${i}: Missing - ${status.missing.join(', ')}\n`;
                } else {
                    summary += `Form ${i}: ✓ Complete\n`;
                }
                summary += '\n';
            }
            
            if (incompleteForms > 0) {
                summary += `\nPlease complete ${incompleteForms} form(s) before final submission.`;
                alert(summary);
            }
        }

        // Function to show comprehensive validation status
        function showComprehensiveValidationStatus() {
            let statusHTML = '<div class="validation-status-summary">';
            statusHTML += '<h4><i class="fas fa-clipboard-check me-2"></i>Form Validation Status</h4>';
            
            for (let i = 1; i <= totalForms; i++) {
                const status = getFormCompletionStatus(i);
                const statusClass = status.completed ? 'status-complete' : 'status-incomplete';
                const statusIcon = status.completed ? 'fas fa-check-circle' : 'fas fa-exclamation-triangle';
                const statusText = status.completed ? 'Complete' : 'Incomplete';
                
                statusHTML += `
                    <div class="validation-status-item ${statusClass}">
                        <div class="status-header">
                            <i class="${statusIcon} me-2"></i>
                            <strong>Form ${i}</strong> - ${statusText}
                        </div>
                `;
                
                if (!status.completed && status.missing.length > 0) {
                    statusHTML += '<div class="missing-items">';
                    statusHTML += '<strong>Missing:</strong> ';
                    statusHTML += status.missing.join(', ');
                    statusHTML += '</div>';
                }
                
                statusHTML += '</div>';
            }
            
            statusHTML += '</div>';
            
            // Show in a modal or alert
            const modal = document.createElement('div');
            modal.className = 'validation-status-modal';
            modal.innerHTML = `
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>Form Validation Status</h3>
                        <button class="close-btn" onclick="this.parentElement.parentElement.parentElement.remove()">&times;</button>
                    </div>
                    <div class="modal-body">
                        ${statusHTML}
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
        }
        
        // Function to show review modal
        function showReviewModal() {
            const modal = document.getElementById('reviewModal');
            const modalBody = document.getElementById('reviewModalBody');
            
            // Generate review content
            modalBody.innerHTML = generateReviewContent();
            
            // Show modal
            modal.style.display = 'block';
            
            // Add event listeners
            document.getElementById('closeReviewModal').addEventListener('click', hideReviewModal);
            document.getElementById('confirmSubmitBtn').addEventListener('click', submitAllForms);
            
            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    hideReviewModal();
                }
            });
        }
        
        // Function to hide review modal
        function hideReviewModal() {
            const modal = document.getElementById('reviewModal');
            modal.style.display = 'none';
        }
        
        // Function to generate review content
        function generateReviewContent() {
            let content = '';
            
            for (let i = 1; i <= totalForms; i++) {
                if (autoSaveData[`form${i}`]) {
                    const formData = autoSaveData[`form${i}`];
                    content += generateFormReview(i, formData);
                }
            }
            
            if (content === '') {
                content = '<div class="text-center p-4"><p class="text-muted">No forms have been filled out yet.</p></div>';
            }
            
            return content;
        }
        
        // Function to generate review for a specific form
        function generateFormReview(formNumber, formData) {
            const hasData = hasFormData(formData);
            const statusClass = hasData ? 'completed' : 'incomplete';
            const statusText = hasData ? '✓ Completed' : '○ Incomplete';
            
            let review = `
                <div class="review-form-section">
                    <div class="review-form-header">
                        <span class="badge ${hasData ? 'bg-success' : 'bg-secondary'} me-2">${statusText}</span>
                        Form ${formNumber} - ${formData.sample_id || 'No Sample ID'}
                    </div>
                    <div class="review-form-content">
            `;
            
            if (hasData) {
                // Sample Information
                review += `
                    <div class="review-field-group sample-info">
                        <div class="review-field">
                            <div class="review-field-label">Sample ID</div>
                            <div class="review-field-value">${formData.sample_id || '<span class="empty">Not specified</span>'}</div>
                        </div>
                        <div class="review-field">
                            <div class="review-field-label">Date</div>
                            <div class="review-field-value">${formData.date || '<span class="empty">Not specified</span>'}</div>
                        </div>
                    </div>
                `;
                
                // Fragrance Section
                review += `
                    <div class="review-field-group">
                        <div class="review-field">
                            <div class="review-field-label">Fragrance Intensity</div>
                            <div class="review-field-value">${formData.fragrance_intensity || '3'}/5</div>
                        </div>
                        <div class="review-field">
                            <div class="review-field-label">Fragrance Attributes</div>
                            <div class="review-field-value">
                                ${generateAttributesReview(formData["fragrance_attributes[]"] || formData.fragrance_attributes)}
                            </div>
                        </div>
                    </div>
                `;
                
                // Flavor Section
                review += `
                    <div class="review-field-group">
                        <div class="review-field">
                            <div class="review-field-label">Flavor Intensity</div>
                            <div class="review-field-value">${formData.flavor_intensity || '3'}/5</div>
                        </div>
                        <div class="review-field">
                            <div class="review-field-label">Flavor Attributes</div>
                            <div class="review-field-value">
                                ${generateAttributesReview(formData["flavor_attributes[]"] || formData.flavor_attributes)}
                            </div>
                        </div>
                        </div>
                    </div>
                `;
                
                // Body Section
                review += `
                    <div class="review-field-group">
                        <div class="review-field">
                            <div class="review-field-label">Body Intensity</div>
                            <div class="review-field-value">${formData.body_intensity || '3'}/5</div>
                        </div>
                        <div class="review-field">
                            <div class="review-field-label">Body Type</div>
                            <div class="review-field-value">
                                ${generateAttributesReview(formData["body_type[]"] || formData.body_type)}
                            </div>
                        </div>
                    </div>
                `;
                
                // Acidity Section
                review += `
                    <div class="review-field-group">
                        <div class="review-field">
                            <div class="review-field-label">Acidity Intensity</div>
                            <div class="review-field-value">${formData.acidity_intensity || '3'}/5</div>
                        </div>
                        <div class="review-field">
                            <div class="review-field-label">Acidity Type</div>
                            <div class="review-field-value">
                                ${generateAttributesReview(formData["acidity_type[]"] || formData.acidity_type)}
                            </div>
                        </div>
                    </div>
                `;
                
                // Sweetness Section
                review += `
                    <div class="review-field-group">
                        <div class="review-field">
                            <div class="review-field-label">Sweetness Intensity</div>
                            <div class="review-field-value">${formData.sweetness_intensity || '3'}/5</div>
                        </div>
                        <div class="review-field">
                            <div class="review-field-label">Sweetness Type</div>
                            <div class="review-field-value">
                                ${generateAttributesReview(formData["sweetness_type[]"] || formData.sweetness_type)}
                            </div>
                        </div>
                    </div>
                `;
                
                // Notes
                if (formData.general_notes) {
                    review += `
                        <div class="review-field">
                            <div class="review-field-label">General Notes</div>
                            <div class="review-field-value">${formData.general_notes}</div>
                        </div>
                    `;
                }
                

            } else {
                review += `
                    <div class="text-center p-4">
                        <p class="text-muted">This form has not been filled out yet.</p>
                    </div>
                `;
            }
            
            review += `
                    </div>
                </div>
            `;
            
            return review;
        }
        
        // Function to generate attributes review
        function generateAttributesReview(attributes) {
            if (!attributes || attributes.length === 0) {
                return '<span class="empty">None selected</span>';
            }
            
            if (Array.isArray(attributes)) {
                return attributes.map(attr => 
                    `<span class="review-attribute-item selected">${attr}</span>`
                ).join(' ');
            } else if (typeof attributes === 'string') {
                // Handle case where attributes might be a single string
                return `<span class="review-attribute-item selected">${attributes}</span>`;
            } else {
                return '<span class="empty">None selected</span>';
            }
        }
        
        // Function to reset a specific form
        function resetForm(formNumber) {
            const form = document.getElementById(`cuppingForm${formNumber}`);
            if (form) {
                // Reset form fields
                form.reset();
                
                // Reset attribute selections visual state
                const formPage = document.getElementById(`form${formNumber}`);
                if (formPage) {
                    formPage.querySelectorAll('.attribute-item').forEach(item => {
                        item.style.borderColor = '';
                        item.style.boxShadow = '';
                    });
                    
                    // Hide all "others" input fields
                    formPage.querySelectorAll('.others-input').forEach(input => {
                        input.style.display = 'none';
                    });
                    
                    // Reset date to current date
                    const dateField = document.getElementById(`date${formNumber}`);
                    if (dateField) {
                        dateField.valueAsDate = new Date();
                    }
                }
            }
        }
        
        // Function to submit all forms to database
        function submitAllForms() {
            // Hide review modal first
            hideReviewModal();
            
            // Validate all forms before submission
            let invalidForms = [];
            for (let i = 1; i <= totalForms; i++) {
                if (autoSaveData[`form${i}`] && hasFormData(autoSaveData[`form${i}`])) {
                    if (!validateForm(i)) {
                        invalidForms.push(i);
                    }
                }
            }
            
            if (invalidForms.length > 0) {
                const formList = invalidForms.join(', ');
                alert(`Please complete the following forms before submission: ${formList}\n\nEach form must have at least one attribute selected in each section.`);
                return;
            }
            
            // Show loading state
            const submitBtn = document.getElementById('submitBtn6');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Submitting All Forms...';
            
            // Submit forms one by one
            let submittedCount = 0;
            let totalToSubmit = 0;
            
            // Count how many forms have data
            for (let i = 1; i <= totalForms; i++) {
                if (autoSaveData[`form${i}`] && hasFormData(autoSaveData[`form${i}`])) {
                    totalToSubmit++;
                }
            }
            
            if (totalToSubmit === 0) {
                alert('No forms have been filled out. Please fill at least one form before submitting.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-check me-2"></i> Finish & Submit All Forms';
                return;
            }
            
            // Submit each form individually
            for (let i = 1; i <= totalForms; i++) {
                if (autoSaveData[`form${i}`] && hasFormData(autoSaveData[`form${i}`])) {
                    submitForm(i, autoSaveData[`form${i}`], () => {
                        submittedCount++;
                        if (submittedCount === totalToSubmit) {
                            // All forms submitted successfully
                            showSuccessMessage();
                            resetAllForms();
                        }
                    });
                }
            }
        }
        
        // Function to submit a single form
        function submitForm(formNumber, formData, callback) {
            const formDataToSend = new FormData();
            formDataToSend.append('form_number', formNumber);
            
            // Add all form data
            Object.keys(formData).forEach(key => {
                if (Array.isArray(formData[key])) {
                    formData[key].forEach(value => {
                        formDataToSend.append(key, value);
                    });
                } else {
                    formDataToSend.append(key, formData[key]);
                }
            });
            
            // Submit to database
            fetch('process_cupping.php', {
                method: 'POST',
                body: formDataToSend
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                    return;
                }
                return response.text();
            })
            .then(data => {
                if (data) {
                    console.log(`Form ${formNumber} submitted:`, data);
                    callback();
                }
            })
            .catch(error => {
                console.error(`Error submitting form ${formNumber}:`, error);
                alert(`Error submitting form ${formNumber}. Please try again.`);
                callback(); // Continue with other forms
            });
        }
        
        // Function to show success message
        function showSuccessMessage() {
            const successMessage = document.getElementById('successMessage6');
            if (successMessage) {
                successMessage.classList.remove('hidden');
                successMessage.innerHTML = '<i class="fas fa-check-circle me-2"></i> All cupping forms have been submitted successfully!';
                setTimeout(() => {
                    successMessage.classList.add('hidden');
                }, 5000);
            }
            
            // Show completion message
            alert('All forms have been successfully submitted to the database!');
            
            // Reset submit button
            const submitBtn = document.getElementById('submitBtn6');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-check me-2"></i> Finish & Submit All Forms';
        }
        
        // Function to reset all forms
        function resetAllForms() {
            // Clear auto-save data
            localStorage.removeItem('cuppingFormAutoSave');
            autoSaveData = {};
            
            // Reset all forms
            for (let i = 1; i <= totalForms; i++) {
                resetForm(i);
            }
        }

        // Add this to your JavaScript
        function resetForm(formNumber) {
            const form = document.getElementById(`cuppingForm${formNumber}`);
            if (form) {
                form.reset();
                
                // Reset attribute selections
                const formPage = document.getElementById(`form${formNumber}`);
                formPage.querySelectorAll('.attribute-item').forEach(item => {
                    item.style.borderColor = '';
                    item.style.boxShadow = '';
                });
                
                // Hide all "others" input fields
                formPage.querySelectorAll('.others-input').forEach(input => {
                    input.style.display = 'none';
                });
                
                // Reset date to current date
                document.getElementById(`date${formNumber}`).valueAsDate = new Date();
            }
        }
        
        // Save all forms function
        function saveAllForms() {
            let savedCount = 0;
            for (let i = 1; i <= totalForms; i++) {
                if (autoSaveData[`form${i}`]) {
                    autoSaveForm(i);
                    savedCount++;
                }
            }
            
            if (savedCount > 0) {
                showFloatingNotification(`${savedCount} forms saved!`, 'success');
            } else {
                showFloatingNotification('No forms to save', 'info');
            }
        }
        
        // Clear all data function
        function clearAllData() {
            if (confirm('Are you sure you want to clear all form data? This action cannot be undone.')) {
                // Clear localStorage
                localStorage.removeItem('cuppingFormAutoSave');
                
                // Clear auto-save data
                autoSaveData = {};
                
                // Reset all forms
                for (let i = 1; i <= totalForms; i++) {
                    resetForm(i);
                }
                
                // Update progress indicator
                updateProgressIndicator();
                
                // Show success message
                showFloatingNotification('All form data cleared successfully!', 'success');
            }
        }

    });
    </script>
</body>
</html>