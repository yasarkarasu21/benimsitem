<?php
session_start();
require_once '../config/database.php';
require_once '../config/functions.php';
checkAdminLogin();
$db = getDB();
$items = $db->query("SELECT * FROM portfolio ORDER BY display_order ASC, id DESC")->fetchAll();
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portfolio Yönetimi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<div class="container-fluid">
    <div class="row">
        <?php include 'includes/sidebar.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Portfolio</h2>
                <a href="portfolio_edit.php" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i>Yeni Ekle</a>
            </div>
            <?php if (empty($items)): ?>
                <p class="text-muted">Henüz proje eklenmemiş.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Başlık</th>
                                <th>Kategori</th>
                                <th>Durum</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $it): ?>
                            <tr>
                                <td><?php echo $it['id']; ?></td>
                                <td><?php echo htmlspecialchars($it['title']); ?></td>
                                <td><?php echo htmlspecialchars($it['category']); ?></td>
                                <td><?php echo $it['is_active'] ? 'Yayında' : 'Taslak'; ?></td>
                                <td>
                                    <a href="portfolio_edit.php?id=<?php echo $it['id']; ?>" class="btn btn-sm btn-outline-primary">Düzenle</a>
                                    <a href="portfolio_delete.php?id=<?php echo $it['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Silmek istediğinize emin misiniz?');">Sil</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>