<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Admin-Panel'; ?> - Hohmann Bau</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Professional Admin CSS -->
    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        :root {
            --primary-50: #f0f9ff;
            --primary-100: #e0f2fe;
            --primary-500: #0ea5e9;
            --primary-600: #0284c7;
            --primary-700: #0369a1;
            --primary-900: #0c4a6e;
            
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
        }
        
        /* Professional Admin Sidebar */
        .admin-sidebar {
            background: linear-gradient(180deg, var(--gray-900) 0%, var(--gray-800) 100%);
            border-right: 1px solid var(--gray-700);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        }
        
        /* Main Content Area */
        .admin-content {
            background: var(--gray-50);
            min-height: 100vh;
        }
        
        /* Top Navigation Bar */
        .admin-topbar {
            background: white;
            border-bottom: 1px solid var(--gray-200);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        
        /* Professional Cards */
        .admin-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--gray-200);
            transition: all 0.2s ease;
        }
        
        .admin-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border-color: var(--primary-200);
        }
        
        /* Professional Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-500) 0%, var(--primary-600) 100%);
            color: white;
            font-weight: 500;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-700) 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
        }
        
        .btn-secondary {
            background: white;
            color: var(--gray-700);
            font-weight: 500;
            padding: 10px 20px;
            border-radius: 8px;
            border: 1px solid var(--gray-300);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        
        .btn-secondary:hover {
            background: var(--gray-50);
            border-color: var(--gray-400);
            transform: translateY(-1px);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            font-weight: 500;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        
        .btn-danger:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }
        
        /* Navigation Items */
        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            margin: 4px 0;
            color: var(--gray-300);
            text-decoration: none;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.2s ease;
            position: relative;
        }
        
        .nav-item:hover {
            background: rgba(255, 255, 255, 0.08);
            color: white;
            transform: translateX(4px);
        }
        
        .nav-item.active {
            background: linear-gradient(135deg, var(--primary-500) 0%, var(--primary-600) 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
        }
        
        .nav-item.active::before {
            content: '';
            position: absolute;
            left: -12px;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 20px;
            background: white;
            border-radius: 2px;
        }
        
        /* Professional Form Elements */
        .form-input {
            background: white;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 14px;
            transition: all 0.2s ease;
            width: 100%;
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--primary-500);
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
        }
        
        .form-label {
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 6px;
            display: block;
        }
        
        /* Professional Alerts */
        .alert {
            padding: 16px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
        }
        
        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #22c55e;
        }
        
        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #ef4444;
        }
        
        .alert-warning {
            background: #fefce8;
            color: #a16207;
            border: 1px solid #eab308;
        }
        
        .alert-info {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #3b82f6;
        }
        
        /* Professional Stats Cards */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            border: 1px solid var(--gray-200);
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-500), var(--primary-600));
        }
        
        /* Professional Tables */
        .admin-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--gray-200);
        }
        
        .admin-table th {
            background: var(--gray-50);
            color: var(--gray-700);
            font-weight: 600;
            padding: 16px 20px;
            text-align: left;
            border-bottom: 1px solid var(--gray-200);
        }
        
        .admin-table td {
            padding: 16px 20px;
            border-bottom: 1px solid var(--gray-100);
            color: var(--gray-600);
        }
        
        .admin-table tr:hover {
            background: var(--gray-50);
        }
        
        /* Page Headers */
        .page-header {
            margin-bottom: 32px;
        }
        
        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 8px;
        }
        
        .page-subtitle {
            color: var(--gray-600);
            font-size: 16px;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .admin-sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            .admin-content {
                padding: 16px;
            }
        }
        
        /* Loading States */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }
        
        /* Professional Animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .slide-in {
            animation: slideIn 0.3s ease-out;
        }
        
        /* Color Preview Boxes */
        .color-preview {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: 2px solid white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        
        .color-preview:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body class="bg-gray-50 antialiased">

<?php
// Flash-Nachrichten anzeigen
if (function_exists('getFlashMessage')) {
    $flash = getFlashMessage();
    if ($flash) {
        $typeClass = 'alert-' . $flash['type'];
        echo '<div class="alert ' . $typeClass . ' slide-in">';
        echo '<i class="fas fa-info-circle"></i>';
        echo '<span>' . htmlspecialchars($flash['message']) . '</span>';
        echo '</div>';
    }
}
?>

<div class="flex min-h-screen bg-gray-50">