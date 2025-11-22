<?php
session_start();
require_once '../config/database.php';
require_once '../config/functions.php';
checkAdminLogin();
$db = getDB();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$errors = [];
$portfolio = null;
if ($id) {
    $stmt = $db->prepare("SELECT * FROM portfolio WHERE id = ?");
    $stmt->execute([$id]);
    $portfolio = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Geçersiz CSRF token.';
    }

    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $category = $_POST['category'] ?? '';
    $project_url = $_POST['project_url'] ?? '';
    $github_url = $_POST['github_url'] ?? '';
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // Resim yükleme
    $imagePath = $portfolio['image'] ?? null;
    if (!empty($_FILES['image']['name'])) {
        $upload = uploadFile($_FILES['image'], 'uploads/portfolio/');
        if ($upload['success']) {
            // eski dosyayı sil
            if (!empty($portfolio['image'])) deleteFile($portfolio['image']);
            $imagePath = $upload['filename'];
        } else {
            $errors[] = $upload['message'];
        }
    }

    if (empty($title)) {
        $errors[] = 'Başlık boş olamaz.';
    }

    if (empty($errors)) {
        if ($id) {
            $stmt = $db->prepare("UPDATE portfolio SET title = ?, description = ?, image = ?, category = ?, project_url = ?, github_url = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$title, $description, $imagePath, $category, $project_url, $github_url, $is_active, $id]);
            setSuccessMessage('Proje güncellendi.');
        } else {
            $stmt = $db->prepare("INSERT INTO portfolio (title, description, image, category, project_url, github_url, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $description, $imagePath, $category, $project_url, $github_url, $is_active]);
            setSuccessMessage('Proje eklendi.');
        }
        header('Location: portfolio.php');
        exit();
    }
}

$csrf = generateCSRFToken();
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $id ? 'Projeyi Düzenle' : 'Yeni Proje'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<div class="container-fluid">
    <div class="row">
        <?php include 'includes/sidebar.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h2><?php echo $id ? 'Projeyi Düzenle' : 'Yeni Proje Ekle'; ?></h2>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $err) echo '<div>'.htmlspecialchars($err).'</div>'; ?>
                </div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf; ?>">
                <div class="mb-3">
                    <label class="form-label">Başlık</label>
                    <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($portfolio['title'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Açıklama</label>
                    <textarea name="description" class="form-control" rows="6"><?php echo htmlspecialchars($portfolio['description'] ?? ''); ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="category" class="form-control" value="<?php echo htmlspecialchars($portfolio['category'] ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Proje URL</label>
                    <input type="url" name="project_url" class="form-control" value="<?php echo htmlspecialchars($portfolio['project_url'] ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">GitHub URL</label>
                    <input type="url" name="github_url" class="form-control" value="<?php echo htmlspecialchars($portfolio['github_url'] ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Kapak Resmi</label>
                    <?php if (!empty($portfolio['image'])): ?>
                        <div class="mb-2"><img src="../<?php echo htmlspecialchars($portfolio['image']); ?>" alt="" style="max-width:200px; border-radius:6px;" /></div>
                    <?php endif; ?>
                    <input type="file" name="image" accept="image/*" class="form-control">
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" <?php echo (isset($portfolio['is_active']) && $portfolio['is_active']) || !$id ? 'checked' : ''; ?>>
                    <label for="is_active" class="form-check-label">Yayında</label>
                </div>
                <button class="btn btn-primary"><?php echo $id ? 'Güncelle' : 'Ekle'; ?></button>
                <a href="portfolio.php" class="btn btn-outline-secondary">İptal</a>
            </form>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>