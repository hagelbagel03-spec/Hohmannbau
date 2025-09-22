<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Admin-Bereich'; ?> - Hohmann Bau</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom Admin CSS -->
    <style>
        .admin-sidebar {
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
        }
        
        .admin-content {
            background: #f8fafc;
            min-height: 100vh;
        }
        
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #065f46 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        
        .btn-secondary {
            background: #6b7280;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .btn-danger {
            background: #ef4444;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .flash-message {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 16px;
        }
        
        .flash-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #22c55e;
        }
        
        .flash-error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #ef4444;
        }
        
        .flash-warning {
            background: #fefce8;
            color: #a16207;
            border: 1px solid #eab308;
        }
        
        .flash-info {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #3b82f6;
        }
    </style>
</head>
<body class="bg-gray-100">

<?php
// Flash-Nachrichten anzeigen
if (function_exists('getFlashMessage')) {
    $flash = getFlashMessage();
    if ($flash) {
        $typeClass = 'flash-' . $flash['type'];
        echo '<div class="flash-message ' . $typeClass . '">';
        echo '<i class="fas fa-info-circle mr-2"></i>';
        echo htmlspecialchars($flash['message']);
        echo '</div>';
    }
}
?>

<div class="flex min-h-screen">