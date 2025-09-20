<?php
class ApiHandler {
    private $pdo;
    private $database;
    
    public function __construct($database, $pdo) {
        $this->database = $database;
        $this->pdo = $pdo;
    }
    
    public function handleRequest($method, $endpoint, $data = null) {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        
        if ($method === 'OPTIONS') {
            return;
        }
        
        try {
            switch($endpoint) {
                case 'content':
                    return $this->handleContent($method, $data);
                case 'features':
                    return $this->handleFeatures($method, $data);
                case 'contact-info':
                    return $this->handleContactInfo($method, $data);
                case 'contact':
                    return $this->handleContact($method, $data);
                case 'projects':
                    return $this->handleProjects($method, $data);
                case 'team':
                    return $this->handleTeam($method, $data);
                case 'jobs':
                    return $this->handleJobs($method, $data);
                case 'applications':
                    return $this->handleApplications($method, $data);
                case 'quote-request':
                    return $this->handleQuoteRequest($method, $data);
                case 'health':
                    return json_encode(['status' => 'healthy', 'timestamp' => date('c')]);
                default:
                    http_response_code(404);
                    return json_encode(['error' => 'Endpoint not found']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            return json_encode(['error' => $e->getMessage()]);
        }
    }
    
    private function handleContent($method, $data) {
        $pageContent = new PageContent($this->pdo);
        
        if ($method === 'GET' && isset($_GET['page'])) {
            $result = $pageContent->getContent($_GET['page']);
            return json_encode($result ?: ['message' => 'Content not found']);
        }
        
        if ($method === 'POST' && $data) {
            // Check if content exists, update or create
            $existing = $pageContent->getContent($data['page_name']);
            if ($existing) {
                $result = $pageContent->updateContent($data['page_name'], $data['content']);
            } else {
                $result = $pageContent->createContent($data['page_name'], $data['content']);
            }
            
            if ($result) {
                $updated = $pageContent->getContent($data['page_name']);
                return json_encode($updated);
            } else {
                http_response_code(500);
                return json_encode(['error' => 'Failed to save content']);
            }
        }
        
        return json_encode(['error' => 'Invalid request']);
    }
    
    private function handleFeatures($method, $data) {
        if ($method === 'GET') {
            $stmt = $this->pdo->prepare("SELECT * FROM features WHERE is_active = 1 ORDER BY order_num");
            $stmt->execute();
            $features = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return json_encode($features);
        }
        return json_encode(['error' => 'Method not allowed']);
    }
    
    private function handleContactInfo($method, $data) {
        if ($method === 'GET') {
            $stmt = $this->pdo->prepare("SELECT * FROM contact_info LIMIT 1");
            $stmt->execute();
            $info = $stmt->fetch(PDO::FETCH_ASSOC);
            return json_encode($info ?: [
                'address' => 'Bahnhofstraße 123, 12345 Musterstadt',
                'phone' => '+49 123 456 789',
                'email' => 'info@hohmann-bau.de',
                'opening_hours' => 'Mo-Fr: 08:00-17:00 Uhr'
            ]);
        }
        return json_encode(['error' => 'Method not allowed']);
    }
    
    private function handleContact($method, $data) {
        if ($method === 'POST') {
            $id = $this->database->generateUUID();
            $stmt = $this->pdo->prepare("INSERT INTO contact_messages (id, name, email, message) VALUES (?, ?, ?, ?)");
            $stmt->execute([$id, $data['name'], $data['email'], $data['message']]);
            
            return json_encode([
                'id' => $id,
                'name' => $data['name'],
                'email' => $data['email'],
                'message' => $data['message'],
                'created_at' => date('c')
            ]);
        }
        return json_encode(['error' => 'Method not allowed']);
    }
    
    private function handleProjects($method, $data) {
        if ($method === 'GET') {
            $stmt = $this->pdo->prepare("SELECT * FROM projects ORDER BY created_at DESC");
            $stmt->execute();
            $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return json_encode($projects);
        }
        return json_encode(['error' => 'Method not allowed']);
    }
    
    private function handleTeam($method, $data) {
        if ($method === 'GET') {
            $stmt = $this->pdo->prepare("SELECT * FROM team_members ORDER BY created_at DESC");
            $stmt->execute();
            $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return json_encode($members);
        }
        return json_encode(['error' => 'Method not allowed']);
    }
    
    private function handleJobs($method, $data) {
        if ($method === 'GET') {
            $stmt = $this->pdo->prepare("SELECT * FROM job_postings WHERE is_active = 1 ORDER BY created_at DESC");
            $stmt->execute();
            $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return json_encode($jobs);
        }
        return json_encode(['error' => 'Method not allowed']);
    }
    
    private function handleApplications($method, $data) {
        if ($method === 'POST') {
            $id = $this->database->generateUUID();
            $cvFilename = null;
            
            // Handle file upload if present
            if (isset($_FILES['cv_file']) && $_FILES['cv_file']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../uploads/cv/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $cvFilename = $id . '_' . $_FILES['cv_file']['name'];
                $uploadPath = $uploadDir . $cvFilename;
                
                if (move_uploaded_file($_FILES['cv_file']['tmp_name'], $uploadPath)) {
                    // File uploaded successfully
                } else {
                    $cvFilename = null;
                }
            }
            
            $stmt = $this->pdo->prepare("INSERT INTO applications (id, job_id, name, email, phone, cover_letter, cv_filename) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $id,
                $_POST['job_id'],
                $_POST['name'],
                $_POST['email'],
                $_POST['phone'] ?? null,
                $_POST['cover_letter'],
                $cvFilename
            ]);
            
            return json_encode([
                'id' => $id,
                'message' => 'Bewerbung erfolgreich eingereicht'
            ]);
        }
        return json_encode(['error' => 'Method not allowed']);
    }
    
    private function handleQuoteRequest($method, $data) {
        if ($method === 'POST') {
            $id = $this->database->generateUUID();
            $filePath = null;
            
            // Handle file upload if present
            if (isset($_FILES['blueprint_file']) && $_FILES['blueprint_file']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../uploads/blueprints/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $filename = $id . '_' . $_FILES['blueprint_file']['name'];
                $uploadPath = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['blueprint_file']['tmp_name'], $uploadPath)) {
                    $filePath = 'uploads/blueprints/' . $filename;
                }
            }
            
            $stmt = $this->pdo->prepare("INSERT INTO quote_requests (id, name, email, phone, project_type, description, budget_range, timeline, file_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $id,
                $_POST['name'],
                $_POST['email'],
                $_POST['phone'] ?? null,
                $_POST['project_type'],
                $_POST['description'],
                $_POST['budget_range'] ?? null,
                $_POST['timeline'] ?? null,
                $filePath
            ]);
            
            return json_encode([
                'id' => $id,
                'message' => 'Angebots-Anfrage erfolgreich gesendet'
            ]);
        }
        return json_encode(['error' => 'Method not allowed']);
    }
}
?>