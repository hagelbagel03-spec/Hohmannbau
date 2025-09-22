<?php
/**
 * Dynamic CSS Generation
 * Generiert CSS basierend auf Admin-Farbeinstellungen
 */

// Hole aktuelle Farbeinstellungen aus der Datenbank
try {
    if (!isset($db)) {
        require_once 'config/database.php';
        $db = getDB();
    }
    $homepage = $db->query("SELECT * FROM homepage WHERE id = '1'")->fetch();
} catch (Exception $e) {
    // Fallback bei Fehler
    $homepage = false;
}

// Standard-Farben falls Spalten nicht existieren oder leer sind
$footer_bg = ($homepage && isset($homepage['footer_bg_color']) && $homepage['footer_bg_color']) ? $homepage['footer_bg_color'] : '#1f2937';
$footer_text = ($homepage && isset($homepage['footer_text_color']) && $homepage['footer_text_color']) ? $homepage['footer_text_color'] : '#ffffff';
$header_bg = ($homepage && isset($homepage['header_bg_color']) && $homepage['header_bg_color']) ? $homepage['header_bg_color'] : '#ffffff';
$header_text = ($homepage && isset($homepage['header_text_color']) && $homepage['header_text_color']) ? $homepage['header_text_color'] : '#1f2937';
$button_primary = ($homepage && isset($homepage['button_primary_color']) && $homepage['button_primary_color']) ? $homepage['button_primary_color'] : '#22c55e';
$button_secondary = ($homepage && isset($homepage['button_secondary_color']) && $homepage['button_secondary_color']) ? $homepage['button_secondary_color'] : '#6b7280';
$accent_color = ($homepage && isset($homepage['accent_color']) && $homepage['accent_color']) ? $homepage['accent_color'] : '#3b82f6';
$body_text = ($homepage && isset($homepage['body_text_color']) && $homepage['body_text_color']) ? $homepage['body_text_color'] : '#374151';
$heading_color = ($homepage && isset($homepage['heading_color']) && $homepage['heading_color']) ? $homepage['heading_color'] : '#1f2937';
$subheading_color = ($homepage && isset($homepage['subheading_color']) && $homepage['subheading_color']) ? $homepage['subheading_color'] : '#374151';
$link_color = ($homepage && isset($homepage['link_color']) && $homepage['link_color']) ? $homepage['link_color'] : '#2563eb';
$highlight_color = ($homepage && isset($homepage['highlight_color']) && $homepage['highlight_color']) ? $homepage['highlight_color'] : '#059669';
$current_theme = ($homepage && isset($homepage['color_theme'])) ? $homepage['color_theme'] : 'green';

// Theme-basierte Farben
$theme_colors = [
    'blue' => ['primary' => '#3b82f6', 'secondary' => '#1d4ed8', 'accent' => '#60a5fa'],
    'green' => ['primary' => '#10b981', 'secondary' => '#065f46', 'accent' => '#34d399'],
    'purple' => ['primary' => '#8b5cf6', 'secondary' => '#6d28d9', 'accent' => '#a78bfa'],
    'red' => ['primary' => '#ef4444', 'secondary' => '#dc2626', 'accent' => '#f87171'],
    'orange' => ['primary' => '#f59e0b', 'secondary' => '#d97706', 'accent' => '#fbbf24'],
    'gray' => ['primary' => '#6b7280', 'secondary' => '#374151', 'accent' => '#9ca3af']
];

$theme_primary = $theme_colors[$current_theme]['primary'];
$theme_secondary = $theme_colors[$current_theme]['secondary'];
$theme_accent = $theme_colors[$current_theme]['accent'];
?>

<style>
/* CSS Custom Properties für dynamische Farben */
:root {
    --footer-bg: <?php echo $footer_bg; ?>;
    --footer-text: <?php echo $footer_text; ?>;
    --header-bg: <?php echo $header_bg; ?>;
    --header-text: <?php echo $header_text; ?>;
    --button-primary: <?php echo $button_primary; ?>;
    --button-secondary: <?php echo $button_secondary; ?>;
    --accent-color: <?php echo $accent_color; ?>;
    --body-text: <?php echo $body_text; ?>;
    --heading-color: <?php echo $heading_color; ?>;
    --subheading-color: <?php echo $subheading_color; ?>;
    --link-color: <?php echo $link_color; ?>;
    --highlight-color: <?php echo $highlight_color; ?>;
    --theme-primary: <?php echo $theme_primary; ?>;
    --theme-secondary: <?php echo $theme_secondary; ?>;
    --theme-accent: <?php echo $theme_accent; ?>;
}

/* Footer-Styling */
footer, .footer {
    background-color: var(--footer-bg) !important;
    color: var(--footer-text) !important;
}

footer a, .footer a {
    color: var(--footer-text) !important;
    opacity: 0.8;
    transition: opacity 0.3s ease;
}

footer a:hover, .footer a:hover {
    opacity: 1;
}

/* Header-Styling */
header, .header, nav {
    background-color: var(--header-bg) !important;
    color: var(--header-text) !important;
}

.navbar-brand, .nav-link {
    color: var(--header-text) !important;
}

/* Button-Styling - Kompatibel mit allen Button-Klassen */
.btn-primary, .button-primary, .btn-primary-pro {
    background: linear-gradient(135deg, var(--button-primary) 0%, var(--theme-secondary) 100%) !important;
    border-color: var(--button-primary) !important;
    color: white !important;
}

.btn-primary:hover, .button-primary:hover, .btn-primary-pro:hover {
    background: var(--theme-secondary) !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(<?php echo hexdec(substr($button_primary, 1, 2)); ?>, <?php echo hexdec(substr($button_primary, 3, 2)); ?>, <?php echo hexdec(substr($button_primary, 5, 2)); ?>, 0.4);
}

