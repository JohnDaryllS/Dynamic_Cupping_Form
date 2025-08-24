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

// Add CSV headers with new average intensity columns
fputcsv($output, [
    'ID', 'User ID', 'User Name', 'Submission Date', 'Form Date', 'Form Number',
    'Table No', 'Batch Number', 'Sample ID', 'Fragrance Intensity', 'Fragrance Attributes',
    'Fragrance Others Text', 'Flavor Intensity', 'Flavor Attributes', 'Flavor Others Text',
    'Body Intensity', 'Body Type', 'Body Others Text', 'Acidity Intensity', 'Acidity Type',
    'Acidity Others Text', 'Sweetness Intensity', 'Sweetness Type', 'Sweetness Others Text',
    'General Notes', 'Created At', 'Average Intensity', 'Overall Average'
]);

// Fetch data from database with average calculations
$query = "SELECT 
    cf.*,
    ROUND((cf.fragrance_intensity + cf.flavor_intensity + cf.body_intensity + cf.acidity_intensity + cf.sweetness_intensity) / 5.0, 2) as average_intensity,
    ROUND((cf.fragrance_intensity + cf.flavor_intensity + cf.body_intensity + cf.acidity_intensity + cf.sweetness_intensity) / 5.0, 2) as overall_average
FROM cupping_forms cf 
ORDER BY cf.submission_date DESC";

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
        $row['created_at'],
        $row['average_intensity'],
        $row['overall_average']
    ]);
}

// Add a blank row for separation
fputcsv($output, []);

// Add summary section with form averages
fputcsv($output, ['FORM AVERAGES SUMMARY']);
fputcsv($output, ['Form Number', 'Total Forms', 'Avg Fragrance', 'Avg Flavor', 'Avg Body', 'Avg Acidity', 'Avg Sweetness', 'Overall Average']);

// Calculate averages by form number
$form_averages_query = "SELECT 
    form_number,
    COUNT(*) as total_forms,
    ROUND(AVG(fragrance_intensity), 2) as avg_fragrance,
    ROUND(AVG(flavor_intensity), 2) as avg_flavor,
    ROUND(AVG(body_intensity), 2) as avg_body,
    ROUND(AVG(acidity_intensity), 2) as avg_acidity,
    ROUND(AVG(sweetness_intensity), 2) as avg_sweetness,
    ROUND(AVG((fragrance_intensity + flavor_intensity + body_intensity + acidity_intensity + sweetness_intensity) / 5.0), 2) as overall_average
FROM cupping_forms 
GROUP BY form_number 
ORDER BY form_number";

$form_averages_result = $conn->query($form_averages_query);

while ($row = $form_averages_result->fetch_assoc()) {
    fputcsv($output, [
        $row['form_number'],
        $row['total_forms'],
        $row['avg_fragrance'],
        $row['avg_flavor'],
        $row['avg_body'],
        $row['avg_acidity'],
        $row['avg_sweetness'],
        $row['overall_average']
    ]);
}

// Add another blank row
fputcsv($output, []);

// Add overall statistics
fputcsv($output, ['OVERALL STATISTICS']);
fputcsv($output, ['Total Forms', 'Overall Avg Fragrance', 'Overall Avg Flavor', 'Overall Avg Body', 'Overall Avg Acidity', 'Overall Avg Sweetness', 'Grand Average']);

$overall_stats_query = "SELECT 
    COUNT(*) as total_forms,
    ROUND(AVG(fragrance_intensity), 2) as overall_avg_fragrance,
    ROUND(AVG(flavor_intensity), 2) as overall_avg_flavor,
    ROUND(AVG(body_intensity), 2) as overall_avg_body,
    ROUND(AVG(acidity_intensity), 2) as overall_avg_acidity,
    ROUND(AVG(sweetness_intensity), 2) as overall_avg_sweetness,
    ROUND(AVG((fragrance_intensity + flavor_intensity + body_intensity + acidity_intensity + sweetness_intensity) / 5.0), 2) as grand_average
FROM cupping_forms";

$overall_stats_result = $conn->query($overall_stats_query);
$overall_stats = $overall_stats_result->fetch_assoc();

fputcsv($output, [
    $overall_stats['total_forms'],
    $overall_stats['overall_avg_fragrance'],
    $overall_stats['overall_avg_flavor'],
    $overall_stats['overall_avg_body'],
    $overall_stats['overall_avg_acidity'],
    $overall_stats['overall_avg_sweetness'],
    $overall_stats['grand_average']
]);

exit();
?>