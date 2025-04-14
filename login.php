<?php
session_start();
include 'db.php';

// Auto-fill new user email if redirected from creation
if (isset($_GET['new_user']) && isset($_SESSION['new_user_email'])) {
    $preset_email = $_SESSION['new_user_email'];
    unset($_SESSION['new_user_email']);
} else {
    $preset_email = '';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false;

    $stmt = $conn->prepare("SELECT id, email, password, role, full_name FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (hash("sha256", $password) == $user["password"]) {
            // Set session variables
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["full_name"] = $user["full_name"] ?? $user["email"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["role"] = $user["role"];

            // Set cookie if "Remember Me" is checked
            if ($remember) {
                $cookie_value = [
                    'user_id' => $user["id"],
                    'token' => bin2hex(random_bytes(16)), // Generate random token
                    'expires' => time() + (30 * 24 * 60 * 60) // 30 days
                ];
                
                // Store token in database
                $token_hash = hash("sha256", $cookie_value['token']);
                $expires = date('Y-m-d H:i:s', $cookie_value['expires']);
                
                $stmt = $conn->prepare("INSERT INTO remember_tokens (user_id, token_hash, expires_at) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $user["id"], $token_hash, $expires);
                $stmt->execute();
                
                // Set cookie
                setcookie(
                    'remember_me',
                    json_encode($cookie_value),
                    $cookie_value['expires'],
                    '/',
                    '',
                    isset($_SERVER['HTTPS']), // Secure if HTTPS
                    true // HttpOnly
                );
            }

            // Redirect based on role
            if ($user["role"] == "admin") {
                header("Location: dashboard.php");
            } else {
                header("Location: user_dashboard.php");
            }
            exit();
        } else {
            $error = "Invalid credentials";
        }
    } else {
        $error = "Invalid credentials";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SCA Arabica Cupping Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Add this new style for the QR code button and modal */
        .qr-code-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            cursor: pointer;
            z-index: 1000;
        }
        
        .qr-code-btn:hover {
            background-color: var(--dark-color);
            transform: scale(1.1);
        }
        
        .qr-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.8);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }
        
        .qr-modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            max-width: 90%;
            max-height: 90%;
        }
        
        .qr-modal-content img {
            max-width: 300px;
            max-height: 300px;
            margin-bottom: 15px;
        }
        
        .close-qr-modal {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .close-qr-modal:hover {
            background-color: var(--dark-color);
        }
    </style>
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-brand">
            <img src="img/image-removebg-preview.png" alt="Specialty Coffee Depot" class="logo">
            <h1>SCA Arabica Cupping Form</h1>
        </div>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="POST" class="login-form">
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" class="form-control" id="email" name="email" placeholder="User/Admin Email" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                    <button class="btn btn-outline-secondary toggle-password" type="button">
                        <i class="far fa-eye"></i>
                    </button>
                </div>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2">
                <i class="fas fa-sign-in-alt me-2"></i> Login
            </button>
        </form>
    </div>

    <!-- QR Code Button -->
    <div class="qr-code-btn" id="qrCodeBtn">
        <i class="fas fa-qrcode fa-lg"></i>
    </div>
    
    <!-- QR Code Modal -->
    <div class="qr-modal" id="qrModal">
        <div class="qr-modal-content">
            <img src="img/Untitled.jpg" alt="QR Code">
            <p>Scan this QR code to access the login page on your mobile device</p>
            <button class="close-qr-modal" id="closeQrModal">Close</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const passwordInput = this.previousElementSibling;
                const icon = this.querySelector('i');
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            });
        });
        
        // QR Code Modal functionality
        const qrCodeBtn = document.getElementById('qrCodeBtn');
        const qrModal = document.getElementById('qrModal');
        const closeQrModal = document.getElementById('closeQrModal');
        
        qrCodeBtn.addEventListener('click', function() {
            qrModal.style.display = 'flex';
        });
        
        closeQrModal.addEventListener('click', function() {
            qrModal.style.display = 'none';
        });
        
        // Close modal when clicking outside the content
        qrModal.addEventListener('click', function(e) {
            if (e.target === qrModal) {
                qrModal.style.display = 'none';
            }
        });
    </script>
</body>
</html>