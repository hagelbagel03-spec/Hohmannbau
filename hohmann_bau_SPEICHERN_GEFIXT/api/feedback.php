<?php
/**
 * Feedback API Endpoint
 * Handle citizen feedback and ratings
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate required fields
        $required_fields = ['name', 'email', 'subject', 'message', 'rating'];
        
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
                http_response_code(400);
                echo json_encode(['error' => "Feld '$field' ist erforderlich"]);
                exit;
            }
        }
        
        // Validate email
        if (!validateEmail($_POST['email'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Ungültige E-Mail-Adresse']);
            exit;
        }
        
        // Validate rating
        $rating = intval($_POST['rating']);
        if ($rating < 1 || $rating > 5) {
            http_response_code(400);
            echo json_encode(['error' => 'Bewertung muss zwischen 1 und 5 liegen']);
            exit;
        }
        
        $db = getDB();
        
        $stmt = $db->prepare("
            INSERT INTO feedback (id, name, email, subject, message, rating)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $id = generateUUID();
        $result = $stmt->execute([
            $id,
            sanitizeInput($_POST['name']),
            sanitizeInput($_POST['email']),
            sanitizeInput($_POST['subject']),
            sanitizeInput($_POST['message']),
            $rating
        ]);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Ihr Feedback wurde erfolgreich übermittelt',
                'id' => $id
            ]);
        } else {
            throw new Exception('Fehler beim Speichern des Feedbacks');
        }
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>