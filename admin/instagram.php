<?php
session_start();
require_once '../config/database.php';
require_once '../config/functions.php';
checkAdminLogin();
$db = getDB();

$edit_item = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM instagram_posts WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_item = $stmt->fetch();
}

// Handle Form Submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add or Update
    if (isset($_POST['save_post'])) {
        $id = $_POST['post_id'] ?? null;
        $post_url = $_POST['post_url'];
        $image_url = $_POST['image_url'];
        $caption = $_POST['caption'] ?? '';

        if ($id) { // Update
            $stmt = $db->prepare("UPDATE instagram_posts SET post_url = ?, image_url = ?, caption = ? WHERE id = ?");
            $stmt->execute([$post_url, $image_url, $caption, $id]);
            setSuccessMessage('Gönderi başarıyla güncellendi.');
        } else { // Insert
            $stmt = $db->prepare("INSERT INTO instagram_posts (post_url, image_url, caption) VALUES (?, ?, ?)");
            $stmt->execute([$post_url, $image_url, $caption]);
            setSuccessMessage('Gönderi başarıyla eklendi.');
        }
    }

    // Delete
    if (isset($_POST['delete_post'])) {
        $id_to_delete = $_POST['id_to_delete'];
        $stmt = $db->prepare("DELETE FROM instagram_posts WHERE id = ?");
        $stmt->execute([$id_to_delete]);
        setSuccessMessage('Gönderi silindi.');
    }
    
    header('Location: instagram.php');
    exit();
}

$items = $db->query("SELECT * FROM instagram_posts ORDER BY display_order ASC, id DESC")->fetchAll();
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instagram Gönderileri</title>
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
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><i class="bi bi-instagram me-2"></i>Instagram Yönetimi</h1>
            </div>

            <?php
            $flash = getFlashMessage();
            if ($flash):
            ?>
            <div class="alert alert-<?php echo $flash['type']; ?> alert-dismissible fade show" role="alert">
                <?php echo clean($flash['text']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi <?php echo $edit_item ? 'bi-pencil-square' : 'bi-plus-circle'; ?> me-2"></i>
                                <?php echo $edit_item ? 'Gönderiyi Düzenle' : 'Yeni Gönderi Ekle'; ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <input type="hidden" name="post_id" value="<?php echo $edit_item['id'] ?? ''; ?>">
                                <div class="mb-3">
                                    <label class="form-label">Gönderi URL</label>
                                    <input type="url" name="post_url" class="form-control" placeholder="https://www.instagram.com/p/POST_ID/" value="<?php echo clean($edit_item['post_url'] ?? ''); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Görsel URL</label>
                                    <input type="url" name="image_url" class="form-control" placeholder="Görselin direkt adresi" value="<?php echo clean($edit_item['image_url'] ?? ''); ?>" required>
                                    <small class="form-text text-muted">Instagram gönderi görseline sağ tıklayıp "Resim adresini kopyala" ile alabilirsiniz.</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Açıklama (İsteğe bağlı)</label>
                                    <textarea name="caption" class="form-control" rows="3"><?php echo clean($edit_item['caption'] ?? ''); ?></textarea>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="submit" name="save_post" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-1"></i> <?php echo $edit_item ? 'Güncelle' : 'Kaydet'; ?>
                                    </button>
                                    <?php if ($edit_item): ?>
                                    <a href="instagram.php" class="btn btn-secondary">İptal</a>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="card">
                         <div class="card-header"><h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Gönderi Listesi</h5></div>
                        <div class="card-body">
                            <?php if (empty($items)): ?>
                                <p class="text-center text-muted">Henüz gönderi eklenmemiş.</p>
                            <?php else: ?>
                                <div class="row row-cols-2 row-cols-md-3 g-3">
                                    <?php foreach ($items as $it): ?>
                                    <div class="col">
                                        <div class="card h-100">
                                            <a href="<?php echo clean($it['post_url']); ?>" target="_blank">
                                                <img src="<?php echo clean($it['image_url']); ?>" class="card-img-top" alt="Instagram Post" style="aspect-ratio: 1/1; object-fit: cover;">
                                            </a>
                                            <div class="card-body p-2">
                                                <div class="btn-group w-100">
                                                    <a href="?edit=<?php echo $it['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                                    <form method="POST" class="d-inline" onsubmit="return confirm('Bu gönderiyi silmek istediğinize emin misiniz?');">
                                                        <input type="hidden" name="id_to_delete" value="<?php echo $it['id']; ?>">
                                                        <button type="submit" name="delete_post" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>