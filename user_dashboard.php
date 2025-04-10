<?php
session_start();
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
    <title>User Dashboard | Specialty Coffee Depot</title>
    <link rel="shortcut icon" href="img/image-removebg-preview.png" type="image/x-icon">
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
            writing-mode: bt-lr;
            appearance: slider-vertical;
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
            writing-mode: bt-lr;
            appearance: slider-vertical;
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
            writing-mode: bt-lr;
            appearance: slider-vertical;
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
        .radio-group {
            display: flex;
            justify-content: space-between;
            margin: 15px 0;
        }
        .radio-option {
            text-align: center;
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
                <img src="img/image-removebg-preview.png" alt="Logo" class="sidebar-logo">
                <h3>Specialty Coffee</h3>
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
                    <span><?php echo $_SESSION["email"]; ?></span>
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
                    <i class="fas fa-info-circle me-2"></i> Welcome to your SCA Arabica Cupping Form!
                </div>
                
                <!-- Coffee Cupping Form Container -->
                <div class="form-container">
                    <form id="cuppingForm" method="POST" action="process_cupping.php" enctype="multipart/form-data">
                        <input type="hidden" name="_subject" value="New Coffee Cupping Form Submission">
                        <input type="hidden" id="batchNumber" name="batch_number" value="1">
                        
                        <div class="header-info">
                            <div>
                                <label for="name">Name:</label>
                                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($full_name); ?>" required readonly>
                            </div>
                            <div>
                                <label for="date">Date:</label>
                                <input type="date" id="date" name="date" required>
                            </div>
                            <div>
                                <label for="tableNo">Table no:</label>
                                <div style="display: flex; gap: 10px;">
                                    <input type="text" id="tableNo" name="table_no" required style="flex: 1;">
                                    <select id="batchSelect" style="width: 100px;">
                                        <?php for ($i = 1; $i <= 10; $i++): ?>
                                        <option value="<?php echo $i; ?>">Batch <?php echo $i; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    
                    <h3>Quality Scale</h3>
                    <div class="quality-scale">
                        <table>
                            <thead>
                                <tr>
                                    <th>FAIR TO GOOD</th>
                                    <th>VERY GOOD</th>
                                    <th>EXCELLENT</th>
                                    <th>OUTSTANDING</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>6.00</td>
                                    <td>7.00</td>
                                    <td>8.00</td>
                                    <td>9.00</td>
                                </tr>
                                <tr>
                                    <td>6.25</td>
                                    <td>7.25</td>
                                    <td>8.25</td>
                                    <td>9.25</td>
                                </tr>
                                <tr>
                                    <td>6.50</td>
                                    <td>7.50</td>
                                    <td>8.50</td>
                                    <td>9.50</td>
                                </tr>
                                <tr>
                                    <td>6.75</td>
                                    <td>7.75</td>
                                    <td>8.75</td>
                                    <td>9.75</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Single Form for All Attributes -->
                    <div class="cupping-form">
                        <!-- Fragrance/Aroma Section -->
                        <div class="range-container">
                            <div class="range-header">
                                <h4>Fragrance/Aroma</h4>
                                <div class="score-box" id="fragranceValue">6.00</div>
                            </div>
                            <input type="range" id="fragranceAroma" name="fragrance_aroma" min="6" max="10" step="0.25" value="6">
                            <div class="range-labels">
                                <span>6.00</span>
                                <span>6.25</span>
                                <span>6.50</span>
                                <span>6.75</span>
                                <span>7.00</span>
                                <span>7.25</span>
                                <span>7.50</span>
                                <span>7.75</span>
                                <span>8.00</span>
                                <span>8.25</span>
                                <span>8.50</span>
                                <span>8.75</span>
                                <span>9.00</span>
                                <span>9.25</span>
                                <span>9.50</span>
                                <span>9.75</span>
                                <span>10.00</span>
                            </div>
                            
                            <!-- Dry and Break vertical sliders with Qualities -->
                            <div class="fragrance-sub-attributes">
                                <div class="sub-attribute">
                                    <label>Dry</label>
                                    <div class="vertical-range">
                                        <div class="vertical-labels">
                                            <span>5</span>
                                            <span>4</span>
                                            <span>3</span>
                                            <span>2</span>
                                            <span>1</span>
                                        </div>
                                        <input type="range" id="dry" name="dry" min="1" max="5" step="1" value="3" orient="vertical">
                                        <div class="score-box" id="dryValue">3</div>
                                    </div>
                                </div>
                                
                                <div class="qualities-container">
                                    <label>Qualities</label>
                                    <input type="text" class="small-input" id="quality1" name="quality1" placeholder="Quality 1">
                                    <input type="text" class="small-input" id="quality2" name="quality2" placeholder="Quality 2">
                                </div>
                                
                                <div class="sub-attribute">
                                    <label>Break</label>
                                    <div class="vertical-range">
                                        <div class="vertical-labels">
                                            <span>5</span>
                                            <span>4</span>
                                            <span>3</span>
                                            <span>2</span>
                                            <span>1</span>
                                        </div>
                                        <input type="range" id="break" name="break" min="1" max="5" step="1" value="3" orient="vertical">
                                        <div class="score-box" id="breakValue">3</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Fragrance/Aroma Notes -->
                            <div class="attribute-notes">
                                <input type="text" id="fragranceNotes" name="fragrance_notes" placeholder="Notes about fragrance/aroma...">
                            </div>
                        </div>

                        <!-- Flavor Section -->
                        <div class="range-container">
                            <div class="range-header">
                                <h4>Flavor</h4>
                                <div class="score-box" id="flavorValue">6.00</div>
                            </div>
                            <input type="range" id="flavor" name="flavor" min="6" max="10" step="0.25" value="6">
                            <div class="range-labels">
                                <span>6.00</span>
                                <span>6.25</span>
                                <span>6.50</span>
                                <span>6.75</span>
                                <span>7.00</span>
                                <span>7.25</span>
                                <span>7.50</span>
                                <span>7.75</span>
                                <span>8.00</span>
                                <span>8.25</span>
                                <span>8.50</span>
                                <span>8.75</span>
                                <span>9.00</span>
                                <span>9.25</span>
                                <span>9.50</span>
                                <span>9.75</span>
                                <span>10.00</span>
                            </div>
                            
                            <!-- Flavor Notes -->
                            <div class="attribute-notes">
                                <input type="text" id="flavorNotes" name="flavor_notes" placeholder="Notes about flavor...">
                            </div>
                        </div>

                        <!-- Aftertaste Section -->
                        <div class="range-container">
                            <div class="range-header">
                                <h4>Aftertaste</h4>
                                <div class="score-box" id="aftertasteValue">6.00</div>
                            </div>
                            <input type="range" id="aftertaste" name="aftertaste" min="6" max="10" step="0.25" value="6">
                            <div class="range-labels">
                                <span>6.00</span>
                                <span>6.25</span>
                                <span>6.50</span>
                                <span>6.75</span>
                                <span>7.00</span>
                                <span>7.25</span>
                                <span>7.50</span>
                                <span>7.75</span>
                                <span>8.00</span>
                                <span>8.25</span>
                                <span>8.50</span>
                                <span>8.75</span>
                                <span>9.00</span>
                                <span>9.25</span>
                                <span>9.50</span>
                                <span>9.75</span>
                                <span>10.00</span>
                            </div>
                            
                            <!-- Aftertaste Notes -->
                            <div class="attribute-notes">
                                <input type="text" id="aftertasteNotes" name="aftertaste_notes" placeholder="Notes about aftertaste...">
                            </div>
                        </div>
                        
                        <!-- Acidity Section -->
                        <div class="range-container">
                            <div class="range-header">
                                <h4>Acidity</h4>
                                <div class="score-box" id="acidityValue">6.00</div>
                            </div>
                            <input type="range" id="acidity" name="acidity" min="6" max="10" step="0.25" value="6">
                            <div class="range-labels">
                                <span>6.00</span>
                                <span>6.25</span>
                                <span>6.50</span>
                                <span>6.75</span>
                                <span>7.00</span>
                                <span>7.25</span>
                                <span>7.50</span>
                                <span>7.75</span>
                                <span>8.00</span>
                                <span>8.25</span>
                                <span>8.50</span>
                                <span>8.75</span>
                                <span>9.00</span>
                                <span>9.25</span>
                                <span>9.50</span>
                                <span>9.75</span>
                                <span>10.00</span>
                            </div><br>
                            
                            <!-- Intensity range -->
                            <div class="intensity-container">
                                <span class="intensity-label">Intensity:</span>
                                <div class="intensity-range">
                                    <input type="range" id="acidityIntensity" name="acidity_intensity" min="1" max="5" step="1" value="3" orient="vertical">
                                    <div class="intensity-labels">
                                        <span>5 (High)</span>
                                        <span>4</span>
                                        <span>3</span>
                                        <span>2</span>
                                        <span>1 (Low)</span>
                                    </div>
                                    <div class="intensity-value" id="acidityIntensityValue">3</div>
                                </div>
                            </div>
                            
                            <!-- Acidity Notes -->
                            <div class="attribute-notes">
                                <input type="text" id="acidityNotes" name="acidity_notes" placeholder="Notes about acidity...">
                            </div>
                        </div>

                        <!-- Body Section -->
                        <div class="range-container">
                            <div class="range-header">
                                <h4>Body</h4>
                                <div class="score-box" id="bodyValue">6.00</div>
                            </div>
                            <input type="range" id="body" name="body" min="6" max="10" step="0.25" value="6">
                            <div class="range-labels">
                                <span>6.00</span>
                                <span>6.25</span>
                                <span>6.50</span>
                                <span>6.75</span>
                                <span>7.00</span>
                                <span>7.25</span>
                                <span>7.50</span>
                                <span>7.75</span>
                                <span>8.00</span>
                                <span>8.25</span>
                                <span>8.50</span>
                                <span>8.75</span>
                                <span>9.00</span>
                                <span>9.25</span>
                                <span>9.50</span>
                                <span>9.75</span>
                                <span>10.00</span>
                            </div>
                            
                            <!-- Level range -->
                            <div class="level-container">
                                <span class="level-label">Level:</span>
                                <div class="level-range">
                                    <input type="range" id="bodyLevel" name="body_level" min="1" max="5" step="1" value="3" orient="vertical">
                                    <div class="level-labels">
                                        <span>5 (Heavy)</span>
                                        <span>4</span>
                                        <span>3</span>
                                        <span>2</span>
                                        <span>1 (Thin)</span>
                                    </div>
                                    <div class="level-value" id="bodyLevelValue">3</div>
                                </div>
                            </div>
                            
                            <!-- Body Notes -->
                            <div class="attribute-notes">
                                <input type="text" id="bodyNotes" name="body_notes" placeholder="Notes about body...">
                            </div>
                        </div>

                        <!-- Uniformity Section -->
                        <div class="range-container">
                            <div class="range-header">
                                <h4>Uniformity</h4>
                            </div>
                            <div class="radio-group">
                                <div class="radio-option">
                                    <input type="radio" id="uniformity6" name="uniformity" value="6">
                                    <label for="uniformity6">6</label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" id="uniformity7" name="uniformity" value="7">
                                    <label for="uniformity7">7</label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" id="uniformity8" name="uniformity" value="8">
                                    <label for="uniformity8">8</label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" id="uniformity9" name="uniformity" value="9">
                                    <label for="uniformity9">9</label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" id="uniformity10" name="uniformity" value="10" checked>
                                    <label for="uniformity10">10</label>
                                </div>
                            </div>
                            
                            <!-- Uniformity Notes -->
                            <div class="attribute-notes">
                                <input type="text" id="uniformityNotes" name="uniformity_notes" placeholder="Notes about uniformity...">
                            </div>
                        </div>

                        <!-- Clean Cup Section -->
                        <div class="range-container">
                            <div class="range-header">
                                <h4>Clean Cup</h4>
                            </div>
                            <div class="radio-group">
                                <div class="radio-option">
                                    <input type="radio" id="cleanCup6" name="clean_cup" value="6">
                                    <label for="cleanCup6">6</label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" id="cleanCup7" name="clean_cup" value="7">
                                    <label for="cleanCup7">7</label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" id="cleanCup8" name="clean_cup" value="8">
                                    <label for="cleanCup8">8</label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" id="cleanCup9" name="clean_cup" value="9">
                                    <label for="cleanCup9">9</label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" id="cleanCup10" name="clean_cup" value="10" checked>
                                    <label for="cleanCup10">10</label>
                                </div>
                            </div>
                            
                            <!-- Clean Cup Notes -->
                            <div class="attribute-notes">
                                <input type="text" id="cleanCupNotes" name="clean_cup_notes" placeholder="Notes about clean cup...">
                            </div>
                        </div>

                        <!-- Overall Section -->
                        <div class="range-container">
                            <div class="range-header">
                                <h4>Overall</h4>
                                <div class="score-box" id="overallValue">6.00</div>
                            </div>
                            <input type="range" id="overall" name="overall" min="6" max="10" step="0.25" value="6">
                            <div class="range-labels">
                                <span>6.00</span>
                                <span>6.25</span>
                                <span>6.50</span>
                                <span>6.75</span>
                                <span>7.00</span>
                                <span>7.25</span>
                                <span>7.50</span>
                                <span>7.75</span>
                                <span>8.00</span>
                                <span>8.25</span>
                                <span>8.50</span>
                                <span>8.75</span>
                                <span>9.00</span>
                                <span>9.25</span>
                                <span>9.50</span>
                                <span>9.75</span>
                                <span>10.00</span>
                            </div>
                            
                            <!-- Overall Notes -->
                            <div class="attribute-notes">
                                <input type="text" id="overallNotes" name="overall_notes" placeholder="Overall notes...">
                            </div>
                        </div>

                        <!-- Balance Section -->
                        <div class="range-container">
                            <div class="range-header">
                                <h4>Balance</h4>
                                <div class="score-box" id="balanceValue">6.00</div>
                            </div>
                            <input type="range" id="balance" name="balance" min="6" max="10" step="0.25" value="6">
                            <div class="range-labels">
                                <span>6.00</span>
                                <span>6.25</span>
                                <span>6.50</span>
                                <span>6.75</span>
                                <span>7.00</span>
                                <span>7.25</span>
                                <span>7.50</span>
                                <span>7.75</span>
                                <span>8.00</span>
                                <span>8.25</span>
                                <span>8.50</span>
                                <span>8.75</span>
                                <span>9.00</span>
                                <span>9.25</span>
                                <span>9.50</span>
                                <span>9.75</span>
                                <span>10.00</span>
                            </div>
                            
                            <!-- Balance Notes -->
                            <div class="attribute-notes">
                                <input type="text" id="balanceNotes" name="balance_notes" placeholder="Notes about balance...">
                            </div>
                        </div>

                        <!-- Sweetness Section -->
                        <div class="range-container">
                            <div class="range-header">
                                <h4>Sweetness</h4>
                            </div>
                            <div class="radio-group">
                                <div class="radio-option">
                                    <input type="radio" id="sweetness6" name="sweetness" value="6">
                                    <label for="sweetness6">6</label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" id="sweetness7" name="sweetness" value="7">
                                    <label for="sweetness7">7</label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" id="sweetness8" name="sweetness" value="8">
                                    <label for="sweetness8">8</label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" id="sweetness9" name="sweetness" value="9">
                                    <label for="sweetness9">9</label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" id="sweetness10" name="sweetness" value="10" checked>
                                    <label for="sweetness10">10</label>
                                </div>
                            </div>
                            
                            <!-- Sweetness Notes -->
                            <div class="attribute-notes">
                                <input type="text" id="sweetnessNotes" name="sweetness_notes" placeholder="Notes about sweetness...">
                            </div>
                        </div>

                        <!-- Defects and Additional Notes Section -->
                        <div class="notes-section">
                            <h3>Defects:</h3>
                            <h4>Taint - 2</h4>
                            <h4>Fault - 4</h4>
                            <div class="notes-columns">
                                <div class="notes-column">
                                    <div class="scoring-row">
                                        <label for="defectiveCups"># of cups:</label>
                                        <input type="number" id="defectiveCups" name="defective_cups" min="0">
                                    </div>
                                </div>
                                <div class="notes-column">
                                    <div class="scoring-row">
                                        <label for="intensity">Intensity:</label>
                                        <input type="number" id="intensity" name="defect_intensity" min="0">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="scoring-row">
                                <label for="totalScore">Total Score:</label>
                                <input type="number" id="totalScore" name="total_score" step="0.01" min="0" readonly>
                            </div>
                            <div class="scoring-row">
                                <label for="defectPoints">Defect points:</label>
                                <input type="number" id="defectPoints" name="defect_points" step="0.01" min="0">
                            </div>
                            <div class="scoring-row">
                                <label for="finalScore">Final Score:</label>
                                <input type="number" id="finalScore" name="final_score" step="0.01" min="0" readonly>
                            </div>
                            
                            <label for="comments">Additional Notes/Comments:</label>
                            <textarea id="comments" name="comments" placeholder="Enter any additional notes here..."></textarea>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" id="submitBtn" class="btn btn-primary mt-4">
                        <i class="fas fa-save me-2"></i> Save Batch
                    </button>
                    
                    <!-- Success Message -->
                    <div id="successMessage" class="hidden">
                                <i class="fas fa-check-circle me-2"></i> Your cupping form has been submitted successfully!
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="loading.js"></script>
    <script>
         document.addEventListener('DOMContentLoaded', function() {
        // Set current date
        document.getElementById('date').valueAsDate = new Date();
        
        // Batch selection
        const batchSelect = document.getElementById('batchSelect');
        const batchNumberField = document.getElementById('batchNumber');
        
        batchSelect.addEventListener('change', function() {
            batchNumberField.value = this.value;
        });
        
        // Setup range inputs
        setupRangeInput('fragranceAroma', 'fragranceValue');
        setupRangeInput('flavor', 'flavorValue');
        setupRangeInput('aftertaste', 'aftertasteValue');
        setupRangeInput('acidity', 'acidityValue');
        setupRangeInput('body', 'bodyValue');
        setupRangeInput('overall', 'overallValue');
        setupRangeInput('balance', 'balanceValue');
        
        // Setup vertical range inputs
        setupVerticalRangeInput('dry', 'dryValue');
        setupVerticalRangeInput('break', 'breakValue');
        
        // Setup intensity range
        const acidityIntensity = document.getElementById('acidityIntensity');
        const acidityIntensityValue = document.getElementById('acidityIntensityValue');
        if (acidityIntensity && acidityIntensityValue) {
            acidityIntensity.addEventListener('input', function() {
                acidityIntensityValue.textContent = this.value;
            });
        }
        
        // Setup level range
        const bodyLevel = document.getElementById('bodyLevel');
        const bodyLevelValue = document.getElementById('bodyLevelValue');
        if (bodyLevel && bodyLevelValue) {
            bodyLevel.addEventListener('input', function() {
                bodyLevelValue.textContent = this.value;
            });
        }
        
        // Add event listeners for radio buttons
        document.querySelectorAll('input[name="uniformity"]').forEach(radio => {
            radio.addEventListener('change', function() {
                calculateTotalScore();
            });
        });
        document.querySelectorAll('input[name="clean_cup"]').forEach(radio => {
            radio.addEventListener('change', function() {
                calculateTotalScore();
            });
        });
        document.querySelectorAll('input[name="sweetness"]').forEach(radio => {
            radio.addEventListener('change', function() {
                calculateTotalScore();
            });
        });
        
        // Calculate defect points when defective cups or intensity changes
        const defectiveCupsInput = document.getElementById('defectiveCups');
        const intensityInput = document.getElementById('intensity');
        
        if (defectiveCupsInput) {
            defectiveCupsInput.addEventListener('input', function() {
                calculateDefectPoints();
            });
        }
        
        if (intensityInput) {
            intensityInput.addEventListener('input', function() {
                calculateDefectPoints();
            });
        }
        
        // Replace your form submit handler with this:
        document.getElementById('cuppingForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            
            // Show loading state
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Saving...';
            
            // Create FormData object
            const formData = new FormData(this);
            
            // Convert FormData to URL-encoded format
            const urlEncodedData = new URLSearchParams(formData).toString();
            
            // Submit via fetch
            fetch('process_cupping.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: urlEncodedData
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                } else {
                    return response.text();
                }
            })
            .then(data => {
                if (data) {
                    // Handle any direct response (not a redirect)
                    console.log(data);
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save me-2"></i> Save Batch';
                alert('Error submitting form. Please try again.');
            });
        });
        
        // Setup range input function
        function setupRangeInput(inputId, valueId) {
            const input = document.getElementById(inputId);
            const valueDisplay = document.getElementById(valueId);
            
            if (input && valueDisplay) {
                input.addEventListener('input', function() {
                    valueDisplay.textContent = parseFloat(this.value).toFixed(2);
                    calculateTotalScore();
                });
            }
        }

        // Add this to your JavaScript
        function resetForm() {
            document.getElementById('cuppingForm').reset();
            // Reset any custom values
            document.getElementById('fragranceValue').textContent = '6.00';
            document.getElementById('flavorValue').textContent = '6.00';
            // Reset all other values to defaults...
            
            // Clear any file inputs if you have them
            const fileInputs = document.querySelectorAll('input[type="file"]');
            fileInputs.forEach(input => input.value = '');
        }
        
        // Setup vertical range input function
        function setupVerticalRangeInput(inputId, valueId) {
            const input = document.getElementById(inputId);
            const valueDisplay = document.getElementById(valueId);
            
            if (input && valueDisplay) {
                input.addEventListener('input', function() {
                    valueDisplay.textContent = this.value;
                });
            }
        }

        // Calculate defect points based on defective cups and intensity
        function calculateDefectPoints() {
            const defectiveCups = parseInt(document.getElementById('defectiveCups').value) || 0;
            const intensity = parseInt(document.getElementById('intensity').value) || 0;
            
            // Calculate defect points (multiply cups by intensity)
            const defectPoints = defectiveCups * intensity;
            document.getElementById('defectPoints').value = defectPoints;
            
            // Calculate final score
            calculateFinalScore();
        }
        
        // Calculate total score according to SCA standards
        function calculateTotalScore() {
            let total = 0;
            
            // Add up all attribute scores with their respective weights:
            // Fragrance/Aroma - 10 points
            total += parseFloat(document.getElementById('fragranceAroma').value) || 0;
            // Flavor - 10 points
            total += parseFloat(document.getElementById('flavor').value) || 0;
            // Aftertaste - 10 points
            total += parseFloat(document.getElementById('aftertaste').value) || 0;
            // Acidity - 10 points
            total += parseFloat(document.getElementById('acidity').value) || 0;
            // Body - 10 points
            total += parseFloat(document.getElementById('body').value) || 0;
            
            // Uniformity - 10 points (radio button)
            const uniformity = document.querySelector('input[name="uniformity"]:checked');
            total += parseFloat(uniformity?.value) || 0;
            
            // Clean Cup - 10 points (radio button)
            const cleanCup = document.querySelector('input[name="clean_cup"]:checked');
            total += parseFloat(cleanCup?.value) || 0;
            
            // Sweetness - 10 points (radio button)
            const sweetness = document.querySelector('input[name="sweetness"]:checked');
            total += parseFloat(sweetness?.value) || 0;
            
            // Balance - 10 points
            total += parseFloat(document.getElementById('balance').value) || 0;
            
            // Overall - 10 points
            total += parseFloat(document.getElementById('overall').value) || 0;
            
            // Cap the total score at 100 (though with proper weights it shouldn't exceed this)
            total = Math.min(total, 100);
            
            document.getElementById('totalScore').value = total.toFixed(2);
            calculateFinalScore();
        }
        
        // Calculate final score
        function calculateFinalScore() {
            const totalScore = parseFloat(document.getElementById('totalScore').value) || 0;
            const defectPoints = parseFloat(document.getElementById('defectPoints').value) || 0;
            
            // Calculate final score (total score minus defect points, minimum 0)
            const finalScore = Math.max(totalScore - defectPoints, 0);
            document.getElementById('finalScore').value = finalScore.toFixed(2);
        }
    });
    </script>
</body>
</html>