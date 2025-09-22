<?php
/**
 * Applications API Endpoint
 * Handle job applications with CV upload
 */

// Set JSON header immediately
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(0);
}

// Error handling function
function jsonError($message, $code = 400) {
    http_response_code($code);
    echo json_encode(['success' => false, 'error' => $message]);
    exit;
}

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonError('Method not allowed', 405);
}

try {
    require_once '../config/database.php';
} catch (Exception $e) {
    jsonError('Database connection failed: ' . $e->getMessage(), 500);
}

try {
    // Validate required fields
    $required_fields = ['name', 'email', 'phone', 'position', 'message'];
    
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            jsonError("Feld '$field' ist erforderlich");
        }
    }
    
    // Validate email
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        jsonError('Ungültige E-Mail-Adresse');
    }
    
    // Handle CV file upload
    $cv_filename = null;
    if (isset($_FILES['cv_file']) && $_FILES['cv_file']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['pdf', 'doc', 'docx'];
        $file_extension = strtolower(pathinfo($_FILES['cv_file']['name'], PATHINFO_EXTENSION));
        
        if (!in_array($file_extension, $allowed_types)) {
            jsonError('Nur PDF, DOC und DOCX Dateien sind erlaubt');
        }
        
        // Check file size (max 5MB)
        if ($_FILES['cv_file']['size'] > 5 * 1024 * 1024) {
            jsonError('Datei ist zu groß (max. 5MB)');
        }
        
        $cv_filename = 'cv_' . uniqid() . '.' . $file_extension;
        $upload_path = '../uploads/' . $cv_filename;
        
        // Create uploads directory if it doesn't exist
        if (!is_dir('../uploads/')) {
            mkdir('../uploads/', 0777, true);
        }
        
        if (!move_uploaded_file($_FILES['cv_file']['tmp_name'], $upload_path)) {
            jsonError('Fehler beim Hochladen der Datei', 500);
        }
    }
    
    $db = getDB();
    
    $stmt = $db->prepare("
        INSERT INTO applications (id, name, email, phone, position, message, cv_filename)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    $id = 'app_' . uniqid();
    $result = $stmt->execute([
        $id,
        trim($_POST['name']),
        trim($_POST['email']),
        trim($_POST['phone']),
        trim($_POST['position']),
        trim($_POST['message']),
        $cv_filename
    ]);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Ihre Bewerbung wurde erfolgreich übermittelt',
            'id' => $id
        ]);
    } else {
        jsonError('Fehler beim Speichern der Bewerbung', 500);
    }
    
} catch (Exception $e) {
    error_log('Application submission error: ' . $e->getMessage());
    jsonError('Ein unerwarteter Fehler ist aufgetreten', 500);
}
?>