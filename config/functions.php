<?php
// Oturum kontrolü
function checkAdminLogin() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: /admin/login.php');
        exit();
    }
}

// XSS koruması
function clean($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// Site ayarlarını getir
function getSiteSettings() {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM site_settings LIMIT 1");
    return $stmt->fetch();
}

// Hero içeriğini getir
function getHeroContent() {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM hero_section WHERE is_active = 1 LIMIT 1");
    return $stmt->fetch();
}

// Slider resimlerini getir
function getSliderImages() {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM hero_slider_images WHERE is_active = 1 ORDER BY display_order ASC, id DESC");
    return $stmt->fetchAll();
}

// Hakkımda içeriğini getir
function getAboutContent() {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM about_section WHERE is_active = 1 LIMIT 1");
    return $stmt->fetch();
}

// Yetenekleri getir
function getSkills($limit = null) {
    $db = getDB();
    $sql = "SELECT * FROM skills WHERE is_active = 1 ORDER BY display_order ASC, id DESC";
    if ($limit) {
        $sql .= " LIMIT " . (int)$limit;
    }
    $stmt = $db->query($sql);
    return $stmt->fetchAll();
}

// Hizmetleri getir
function getServices($limit = null) {
    $db = getDB();
    $sql = "SELECT * FROM services WHERE is_active = 1 ORDER BY display_order ASC, id DESC";
    if ($limit) {
        $sql .= " LIMIT " . (int)$limit;
    }
    $stmt = $db->query($sql);
    return $stmt->fetchAll();
}

// Portfolio projelerini getir
function getPortfolio($limit = null, $featured_only = false) {
    $db = getDB();
    $sql = "SELECT * FROM portfolio WHERE is_active = 1";
    if ($featured_only) {
        $sql .= " AND is_featured = 1";
    }
    $sql .= " ORDER BY display_order ASC, id DESC";
    if ($limit) {
        $sql .= " LIMIT " . (int)$limit;
    }
    $stmt = $db->query($sql);
    return $stmt->fetchAll();
}

// YouTube videolarını getir
function getYoutubeVideos($limit = null) {
    $db = getDB();
    $settings = getSiteSettings();
    
    if (!$settings['show_youtube']) {
        return [];
    }
    
    $sql = "SELECT * FROM youtube_videos WHERE is_active = 1 ORDER BY display_order ASC, id DESC";
    if ($limit) {
        $sql .= " LIMIT " . (int)$limit;
    }
    $stmt = $db->query($sql);
    return $stmt->fetchAll();
}

// Instagram gönderilerini getir
function getInstagramPosts($limit = null) {
    $db = getDB();
    $settings = getSiteSettings();
    
    if (!$settings['show_instagram']) {
        return [];
    }
    
    $sql = "SELECT * FROM instagram_posts WHERE is_active = 1 ORDER BY display_order ASC, id DESC";
    if ($limit) {
        $sql .= " LIMIT " . (int)$limit;
    }
    $stmt = $db->query($sql);
    return $stmt->fetchAll();
}

// İletişim mesajı kaydet
function saveContactMessage($name, $email, $subject, $message) {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$name, $email, $subject, $message]);
}

// Dosya yükleme fonksiyonu
function uploadFile($file, $directory = 'uploads/') {
    $upload_dir = __DIR__ . '/../' . $directory;
    
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'];
    
    if (!in_array($file_extension, $allowed_extensions)) {
        return ['success' => false, 'message' => 'Geçersiz dosya türü'];
    }
    
    $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
    $target_path = $upload_dir . $new_filename;
    
    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        return ['success' => true, 'filename' => $directory . $new_filename];
    }
    
    return ['success' => false, 'message' => 'Dosya yüklenemedi'];
}

// Dosya silme fonksiyonu
function deleteFile($filepath) {
    $full_path = __DIR__ . '/../' . $filepath;
    if (file_exists($full_path)) {
        return unlink($full_path);
    }
    return false;
}

// Tarih formatlama
function formatDate($date, $format = 'd.m.Y H:i') {
    return date($format, strtotime($date));
}

// Metin kısaltma
function truncate($text, $length = 100) {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}

// CSRF Token oluştur
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// CSRF Token doğrula
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Başarı mesajı göster
function setSuccessMessage($message) {
    $_SESSION['success_message'] = $message;
}

// Hata mesajı göster
function setErrorMessage($message) {
    $_SESSION['error_message'] = $message;
}

// Mesajları getir ve temizle
function getFlashMessage() {
    $message = null;
    if (isset($_SESSION['success_message'])) {
        $message = ['type' => 'success', 'text' => $_SESSION['success_message']];
        unset($_SESSION['success_message']);
    } elseif (isset($_SESSION['error_message'])) {
        $message = ['type' => 'danger', 'text' => $_SESSION['error_message']];
        unset($_SESSION['error_message']);
    }
    return $message;
}

// Aktif sayfa linkini belirle
function isActive($page) {
    $currentPage = basename($_SERVER['SCRIPT_NAME']);
    return $currentPage == $page ? 'active' : '';
}
?>
