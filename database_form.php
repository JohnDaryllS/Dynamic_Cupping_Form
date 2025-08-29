<?php
session_start();
require 'db.php';

// Verify admin access
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Search functionality
$search = $_GET['search'] ?? '';
$page = max(1, $_GET['page'] ?? 1);
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Build base query to get unique users with their submission counts
$query = "SELECT 
    u.id as user_id,
    u.full_name,
    COUNT(cf.id) as submission_count,
    MIN(cf.submission_date) as first_submission,
    MAX(cf.submission_date) as last_submission,
    COUNT(*) OVER() as total_count
FROM users u
LEFT JOIN cupping_forms cf ON u.id = cf.user_id
WHERE u.role = 'user'";

// Add search conditions
if (!empty($search)) {
    $query .= " AND u.full_name LIKE ?";
    $search_param = "%$search%";
}

// Add pagination
$query .= " GROUP BY u.id, u.full_name ORDER BY last_submission DESC LIMIT ? OFFSET ?";

// Prepare and execute query
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Database error: " . $conn->error);
}

if (!empty($search)) {
    $stmt->bind_param("sii", $search_param, $per_page, $offset);
} else {
    $stmt->bind_param("ii", $per_page, $offset);
}

$stmt->execute();
$result = $stmt->get_result();
$total_count = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Cupping Database | Specialty Coffee Depot</title>
    <link rel="shortcut icon" href="img/fci.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        .table-responsive {
            overflow-x: auto;
        }
        .pagination {
            justify-content: center;
        }
        .user-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #6F4E37;
            color: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
            font-weight: bold;
        }
        .action-buttons .btn {
            margin: 2px;
        }
    </style>
