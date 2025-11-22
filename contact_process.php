<?php
session_start();
require_once 'config/database.php';
require_once 'config/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // Validasyon
    $errors = [];
    
    if (empty($name)) {
        $errors[] = 'Ad Soyad alanı zorunludur.';
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Geçerli bir e-posta adresi giriniz.';
    }
    
    if (empty($subject)) {
        $errors[] = 'Konu alanı zorunludur.';
    }
    
    if (empty($message)) {
        $errors[] = 'Mesaj alanı zorunludur.';
    }
    
    if (empty($errors)) {
        try {
            if (saveContactMessage($name, $email, $subject, $message)) {
                setSuccessMessage('Mesajınız başarıyla gönderildi. En kısa sürede size dönüş yapacağız.');
            } else {
                setErrorMessage('Mesaj gönderilirken bir hata oluştu. Lütfen tekrar deneyin.');
            }
        } catch (Exception $e) {
            setErrorMessage('Bir hata oluştu: ' . $e->getMessage());
        }
    } else {
        setErrorMessage(implode('<br>', $errors));
    }
} else {
    setErrorMessage('Geçersiz istek.');
}

header('Location: index.php#contact');
exit();
?>
