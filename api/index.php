<?php
require_once __DIR__ . '/../config/config.php';

$method = $_SERVER['REQUEST_METHOD'];
$path = trim($_SERVER['PATH_INFO'] ?? '', '/');
$pathParts = explode('/', $path);

// API-Endpoint verarbeiten
$endpoint = $pathParts[0] ?? '';
$param = $pathParts[1] ?? null;

// Daten für POST/PUT Requests
$data = null;
if ($method === 'POST' || $method === 'PUT') {
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (strpos($contentType, 'application/json') !== false) {
        $data = json_decode(file_get_contents('php://input'), true);
    } else {
        $data = $_POST;
    }
}

// Spezielle Behandlung für content-Endpoint mit Parameter
if ($endpoint === 'content' && $param) {
    $_GET['page'] = $param;
}

$apiHandler = new ApiHandler($database, $pdo);
echo $apiHandler->handleRequest($method, $endpoint, $data);
?>