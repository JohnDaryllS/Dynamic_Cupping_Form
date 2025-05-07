<?php
require 'autologin.php';
include 'db.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: login.php");
    exit();
}

// Handle User Creation with Full Name
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_user'])) {
    // Validate required fields
    if (empty($_POST['full_name']) || empty($_POST['email']) || empty($_POST['password'])) {
        $_SESSION['error'] = "All fields are required";
        header("Location: dashboard.php");
        exit();
    }

    $full_name = trim($_POST['full_name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = hash("sha256", $_POST['password']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format";
        header("Location: dashboard.php");
        exit();
    }

    // First check if email already exists
    $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $_SESSION['error'] = "Email already exists";
        header("Location: dashboard.php");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, 'user')");
    $stmt->bind_param("sss", $full_name, $email, $password);

    if ($stmt->execute()) {
        $_SESSION['success'] = "User created successfully!";
    } else {
        $_SESSION['error'] = "Failed to create user";
    }
    header("Location: dashboard.php");
    exit();
}

// Handle Email Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_email'])) {
    $user_id = $_POST['user_id'];
    $new_email = filter_var($_POST['new_email'], FILTER_SANITIZE_EMAIL);

    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format";
    } else {
        $stmt = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
        $stmt->bind_param("si", $new_email, $user_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Email updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update email. Email may already exist.";
        }
    }
    header("Location: dashboard.php");
    exit();
}

// Handle Password Change
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_password'])) {
    $user_id = $_POST['user_id'];
    $new_password = hash("sha256", $_POST['new_password']);

    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $new_password, $user_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Password changed successfully!";
    } else {
        $_SESSION['error'] = "Failed to change password";
    }
    header("Location: dashboard.php");
    exit();
}

