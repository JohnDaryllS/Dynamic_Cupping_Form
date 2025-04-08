<?php
session_start();
require 'db.php';

// Verify admin access
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Content-Type: application/json');
    die(json_encode(['error' => 'Unauthorized access']));
}

if (!isset($_GET['id'])) {
    header('Content-Type: application/json');
    die(json_encode(['error' => 'No ID provided']));
}

$form_id = (int)$_GET['id'];

try {
    $query = "SELECT 
                cf.*,
                u.full_name,
                DATE_FORMAT(cf.submission_date, '%M %d, %Y %H:%i') as submission_date_formatted,
                DATE_FORMAT(cf.form_date, '%M %d, %Y') as form_date_formatted
              FROM cupping_forms cf
              LEFT JOIN users u ON cf.user_id = u.id
              WHERE cf.id = ?";
    
    $stmt = $conn->prepare($query);
    if (!$stmt) throw new Exception("Database error: " . $conn->error);
    
    $stmt->bind_param("i", $form_id);
    if (!$stmt->execute()) throw new Exception("Execution failed: " . $stmt->error);
    
    $result = $stmt->get_result();
    if ($result->num_rows === 0) throw new Exception("Form not found");
    
    $form = $result->fetch_assoc();
    
    ob_start(); // Start output buffering
    ?>
    
    <div class="container-fluid p-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Submission Details</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Submitted by:</strong> <?= htmlspecialchars($form['full_name'] ?? 'N/A') ?></p>
                        <p><strong>Submitted on:</strong> <?= $form['submission_date_formatted'] ?></p>
                        <p><strong>Cupping date:</strong> <?= $form['form_date_formatted'] ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Batch Information</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Table:</strong> <?= htmlspecialchars($form['table_no']) ?></p>
                        <p><strong>Batch:</strong> <?= $form['batch_number'] ?></p>
                        <p><strong>Total Score:</strong> <span class="badge bg-success"><?= number_format($form['total_score'], 2) ?></span></p>
                        <p><strong>Final Score:</strong> <span class="badge bg-primary"><?= number_format($form['final_score'], 2) ?></span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Cupping Attributes -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Cupping Attributes</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="20%">Attribute</th>
                                        <th width="15%">Score</th>
                                        <th width="25%">Details</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Fragrance/Aroma -->
                                    <tr>
                                        <td>Fragrance/Aroma</td>
                                        <td><?= number_format($form['fragrance_aroma'], 2) ?></td>
                                        <td>
                                            <strong>Dry:</strong> <?= $form['dry'] ?>/5<br>
                                            <strong>Break:</strong> <?= $form['break_value'] ?>/5<br>
                                            <strong>Qualities:</strong> 
                                            <?= !empty($form['quality1']) ? htmlspecialchars($form['quality1']) : 'N/A' ?>
                                            <?= !empty($form['quality2']) ? ', ' . htmlspecialchars($form['quality2']) : '' ?>
                                        </td>
                                        <td><?= !empty($form['fragrance_notes']) ? htmlspecialchars($form['fragrance_notes']) : 'No notes' ?></td>
                                    </tr>
                                    
                                    <!-- Flavor -->
                                    <tr>
                                        <td>Flavor</td>
                                        <td><?= number_format($form['flavor'], 2) ?></td>
                                        <td>-</td>
                                        <td><?= !empty($form['flavor_notes']) ? htmlspecialchars($form['flavor_notes']) : 'No notes' ?></td>
                                    </tr>
                                    
                                    <!-- Aftertaste -->
                                    <tr>
                                        <td>Aftertaste</td>
                                        <td><?= number_format($form['aftertaste'], 2) ?></td>
                                        <td>-</td>
                                        <td><?= !empty($form['aftertaste_notes']) ? htmlspecialchars($form['aftertaste_notes']) : 'No notes' ?></td>
                                    </tr>
                                    
                                    <!-- Acidity -->
                                    <tr>
                                        <td>Acidity</td>
                                        <td><?= number_format($form['acidity'], 2) ?></td>
                                        <td><strong>Intensity:</strong> <?= $form['acidity_intensity'] ?>/5</td>
                                        <td><?= !empty($form['acidity_notes']) ? htmlspecialchars($form['acidity_notes']) : 'No notes' ?></td>
                                    </tr>
                                    
                                    <!-- Body -->
                                    <tr>
                                        <td>Body</td>
                                        <td><?= number_format($form['body'], 2) ?></td>
                                        <td><strong>Level:</strong> <?= $form['body_level'] ?>/5</td>
                                        <td><?= !empty($form['body_notes']) ? htmlspecialchars($form['body_notes']) : 'No notes' ?></td>
                                    </tr>
                                    
                                    <!-- Uniformity -->
                                    <tr>
                                        <td>Uniformity</td>
                                        <td><?= $form['uniformity'] ?></td>
                                        <td>-</td>
                                        <td><?= !empty($form['uniformity_notes']) ? htmlspecialchars($form['uniformity_notes']) : 'No notes' ?></td>
                                    </tr>
                                    
                                    <!-- Clean Cup -->
                                    <tr>
                                        <td>Clean Cup</td>
                                        <td><?= $form['clean_cup'] ?></td>
                                        <td>-</td>
                                        <td><?= !empty($form['clean_cup_notes']) ? htmlspecialchars($form['clean_cup_notes']) : 'No notes' ?></td>
                                    </tr>
                                    
                                    <!-- Balance -->
                                    <tr>
                                        <td>Balance</td>
                                        <td><?= number_format($form['balance'], 2) ?></td>
                                        <td>-</td>
                                        <td><?= !empty($form['balance_notes']) ? htmlspecialchars($form['balance_notes']) : 'No notes' ?></td>
                                    </tr>
                                    
                                    <!-- Overall -->
                                    <tr>
                                        <td>Overall</td>
                                        <td><?= number_format($form['overall'], 2) ?></td>
                                        <td>-</td>
                                        <td><?= !empty($form['overall_notes']) ? htmlspecialchars($form['overall_notes']) : 'No notes' ?></td>
                                    </tr>
                                    
                                    <!-- Sweetness -->
                                    <tr>
                                        <td>Sweetness</td>
                                        <td><?= $form['sweetness'] ?></td>
                                        <td>-</td>
                                        <td><?= !empty($form['sweetness_notes']) ? htmlspecialchars($form['sweetness_notes']) : 'No notes' ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Defects Section -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">Defects</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Defective Cups:</strong> <?= $form['defective_cups'] ?></p>
                        <p><strong>Defect Intensity:</strong> <?= $form['defect_intensity'] ?></p>
                        <p><strong>Defect Points:</strong> <?= $form['defect_points'] ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Comments Section -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Additional Comments</h5>
                    </div>
                    <div class="card-body">
                        <?= !empty($form['comments']) ? nl2br(htmlspecialchars($form['comments'])) : '<p class="text-muted">No additional comments</p>' ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php
    $output = ob_get_clean();
    echo $output;
    
} catch (Exception $e) {
    ob_end_clean();
    echo '<div class="alert alert-danger m-4">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
}
?>