.btn-secondary, .button-secondary {
    background-color: var(--button-secondary) !important;
    border-color: var(--button-secondary) !important;
    color: white !important;
}

.btn-secondary:hover, .button-secondary:hover {
    background-color: var(--theme-accent) !important;
    border-color: var(--theme-accent) !important;
}

/* Text-Styling - Alle Überschriften und Texte steuerbar */
h1, .h1, .heading-1 {
    color: var(--heading-color) !important;
}

h2, .h2, .heading-2 {
    color: var(--heading-color) !important;
}

h3, .h3, .heading-3 {
    color: var(--subheading-color) !important;
}

h4, h5, h6, .h4, .h5, .h6 {
    color: var(--subheading-color) !important;
}

/* Body Text - Alle Beschreibungstexte */
body, p, .text-body, .text-large, .text-gray-600, .text-gray-100 {
    color: var(--body-text) !important;
}

/* Spezifische Texte die steuerbar sein sollen */
.hero-subtitle, .section-subtitle, .service-description {
    color: var(--body-text) !important;
}

/* Links - alle anklickbaren Elemente */
a, .text-primary, .text-primary-600, .text-blue-600 {
    color: var(--link-color) !important;
}

a:hover, .text-primary:hover {
    color: var(--highlight-color) !important;
}

/* Highlights und Betonungen */
.font-semibold, .font-bold, .text-primary-700, .text-green-600 {
    color: var(--highlight-color) !important;
}

/* Service Cards Text */
.service-card h3, .card-professional h3 {
    color: var(--subheading-color) !important;
}

.service-card p, .card-professional p {
    color: var(--body-text) !important;
}

/* Navigation Text */
.nav-link {
    color: var(--header-text) !important;
}

.nav-link:hover, .nav-link.active {
    color: var(--highlight-color) !important;
}

.bg-accent {
    background-color: var(--accent-color) !important;
}

/* Hero Section - Theme-basiert */
.hero-section {
    background: linear-gradient(135deg, var(--theme-primary) 0%, var(--theme-secondary) 100%) !important;
}

/* Cards und Komponenten */
.card:hover, .service-card:hover {
    border-color: var(--accent-color) !important;
    box-shadow: 0 8px 25px rgba(<?php echo hexdec(substr($accent_color, 1, 2)); ?>, <?php echo hexdec(substr($accent_color, 3, 2)); ?>, <?php echo hexdec(substr($accent_color, 5, 2)); ?>, 0.15);
}

/* Links */
a {
    color: var(--accent-color) !important;
    transition: all 0.3s ease;
}

a:hover {
    color: var(--theme-secondary) !important;
}

/* Badges und Tags */
.badge-primary, .tag-primary {
    background-color: var(--theme-primary) !important;
    color: white !important;
}

.badge-accent, .tag-accent {
    background-color: var(--accent-color) !important;
    color: white !important;
}

/* Borders */
.border-accent {
    border-color: var(--accent-color) !important;
}

.border-theme {
    border-color: var(--theme-primary) !important;
}

/* Form Elements */
.form-control:focus, input:focus, textarea:focus {
    border-color: var(--accent-color) !important;
    box-shadow: 0 0 0 0.2rem rgba(<?php echo hexdec(substr($accent_color, 1, 2)); ?>, <?php echo hexdec(substr($accent_color, 3, 2)); ?>, <?php echo hexdec(substr($accent_color, 5, 2)); ?>, 0.25) !important;
}

/* Icons */
.icon-accent {
    color: var(--accent-color) !important;
}

.icon-theme {
    color: var(--theme-primary) !important;
}

/* Contact Form */
.contact-form {
    background-color: var(--header-bg) !important;
}

/* Statistics Cards */
.stats-card {
    border-left: 4px solid var(--theme-primary) !important;
}

/* Navigation Active States */
.nav-link.active, .navbar-nav .nav-link.active {
    color: var(--theme-primary) !important;
    border-bottom: 2px solid var(--theme-primary) !important;
}

/* Custom Animations */
@keyframes pulse-theme {
    0%, 100% { box-shadow: 0 0 0 0 rgba(<?php echo hexdec(substr($theme_primary, 1, 2)); ?>, <?php echo hexdec(substr($theme_primary, 3, 2)); ?>, <?php echo hexdec(substr($theme_primary, 5, 2)); ?>, 0.7); }
    50% { box-shadow: 0 0 0 20px rgba(<?php echo hexdec(substr($theme_primary, 1, 2)); ?>, <?php echo hexdec(substr($theme_primary, 3, 2)); ?>, <?php echo hexdec(substr($theme_primary, 5, 2)); ?>, 0); }
}

.pulse-animation {
    animation: pulse-theme 2s infinite;
}

/* Dark Mode Support */
@media (prefers-color-scheme: dark) {
    :root {
        --body-text: #e5e7eb;
        --header-bg: #1f2937;
        --header-text: #ffffff;
    }
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
    .btn-primary, .btn-secondary {
        padding: 12px 20px !important;
        font-size: 16px !important;
    }
}

/* Print Styles */
@media print {
    .btn-primary, .btn-secondary {
        background: transparent !important;
        color: var(--body-text) !important;
        border: 1px solid var(--body-text) !important;
    }
}

/* Accessibility */
.btn-primary:focus, .btn-secondary:focus {
    outline: 2px solid var(--accent-color) !important;
    outline-offset: 2px !important;
}

/* Loading States */
.loading {
    background: linear-gradient(90deg, var(--theme-primary), var(--theme-accent), var(--theme-primary)) !important;
    background-size: 200% 100% !important;
    animation: loading 2s infinite !important;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
</style>