</head>
<body>

    <div id="loading-screen">
        <div class="loading-spinner"></div>
        <div class="loading-text">Loading...</div>
    </div>
    
    <div class="admin-container">
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
                <li><a href="dashboard.php"><i class="fas fa-users"></i> User Management</a></li>
                <li class="active"><a href="database_form.php"><i class="fas fa-database"></i> Cupping Database</a></li>
            </ul>
            <div class="sidebar-footer">
                <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <header class="main-header">
                <h1>Cupping Database</h1>
                <div class="user-info">
                    <span>Welcome, <?= htmlspecialchars($_SESSION['email']) ?></span>
                    <div class="avatar"><?= strtoupper(substr($_SESSION['email'], 0, 1)) ?></div>
                </div>
            </header>

            <div class="content-wrapper">
                <!-- Success/Error Messages -->
                <?php if (isset($_SESSION['form_success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= $_SESSION['form_success'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['form_success']); ?>
                <?php endif; ?>

                <!-- Search Form -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-6">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Search by name or table..." 
                                       value="<?= htmlspecialchars($search) ?>">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-2"></i> Search
                                </button>
                            </div>
                            <div class="col-md-2">
                                <a href="database_form.php" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-sync me-2"></i> Reset
                                </a>
                            </div>
                            <div class="col-md-2">
                                <a href="export_cupping.php" class="btn btn-success w-100">
                                    <i class="fas fa-file-excel me-2"></i> Export Excel
                                </a>
                            </div>

                        </form>
                    </div>
                </div>

                <!-- Results Table -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>User ID</th>
                                        <th>Full Name</th>
                                        <th>Total Submissions</th>
                                        <th>First Submission</th>
                                        <th>Last Submission</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result->fetch_assoc()): 
                                        $total_count = $row['total_count'] ?? 0; ?>
                                    <tr>
                                        <td><?= $row['user_id'] ?></td>
                                        <td><?= htmlspecialchars($row['full_name'] ?? 'N/A') ?></td>
                                        <td>
                                            <span class="badge bg-primary"><?= $row['submission_count'] ?></span>
                                        </td>
                                        <td><?= $row['first_submission'] ? date('M d, Y', strtotime($row['first_submission'])) : 'N/A' ?></td>
                                        <td><?= $row['last_submission'] ? date('M d, Y', strtotime($row['last_submission'])) : 'N/A' ?></td>
                                        <td class="action-buttons">
                                            <button class="btn btn-sm btn-primary view-all-submits" 
                                                    data-user-id="<?= $row['user_id'] ?>"
                                                    data-user-name="<?= htmlspecialchars($row['full_name']) ?>">
                                                <i class="fas fa-list"></i> View All Submits
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?php if ($total_count > $per_page): ?>
                        <nav aria-label="Page navigation">
                            <ul class="pagination mt-4">
                                <?php $total_pages = ceil($total_count / $per_page); ?>
                                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page-1 ?>">
                                        Previous
                                    </a>
                                </li>
                                
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                    <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $i ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                                <?php endfor; ?>
                                
                                <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page+1 ?>">
                                        Next
                                    </a>
                                </li>
                            </ul>
                        </nav>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- View All Submissions Modal -->
    <div class="modal fade" id="submissionsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">All Submissions for <span id="userNameDisplay"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalSubmissionsContent">
                    <!-- Content loaded via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this cupping form? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" action="delete_cupping.php">
                        <input type="hidden" name="id" id="deleteIdInput">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="loading.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // View all submissions handler
        document.querySelectorAll('.view-all-submits').forEach(btn => {
            btn.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                const userName = this.getAttribute('data-user-name');
                const modalContent = document.getElementById('modalSubmissionsContent');
                const userNameDisplay = document.getElementById('userNameDisplay');
                
                // Update modal title
                userNameDisplay.textContent = userName;
                
                        // Show loading state
        modalContent.innerHTML = `
            <div class="text-center p-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading submissions...</p>
            </div>
        `;
        
        const modal = new bootstrap.Modal(document.getElementById('submissionsModal'));
        modal.show();
        
        // Fetch all submissions for this user
        fetch(`get_user_submissions.php?user_id=${userId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
                         .then(data => {
                 if (data.success) {
                     modalContent.innerHTML = data.content;
                     
                     // Add event listeners for the dynamically loaded buttons
                     addModalEventListeners();
                 } else {
                    modalContent.innerHTML = `
                        <div class="alert alert-danger">
                            <h5>Error Loading Submissions</h5>
                            <p>${data.error || 'Failed to load user submissions'}</p>
                            <p>Please try again or contact support.</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                modalContent.innerHTML = `
                    <div class="alert alert-danger">
                        <h5>Error Loading Submissions</h5>
                        <p>${error.message}</p>
                        <p>Please try again or contact support.</p>
                    </div>
                `;
            });
         });
 });

 // Function to add event listeners for modal buttons
 function addModalEventListeners() {
     // View details handler for submissions in modal
     document.querySelectorAll('.view-details').forEach(btn => {
         btn.addEventListener('click', function() {
             const formId = this.getAttribute('data-id');
             const modalContent = document.getElementById('modalSubmissionsContent');
             
             // Show loading state
             modalContent.innerHTML = `
                 <div class="text-center p-4">
                     <div class="spinner-border text-primary" role="status">
                         <span class="visually-hidden">Loading...</span>
                     </div>
                     <p class="mt-2">Loading details...</p>
                 </div>
             `;
             
             // Fetch the details
             fetch(`get_cupping_details.php?id=${formId}`)
                 .then(response => {
                     if (!response.ok) {
                         throw new Error(`HTTP error! status: ${response.status}`);
                     }
                     return response.json();
                 })
                 .then(data => {
                     if (data.success) {
                         modalContent.innerHTML = data.content;
                     } else {
                         modalContent.innerHTML = `
                             <div class="alert alert-danger">
                                 <h5>Error Loading Details</h5>
                                 <p>${data.error || 'Failed to load form details'}</p>
                                 <p>Please try again or contact support.</p>
                             </div>
                         `;
                     }
                 })
                 .catch(error => {
                     console.error('Error:', error);
                     modalContent.innerHTML = `
                         <div class="alert alert-danger">
                             <h5>Error Loading Details</h5>
                             <p>${error.message}</p>
                             <p>Please try again or contact support.</p>
                         </div>
                     `;
                 });
         });
     });

     // Delete handler for submissions in modal
     document.querySelectorAll('.delete-entry').forEach(btn => {
         btn.addEventListener('click', function() {
             const formId = this.getAttribute('data-id');
             if (confirm('Are you sure you want to delete this submission? This action cannot be undone.')) {
                 // Create form data
                 const formData = new FormData();
                 formData.append('id', formId);
                 
                 fetch('delete_cupping.php', {
                     method: 'POST',
                     body: formData
                 })
                 .then(response => {
                     if (!response.ok) {
                         throw new Error('Network response was not ok');
                     }
                     return response.json();
                 })
                 .then(data => {
                     if (data.success) {
                         // Remove the row from the table
                         this.closest('tr').remove();
                         alert('Submission deleted successfully!');
                     } else {
                         alert('Error: ' + (data.message || 'Failed to delete'));
                     }
                 })
                 .catch(error => {
                     console.error('Error:', error);
                     alert('Error deleting submission. Please try again.');
                 });
             }
         });
     });
 }

 // Handle form submission
document.getElementById('deleteForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Close the modal
            const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
            deleteModal.hide();
            
            // Show success message
            const successAlert = `
                <div class="alert alert-success alert-dismissible fade show">
                    Cupping form deleted successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            document.querySelector('.content-wrapper').insertAdjacentHTML('afterbegin', successAlert);
            
            // Reload the page after 1 second
            setTimeout(() => location.reload(), 1000);
        } else {
            alert('Error: ' + (data.message || 'Failed to delete'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error deleting form. Please try again.');
    });
});
    });
    </script>
</body>
</html>