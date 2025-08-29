<?php
// Prevent any output before JSON
error_reporting(E_ALL);
ini_set('display_errors', 0);

session_start();
require 'db.php';

// Verify admin access
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Content-Type: application/json');
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized access - Role: ' . ($_SESSION['role'] ?? 'not set')]);
    exit();
}

// Check if user_id is provided
if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'User ID is required']);
    exit();
}

$user_id = intval($_GET['user_id']);

try {
    // Get user information
$user_query = "SELECT full_name FROM users WHERE id = ?";
$user_stmt = $conn->prepare($user_query);
if (!$user_stmt) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'User query prepare error: ' . $conn->error]);
    exit();
}

$user_stmt->bind_param("i", $user_id);
if (!$user_stmt->execute()) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'User query execute error: ' . $user_stmt->error]);
    exit();
}

$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();

if (!$user) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'User not found']);
    exit();
}

// Get all submissions for this user
$query = "SELECT 
    cf.id,
    cf.user_name,
    DATE_FORMAT(cf.submission_date, '%b %d, %Y') as formatted_date,
    cf.batch_number as form_number,
    cf.table_no,
    cf.batch_number as sample_id,
    cf.fragrance_intensity,
    cf.flavor_intensity,
    cf.body_intensity,
    cf.acidity_intensity,
    cf.sweetness_intensity
FROM cupping_forms cf
WHERE cf.user_id = ?
ORDER BY cf.submission_date DESC";

$stmt = $conn->prepare($query);
if (!$stmt) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Database prepare error: ' . $conn->error]);
    exit();
}

$stmt->bind_param("i", $user_id);
if (!$stmt->execute()) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Database execute error: ' . $stmt->error]);
    exit();
}

$result = $stmt->get_result();

$submissions = [];
while ($row = $result->fetch_assoc()) {
    $submissions[] = $row;
}

// Generate HTML content
$html = '<div class="table-responsive">';
$html .= '<table class="table table-striped table-hover">';
$html .= '<thead class="table-dark">';
$html .= '<tr>';
$html .= '<th>ID</th>';
$html .= '<th>Date</th>';
$html .= '<th>Form #</th>';
$html .= '<th>Table</th>';
$html .= '<th>Sample ID</th>';
$html .= '<th>Fragrance</th>';
$html .= '<th>Flavor</th>';
$html .= '<th>Body</th>';
$html .= '<th>Acidity</th>';
$html .= '<th>Sweetness</th>';
$html .= '<th>Actions</th>';
$html .= '</tr>';
$html .= '</thead>';
$html .= '<tbody>';

if (empty($submissions)) {
    $html .= '<tr><td colspan="11" class="text-center text-muted">No submissions found for this user.</td></tr>';
} else {
    foreach ($submissions as $submission) {
        $html .= '<tr>';
        $html .= '<td>' . $submission['id'] . '</td>';
        $html .= '<td>' . $submission['formatted_date'] . '</td>';
        $html .= '<td>' . $submission['form_number'] . '</td>';
        $html .= '<td>' . htmlspecialchars($submission['table_no']) . '</td>';
        $html .= '<td>' . htmlspecialchars($submission['sample_id'] ?? 'N/A') . '</td>';
        $html .= '<td>' . $submission['fragrance_intensity'] . '</td>';
        $html .= '<td>' . $submission['flavor_intensity'] . '</td>';
        $html .= '<td>' . $submission['body_intensity'] . '</td>';
        $html .= '<td>' . $submission['acidity_intensity'] . '</td>';
        $html .= '<td>' . $submission['sweetness_intensity'] . '</td>';
        $html .= '<td class="action-buttons">';
        $html .= '<button class="btn btn-sm btn-primary view-details" data-id="' . $submission['id'] . '">';
        $html .= '<i class="fas fa-eye"></i> View';
        $html .= '</button>';
        $html .= '<button class="btn btn-sm btn-danger delete-entry" data-id="' . $submission['id'] . '">';
        $html .= '<i class="fas fa-trash"></i>';
        $html .= '</button>';
        $html .= '</td>';
        $html .= '</tr>';
    }
}

$html .= '</tbody>';
$html .= '</table>';
$html .= '</div>';



header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'content' => $html,
    'user_name' => $user['full_name'],
    'submission_count' => count($submissions)
]);
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Unexpected error: ' . $e->getMessage()]);
}
?>
