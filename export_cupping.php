<?php
session_start();
require 'db.php';

// Verify admin access
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Set headers for CSV download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=cupping_forms_'.date('Y-m-d').'.csv');

// Create output file pointer
$output = fopen('php://output', 'w');

// Add CSV headers
fputcsv($output, [
    'ID', 'User ID', 'User Name', 'Submission Date', 'Form Date', 'Form Number',
    'Table No', 'Batch Number', 'Sample ID', 'Fragrance Intensity', 'Fragrance Attributes',
    'Fragrance Others Text', 'Flavor Intensity', 'Flavor Attributes', 'Flavor Others Text',
    'Body Intensity', 'Body Type', 'Body Others Text', 'Acidity Intensity', 'Acidity Type',
    'Acidity Others Text', 'Sweetness Intensity', 'Sweetness Type', 'Sweetness Others Text',
    'General Notes', 'Created At'
]);

// Fetch data from database
$query = "SELECT * FROM cupping_forms ORDER BY submission_date DESC";
$result = $conn->query($query);

// Add data rows
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['id'],
        $row['user_id'],
        $row['user_name'],
        $row['submission_date'],
        $row['form_date'],
        $row['form_number'],
        $row['table_no'],
        $row['batch_number'],
        $row['sample_id'],
        $row['fragrance_intensity'],
        $row['fragrance_attributes'],
        $row['fragrance_others_text'],
        $row['flavor_intensity'],
        $row['flavor_attributes'],
        $row['flavor_others_text'],
        $row['body_intensity'],
        $row['body_type'],
        $row['body_others_text'],
        $row['acidity_intensity'],
        $row['acidity_type'],
        $row['acidity_others_text'],
        $row['sweetness_intensity'],
        $row['sweetness_type'],
        $row['sweetness_others_text'],
        $row['general_notes'],
        $row['created_at']
    ]);
}

exit();
?>