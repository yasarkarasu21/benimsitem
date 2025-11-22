<?php
// css/dynamic-styles.php
header('Content-Type: text/css');

// Veritabanı bağlantısı ve fonksiyonları
require_once '../config/database.php';
require_once '../config/functions.php';

$settings = getSiteSettings();

$primaryColor = $settings['primary_color'] ?? '#667eea';
$secondaryColor = $settings['secondary_color'] ?? '#764ba2';
$textColor = $settings['text_color'] ?? '#495057';
?>

:root {
    --primary-color: <?php echo $primaryColor; ?>;
    --secondary-color: <?php echo $secondaryColor; ?>;
    --text-color: <?php echo $textColor; ?>;
    
    /* Diğer renkleri ana renklere göre ayarlayabiliriz */
    --bs-primary: <?php echo $primaryColor; ?>;
    --bs-primary-rgb: <?php echo implode(', ', sscanf($primaryColor, "#%02x%02x%02x")); ?>;
}

body {
    color: var(--text-color);
}

.btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.btn-primary:hover {
    opacity: 0.9;
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.text-primary {
    color: var(--primary-color) !important;
}

a {
    color: var(--primary-color);
}

a:hover {
    color: var(--secondary-color);
}

.hero-section {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
}
