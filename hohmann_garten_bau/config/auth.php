<?php
/**
 * Authentication System
 * Simple session-based authentication for admin panel
 */

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class Auth {
    
    /**
     * Check if user is logged in
     */
    public static function isLoggedIn() {
        return isset($_SESSION['admin_id']) && isset($_SESSION['admin_username']);
    }
    
    /**
     * Get current admin user
     */
    public static function getCurrentAdmin() {
        if (self::isLoggedIn()) {
            return [
                'id' => $_SESSION['admin_id'],
                'username' => $_SESSION['admin_username'],
                'email' => $_SESSION['admin_email'] ?? ''
            ];
        }
        return null;
    }
    
    /**
     * Login admin user
     */
    public static function login($username, $password) {
        $db = getDB();
        
        try {
            $stmt = $db->prepare("SELECT id, username, email, hashed_password FROM admins WHERE username = ?");
            $stmt->execute([$username]);
            $admin = $stmt->fetch();
            
            if ($admin) {
                // Check if password is already hashed or plain text
                if (password_verify($password, $admin['hashed_password']) || $admin['hashed_password'] === $password) {
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_username'] = $admin['username'];
                    $_SESSION['admin_email'] = $admin['email'];
                    $_SESSION['login_time'] = time();
                    
                    return true;
                }
            }
            
            return false;
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Logout admin user
     */
    public static function logout() {
        session_unset();
        session_destroy();
    }
    
    /**
     * Require authentication (redirect to login if not authenticated)
     */
    public static function requireAuth() {
        if (!self::isLoggedIn()) {
            header('Location: login.php');
            exit;
        }
        
        // Check for session timeout (24 hours)
        $timeout = 24 * 60 * 60; // 24 hours in seconds
        if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > $timeout) {
            self::logout();
            header('Location: login.php?timeout=1');
            exit;
        }
    }
    
    /**
     * Generate CSRF token
     */
    public static function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Verify CSRF token
     */
    public static function verifyCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}

/**
 * Helper function to require authentication
 */
function requireAuth() {
    Auth::requireAuth();
}

/**
 * Helper function to check if user is logged in
 */
function isLoggedIn() {
    return Auth::isLoggedIn();
}

/**
 * Helper function to get current admin
 */
function getCurrentAdmin() {
    return Auth::getCurrentAdmin();
}
?>