<?php
session_start();
require_once '../config/database.php';
require_once '../config/functions.php';
checkAdminLogin();
$db = getDB();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id) {
    $stmt = $db->prepare("SELECT image FROM portfolio WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if ($row) {
        if (!empty($row['image'])) deleteFile($row['image']);
        $db->prepare("DELETE FROM portfolio WHERE id = ?")->execute([$id]);
        setSuccessMessage('Proje silindi.');
    }
}
header('Location: portfolio.php');
exit();
?>