// Handle User Deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];

    // Prevent deleting the main admin account
    if ($user_id == 1) {
        $_SESSION['error'] = "Cannot delete the main admin account";
        header("Location: dashboard.php");
        exit();
    }

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "User deleted successfully!";
    } else {
        $_SESSION['error'] = "Failed to delete user";
    }
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | SCA Cupping Form</title>
    <link rel="shortcut icon" href="img/1.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
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
                <img src="img/1.png" alt="Logo" class="sidebar-logo">
                <h3>SCA Cupping Form</h3>
            </div>
            <ul class="sidebar-menu">
                <li class="active"><a href="#"><i class="fas fa-users"></i> User Management</a></li>
                <li><a href="database_form.php"><i class="fas fa-database"></i> Database Form</a></li>
            </ul>
            <div class="sidebar-footer">
                <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <header class="main-header">
                <h1>User Management</h1>
                <div class="user-info">
                    <span>Welcome, <?php echo $_SESSION["email"]; ?></span>
                    <div class="avatar"><?php echo strtoupper(substr($_SESSION["email"], 0, 1)); ?></div>
                </div>
            </header>

            <div class="content-wrapper">
                <!-- Success/Error Messages -->
                <?php if(isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if(isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title">Create New User</h3>
                        <p class="card-subtitle">Add new staff members to the system</p>
                    </div>
                    <div class="card-body">
                        <form method="POST" id="createUserForm" class="needs-validation" novalidate>
                            <input type="hidden" name="create_user" value="1">
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="full_name" name="full_name" required>
                                <div class="invalid-feedback">Please provide a full name.</div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <div class="invalid-feedback">Please provide a valid email.</div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required minlength="8">
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="far fa-eye"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback">Password must be at least 8 characters.</div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2">
                                <i class="fas fa-user-plus me-2"></i> Create User
                            </button>
                        </form>
                    </div>
                </div>

                <!-- User List with Action Buttons -->
                <div class="card shadow-sm mt-4">
                    <div class="card-header">
                        <h3 class="card-title">User List</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $result = $conn->query("SELECT id, full_name, email, role FROM users ORDER BY id DESC");
                                while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><span class="badge bg-<?php echo $row['role'] == 'admin' ? 'primary' : 'secondary'; ?>"><?php echo ucfirst($row['role']); ?></span></td>
                                    <td class="action-buttons">
                                            <!-- Edit Email Button -->
                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" 
                                                    data-bs-target="#editEmailModal" 
                                                    data-userid="<?php echo $row['id']; ?>"
                                                    data-currentemail="<?php echo $row['email']; ?>">
                                                <i class="fas fa-envelope"></i> Edit Email
                                            </button>
                                            
                                            <!-- Change Password Button -->
                                            <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" 
                                                    data-bs-target="#changePasswordModal" 
                                                    data-userid="<?php echo $row['id']; ?>">
                                                <i class="fas fa-key"></i> Change Password
                                            </button>
                                            
                                            <!-- Delete Button -->
                                            <button class="btn btn-sm btn-outline-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#confirmDeleteModal"
                                                    data-userid="<?php echo $row['id']; ?>">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Edit Email Modal -->
    <div class="modal fade" id="editEmailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Email Address</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="user_id" id="email_user_id">
                        <input type="hidden" name="update_email" value="1">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <p class="form-control-static" id="display_full_name"></p>
                        </div>
                        <div class="mb-3">
                            <label for="new_email" class="form-label">New Email Address</label>
                            <input type="email" class="form-control" id="new_email" name="new_email" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Email</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" id="passwordChangeForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Change Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="user_id" id="password_user_id">
                        <input type="hidden" name="change_password" value="1">
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="new_password" name="new_password" required minlength="8">
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                            <div class="form-text">Minimum 8 characters</div>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" required>
                            <div class="invalid-feedback">Passwords do not match</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade modal-confirm" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header flex-column">
                    <div class="icon-box">
                        <i class="fas fa-exclamation"></i>
                    </div>
                    <h4 class="modal-title w-100">Are you sure?</h4>
                </div>
                <div class="modal-body">
                    <p>Do you really want to delete this user account? This process cannot be undone.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <form method="POST" class="w-100">
                        <input type="hidden" name="user_id" id="delete_user_id">
                        <input type="hidden" name="delete_user" value="1">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="loading.js"></script>
    <script>

        // Client-side form validation
        document.getElementById('createUserForm').addEventListener('submit', function(e) {
            const form = this;
            const fullName = form.querySelector('#full_name');
            const email = form.querySelector('#email');
            const password = form.querySelector('#password');
            
            // Reset validation states
            form.classList.remove('was-validated');
            fullName.classList.remove('is-invalid');
            email.classList.remove('is-invalid');
            password.classList.remove('is-invalid');
            
            // Validate fields
            let isValid = true;
            
            if (fullName.value.trim() === '') {
                fullName.classList.add('is-invalid');
                isValid = false;
            }
            
            if (email.value === '' || !email.validity.valid) {
                email.classList.add('is-invalid');
                isValid = false;
            }
            
            if (password.value.length < 8) {
                password.classList.add('is-invalid');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
                form.classList.add('was-validated');
            }
        });

        // Initialize edit email modal
        document.getElementById('editEmailModal').addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var userId = button.getAttribute('data-userid');
            var currentEmail = button.getAttribute('data-currentemail');
            var fullName = button.closest('tr').querySelector('td:nth-child(2)').textContent;
            
            var modal = this;
            modal.querySelector('#email_user_id').value = userId;
            modal.querySelector('#new_email').value = currentEmail;
            modal.querySelector('#display_full_name').textContent = fullName;
        });

        // Initialize password change modal
        document.getElementById('changePasswordModal').addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var userId = button.getAttribute('data-userid');
            
            var modal = this;
            modal.querySelector('#password_user_id').value = userId;
            modal.querySelector('#new_password').value = '';
            modal.querySelector('#confirm_password').value = '';
        });

        // Initialize delete confirmation modal
        document.getElementById('confirmDeleteModal').addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var userId = button.getAttribute('data-userid');
            
            var modal = this;
            modal.querySelector('#delete_user_id').value = userId;
        });

        // Password confirmation validation
        const passwordForm = document.getElementById('passwordChangeForm');
        const newPassword = document.getElementById('new_password');
        const confirmPassword = document.getElementById('confirm_password');

        passwordForm.addEventListener('submit', function(e) {
            if (newPassword.value !== confirmPassword.value) {
                e.preventDefault();
                confirmPassword.classList.add('is-invalid');
            }
        });

        newPassword.addEventListener('input', validatePassword);
        confirmPassword.addEventListener('input', validatePassword);

        function validatePassword() {
            if (newPassword.value === confirmPassword.value) {
                confirmPassword.classList.remove('is-invalid');
            } else {
                confirmPassword.classList.add('is-invalid');
            }
        }

        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentNode.querySelector('input');
                const icon = this.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            });
        });
    </script>
</body>
</html>