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
    

    
    // Helper function to decode JSON attributes
    function decodeAttributes($jsonString) {
        if (empty($jsonString)) return [];
        $decoded = json_decode($jsonString, true);
        return is_array($decoded) ? $decoded : [];
    }
    
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
                        <p><strong>Form number:</strong> <?= $form['form_number'] ?></p>
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
                        <p><strong>Sample ID:</strong> <?= htmlspecialchars($form['sample_id'] ?? 'N/A') ?></p>
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
                                        <th width="15%">Intensity</th>
                                        <th width="25%">Selected Types</th>
                                        <th>Additional Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Fragrance/Aroma -->
                                    <tr>
                                        <td><strong>Fragrance/Aroma</strong></td>
                                        <td><?= $form['fragrance_intensity'] ?>/5</td>
                                        <td>
                                            <?php 
                                            $fragranceAttrs = decodeAttributes($form['fragrance_attributes']);
                                            if (!empty($fragranceAttrs)) {
                                                echo implode(', ', array_map('ucfirst', $fragranceAttrs));
                                            } else {
                                                echo 'None selected';
                                            }
                                            ?>
                                        </td>
                                        <td><?= htmlspecialchars($form['fragrance_others_text'] ?? '') ?></td>
                                    </tr>
                                    
                                    <!-- Flavor -->
                                    <tr>
                                        <td><strong>Flavor</strong></td>
                                        <td><?= $form['flavor_intensity'] ?>/5</td>
                                        <td>
                                            <?php 
                                            $flavorAttrs = decodeAttributes($form['flavor_attributes']);
                                            if (!empty($flavorAttrs)) {
                                                echo implode(', ', array_map('ucfirst', $flavorAttrs));
                                            } else {
                                                echo 'None selected';
                                            }
                                            ?>
                                        </td>
                                        <td><?= htmlspecialchars($form['flavor_others_text'] ?? '') ?></td>
                                    </tr>
                                    
                                    <!-- Body -->
                                    <tr>
                                        <td><strong>Body/Mouthfeel</strong></td>
                                        <td><?= $form['body_intensity'] ?>/5</td>
                                        <td>
                                            <?php 
                                            $bodyTypes = decodeAttributes($form['body_type']);
                                            if (!empty($bodyTypes)) {
                                                echo implode(', ', array_map('ucfirst', $bodyTypes));
                                            } else {
                                                echo 'None selected';
                                            }
                                            ?>
                                        </td>
                                        <td><?= htmlspecialchars($form['body_others_text'] ?? '') ?></td>
                                    </tr>
                                    
                                    <!-- Acidity -->
                                    <tr>
                                        <td><strong>Acidity</strong></td>
                                        <td><?= $form['acidity_intensity'] ?>/5</td>
                                        <td>
                                            <?php 
                                            $acidityTypes = decodeAttributes($form['acidity_type']);
                                            if (!empty($acidityTypes)) {
                                                echo implode(', ', array_map('ucfirst', $acidityTypes));
                                            } else {
                                                echo 'None selected';
                                            }
                                            ?>
                                        </td>
                                        <td><?= htmlspecialchars($form['acidity_others_text'] ?? '') ?></td>
                                    </tr>
                                    
                                    <!-- Sweetness -->
                                    <tr>
                                        <td><strong>Sweetness</strong></td>
                                        <td><?= $form['sweetness_intensity'] ?>/5</td>
                                        <td>
                                            <?php 
                                            $sweetnessTypes = decodeAttributes($form['sweetness_type']);
                                            if (!empty($sweetnessTypes)) {
                                                echo implode(', ', array_map('ucfirst', $sweetnessTypes));
                                            } else {
                                                echo 'None selected';
                                            }
                                            ?>
                                        </td>
                                        <td><?= htmlspecialchars($form['sweetness_others_text'] ?? '') ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- General Notes -->
        <?php if (!empty($form['general_notes'])): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">General Notes</h5>
                    </div>
                    <div class="card-body">
                        <p><?= nl2br(htmlspecialchars($form['general_notes'])) ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        

    </div>
    
    <?php
    $content = ob_get_clean(); // Get the buffered content
    
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'content' => $content
    ]);
    
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>