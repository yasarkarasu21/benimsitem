<?php
session_start();
require_once '../config/database.php';
require_once '../config/functions.php';

checkAdminLogin();

// İstatistikler
$db = getDB();
$stats = [
    'portfolios' => $db->query("SELECT COUNT(*) as count FROM portfolio")->fetch()['count'],
    'services' => $db->query("SELECT COUNT(*) as count FROM services")->fetch()['count'],
    'skills' => $db->query("SELECT COUNT(*) as count FROM skills")->fetch()['count'],
    'messages' => $db->query("SELECT COUNT(*) as count FROM contact_messages WHERE is_read = 0")->fetch()['count'],
    'youtube' => $db->query("SELECT COUNT(*) as count FROM youtube_videos")->fetch()['count'],
    'instagram' => $db->query("SELECT COUNT(*) as count FROM instagram_posts")->fetch()['count'],
];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><i class="bi bi-speedometer2 me-2"></i>Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="../index.php" class="btn btn-sm btn-outline-primary" target="_blank">
                            <i class="bi bi-box-arrow-up-right me-1"></i>Siteyi Görüntüle
                        </a>
                    </div>
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

                <!-- İstatistik Kartları -->
                <div class="row g-4 mb-4">
                    <div class="col-xl-3 col-md-6">
                        <div class="card border-0 shadow-sm stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1 small">Portfolio Projeleri</p>
                                        <h3 class="mb-0 fw-bold"><?php echo $stats['portfolios']; ?></h3>
                                    </div>
                                    <div class="stat-icon bg-primary">
                                        <i class="bi bi-briefcase text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="card border-0 shadow-sm stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1 small">Hizmetler</p>
                                        <h3 class="mb-0 fw-bold"><?php echo $stats['services']; ?></h3>
                                    </div>
                                    <div class="stat-icon bg-success">
                                        <i class="bi bi-gear text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="card border-0 shadow-sm stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1 small">Yetenekler</p>
                                        <h3 class="mb-0 fw-bold"><?php echo $stats['skills']; ?></h3>
                                    </div>
                                    <div class="stat-icon bg-info">
                                        <i class="bi bi-star text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="card border-0 shadow-sm stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1 small">Okunmamış Mesaj</p>
                                        <h3 class="mb-0 fw-bold"><?php echo $stats['messages']; ?></h3>
                                    </div>
                                    <div class="stat-icon bg-warning">
                                        <i class="bi bi-envelope text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hızlı Erişim -->
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white border-bottom">
                                <h5 class="card-title mb-0"><i class="bi bi-lightning me-2 text-warning"></i>Hızlı Erişim</h5>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    <a href="portfolio.php" class="list-group-item list-group-item-action">
                                        <i class="bi bi-briefcase me-2 text-primary"></i>Portfolio Yönetimi
                                    </a>
                                    <a href="services.php" class="list-group-item list-group-item-action">
                                        <i class="bi bi-gear me-2 text-success"></i>Hizmetler
                                    </a>
                                    <a href="skills.php" class="list-group-item list-group-item-action">
                                        <i class="bi bi-star me-2 text-info"></i>Yetenekler
                                    </a>
                                    <a href="youtube.php" class="list-group-item list-group-item-action">
                                        <i class="bi bi-youtube me-2 text-danger"></i>YouTube Videoları
                                    </a>
                                    <a href="instagram.php" class="list-group-item list-group-item-action">
                                        <i class="bi bi-instagram me-2 text-danger"></i>Instagram Gönderileri
                                    </a>
                                    <a href="settings.php" class="list-group-item list-group-item-action">
                                        <i class="bi bi-sliders me-2 text-secondary"></i>Site Ayarları
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white border-bottom">
                                <h5 class="card-title mb-0"><i class="bi bi-envelope me-2 text-primary"></i>Son Mesajlar</h5>
                            </div>
                            <div class="card-body">
                                <?php
                                $recent_messages = $db->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5")->fetchAll();
                                if (!empty($recent_messages)):
                                ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach ($recent_messages as $msg): ?>
                                    <a href="messages.php?id=<?php echo $msg['id']; ?>" class="list-group-item list-group-item-action <?php echo $msg['is_read'] ? '' : 'fw-bold'; ?>">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1 <?php echo $msg['is_read'] ? 'text-muted' : ''; ?>">
                                                <?php echo clean($msg['name']); ?>
                                                <?php if (!$msg['is_read']): ?>
                                                <span class="badge bg-primary">Yeni</span>
                                                <?php endif; ?>
                                            </h6>
                                            <small class="text-muted"><?php echo formatDate($msg['created_at'], 'd.m.Y'); ?></small>
                                        </div>
                                        <p class="mb-1 small text-muted"><?php echo truncate(clean($msg['subject']), 50); ?></p>
                                    </a>
                                    <?php endforeach; ?>
                                </div>
                                <div class="text-center mt-3">
                                    <a href="messages.php" class="btn btn-sm btn-outline-primary">Tüm Mesajları Gör</a>
                                </div>
                                <?php else: ?>
                                <p class="text-muted text-center py-4">Henüz mesaj bulunmuyor.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sosyal Medya İstatistikleri -->
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom">
                                <h5 class="card-title mb-0"><i class="bi bi-youtube me-2 text-danger"></i>YouTube</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1">Toplam Video</p>
                                        <h3 class="mb-0 fw-bold"><?php echo $stats['youtube']; ?></h3>
                                    </div>
                                    <a href="youtube.php" class="btn btn-outline-danger">
                                        <i class="bi bi-plus-circle me-1"></i>Video Ekle
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom">
                                <h5 class="card-title mb-0"><i class="bi bi-instagram me-2 text-danger"></i>Instagram</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1">Toplam Gönderi</p>
                                        <h3 class="mb-0 fw-bold"><?php echo $stats['instagram']; ?></h3>
                                    </div>
                                    <a href="instagram.php" class="btn btn-outline-danger">
                                        <i class="bi bi-plus-circle me-1"></i>Gönderi Ekle
                                    </a>
                                </div>
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
