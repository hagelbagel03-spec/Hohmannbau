<?php
class PageContent {
    private $db;
    
    public function __construct($database) {
        if ($database) {
            $this->db = $database->getConnection();
            $this->createTables();
        }
    }
    
    private function createTables() {
        if (!$this->db) return;
        
        try {
            // Create page_contents table
            $sql = "CREATE TABLE IF NOT EXISTS page_contents (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                page_name VARCHAR(50) NOT NULL UNIQUE,
                content TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )";
            $this->db->exec($sql);
            
            // Create design_settings table
            $sql = "CREATE TABLE IF NOT EXISTS design_settings (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                setting_type VARCHAR(50) NOT NULL UNIQUE,
                settings TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )";
            $this->db->exec($sql);
            
            // Create media_files table
            $sql = "CREATE TABLE IF NOT EXISTS media_files (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                filename VARCHAR(255) NOT NULL,
                original_name VARCHAR(255) NOT NULL,
                file_path VARCHAR(500) NOT NULL,
                file_type VARCHAR(50) NOT NULL,
                file_size INTEGER NOT NULL,
                mime_type VARCHAR(100) NOT NULL,
                uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )";
            $this->db->exec($sql);
            
            // Insert default content if not exists
            $this->insertDefaultContent();
        } catch (Exception $e) {
            error_log("Error creating tables: " . $e->getMessage());
        }
    }
    
    private function insertDefaultContent() {
        if (!$this->db) return;
        
        $defaultPages = [
            'home' => [
                'hero_title' => 'Bauen mit Vertrauen',
                'hero_subtitle' => 'Ihr zuverlässiger Partner für Hochbau, Tiefbau und Sanierungen',
                'hero_image' => 'https://images.unsplash.com/photo-1599995903128-531fc7fb694b?crop=entropy&cs=srgb&fm=jpg&ixid=M3w3NDk1Nzl8MHwxfHNlYXJjaHwyfHxjb25zdHJ1Y3Rpb24lMjBzaXRlfGVufDB8fHx8MTc1ODM3ODEyMHww&ixlib=rb-4.1.0&q=85',
                'hero_cta_text' => 'Jetzt Angebot anfordern',
                'about_title' => 'Über uns',
                'about_text' => 'Mit über 25 Jahren Erfahrung sind wir Ihr vertrauensvoller Partner für alle Bauprojekte. Wir stehen für Qualität, Zuverlässigkeit und termingerechte Ausführung.'
            ],
            'services' => [
                'title' => 'Unsere Leistungen',
                'subtitle' => 'Umfassende Baulösungen aus einer Hand',
                'description' => 'Von der ersten Idee bis zur schlüsselfertigen Übergabe begleiten wir Sie durch Ihr gesamtes Bauvorhaben. Unsere erfahrenen Fachkräfte garantieren höchste Qualität und termingerechte Ausführung.'
            ],
            'projects' => [
                'title' => 'Unsere Projekte',
                'subtitle' => 'Referenzen aus verschiedenen Bereichen',
                'description' => 'Entdecken Sie unsere erfolgreich abgeschlossenen Bauprojekte und lassen Sie sich von der Vielfalt und Qualität unserer Arbeit überzeugen.'
            ],
            'team' => [
                'title' => 'Unser Team',
                'subtitle' => 'Erfahrene Fachkräfte für Ihr Projekt',
                'description' => 'Lernen Sie die Menschen kennen, die hinter unseren erfolgreichen Bauprojekten stehen. Unser erfahrenes Team aus Ingenieuren, Architekten und Baupleitern bringt jahrzehntelange Expertise mit.'
            ],
            'contact' => [
                'title' => 'Kontakt',
                'subtitle' => 'Lassen Sie uns über Ihr Projekt sprechen',
                'description' => 'Haben Sie Fragen zu unseren Leistungen oder möchten Sie ein Projekt mit uns besprechen? Wir freuen uns auf Ihre Nachricht und melden uns schnellstmöglich bei Ihnen.',
                'address' => 'Bahnhofstraße 123, 12345 Musterstadt',
                'phone' => '+49 123 456 789',
                'email' => 'info@hohmann-bau.de',
                'opening_hours' => 'Mo-Fr: 08:00-17:00 Uhr'
            ],
            'career' => [
                'title' => 'Karriere',
                'subtitle' => 'Werden Sie Teil unseres Teams',
                'description' => 'Wir suchen motivierte Fachkräfte, die mit uns gemeinsam die Zukunft des Bauens gestalten möchten. Entdecken Sie vielfältige Karrieremöglichkeiten in einem innovativen Unternehmen.'
            ],
            'footer' => [
                'company_name' => 'Hohmann Bau GmbH',
                'company_description' => 'Ihr Partner für professionelle Bauprojekte',
                'copyright' => '© 2024 Hohmann Bau GmbH. Alle Rechte vorbehalten.'
            ],
            'navigation' => [
                'logo_text' => 'Hohmann Bau',
                'cta_button_text' => 'Angebot erhalten'
            ]
        ];
        
        foreach ($defaultPages as $pageName => $content) {
            try {
                $stmt = $this->db->prepare("SELECT id FROM page_contents WHERE page_name = ?");
                $stmt->execute([$pageName]);
                
                if (!$stmt->fetch()) {
                    $this->saveContent($pageName, $content);
                }
            } catch (Exception $e) {
                error_log("Error inserting default content for $pageName: " . $e->getMessage());
            }
        }
        
        // Insert default design settings
        $defaultDesignSettings = [
            'theme' => [
                'primary_color' => '#16a34a',
                'secondary_color' => '#059669',
                'accent_color' => '#10b981',
                'background_color' => '#ffffff',
                'text_color' => '#1f2937',
                'border_color' => '#e5e7eb'
            ],
            'typography' => [
                'font_family' => 'Inter, system-ui, sans-serif',
                'heading_font' => 'Inter, system-ui, sans-serif',
                'font_size_base' => '16px',
                'font_size_lg' => '18px',
                'font_size_xl' => '20px',
                'line_height' => '1.6'
            ],
            'layout' => [
                'container_width' => '1200px',
                'section_padding' => '80px',
                'card_border_radius' => '8px',
                'button_border_radius' => '6px'
            ]
        ];
        
        foreach ($defaultDesignSettings as $settingType => $settings) {
            try {
                $stmt = $this->db->prepare("SELECT id FROM design_settings WHERE setting_type = ?");
                $stmt->execute([$settingType]);
                
                if (!$stmt->fetch()) {
                    $this->saveDesignSettings($settingType, $settings);
                }
            } catch (Exception $e) {
                error_log("Error inserting default design settings for $settingType: " . $e->getMessage());
            }
        }
    }
    
    public function getContent($pageName) {
        if (!$this->db) return [];
        
        try {
            $stmt = $this->db->prepare("SELECT content FROM page_contents WHERE page_name = ?");
            $stmt->execute([$pageName]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
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
            
            // Try to update first
            $stmt = $this->db->prepare("UPDATE page_contents SET content = ?, updated_at = CURRENT_TIMESTAMP WHERE page_name = ?");
            $stmt->execute([$contentJson, $pageName]);
            
            // If no rows affected, insert new
            if ($stmt->rowCount() == 0) {
                $stmt = $this->db->prepare("INSERT INTO page_contents (page_name, content) VALUES (?, ?)");
                $stmt->execute([$pageName, $contentJson]);
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
            $stmt = $this->db->prepare("SELECT settings FROM design_settings WHERE setting_type = ?");
            $stmt->execute([$settingType]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
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
            $settingsJson = json_encode($settings, JSON_UNESCAPED_UNICODE);
            
            // Try to update first
            $stmt = $this->db->prepare("UPDATE design_settings SET settings = ?, updated_at = CURRENT_TIMESTAMP WHERE setting_type = ?");
            $stmt->execute([$settingsJson, $settingType]);
            
            // If no rows affected, insert new
            if ($stmt->rowCount() == 0) {
                $stmt = $this->db->prepare("INSERT INTO design_settings (setting_type, settings) VALUES (?, ?)");
                $stmt->execute([$settingType, $settingsJson]);
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
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
                    $stmt = $this->db->prepare("
                        INSERT INTO media_files (filename, original_name, file_path, file_type, file_size, mime_type) 
                        VALUES (?, ?, ?, ?, ?, ?)
                    ");
                    
                    $fileType = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']) ? 'image' : 'document';
                    
                    $stmt->execute([
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
            $stmt = $this->db->query("SELECT * FROM media_files ORDER BY uploaded_at DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            $file = $stmt->fetch(PDO::FETCH_ASSOC);
            
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
}
?>