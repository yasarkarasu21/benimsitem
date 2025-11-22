<?php
session_start();
require_once '../config/database.php';
require_once '../config/functions.php';
checkAdminLogin();
$db = getDB();

// Handle Form Submissions (Add/Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add new slider image
    if (isset($_POST['add_slider_image'])) {
        $title = $_POST['title'] ?? '';
        $subtitle = $_POST['subtitle'] ?? '';
        
        if (isset($_FILES['slider_image']) && $_FILES['slider_image']['error'] == 0) {
            $upload = uploadFile($_FILES['slider_image'], 'uploads/slider/');
            if ($upload['success']) {
                $stmt = $db->prepare("INSERT INTO hero_slider_images (image_path, title, subtitle) VALUES (?, ?, ?)");
                $stmt->execute([$upload['filename'], $title, $subtitle]);
                setSuccessMessage('Slider resmi başarıyla eklendi.');
            } else {
                setErrorMessage($upload['message']);
            }
        } else {
            setErrorMessage('Lütfen bir resim dosyası seçin.');
        }
    }
    
    // Delete slider image
    if (isset($_POST['delete_slider_image'])) {
        $id_to_delete = $_POST['image_id'];
        
        // First, get the image path to delete the file
        $stmt = $db->prepare("SELECT image_path FROM hero_slider_images WHERE id = ?");
        $stmt->execute([$id_to_delete]);
        $image = $stmt->fetch();
        
        if ($image) {
            deleteFile($image['image_path']);
            
            // Then, delete the record from the database
            $stmt = $db->prepare("DELETE FROM hero_slider_images WHERE id = ?");
            $stmt->execute([$id_to_delete]);
            
            setSuccessMessage('Slider resmi silindi.');
        } else {
            setErrorMessage('Resim bulunamadı.');
        }
    }
    
    header('Location: slider.php');
    exit();
}

$slider_images = $db->query("SELECT * FROM hero_slider_images ORDER BY display_order ASC, id DESC")->fetchAll();
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Slider Yönetimi</title>
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
                <h1 class="h2"><i class="bi bi-images me-2"></i>Slider Yönetimi</h1>
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
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Yeni Resim Ekle</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="slider_image" class="form-label">Resim Dosyası</label>
                                    <input class="form-control" type="file" id="slider_image" name="slider_image" required>
                                </div>
                                <div class="mb-3">
                                    <label for="title" class="form-label">Başlık (İsteğe bağlı)</label>
                                    <input type="text" class="form-control" id="title" name="title" placeholder="Ana başlık...">
                                </div>
                                <div class="mb-3">
                                    <label for="subtitle" class="form-label">Alt Başlık (İsteğe bağlı)</label>
                                    <input type="text" class="form-control" id="subtitle" name="subtitle" placeholder="Daha kısa alt başlık...">
                                </div>
                                <div class="d-grid">
                                    <button type="submit" name="add_slider_image" class="btn btn-primary">
                                        <i class="bi bi-plus-lg me-1"></i> Ekle
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-7">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-image-fill me-2"></i>Yüklü Resimler</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($slider_images)): ?>
                                <p class="text-center text-muted">Henüz hiç slider resmi eklenmemiş.</p>
                            <?php else: ?>
                            <div class="row row-cols-2 g-3">
                                <?php foreach ($slider_images as $image): ?>
                                <div class="col">
                                    <div class="card h-100">
                                        <img src="../<?php echo clean($image['image_path']); ?>" class="card-img-top" alt="..." style="height: 150px; object-fit: cover;">
                                        <div class="card-body p-2">
                                            <p class="small fw-bold mb-1"><?php echo clean($image['title']); ?></p>
                                            <p class="small text-muted mb-2"><?php echo clean($image['subtitle']); ?></p>
                                            <form method="POST" onsubmit="return confirm('Bu resmi silmek istediğinize emin misiniz?');">
                                                <input type="hidden" name="image_id" value="<?php echo $image['id']; ?>">
                                                <button type="submit" name="delete_slider_image" class="btn btn-sm btn-danger w-100">
                                                    <i class="bi bi-trash"></i> Sil
                                                </button>
                                            </form>
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
