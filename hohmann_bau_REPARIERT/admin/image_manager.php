<?php
/**
 * Bilder-Manager für Upload und Verwaltung
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

// Stelle sicher, dass Upload-Verzeichnisse existieren
$uploadDirs = [
    '../uploads',
    '../uploads/hero',
    '../uploads/gallery',
    '../uploads/services',
    '../uploads/team'
];

foreach ($uploadDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['upload_file'])) {
    $category = $_POST['upload_category'] ?? 'general';
    $altText = $_POST['alt_text'] ?? '';
    
    $file = $_FILES['upload_file'];
    
    if ($file['error'] === UPLOAD_ERR_OK) {
        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (in_array($file['type'], $allowedTypes)) {
            
            // Generate unique filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '_' . time() . '.' . $extension;
            $targetDir = "../uploads/$category/";
            $targetPath = $targetDir . $filename;
            $webPath = "/uploads/$category/" . $filename;
            
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                // Save to database
                try {
                    $uploadId = uniqid();
                    $stmt = $db->prepare("INSERT INTO uploads (id, original_name, file_path, file_size, mime_type, category, alt_text) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([
                        $uploadId,
                        $file['name'],
                        $webPath,
                        $file['size'],
                        $file['type'],
                        $category,
                        $altText
                    ]);
                    
                    $message = 'Bild erfolgreich hochgeladen!';
                } catch (Exception $e) {
                    $error = 'Fehler beim Speichern in Datenbank: ' . $e->getMessage();
                    unlink($targetPath); // Delete file if DB insert fails
                }
            } else {
                $error = 'Fehler beim Hochladen der Datei.';
            }
        } else {
            $error = 'Nur Bild-Dateien sind erlaubt (JPEG, PNG, GIF, WebP).';
        }
    } else {
        $error = 'Fehler beim Datei-Upload: ' . $file['error'];
    }
}

// Handle image assignment to homepage fields
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['assign_image'])) {
    $imageId = $_POST['image_id'];
    $field = $_POST['homepage_field'];
    
    try {
        // Get image path
        $stmt = $db->prepare("SELECT file_path FROM uploads WHERE id = ?");
        $stmt->execute([$imageId]);
        $image = $stmt->fetch();
        
        if ($image) {
            // Update homepage field
            $updateFields = [
                'hero_background_image' => 'UPDATE homepage SET hero_background_image = ? WHERE id = "1"',
                'about_image' => 'UPDATE homepage SET about_image = ? WHERE id = "1"',
                'gallery_image_1' => 'UPDATE homepage SET gallery_image_1 = ? WHERE id = "1"',
                'gallery_image_2' => 'UPDATE homepage SET gallery_image_2 = ? WHERE id = "1"',
                'gallery_image_3' => 'UPDATE homepage SET gallery_image_3 = ? WHERE id = "1"',
                'gallery_image_4' => 'UPDATE homepage SET gallery_image_4 = ? WHERE id = "1"'
            ];
            
            if (isset($updateFields[$field])) {
                $stmt = $db->prepare($updateFields[$field]);
                $stmt->execute([$image['file_path']]);
                $message = 'Bild erfolgreich zugewiesen!';
            }
        }
    } catch (Exception $e) {
        $error = 'Fehler beim Zuweisen des Bildes: ' . $e->getMessage();
    }
}

// Get uploaded images
try {
    $uploads = $db->query("SELECT * FROM uploads ORDER BY uploaded_at DESC")->fetchAll();
    $homepage = $db->query("SELECT * FROM homepage WHERE id = '1'")->fetch();
} catch (Exception $e) {
    $error = 'Fehler beim Laden der Bilder: ' . $e->getMessage();
    $uploads = [];
    $homepage = [];
}

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bilder-Manager - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .image-thumbnail {
            width: 150px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .upload-zone {
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            background: #f9fafb;
        }
        
        .upload-zone:hover {
            border-color: #10b981;
            background: #f0fdf4;
        }
        
        .upload-zone.dragover {
            border-color: #10b981;
            background: #ecfdf5;
        }
        
        .btn-assign {
            background: #3b82f6;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 12px;
        }
        
        .btn-assign:hover {
            background: #2563eb;
        }
        
        .current-image {
            border: 3px solid #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        }
    </style>
</head>
<body class="bg-gray-50">

<div class="flex h-screen">
    <!-- Sidebar -->
    <div class="w-64 bg-white shadow-lg">
        <div class="p-6 border-b bg-gradient-to-r from-blue-600 to-blue-700 text-white">
            <h1 class="text-xl font-bold flex items-center">
                <i class="fas fa-images mr-2"></i>
                Bilder-Manager
            </h1>
        </div>
        <nav class="p-4">
            <ul class="space-y-2">
                <li><a href="#upload" class="block p-2 rounded hover:bg-gray-100" onclick="showSection('upload')">📤 Upload</a></li>
                <li><a href="#gallery" class="block p-2 rounded hover:bg-gray-100" onclick="showSection('gallery')">🖼️ Galerie</a></li>
                <li><a href="#assignments" class="block p-2 rounded hover:bg-gray-100" onclick="showSection('assignments')">🔗 Zuweisungen</a></li>
                <li><a href="index.php" class="block p-2 rounded hover:bg-gray-100">← Dashboard</a></li>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8 overflow-y-auto">
        <?php if ($message): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <i class="fas fa-check-circle mr-2"></i>
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Upload Section -->
        <div id="upload-section" class="section">
            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                <h2 class="text-2xl font-bold mb-6 flex items-center text-blue-700">
                    <i class="fas fa-upload mr-3"></i>
                    Neue Bilder hochladen
                </h2>
                
                <form method="POST" enctype="multipart/form-data" class="space-y-6">
                    <div class="upload-zone" id="uploadZone">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                        <p class="text-lg font-semibold text-gray-700 mb-2">Bilder hier ablegen oder klicken zum Auswählen</p>
                        <p class="text-sm text-gray-500">JPEG, PNG, GIF, WebP bis 10MB</p>
                        <input type="file" name="upload_file" id="fileInput" class="hidden" accept="image/*" required>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kategorie</label>
                            <select name="upload_category" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option value="hero">Hero-Bereich</option>
                                <option value="gallery">Galerie</option>
                                <option value="services">Services</option>
                                <option value="team">Team</option>
                                <option value="general">Allgemein</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alt-Text (für SEO)</label>
                            <input type="text" name="alt_text" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Beschreibung des Bildes">
                        </div>
                    </div>
                    
                    <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 flex items-center">
                        <i class="fas fa-upload mr-2"></i>
                        Bild hochladen
                    </button>
                </form>
            </div>
        </div>

        <!-- Gallery Section -->
        <div id="gallery-section" class="section hidden">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold mb-6 flex items-center text-blue-700">
                    <i class="fas fa-images mr-3"></i>
                    Alle hochgeladenen Bilder
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <?php foreach ($uploads as $upload): ?>
                        <div class="border rounded-lg p-4 hover:shadow-lg transition-shadow">
                            <img src="<?php echo htmlspecialchars($upload['file_path']); ?>" 
                                 alt="<?php echo htmlspecialchars($upload['alt_text']); ?>" 
                                 class="image-thumbnail w-full mb-3">
                            
                            <div class="space-y-2">
                                <p class="font-semibold text-sm truncate"><?php echo htmlspecialchars($upload['original_name']); ?></p>
                                <p class="text-xs text-gray-600">
                                    <span class="inline-block bg-gray-100 px-2 py-1 rounded"><?php echo $upload['category']; ?></span>
                                </p>
                                <p class="text-xs text-gray-500">
                                    <?php echo number_format($upload['file_size'] / 1024, 1); ?> KB
                                </p>
                                
                                <!-- Assignment buttons -->
                                <div class="space-y-1">
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="image_id" value="<?php echo $upload['id']; ?>">
                                        <input type="hidden" name="homepage_field" value="hero_background_image">
                                        <button type="submit" name="assign_image" class="btn-assign w-full">Hero setzen</button>
                                    </form>
                                    
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="image_id" value="<?php echo $upload['id']; ?>">
                                        <input type="hidden" name="homepage_field" value="gallery_image_1">
                                        <button type="submit" name="assign_image" class="btn-assign w-full">Galerie 1</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if (empty($uploads)): ?>
                    <div class="text-center py-12">
                        <i class="fas fa-image text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Noch keine Bilder hochgeladen</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Assignments Section -->
        <div id="assignments-section" class="section hidden">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold mb-6 flex items-center text-blue-700">
                    <i class="fas fa-link mr-3"></i>
                    Aktuelle Bild-Zuweisungen
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="border rounded-lg p-4">
                        <h3 class="font-semibold mb-3">Hero Hintergrundbild</h3>
                        <?php if ($homepage['hero_background_image']): ?>
                            <img src="<?php echo htmlspecialchars($homepage['hero_background_image']); ?>" class="image-thumbnail w-full current-image">
                            <p class="text-sm text-green-600 mt-2">✓ Zugewiesen</p>
                        <?php else: ?>
                            <div class="image-thumbnail w-full bg-gray-100 flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-sm text-gray-500 mt-2">Kein Bild zugewiesen</p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="border rounded-lg p-4">
                        <h3 class="font-semibold mb-3">Über uns Bild</h3>
                        <?php if ($homepage['about_image']): ?>
                            <img src="<?php echo htmlspecialchars($homepage['about_image']); ?>" class="image-thumbnail w-full current-image">
                            <p class="text-sm text-green-600 mt-2">✓ Zugewiesen</p>
                        <?php else: ?>
                            <div class="image-thumbnail w-full bg-gray-100 flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-sm text-gray-500 mt-2">Kein Bild zugewiesen</p>
                        <?php endif; ?>
                    </div>
                    
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                        <div class="border rounded-lg p-4">
                            <h3 class="font-semibold mb-3">Galerie Bild <?php echo $i; ?></h3>
                            <?php 
                            $galleryField = "gallery_image_$i";
                            if ($homepage[$galleryField]): ?>
                                <img src="<?php echo htmlspecialchars($homepage[$galleryField]); ?>" class="image-thumbnail w-full current-image">
                                <p class="text-sm text-green-600 mt-2">✓ Zugewiesen</p>
                            <?php else: ?>
                                <div class="image-thumbnail w-full bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400 text-2xl"></i>
                                </div>
                                <p class="text-sm text-gray-500 mt-2">Kein Bild zugewiesen</p>
                            <?php endif; ?>
                        </div>
                    <?php endfor; ?>
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
}

// Show upload section by default
document.addEventListener('DOMContentLoaded', function() {
    showSection('upload');
    
    // File upload drag & drop
    const uploadZone = document.getElementById('uploadZone');
    const fileInput = document.getElementById('fileInput');
    
    uploadZone.addEventListener('click', () => fileInput.click());
    
    uploadZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadZone.classList.add('dragover');
    });
    
    uploadZone.addEventListener('dragleave', () => {
        uploadZone.classList.remove('dragover');
    });
    
    uploadZone.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadZone.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            // Show preview
            const file = files[0];
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    uploadZone.innerHTML = `
                        <img src="${e.target.result}" class="max-w-full max-h-32 mx-auto mb-2 rounded">
                        <p class="text-sm text-green-600">Bereit zum Upload: ${file.name}</p>
                    `;
                };
                reader.readAsDataURL(file);
            }
        }
    });
    
    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                uploadZone.innerHTML = `
                    <img src="${e.target.result}" class="max-w-full max-h-32 mx-auto mb-2 rounded">
                    <p class="text-sm text-green-600">Bereit zum Upload: ${file.name}</p>
                `;
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>

</body>
</html>