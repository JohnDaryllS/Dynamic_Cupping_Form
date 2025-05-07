<?php
session_start();
require 'db.php';

// Check if user is not logged in but has a remember me cookie
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me'])) {
    $cookie_data = json_decode($_COOKIE['remember_me'], true);
    
    if ($cookie_data && isset($cookie_data['user_id'], $cookie_data['token'], $cookie_data['expires'])) {
        // Verify token hasn't expired
        if (time() < $cookie_data['expires']) {
            // Verify token in database
            $token_hash = hash("sha256", $cookie_data['token']);
            $stmt = $conn->prepare("SELECT user_id, expires_at FROM remember_tokens 
                                  WHERE user_id = ? AND token_hash = ? AND expires_at > NOW()");
            $stmt->bind_param("is", $cookie_data['user_id'], $token_hash);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows == 1) {
                $token = $result->fetch_assoc();
                
                // Get user data
                $stmt = $conn->prepare("SELECT id, email, role, full_name FROM users WHERE id = ?");
                $stmt->bind_param("i", $token['user_id']);
                $stmt->execute();
                $user = $stmt->get_result()->fetch_assoc();
                
                if ($user) {
                    // Regenerate session ID for security
                    session_regenerate_id(true);
                    
                    // Set session variables
                    $_SESSION["user_id"] = $user["id"];
                    $_SESSION["full_name"] = $user["full_name"] ?? $user["email"];
                    $_SESSION["email"] = $user["email"];
                    $_SESSION["role"] = $user["role"];
                    
                    // Update token expiration (optional)
                    $new_expires = time() + (30 * 24 * 60 * 60);
                    $stmt = $conn->prepare("UPDATE remember_tokens SET expires_at = ? 
                                          WHERE user_id = ? AND token_hash = ?");
                    $new_expires_date = date('Y-m-d H:i:s', $new_expires);
                    $stmt->bind_param("sis", $new_expires_date, 
                                     $user["id"], $token_hash);
                    $stmt->execute();
                    
                    // Update cookie
                    $cookie_data['expires'] = $new_expires;
                    setcookie(
                        'remember_me',
                        json_encode($cookie_data),
                        $new_expires,
                        '/',
                        '',
                        isset($_SERVER['HTTPS']),
                        true
                    );
                }
            }
        }
    }
}
?>