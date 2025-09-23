<?php
/**
 * Erweiterte Text- und Bildbearbeitung für alle Inhalte
 */

session_start();
if (!isset($_SESSION['admin_id']) && !isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

require_once '../config/database.php';

$db = getDB();
$message = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    try {
        switch ($action) {
            case 'update_navigation':
                $stmt = $db->prepare("UPDATE homepage SET nav_home_text = ?, nav_about_text = ?, nav_services_text = ?, nav_team_text = ?, nav_careers_text = ?, nav_news_text = ?, nav_contact_text = ? WHERE id = '1'");
                $stmt->execute([
                    $_POST['nav_home_text'],
                    $_POST['nav_about_text'], 
                    $_POST['nav_services_text'],
                    $_POST['nav_team_text'],
                    $_POST['nav_careers_text'],
                    $_POST['nav_news_text'],
                    $_POST['nav_contact_text']
                ]);
                $message = 'Navigation erfolgreich aktualisiert!';
                break;
                
            case 'update_company_info':
                $stmt = $db->prepare("UPDATE homepage SET company_name = ?, company_tagline = ?, hero_title = ?, hero_subtitle = ? WHERE id = '1'");
                $stmt->execute([
                    $_POST['company_name'],
                    $_POST['company_tagline'],
                    $_POST['hero_title'],
                    $_POST['hero_subtitle']
                ]);
                $message = 'Firmen-Informationen erfolgreich aktualisiert!';
                break;
                
            case 'update_sections':
                $stmt = $db->prepare("UPDATE homepage SET 
                    services_section_title = ?, services_section_description = ?,
                    team_section_title = ?, team_section_description = ?,
                    news_section_title = ?, news_section_description = ?,
                    cta_section_title = ?, cta_section_description = ?,
                    cta_button_text = ?, cta_phone_button_text = ?
                    WHERE id = '1'");
                $stmt->execute([
                    $_POST['services_section_title'],
                    $_POST['services_section_description'],
                    $_POST['team_section_title'],
                    $_POST['team_section_description'],
                    $_POST['news_section_title'],
                    $_POST['news_section_description'],
                    $_POST['cta_section_title'],
                    $_POST['cta_section_description'],
                    $_POST['cta_button_text'],
                    $_POST['cta_phone_button_text']
                ]);
                $message = 'Sektions-Texte erfolgreich aktualisiert!';
                break;
                
            case 'update_trust_indicators':
                $stmt = $db->prepare("UPDATE homepage SET 
                    trust_indicator_1_value = ?, trust_indicator_1_label = ?,
                    trust_indicator_2_value = ?, trust_indicator_2_label = ?,
                    trust_indicator_3_value = ?, trust_indicator_3_label = ?
                    WHERE id = '1'");
                $stmt->execute([
                    $_POST['trust_indicator_1_value'],
                    $_POST['trust_indicator_1_label'],
                    $_POST['trust_indicator_2_value'],
                    $_POST['trust_indicator_2_label'],
                    $_POST['trust_indicator_3_value'],
                    $_POST['trust_indicator_3_label']
                ]);
                $message = 'Vertrauens-Indikatoren erfolgreich aktualisiert!';
                break;
                
            case 'update_service_text':
                $stmt = $db->prepare("UPDATE services SET title = ?, description = ? WHERE id = ?");
                $stmt->execute([
                    $_POST['service_title'],
                    $_POST['service_description'],
                    $_POST['service_id']
                ]);
                $message = 'Service-Text erfolgreich aktualisiert!';
                break;
                
            case 'update_team_text':
                $stmt = $db->prepare("UPDATE team SET name = ?, position = ?, description = ? WHERE id = ?");
                $stmt->execute([
                    $_POST['team_name'],
                    $_POST['team_position'],
                    $_POST['team_description'],
                    $_POST['team_id']
                ]);
                $message = 'Team-Text erfolgreich aktualisiert!';
                break;
        }
    } catch (Exception $e) {
        $error = 'Fehler beim Speichern: ' . $e->getMessage();
    }
}

// Get current data
try {
    $homepage = $db->query("SELECT * FROM homepage WHERE id = '1'")->fetch();
    $services = $db->query("SELECT * FROM services ORDER BY `order`, title")->fetchAll();
    $team = $db->query("SELECT * FROM team ORDER BY `order`, name")->fetchAll();
} catch (Exception $e) {
    $error = 'Fehler beim Laden der Daten: ' . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Text-Editor - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .section-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            margin-bottom: 2rem;
        }
        
        .section-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 12px 12px 0 0;
            font-weight: 600;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }
        
        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s ease;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }
        
        .form-textarea {
            min-height: 80px;
            resize: vertical;
        }
        
        .btn-save {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .btn-save:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        
        .alert-success {
            background: #d1fae5;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }
        
        .alert-error {
            background: #fee2e2;
            border: 1px solid #fca5a5;
            color: #991b1b;
        }
    </style>
</head>
<body class="bg-gray-50">

<div class="flex h-screen">
    <!-- Sidebar -->
    <div class="w-64 bg-white shadow-lg">
        <div class="p-6 border-b bg-gradient-to-r from-green-600 to-green-700 text-white">
            <h1 class="text-xl font-bold flex items-center">
                <i class="fas fa-edit mr-2"></i>
                Text-Editor
            </h1>
        </div>
        <nav class="p-4">
            <ul class="space-y-2">
                <li><a href="#navigation" class="block p-2 rounded hover:bg-gray-100" onclick="showSection('navigation')">📱 Navigation</a></li>
                <li><a href="#company" class="block p-2 rounded hover:bg-gray-100" onclick="showSection('company')">🏢 Firma</a></li>
                <li><a href="#sections" class="block p-2 rounded hover:bg-gray-100" onclick="showSection('sections')">📄 Sektionen</a></li>
                <li><a href="#trust" class="block p-2 rounded hover:bg-gray-100" onclick="showSection('trust')">⭐ Vertrauen</a></li>
                <li><a href="#services" class="block p-2 rounded hover:bg-gray-100" onclick="showSection('services')">🛠️ Services</a></li>
                <li><a href="#team" class="block p-2 rounded hover:bg-gray-100" onclick="showSection('team')">👥 Team</a></li>
                <li><a href="index.php" class="block p-2 rounded hover:bg-gray-100">← Dashboard</a></li>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8 overflow-y-auto">
        <?php if ($message): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle mr-2"></i>
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Navigation Section -->
        <div id="navigation-section" class="section">
            <div class="section-card">
                <div class="section-header">
                    <i class="fas fa-bars mr-2"></i>
                    Navigation bearbeiten
                </div>
                <div class="p-6">
                    <form method="POST">
                        <input type="hidden" name="action" value="update_navigation">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="form-group">
                                <label class="form-label">Home</label>
                                <input type="text" name="nav_home_text" value="<?php echo htmlspecialchars($homepage['nav_home_text'] ?? 'Home'); ?>" class="form-input">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Über uns</label>
                                <input type="text" name="nav_about_text" value="<?php echo htmlspecialchars($homepage['nav_about_text'] ?? 'Über uns'); ?>" class="form-input">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Leistungen</label>
                                <input type="text" name="nav_services_text" value="<?php echo htmlspecialchars($homepage['nav_services_text'] ?? 'Leistungen'); ?>" class="form-input">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Team</label>
                                <input type="text" name="nav_team_text" value="<?php echo htmlspecialchars($homepage['nav_team_text'] ?? 'Team'); ?>" class="form-input">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Karriere</label>
                                <input type="text" name="nav_careers_text" value="<?php echo htmlspecialchars($homepage['nav_careers_text'] ?? 'Karriere'); ?>" class="form-input">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Aktuelles</label>
                                <input type="text" name="nav_news_text" value="<?php echo htmlspecialchars($homepage['nav_news_text'] ?? 'Aktuelles'); ?>" class="form-input">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Kontakt</label>
                                <input type="text" name="nav_contact_text" value="<?php echo htmlspecialchars($homepage['nav_contact_text'] ?? 'Kontakt'); ?>" class="form-input">
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-save">
                            <i class="fas fa-save mr-2"></i>
                            Navigation speichern
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Company Section -->
        <div id="company-section" class="section hidden">
            <div class="section-card">
                <div class="section-header">
                    <i class="fas fa-building mr-2"></i>
                    Firmen-Informationen bearbeiten
                </div>
                <div class="p-6">
                    <form method="POST">
                        <input type="hidden" name="action" value="update_company_info">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label class="form-label">Firmenname</label>
                                <input type="text" name="company_name" value="<?php echo htmlspecialchars($homepage['company_name'] ?? 'Hohmann Bau'); ?>" class="form-input">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Untertitel/Tagline</label>
                                <input type="text" name="company_tagline" value="<?php echo htmlspecialchars($homepage['company_tagline'] ?? 'Garten & Landschaftsbau'); ?>" class="form-input">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Hero Haupttitel</label>
                                <input type="text" name="hero_title" value="<?php echo htmlspecialchars($homepage['hero_title']); ?>" class="form-input">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Hero Untertitel</label>
                                <textarea name="hero_subtitle" class="form-input form-textarea"><?php echo htmlspecialchars($homepage['hero_subtitle']); ?></textarea>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-save">
                            <i class="fas fa-save mr-2"></i>
                            Firmen-Info speichern
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sections Section -->
        <div id="sections-section" class="section hidden">
            <div class="section-card">
                <div class="section-header">
                    <i class="fas fa-list mr-2"></i>
                    Sektions-Texte bearbeiten
                </div>
                <div class="p-6">
                    <form method="POST">
                        <input type="hidden" name="action" value="update_sections">
                        
                        <!-- Services Sektion -->
                        <h3 class="text-lg font-semibold mb-4 text-green-700">🛠️ Services Sektion</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div class="form-group">
                                <label class="form-label">Titel</label>
                                <input type="text" name="services_section_title" value="<?php echo htmlspecialchars($homepage['services_section_title'] ?? 'Unsere Leistungen'); ?>" class="form-input">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Beschreibung</label>
                                <textarea name="services_section_description" class="form-input form-textarea"><?php echo htmlspecialchars($homepage['services_section_description'] ?? ''); ?></textarea>
                            </div>
                        </div>
                        
                        <!-- Team Sektion -->
                        <h3 class="text-lg font-semibold mb-4 text-green-700">👥 Team Sektion</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div class="form-group">
                                <label class="form-label">Titel</label>
                                <input type="text" name="team_section_title" value="<?php echo htmlspecialchars($homepage['team_section_title'] ?? 'Unser Team'); ?>" class="form-input">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Beschreibung</label>
                                <textarea name="team_section_description" class="form-input form-textarea"><?php echo htmlspecialchars($homepage['team_section_description'] ?? ''); ?></textarea>
                            </div>
                        </div>
                        
                        <!-- News Sektion -->
                        <h3 class="text-lg font-semibold mb-4 text-green-700">📰 News Sektion</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div class="form-group">
                                <label class="form-label">Titel</label>
                                <input type="text" name="news_section_title" value="<?php echo htmlspecialchars($homepage['news_section_title'] ?? 'Aktuelles'); ?>" class="form-input">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Beschreibung</label>
                                <textarea name="news_section_description" class="form-input form-textarea"><?php echo htmlspecialchars($homepage['news_section_description'] ?? ''); ?></textarea>
                            </div>
                        </div>
                        
                        <!-- CTA Sektion -->
                        <h3 class="text-lg font-semibold mb-4 text-green-700">🎯 Call-to-Action Sektion</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div class="form-group">
                                <label class="form-label">Titel</label>
                                <input type="text" name="cta_section_title" value="<?php echo htmlspecialchars($homepage['cta_section_title'] ?? 'Bereit für Ihren Traumgarten?'); ?>" class="form-input">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Beschreibung</label>
                                <textarea name="cta_section_description" class="form-input form-textarea"><?php echo htmlspecialchars($homepage['cta_section_description'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Button Text</label>
                                <input type="text" name="cta_button_text" value="<?php echo htmlspecialchars($homepage['cta_button_text'] ?? 'Beratungstermin vereinbaren'); ?>" class="form-input">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Telefon Button Text</label>
                                <input type="text" name="cta_phone_button_text" value="<?php echo htmlspecialchars($homepage['cta_phone_button_text'] ?? 'Jetzt anrufen'); ?>" class="form-input">
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-save">
                            <i class="fas fa-save mr-2"></i>
                            Sektions-Texte speichern
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Trust Indicators Section -->
        <div id="trust-section" class="section hidden">
            <div class="section-card">
                <div class="section-header">
                    <i class="fas fa-star mr-2"></i>
                    Vertrauens-Indikatoren bearbeiten
                </div>
                <div class="p-6">
                    <form method="POST">
                        <input type="hidden" name="action" value="update_trust_indicators">
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-semibold mb-3 text-green-700">Indikator 1</h4>
                                <div class="form-group">
                                    <label class="form-label">Wert</label>
                                    <input type="text" name="trust_indicator_1_value" value="<?php echo htmlspecialchars($homepage['trust_indicator_1_value'] ?? '25+'); ?>" class="form-input">
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Beschreibung</label>
                                    <input type="text" name="trust_indicator_1_label" value="<?php echo htmlspecialchars($homepage['trust_indicator_1_label'] ?? 'Jahre Erfahrung'); ?>" class="form-input">
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-semibold mb-3 text-green-700">Indikator 2</h4>
                                <div class="form-group">
                                    <label class="form-label">Wert</label>
                                    <input type="text" name="trust_indicator_2_value" value="<?php echo htmlspecialchars($homepage['trust_indicator_2_value'] ?? '150+'); ?>" class="form-input">
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Beschreibung</label>
                                    <input type="text" name="trust_indicator_2_label" value="<?php echo htmlspecialchars($homepage['trust_indicator_2_label'] ?? 'Projekte/Jahr'); ?>" class="form-input">
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-semibold mb-3 text-green-700">Indikator 3</h4>
                                <div class="form-group">
                                    <label class="form-label">Wert</label>
                                    <input type="text" name="trust_indicator_3_value" value="<?php echo htmlspecialchars($homepage['trust_indicator_3_value'] ?? '98%'); ?>" class="form-input">
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Beschreibung</label>
                                    <input type="text" name="trust_indicator_3_label" value="<?php echo htmlspecialchars($homepage['trust_indicator_3_label'] ?? 'Zufriedenheit'); ?>" class="form-input">
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-save mt-4">
                            <i class="fas fa-save mr-2"></i>
                            Vertrauens-Indikatoren speichern
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Services Section -->
        <div id="services-section" class="section hidden">
            <div class="section-card">
                <div class="section-header">
                    <i class="fas fa-tools mr-2"></i>
                    Service-Texte bearbeiten
                </div>
                <div class="p-6">
                    <?php foreach ($services as $service): ?>
                        <div class="bg-gray-50 p-4 rounded-lg mb-4">
                            <form method="POST" class="space-y-4">
                                <input type="hidden" name="action" value="update_service_text">
                                <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="form-group">
                                        <label class="form-label">Service Titel</label>
                                        <input type="text" name="service_title" value="<?php echo htmlspecialchars($service['title']); ?>" class="form-input">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Service Beschreibung</label>
                                        <textarea name="service_description" class="form-input form-textarea"><?php echo htmlspecialchars($service['description']); ?></textarea>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn-save">
                                    <i class="fas fa-save mr-2"></i>
                                    Service speichern
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Team Section -->
        <div id="team-section" class="section hidden">
            <div class="section-card">
                <div class="section-header">
                    <i class="fas fa-users mr-2"></i>
                    Team-Texte bearbeiten
                </div>
                <div class="p-6">
                    <?php foreach ($team as $member): ?>
                        <div class="bg-gray-50 p-4 rounded-lg mb-4">
                            <form method="POST" class="space-y-4">
                                <input type="hidden" name="action" value="update_team_text">
                                <input type="hidden" name="team_id" value="<?php echo $member['id']; ?>">
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="form-group">
                                        <label class="form-label">Name</label>
                                        <input type="text" name="team_name" value="<?php echo htmlspecialchars($member['name']); ?>" class="form-input">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Position</label>
                                        <input type="text" name="team_position" value="<?php echo htmlspecialchars($member['position']); ?>" class="form-input">
                                    </div>
                                    
                                    <div class="form-group md:col-span-2">
                                        <label class="form-label">Beschreibung</label>
                                        <textarea name="team_description" class="form-input form-textarea"><?php echo htmlspecialchars($member['description']); ?></textarea>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn-save">
                                    <i class="fas fa-save mr-2"></i>
                                    Team-Mitglied speichern
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showSection(sectionName) {
    // Hide all sections
    document.querySelectorAll('.section').forEach(section => {
        section.classList.add('hidden');
    });
    
    // Show selected section
    document.getElementById(sectionName + '-section').classList.remove('hidden');
    
    // Update active nav
    document.querySelectorAll('nav a').forEach(link => {
        link.classList.remove('bg-green-100', 'text-green-700');
    });
    
    const activeLink = document.querySelector(`nav a[href="#${sectionName}"]`);
    if (activeLink) {
        activeLink.classList.add('bg-green-100', 'text-green-700');
    }
}

// Show navigation section by default
document.addEventListener('DOMContentLoaded', function() {
    showSection('navigation');
});

// Form enhancement
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Wird gespeichert...';
            submitBtn.disabled = true;
            
            // Re-enable after 3 seconds as fallback
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 3000);
        }
    });
});
</script>

</body>
</html>