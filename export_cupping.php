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
    'ID', 'User ID', 'User Name', 'Submission Date', 'Form Date', 
    'Table No', 'Batch Number', 'Fragrance/Aroma', 'Dry', 'Break',
    'Quality 1', 'Quality 2', 'Fragrance Notes', 'Flavor', 'Flavor Notes',
    'Aftertaste', 'Aftertaste Notes', 'Acidity', 'Acidity Intensity',
    'Acidity Notes', 'Body', 'Body Level', 'Body Notes', 'Uniformity',
    'Uniformity Notes', 'Clean Cup', 'Clean Cup Notes', 'Overall',
    'Overall Notes', 'Balance', 'Balance Notes', 'Sweetness',
    'Sweetness Notes', 'Defective Cups', 'Defect Intensity',
    'Defect Points', 'Total Score', 'Final Score', 'Comments'
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
        $row['table_no'],
        $row['batch_number'],
        $row['fragrance_aroma'],
        $row['dry'],
        $row['break_value'],
        $row['quality1'],
        $row['quality2'],
        $row['fragrance_notes'],
        $row['flavor'],
        $row['flavor_notes'],
        $row['aftertaste'],
        $row['aftertaste_notes'],
        $row['acidity'],
        $row['acidity_intensity'],
        $row['acidity_notes'],
        $row['body'],
        $row['body_level'],
        $row['body_notes'],
        $row['uniformity'],
        $row['uniformity_notes'],
        $row['clean_cup'],
        $row['clean_cup_notes'],
        $row['overall'],
        $row['overall_notes'],
        $row['balance'],
        $row['balance_notes'],
        $row['sweetness'],
        $row['sweetness_notes'],
        $row['defective_cups'],
        $row['defect_intensity'],
        $row['defect_points'],
        $row['total_score'],
        $row['final_score'],
        $row['comments']
    ]);
}

exit();
?>