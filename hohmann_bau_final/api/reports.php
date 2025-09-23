<?php
/**
 * Reports API Endpoint
 * Handle incident reports submission
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
        $required_fields = ['incident_type', 'description', 'location', 'incident_date', 'incident_time', 'reporter_name', 'reporter_email', 'reporter_phone'];
        
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
                http_response_code(400);
                echo json_encode(['error' => "Feld '$field' ist erforderlich"]);
                exit;
            }
        }
        
        // Validate email
        if (!validateEmail($_POST['reporter_email'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Ungültige E-Mail-Adresse']);
            exit;
        }
        
        $db = getDB();
        
        $stmt = $db->prepare("
            INSERT INTO reports (
                id, incident_type, description, location, incident_date, incident_time,
                reporter_name, reporter_email, reporter_phone, is_witness, witnesses_present,
                witness_details, evidence_available, evidence_description, additional_info
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $id = generateUUID();
        $result = $stmt->execute([
            $id,
            sanitizeInput($_POST['incident_type']),
            sanitizeInput($_POST['description']),
            sanitizeInput($_POST['location']),
            $_POST['incident_date'],
            $_POST['incident_time'],
            sanitizeInput($_POST['reporter_name']),
            sanitizeInput($_POST['reporter_email']),
            sanitizeInput($_POST['reporter_phone']),
            isset($_POST['is_witness']) ? 1 : 0,
            isset($_POST['witnesses_present']) ? 1 : 0,
            isset($_POST['witness_details']) ? sanitizeInput($_POST['witness_details']) : null,
            isset($_POST['evidence_available']) ? 1 : 0,
            isset($_POST['evidence_description']) ? sanitizeInput($_POST['evidence_description']) : null,
            isset($_POST['additional_info']) ? sanitizeInput($_POST['additional_info']) : null
        ]);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Ihr Bericht wurde erfolgreich übermittelt',
                'id' => $id
            ]);
        } else {
            throw new Exception('Fehler beim Speichern des Berichts');
        }
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get incident types
    echo json_encode([
        'incident_types' => [
            'Diebstahl',
            'Einbruch', 
            'Vandalismus',
            'Verkehrsunfall',
            'Ruhestörung',
            'Betrug',
            'Körperverletzung',
            'Sachbeschädigung',
            'Verdächtige Aktivität',
            'Andere'
        ]
    ]);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>