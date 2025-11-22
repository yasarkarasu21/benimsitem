<?php
session_start();
require_once '../config/database.php';
require_once '../config/functions.php';
checkAdminLogin();
$db = getDB();
$items = $db->query("SELECT * FROM services ORDER BY display_order ASC, id DESC")->fetchAll();
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hizmetler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<div class="container-fluid">
    <div class="row">
        <?php include 'includes/sidebar.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Hizmetler</h2>
                <a href="service_edit.php" class="btn btn-primary">Yeni Ekle</a>
            </div>
            <?php if (empty($items)): ?>
                <p class="text-muted">Henüz hizmet eklenmemiş.</p>
            <?php else: ?>
                <div class="list-group">
                    <?php foreach ($items as $it): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0"><?php echo htmlspecialchars($it['title']); ?></h6>
                                <small class="text-muted"><?php echo htmlspecialchars($it['description']); ?></small>
                            </div>
                            <div>
                                <a href="service_edit.php?id=<?php echo $it['id']; ?>" class="btn btn-sm btn-outline-primary">Düzenle</a>
                                <a href="service_delete.php?id=<?php echo $it['id']; ?>" class="btn btn-sm btn-outline-danger">Sil</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>