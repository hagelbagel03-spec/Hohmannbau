<?php
/**
 * Chat Messages API Endpoint
 * Handle chat messages from website visitors
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate required fields
        $required_fields = ['visitor_name', 'visitor_email', 'message'];
        
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
                http_response_code(400);
                echo json_encode(['error' => "Feld '$field' ist erforderlich"]);
                exit;
            }
        }
        
        // Validate email
        if (!validateEmail($_POST['visitor_email'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Ungültige E-Mail-Adresse']);
            exit;
        }
        
        $db = getDB();
        
        $stmt = $db->prepare("
            INSERT INTO chat_messages (id, visitor_name, visitor_email, message)
            VALUES (?, ?, ?, ?)
        ");
        
        $id = generateUUID();
        $result = $stmt->execute([
            $id,
            sanitizeInput($_POST['visitor_name']),
            sanitizeInput($_POST['visitor_email']),
            sanitizeInput($_POST['message'])
        ]);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Ihre Nachricht wurde erfolgreich übermittelt',
                'id' => $id
            ]);
        } else {
            throw new Exception('Fehler beim Speichern der Nachricht');
        }
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get chat buttons for public display
    try {
        $db = getDB();
        $buttons = $db->query("SELECT * FROM chat_buttons WHERE active = 1 ORDER BY `order`, label")->fetchAll();
        
        echo json_encode([
            'success' => true,
            'buttons' => $buttons
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>