<?php
session_start();
require_once '../config/database.php';
require_once '../config/functions.php';
checkAdminLogin();
$db = getDB();

$edit_item = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM youtube_videos WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_item = $stmt->fetch();
}

// Handle Form Submissions (Add/Edit/Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add or Update
    if (isset($_POST['save_video'])) {
        $id = $_POST['video_id'] ?? null;
        $title = $_POST['title'];
        $video_url = $_POST['video_url'];
        
        // Extract video ID from URL
        parse_str(parse_url($video_url, PHP_URL_QUERY), $vars);
        $video_id = $vars['v'] ?? '';

        if (empty($video_id)) {
            setErrorMessage('Geçersiz YouTube URL. URL içinde "v=" parametresi bulunmalıdır.');
        } else {
            if ($id) { // Update
                $stmt = $db->prepare("UPDATE youtube_videos SET title = ?, video_id = ? WHERE id = ?");
                $stmt->execute([$title, $video_id, $id]);
                setSuccessMessage('Video başarıyla güncellendi.');
            } else { // Insert
                $stmt = $db->prepare("INSERT INTO youtube_videos (title, video_id) VALUES (?, ?)");
                $stmt->execute([$title, $video_id]);
                setSuccessMessage('Video başarıyla eklendi.');
            }
        }
    }

    // Delete
    if (isset($_POST['delete_video'])) {
        $id_to_delete = $_POST['id_to_delete'];
        $stmt = $db->prepare("DELETE FROM youtube_videos WHERE id = ?");
        $stmt->execute([$id_to_delete]);
        setSuccessMessage('Video silindi.');
    }
    
    header('Location: youtube.php');
    exit();
}

$items = $db->query("SELECT * FROM youtube_videos ORDER BY display_order ASC, id DESC")->fetchAll();
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>YouTube Videoları</title>
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
                <h1 class="h2"><i class="bi bi-youtube me-2"></i>YouTube Yönetimi</h1>
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
                                <?php echo $edit_item ? 'Videoyu Düzenle' : 'Yeni Video Ekle'; ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <input type="hidden" name="video_id" value="<?php echo $edit_item['id'] ?? ''; ?>">
                                <div class="mb-3">
                                    <label class="form-label">Video Başlığı</label>
                                    <input type="text" name="title" class="form-control" value="<?php echo clean($edit_item['title'] ?? ''); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">YouTube Video URL</label>
                                    <input type="url" name="video_url" class="form-control" placeholder="https://www.youtube.com/watch?v=VIDEO_ID" value="<?php echo $edit_item ? 'https://www.youtube.com/watch?v='.clean($edit_item['video_id']) : ''; ?>" required>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="submit" name="save_video" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-1"></i> <?php echo $edit_item ? 'Güncelle' : 'Kaydet'; ?>
                                    </button>
                                    <?php if ($edit_item): ?>
                                    <a href="youtube.php" class="btn btn-secondary">İptal</a>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="card">
                         <div class="card-header"><h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Video Listesi</h5></div>
                        <div class="card-body">
                            <?php if (empty($items)): ?>
                                <p class="text-center text-muted">Henüz video eklenmemiş.</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Önizleme</th>
                                                <th>Başlık</th>
                                                <th style="width: 150px;">İşlemler</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($items as $it): ?>
                                            <tr>
                                                <td>
                                                    <img src="https://i3.ytimg.com/vi/<?php echo clean($it['video_id']); ?>/mqdefault.jpg" width="120" alt="<?php echo clean($it['title']); ?>">
                                                </td>
                                                <td class="align-middle"><?php echo clean($it['title']); ?></td>
                                                <td class="align-middle">
                                                    <a href="?edit=<?php echo $it['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                                    <form method="POST" class="d-inline" onsubmit="return confirm('Bu videoyu silmek istediğinize emin misiniz?');">
                                                        <input type="hidden" name="id_to_delete" value="<?php echo $it['id']; ?>">
                                                        <button type="submit" name="delete_video" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
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