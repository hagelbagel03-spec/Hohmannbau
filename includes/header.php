<!DOCTYPE html>
<html lang="de" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? htmlspecialchars($title) : 'Hohmann Bau - Ihr Experte für Garten- und Landschaftsbau'; ?></title>
    <meta name="description" content="<?php echo isset($description) ? htmlspecialchars($description) : 'Professioneller Garten- und Landschaftsbau in höchster Qualität. Planung, Gestaltung und Pflege Ihrer Außenanlagen.'; ?>">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Dynamic Styles from Admin Panel -->
    <?php 
    $load_dynamic_styles = true;
    if (file_exists('includes/dynamic_styles.php')) {
        include_once 'includes/dynamic_styles.php';
    }
    ?>
    
    <!-- Professional Website CSS -->
    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        .font-heading {
            font-family: 'Playfair Display', serif;
        }
        
        :root {
            --primary-50: #f0fdf4;
            --primary-100: #dcfce7;
            --primary-200: #bbf7d0;
            --primary-300: #86efac;
            --primary-400: #4ade80;
            --primary-500: #22c55e;
            --primary-600: #16a34a;
            --primary-700: #15803d;
            --primary-800: #166534;
            --primary-900: #14532d;
            
            --secondary-50: #f8fafc;
            --secondary-100: #f1f5f9;
            --secondary-200: #e2e8f0;
            --secondary-300: #cbd5e1;
            --secondary-400: #94a3b8;
            --secondary-500: #64748b;
            --secondary-600: #475569;
            --secondary-700: #334155;
            --secondary-800: #1e293b;
            --secondary-900: #0f172a;
        }
        
        /* Professional Hero Section */
        .hero-gradient {
            background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-800) 50%, var(--secondary-800) 100%);
            position: relative;
            overflow: hidden;
        }
        
        .hero-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.03)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.03)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.02)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.02)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.02)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        /* Professional Navigation */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        
        .nav-link {
            color: var(--secondary-700);
            font-weight: 500;
            padding: 12px 20px;
            border-radius: 10px;
            transition: all 0.2s ease;
            text-decoration: none;
            position: relative;
        }
        
        .nav-link:hover {
            color: var(--primary-600);
            background: var(--primary-50);
            transform: translateY(-1px);
        }
        
        .nav-link.active {
            color: var(--primary-600);
            background: var(--primary-100);
            font-weight: 600;
        }
        
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 20px;
            right: 20px;
            height: 2px;
            background: var(--primary-600);
            border-radius: 1px;
        }
        
        /* Professional Cards */
        .card-professional {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .card-professional:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border-color: var(--primary-200);
        }
        
        /* Professional Buttons */
        .btn-primary-pro {
            background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-700) 100%);
            color: white;
            font-weight: 600;
            padding: 14px 28px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 16px;
        }
        
        .btn-primary-pro:hover {
            background: linear-gradient(135deg, var(--primary-700) 0%, var(--primary-800) 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(34, 197, 94, 0.3);
        }
        
        .btn-secondary-pro {
            background: white;
            color: var(--secondary-700);
            font-weight: 600;
            padding: 14px 28px;
            border-radius: 12px;
            border: 2px solid var(--secondary-200);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 16px;
        }
        
        .btn-secondary-pro:hover {
            background: var(--secondary-50);
            border-color: var(--primary-300);
            color: var(--primary-700);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        /* Professional Sections */
        .section-professional {
            padding: 80px 0;
            position: relative;
        }
        
        .section-professional.bg-gray {
            background: linear-gradient(135deg, var(--secondary-50) 0%, var(--secondary-100) 100%);
        }
        
        .section-professional.bg-white {
            background: white;
        }
        
        /* Professional Typography */
        .heading-1 {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 800;
            line-height: 1.1;
            color: var(--secondary-900);
            margin-bottom: 1.5rem;
        }
        
        .heading-2 {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 700;
            line-height: 1.2;
            color: var(--secondary-900);
            margin-bottom: 1rem;
        }
        
        .heading-3 {
            font-size: clamp(1.5rem, 3vw, 2rem);
            font-weight: 600;
            line-height: 1.3;
            color: var(--secondary-800);
            margin-bottom: 0.75rem;
        }
        
        .text-large {
            font-size: 1.25rem;
            line-height: 1.6;
            color: var(--secondary-600);
        }
        
        .text-body {
            font-size: 1rem;
            line-height: 1.7;
            color: var(--secondary-600);
        }
        
        /* Professional Footer */
        .footer-professional {
            background: linear-gradient(135deg, var(--secondary-900) 0%, var(--secondary-800) 100%);
            color: var(--secondary-200);
            position: relative;
            overflow: hidden;
        }
        
        .footer-professional::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        }
        
        .footer-link {
            color: var(--secondary-300);
            text-decoration: none;
            transition: all 0.2s ease;
        }
        
        .footer-link:hover {
            color: var(--primary-400);
            transform: translateX(4px);
        }
        
        /* Professional Stats */
        .stat-card-pro {
            text-align: center;
            padding: 2rem;
            border-radius: 16px;
            background: white;
            border: 1px solid var(--secondary-200);
            position: relative;
            overflow: hidden;
        }
        
        .stat-card-pro::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-500), var(--primary-600));
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-600);
            display: block;
        }
        
        .stat-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--secondary-600);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        /* Professional Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .animate-slide-in-right {
            animation: slideInRight 0.6s ease-out;
        }
        
        /* Professional Form Elements */
        .form-professional {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--secondary-200);
        }
        
        .input-professional {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--secondary-200);
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.2s ease;
            background: white;
        }
        
        .input-professional:focus {
            outline: none;
            border-color: var(--primary-500);
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
        }
        
        .label-professional {
            font-weight: 600;
            color: var(--secondary-700);
            margin-bottom: 8px;
            display: block;
        }
        
        /* Professional Service Icons */
        .service-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .service-icon:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 10px 30px rgba(34, 197, 94, 0.3);
        }
        
        /* Professional Testimonials */
        .testimonial-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
            border: 1px solid var(--secondary-100);
            position: relative;
            overflow: hidden;
        }
        
        .testimonial-card::before {
            content: '"';
            position: absolute;
            top: -10px;
            left: 20px;
            font-size: 120px;
            color: var(--primary-100);
            font-family: 'Playfair Display', serif;
            line-height: 1;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .section-professional {
                padding: 60px 0;
            }
            
            .btn-primary-pro,
            .btn-secondary-pro {
                padding: 12px 20px;
                font-size: 14px;
            }
            
            .navbar {
                padding: 1rem 0;
            }
        }
        
        /* Professional Loading States */
        .loading-professional {
            position: relative;
            overflow: hidden;
        }
        
        .loading-professional::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.6), transparent);
            animation: shimmer 1.5s infinite;
        }
        
        @keyframes shimmer {
            0% {
                left: -100%;
            }
            100% {
                left: 100%;
            }
        }
    </style>
</head>
<body class="antialiased bg-white text-gray-900">