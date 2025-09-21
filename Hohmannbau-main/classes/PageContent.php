<?php
class PageContent {
    private $db;
    
    public function __construct($database) {
        if ($database) {
            $this->db = $database->getConnection();
        }
    }
    
    public function getContent($pageName) {
        if (!$this->db) return [];
        
        try {
            $stmt = $this->db->prepare("SELECT content FROM page_contents WHERE page_name = ?");
            $stmt->execute([$pageName]);
            $result = $stmt->fetch();
            
            if ($result) {
                return json_decode($result['content'], true) ?: [];
            }
        } catch (Exception $e) {
            error_log("Error getting content for $pageName: " . $e->getMessage());
        }
        
        return [];
    }
    
    public function saveContent($pageName, $content) {
        if (!$this->db) return false;
        
        try {
            $contentJson = json_encode($content, JSON_UNESCAPED_UNICODE);
            
            // Check if exists
            $stmt = $this->db->prepare("SELECT id FROM page_contents WHERE page_name = ?");
            $stmt->execute([$pageName]);
            
            if ($stmt->fetch()) {
                // Update existing
                $stmt = $this->db->prepare("UPDATE page_contents SET content = ?, updated_at = NOW() WHERE page_name = ?");
                $stmt->execute([$contentJson, $pageName]);
            } else {
                // Insert new
                $stmt = $this->db->prepare("INSERT INTO page_contents (id, page_name, content) VALUES (?, ?, ?)");
                $stmt->execute([uniqid(), $pageName, $contentJson]);
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Error saving page content: " . $e->getMessage());
            return false;
        }
    }
    
    public function getDesignSettings($settingType) {
        if (!$this->db) return [];
        
        try {
            // Create design_settings table if not exists
            $this->db->exec("CREATE TABLE IF NOT EXISTS design_settings (
                id VARCHAR(255) PRIMARY KEY,
                setting_type VARCHAR(50) NOT NULL UNIQUE,
                settings JSON NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )");
            
            $stmt = $this->db->prepare("SELECT settings FROM design_settings WHERE setting_type = ?");
            $stmt->execute([$settingType]);
            $result = $stmt->fetch();
            
            if ($result) {
                return json_decode($result['settings'], true) ?: [];
            }
        } catch (Exception $e) {
            error_log("Error getting design settings for $settingType: " . $e->getMessage());
        }
        
        return [];
    }
    
    public function saveDesignSettings($settingType, $settings) {
        if (!$this->db) return false;
        
        try {
            // Create design_settings table if not exists
            $this->db->exec("CREATE TABLE IF NOT EXISTS design_settings (
                id VARCHAR(255) PRIMARY KEY,
                setting_type VARCHAR(50) NOT NULL UNIQUE,
                settings JSON NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )");
            
            $settingsJson = json_encode($settings, JSON_UNESCAPED_UNICODE);
            
            // Check if exists
            $stmt = $this->db->prepare("SELECT id FROM design_settings WHERE setting_type = ?");
            $stmt->execute([$settingType]);
            
            if ($stmt->fetch()) {
                // Update existing
                $stmt = $this->db->prepare("UPDATE design_settings SET settings = ?, updated_at = NOW() WHERE setting_type = ?");
                $stmt->execute([$settingsJson, $settingType]);
            } else {
                // Insert new
                $stmt = $this->db->prepare("INSERT INTO design_settings (id, setting_type, settings) VALUES (?, ?, ?)");
                $stmt->execute([uniqid(), $settingType, $settingsJson]);
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Error saving design settings: " . $e->getMessage());
            return false;
        }
    }
    
    public function getAllPages() {
        if (!$this->db) return [];
        
        try {
            $stmt = $this->db->query("SELECT page_name, content, updated_at FROM page_contents ORDER BY page_name");
            $pages = [];
            
            while ($row = $stmt->fetch()) {
                $pages[] = [
                    'page_name' => $row['page_name'],
                    'content' => json_decode($row['content'], true) ?: [],
                    'updated_at' => $row['updated_at']
                ];
            }
            
            return $pages;
        } catch (Exception $e) {
            error_log("Error getting all pages: " . $e->getMessage());
            return [];
        }
    }
    
    public function getProjects() {
        if (!$this->db) return [];
        
        try {
            $stmt = $this->db->query("SELECT * FROM projects ORDER BY created_at DESC");
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error getting projects: " . $e->getMessage());
            return [];
        }
    }
    
    public function saveProject($data) {
        if (!$this->db) return false;
        
        try {
            if (isset($data['id']) && $data['id']) {
                // Update existing
                $stmt = $this->db->prepare("UPDATE projects SET title = ?, category = ?, description = ?, image_url = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([$data['title'], $data['category'], $data['description'], $data['image_url'], $data['id']]);
            } else {
                // Insert new
                $stmt = $this->db->prepare("INSERT INTO projects (id, title, category, description, image_url) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([uniqid(), $data['title'], $data['category'], $data['description'], $data['image_url']]);
            }
            return true;
        } catch (Exception $e) {
            error_log("Error saving project: " . $e->getMessage());
            return false;
        }
    }
    
    public function deleteProject($id) {
        if (!$this->db) return false;
        
        try {
            $stmt = $this->db->prepare("DELETE FROM projects WHERE id = ?");
            $stmt->execute([$id]);
            return true;
        } catch (Exception $e) {
            error_log("Error deleting project: " . $e->getMessage());
            return false;
        }
    }
    
    public function getContactMessages() {
        if (!$this->db) return [];
        
        try {
            $stmt = $this->db->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error getting contact messages: " . $e->getMessage());
            return [];
        }
    }
    
    public function saveContactMessage($data) {
        if (!$this->db) return false;
        
        try {
            $stmt = $this->db->prepare("INSERT INTO contact_messages (id, name, email, message) VALUES (?, ?, ?, ?)");
            $stmt->execute([uniqid(), $data['name'], $data['email'], $data['message']]);
            return true;
        } catch (Exception $e) {
            error_log("Error saving contact message: " . $e->getMessage());
            return false;
        }
    }
    
    public function getJobPostings() {
        if (!$this->db) return [];
        
        try {
            $stmt = $this->db->query("SELECT * FROM job_postings ORDER BY created_at DESC");
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error getting job postings: " . $e->getMessage());
            return [];
        }
    }
    
    public function saveJobPosting($data) {
        if (!$this->db) return false;
        
        try {
            if (isset($data['id']) && $data['id']) {
                // Update existing
                $stmt = $this->db->prepare("UPDATE job_postings SET title = ?, description = ?, requirements = ?, location = ?, employment_type = ?, is_active = ? WHERE id = ?");
                $stmt->execute([$data['title'], $data['description'], $data['requirements'], $data['location'], $data['employment_type'], $data['is_active'] ?? 1, $data['id']]);
            } else {
                // Insert new
                $stmt = $this->db->prepare("INSERT INTO job_postings (id, title, description, requirements, location, employment_type, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([uniqid(), $data['title'], $data['description'], $data['requirements'], $data['location'], $data['employment_type'], $data['is_active'] ?? 1]);
            }
            return true;
        } catch (Exception $e) {
            error_log("Error saving job posting: " . $e->getMessage());
            return false;
        }
    }
    
    public function getApplications() {
        if (!$this->db) return [];
        
        try {
            $stmt = $this->db->query("SELECT a.*, j.title as job_title FROM applications a LEFT JOIN job_postings j ON a.job_id = j.id ORDER BY a.created_at DESC");
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error getting applications: " . $e->getMessage());
            return [];
        }
    }
    
    public function getTeamMembers() {
        if (!$this->db) return [];
        
        try {
            $stmt = $this->db->query("SELECT * FROM team_members ORDER BY created_at ASC");
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error getting team members: " . $e->getMessage());
            return [];
        }
    }
    
    public function saveTeamMember($data) {
        if (!$this->db) return false;
        
        try {
            if (isset($data['id']) && $data['id']) {
                // Update existing
                $stmt = $this->db->prepare("UPDATE team_members SET name = ?, role = ?, image_url = ?, bio = ? WHERE id = ?");
                $stmt->execute([$data['name'], $data['role'], $data['image_url'], $data['bio'], $data['id']]);
            } else {
                // Insert new
                $stmt = $this->db->prepare("INSERT INTO team_members (id, name, role, image_url, bio) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([uniqid(), $data['name'], $data['role'], $data['image_url'], $data['bio']]);
            }
            return true;
        } catch (Exception $e) {
            error_log("Error saving team member: " . $e->getMessage());
            return false;
        }
    }
    
    public function uploadMedia($file) {
        try {
            $uploadDir = __DIR__ . '/../uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx'];
            
            if (!in_array($fileExtension, $allowedTypes)) {
                throw new Exception('Dateityp nicht erlaubt');
            }
            
            $fileName = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file['name']);
            $filePath = $uploadDir . $fileName;
            
            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                // Save to database
                if ($this->db) {
                    // Create media_files table if not exists
                    $this->db->exec("CREATE TABLE IF NOT EXISTS media_files (
                        id VARCHAR(255) PRIMARY KEY,
                        filename VARCHAR(255) NOT NULL,
                        original_name VARCHAR(255) NOT NULL,
                        file_path VARCHAR(500) NOT NULL,
                        file_type VARCHAR(50) NOT NULL,
                        file_size INT NOT NULL,
                        mime_type VARCHAR(100) NOT NULL,
                        uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                    )");
                    
                    $stmt = $this->db->prepare("
                        INSERT INTO media_files (id, filename, original_name, file_path, file_type, file_size, mime_type) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)
                    ");
                    
                    $fileType = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']) ? 'image' : 'document';
                    
                    $stmt->execute([
                        uniqid(),
                        $fileName,
                        $file['name'],
                        $filePath,
                        $fileType,
                        $file['size'],
                        $file['type']
                    ]);
                }
                
                return [
                    'success' => true,
                    'filename' => $fileName,
                    'url' => 'uploads/' . $fileName
                ];
            } else {
                throw new Exception('Fehler beim Hochladen der Datei');
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    public function getMediaFiles() {
        if (!$this->db) return [];
        
        try {
            // Create media_files table if not exists
            $this->db->exec("CREATE TABLE IF NOT EXISTS media_files (
                id VARCHAR(255) PRIMARY KEY,
                filename VARCHAR(255) NOT NULL,
                original_name VARCHAR(255) NOT NULL,
                file_path VARCHAR(500) NOT NULL,
                file_type VARCHAR(50) NOT NULL,
                file_size INT NOT NULL,
                mime_type VARCHAR(100) NOT NULL,
                uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");
            
            $stmt = $this->db->query("SELECT * FROM media_files ORDER BY uploaded_at DESC");
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error getting media files: " . $e->getMessage());
            return [];
        }
    }
    
    public function deleteMediaFile($id) {
        if (!$this->db) return false;
        
        try {
            // Get file info first
            $stmt = $this->db->prepare("SELECT file_path FROM media_files WHERE id = ?");
            $stmt->execute([$id]);
            $file = $stmt->fetch();
            
            if ($file && file_exists($file['file_path'])) {
                unlink($file['file_path']);
            }
            
            // Delete from database
            $stmt = $this->db->prepare("DELETE FROM media_files WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            error_log("Error deleting media file: " . $e->getMessage());
            return false;
        }
    }
    
    public function generateCSS() {
        $theme = $this->getDesignSettings('theme') ?: [];
        $typography = $this->getDesignSettings('typography') ?: [];
        $layout = $this->getDesignSettings('layout') ?: [];
        
        $css = ":root {\n";
        
        // Theme colors
        if (!empty($theme['primary_color'])) {
            $css .= "  --primary-color: {$theme['primary_color']};\n";
        }
        if (!empty($theme['secondary_color'])) {
            $css .= "  --secondary-color: {$theme['secondary_color']};\n";
        }
        if (!empty($theme['accent_color'])) {
            $css .= "  --accent-color: {$theme['accent_color']};\n";
        }
        
        // Typography
        if (!empty($typography['font_family'])) {
            $css .= "  --font-family: {$typography['font_family']};\n";
        }
        if (!empty($typography['heading_font'])) {
            $css .= "  --heading-font: {$typography['heading_font']};\n";
        }
        if (!empty($typography['font_size_base'])) {
            $css .= "  --font-size-base: {$typography['font_size_base']};\n";
        }
        
        // Layout
        if (!empty($layout['container_width'])) {
            $css .= "  --container-width: {$layout['container_width']};\n";
        }
        if (!empty($layout['section_padding'])) {
            $css .= "  --section-padding: {$layout['section_padding']};\n";
        }
        
        $css .= "}\n\n";
        
        // Apply variables
        $css .= "body {\n";
        $css .= "  font-family: var(--font-family, 'Inter, system-ui, sans-serif');\n";
        $css .= "  font-size: var(--font-size-base, 16px);\n";
        $css .= "}\n\n";
        
        $css .= "h1, h2, h3, h4, h5, h6 {\n";
        $css .= "  font-family: var(--heading-font, 'Inter, system-ui, sans-serif');\n";
        $css .= "}\n\n";
        
        $css .= ".primary-color { color: var(--primary-color, #16a34a); }\n";
        $css .= ".bg-primary { background-color: var(--primary-color, #16a34a); }\n";
        $css .= ".secondary-color { color: var(--secondary-color, #059669); }\n";
        $css .= ".bg-secondary { background-color: var(--secondary-color, #059669); }\n";
        
        return $css;
    }
    
    // Dashboard Statistics
    public function getDashboardStats() {
        if (!$this->db) return [];
        
        try {
            $stats = [];
            
            // Count various entities
            $tables = [
                'projects' => 'Projekte',
                'contact_messages' => 'Kontaktnachrichten', 
                'job_postings' => 'Stellenausschreibungen',
                'applications' => 'Bewerbungen',
                'team_members' => 'Team Mitglieder'
            ];
            
            foreach ($tables as $table => $label) {
                try {
                    $stmt = $this->db->query("SELECT COUNT(*) as count FROM $table");
                    $result = $stmt->fetch();
                    $stats[$table] = $result ? $result['count'] : 0;
                } catch (Exception $e) {
                    $stats[$table] = 0;
                }
            }
            
            return $stats;
        } catch (Exception $e) {
            error_log("Error getting dashboard stats: " . $e->getMessage());
            return [];
        }
    }
}
?>