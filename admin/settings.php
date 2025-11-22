<?php
session_start();
require_once '../config/database.php';
require_once '../config/functions.php';
checkAdminLogin();
$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $site_name = $_POST['site_name'] ?? '';
    $site_title = $_POST['site_title'] ?? '';
    $site_description = $_POST['site_description'] ?? '';
    $primary_color = $_POST['primary_color'] ?? '#667eea';
    $secondary_color = $_POST['secondary_color'] ?? '#764ba2';
    $text_color = $_POST['text_color'] ?? '#495057';

    $stmt = $db->prepare(
        "UPDATE site_settings SET 
            site_name = ?, 
            site_title = ?, 
            site_description = ?,
            primary_color = ?,
            secondary_color = ?,
            text_color = ?
        WHERE id = 1"
    );
    $stmt->execute([
        $site_name, 
        $site_title, 
        $site_description,
        $primary_color,
        $secondary_color,
        $text_color
    ]);

    setSuccessMessage('Ayarlar başarıyla kaydedildi.');
    header('Location: settings.php');
    exit();
}

$settings = getSiteSettings();
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Site Ayarları</title>
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
                <h1 class="h2"><i class="bi bi-sliders me-2"></i>Site Ayarları</h1>
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

            <form method="POST">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Genel Ayarlar</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Site Adı</label>
                                    <input type="text" name="site_name" class="form-control" value="<?php echo clean($settings['site_name'] ?? ''); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Site Başlığı</label>
                                    <input type="text" name="site_title" class="form-control" value="<?php echo clean($settings['site_title'] ?? ''); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Site Açıklaması</label>
                                    <textarea name="site_description" class="form-control" rows="3"><?php echo clean($settings['site_description'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-palette me-2"></i>Renk Şeması</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="primary_color" class="form-label">Ana Renk</label>
                                        <input type="color" class="form-control form-control-color" id="primary_color" name="primary_color" value="<?php echo clean($settings['primary_color'] ?? '#667eea'); ?>">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="secondary_color" class="form-label">İkincil Renk</label>
                                        <input type="color" class="form-control form-control-color" id="secondary_color" name="secondary_color" value="<?php echo clean($settings['secondary_color'] ?? '#764ba2'); ?>">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="text_color" class="form-label">Metin Rengi</label>
                                        <input type="color" class="form-control form-control-color" id="text_color" name="text_color" value="<?php echo clean($settings['text_color'] ?? '#495057'); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-save me-2"></i>Kaydet</h5>
                            </div>
                            <div class="card-body">
                                <p>Ayarları kaydetmek için butona tıklayın.</p>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-check-circle me-2"></i>Ayarları Kaydet
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>