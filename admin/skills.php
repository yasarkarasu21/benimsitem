<?php
session_start();
require_once '../config/database.php';
require_once '../config/functions.php';
checkAdminLogin();
$db = getDB();
$items = $db->query("SELECT * FROM skills ORDER BY display_order ASC, id DESC")->fetchAll();
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Yetenekler</title>
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
                <h2>Yetenekler</h2>
                <a href="skill_edit.php" class="btn btn-primary">Yeni Ekle</a>
            </div>
            <?php if (empty($items)): ?>
                <p class="text-muted">Henüz yetenek eklenmemiş.</p>
            <?php else: ?>
                <div class="row g-3">
                    <?php foreach ($items as $it): ?>
                        <div class="col-md-6">
                            <div class="card p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0"><?php echo htmlspecialchars($it['name']); ?></h6>
                                        <small class="text-muted"><?php echo htmlspecialchars($it['percentage']); ?>%</small>
                                    </div>
                                    <div>
                                        <a href="skill_edit.php?id=<?php echo $it['id']; ?>" class="btn btn-sm btn-outline-primary">Düzenle</a>
                                        <a href="skill_delete.php?id=<?php echo $it['id']; ?>" class="btn btn-sm btn-outline-danger">Sil</a>
                                    </div>
                                </div>
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