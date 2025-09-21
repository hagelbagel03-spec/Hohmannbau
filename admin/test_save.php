<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/PageContent.php';

echo "<h1>Save Test</h1>";

// Test database connection
try {
    $database = new Database();
    $pageContent = new PageContent($database);
    echo "<p style='color: green;'>✅ Datenbank-Verbindung OK</p>";
    
    // Test save
    $testContent = [
        'test_field' => 'Test Wert ' . date('Y-m-d H:i:s'),
        'another_field' => 'Noch ein Test'
    ];
    
    $result = $pageContent->saveContent('test_page', $testContent);
    
    if ($result) {
        echo "<p style='color: green;'>✅ Speichern funktioniert!</p>";
        
        // Test load
        $loaded = $pageContent->getContent('test_page');
        echo "<p>Geladene Daten:</p>";
        echo "<pre>" . print_r($loaded, true) . "</pre>";
    } else {
        echo "<p style='color: red;'>❌ Speichern fehlgeschlagen</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Fehler: " . $e->getMessage() . "</p>";
}

// Test AJAX endpoint
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    if ($_POST['action'] === 'test_save') {
        try {
            $database = new Database();
            $pageContent = new PageContent($database);
            
            $result = $pageContent->saveContent('ajax_test', [
                'ajax_field' => 'AJAX Test ' . date('Y-m-d H:i:s')
            ]);
            
            echo json_encode(['success' => $result, 'message' => 'AJAX Test OK']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit();
    }
}
?>

<script>
function testAjaxSave() {
    fetch('test_save.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=test_save'
    })
    .then(response => response.json())
    .then(data => {
        alert('AJAX Test: ' + JSON.stringify(data));
    })
    .catch(error => {
        alert('AJAX Fehler: ' + error);
    });
}
</script>

<button onclick="testAjaxSave()">AJAX Save Test</button>