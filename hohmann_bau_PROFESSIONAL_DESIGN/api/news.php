<?php
/**
 * News API Endpoint
 * Get published news articles
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $db = getDB();
        
        // Check if specific article is requested
        if (isset($_GET['id'])) {
            $stmt = $db->prepare("SELECT * FROM news WHERE id = ? AND published = 1");
            $stmt->execute([sanitizeInput($_GET['id'])]);
            $article = $stmt->fetch();
            
            if ($article) {
                echo json_encode([
                    'success' => true,
                    'article' => $article
                ]);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Artikel nicht gefunden']);
            }
        } else {
            // Get all published news
            $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
            $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
            
            $stmt = $db->prepare("SELECT * FROM news WHERE published = 1 ORDER BY date DESC LIMIT ? OFFSET ?");
            $stmt->execute([$limit, $offset]);
            $articles = $stmt->fetchAll();
            
            // Get total count
            $total_stmt = $db->prepare("SELECT COUNT(*) as total FROM news WHERE published = 1");
            $total_stmt->execute();
            $total = $total_stmt->fetch()['total'];
            
            echo json_encode([
                'success' => true,
                'articles' => $articles,
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset
            ]);
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