<?php
/**
 * Einfacher Bild-Upload ohne Datenbank
 */

session_start();
if (!isset($_SESSION['admin_id']) && !isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$message = '';
$error = '';

// Upload-Verzeichnisse erstellen
$uploadDirs = [
    '../uploads',
    '../uploads/hero',
    '../uploads/gallery', 
    '../uploads/services',
    '../uploads/team',
    '../uploads/news'
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
            
            // Generate filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = $category . '_' . time() . '.' . $extension;
            $targetDir = "../uploads/$category/";
            $targetPath = $targetDir . $filename;
            
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $message = "Bild erfolgreich hochgeladen: /uploads/$category/$filename";
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

// Get uploaded images
function getUploadedImages($category = null) {
    $images = [];
    $baseDir = '../uploads/';
    
    if ($category) {
        $dirs = [$category];
    } else {
        $dirs = ['hero', 'gallery', 'services', 'team', 'news', 'general'];
    }
    
    foreach ($dirs as $dir) {
        $fullPath = $baseDir . $dir;
        if (is_dir($fullPath)) {
            $files = glob($fullPath . '/*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
            foreach ($files as $file) {
                $images[] = [
                    'path' => str_replace('../', '/', $file),
                    'name' => basename($file),
                    'category' => $dir,
                    'size' => filesize($file)
                ];
            }
        }
    }
    
    return $images;
}

$allImages = getUploadedImages();

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bild-Upload - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .upload-zone {
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            background: #f9fafb;
            cursor: pointer;
        }
        
        .upload-zone:hover,
        .upload-zone.dragover {
            border-color: #10b981;
            background: #f0fdf4;
        }
        
        .image-thumbnail {
            width: 150px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .copy-path {
            font-family: monospace;
            background: #f3f4f6;
            padding: 0.5rem;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-gray-50">

<div class="flex h-screen">
    <!-- Sidebar -->
    <div class="w-64 bg-white shadow-lg">
        <div class="p-6 border-b bg-gradient-to-r from-green-600 to-green-700 text-white">
            <h1 class="text-xl font-bold flex items-center">
                <i class="fas fa-upload mr-2"></i>
                Bild-Upload
            </h1>
        </div>
        <nav class="p-4">
            <ul class="space-y-2">
                <li><a href="#upload" class="block p-2 rounded hover:bg-gray-100" onclick="showSection('upload')">📤 Upload</a></li>
                <li><a href="#gallery" class="block p-2 rounded hover:bg-gray-100" onclick="showSection('gallery')">🖼️ Galerie</a></li>
                <li><a href="text_editor.php" class="block p-2 rounded hover:bg-gray-100">📝 Text Editor</a></li>
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
                <h2 class="text-2xl font-bold mb-6 flex items-center text-green-700">
                    <i class="fas fa-cloud-upload-alt mr-3"></i>
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
                                <option value="news">News</option>
                                <option value="general">Allgemein</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alt-Text (für SEO)</label>
                            <input type="text" name="alt_text" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Beschreibung des Bildes">
                        </div>
                    </div>
                    
                    <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-md hover:bg-green-700 flex items-center">
                        <i class="fas fa-upload mr-2"></i>
                        Bild hochladen
                    </button>
                </form>
            </div>
        </div>

        <!-- Gallery Section -->
        <div id="gallery-section" class="section hidden">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold mb-6 flex items-center text-green-700">
                    <i class="fas fa-images mr-3"></i>
                    Alle hochgeladenen Bilder
                </h2>
                
                <!-- Instructions -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-blue-900 mb-2">💡 So verwenden Sie die Bilder:</h3>
                    <p class="text-sm text-blue-800">Klicken Sie auf einen Pfad um ihn zu kopieren. Dann können Sie ihn in den Text-Editor eingeben.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($allImages as $image): ?>
                        <div class="border rounded-lg p-4 hover:shadow-lg transition-shadow">
                            <img src="<?php echo htmlspecialchars($image['path']); ?>" 
                                 alt="Uploaded image" 
                                 class="image-thumbnail w-full mb-3">
                            
                            <div class="space-y-2">
                                <p class="font-semibold text-sm"><?php echo htmlspecialchars($image['name']); ?></p>
                                
                                <p class="text-xs text-gray-600">
                                    <span class="inline-block bg-gray-100 px-2 py-1 rounded"><?php echo $image['category']; ?></span>
                                    <span class="ml-2"><?php echo number_format($image['size'] / 1024, 1); ?> KB</span>
                                </p>
                                
                                <div class="copy-path text-xs" onclick="copyPath('<?php echo $image['path']; ?>')" title="Klicken zum Kopieren">
                                    <?php echo htmlspecialchars($image['path']); ?>
                                </div>
                                
                                <div class="text-xs text-green-600" id="copied-<?php echo md5($image['path']); ?>" style="display: none;">
                                    ✓ Pfad kopiert!
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if (empty($allImages)): ?>
                    <div class="text-center py-12">
                        <i class="fas fa-image text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Noch keine Bilder hochgeladen</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function showSection(sectionName) {
    document.querySelectorAll('.section').forEach(section => {
        section.classList.add('hidden');
    });
    
    document.getElementById(sectionName + '-section').classList.remove('hidden');
}

function copyPath(path) {
    navigator.clipboard.writeText(path).then(function() {
        const copyId = 'copied-' + btoa(path).replace(/[^a-zA-Z0-9]/g, '').substr(0, 10);
        const indicator = document.querySelector(`[id*="copied-"]`);
        if (indicator) {
            indicator.style.display = 'block';
            setTimeout(() => {
                indicator.style.display = 'none';
            }, 2000);
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    showSection('upload');
    
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
            showPreview(files[0]);
        }
    });
    
    fileInput.addEventListener('change', function() {
        if (this.files[0]) {
            showPreview(this.files[0]);
        }
    });
    
    function showPreview(file) {
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
</script>

</body>
</html>