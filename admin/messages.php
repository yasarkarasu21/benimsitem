<?php
session_start();
require_once '../config/database.php';
require_once '../config/functions.php';
checkAdminLogin();
$db = getDB();

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $db->prepare("SELECT * FROM contact_messages WHERE id = ?");
    $stmt->execute([$id]);
    $message = $stmt->fetch();
    if ($message) {
        // is_read güncelle
        $db->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?")->execute([$id]);
    }
}

$messages = $db->query("SELECT * FROM contact_messages ORDER BY created_at DESC")->fetchAll();
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mesajlar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<div class="container-fluid">
    <div class="row">
        <?php include 'includes/sidebar.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h2>Mesajlar</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="list-group">
                        <?php foreach ($messages as $m): ?>
                        <a href="messages.php?id=<?php echo $m['id']; ?>" class="list-group-item list-group-item-action <?php echo $m['is_read'] ? '' : 'fw-bold'; ?>">
                            <div class="d-flex justify-content-between">
                                <div><?php echo htmlspecialchars($m['name']); ?></div>
                                <small class="text-muted"><?php echo formatDate($m['created_at'], 'd.m.Y'); ?></small>
                            </div>
                            <div class="small text-muted"><?php echo truncate(htmlspecialchars($m['subject']), 50); ?></div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="col-md-8">
                    <?php if (isset($message) && $message): ?>
                        <div class="card">
                            <div class="card-body">
                                <h5><?php echo htmlspecialchars($message['subject']); ?></h5>
                                <p class="text-muted">Gönderen: <?php echo htmlspecialchars($message['name']); ?> — <?php echo htmlspecialchars($message['email']); ?></p>
                                <div class="mt-3"><?php echo nl2br(htmlspecialchars($message['message'])); ?></div>
                                <div class="mt-3">
                                    <a href="messages.php" class="btn btn-sm btn-outline-secondary">Geri</a>
                                    <a href="message_delete.php?id=<?php echo $message['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Silmek istediğinize emin misiniz?');">Sil</a>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Gösterilecek mesaj seçilmedi.</p>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>