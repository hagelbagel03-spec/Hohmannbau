<?php
class PageContent {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function getContent($pageName) {
        $stmt = $this->pdo->prepare("SELECT * FROM page_contents WHERE page_name = ?");
        $stmt->execute([$pageName]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            $result['content'] = json_decode($result['content'], true);
            return $result;
        }
        return null;
    }
    
    public function updateContent($pageName, $content) {
        $contentJson = json_encode($content);
        $stmt = $this->pdo->prepare("UPDATE page_contents SET content = ?, updated_at = NOW() WHERE page_name = ?");
        return $stmt->execute([$contentJson, $pageName]);
    }
    
    public function createContent($pageName, $content) {
        $id = $this->generateUUID();
        $contentJson = json_encode($content);
        $stmt = $this->pdo->prepare("INSERT INTO page_contents (id, page_name, content) VALUES (?, ?, ?)");
        return $stmt->execute([$id, $pageName, $contentJson]);
    }
    
    private function generateUUID() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}
?>