<?php
// admin/includes/header.php
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top main-header p-0 shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="index.php">
            <strong><?php echo htmlspecialchars(getSiteSettings()['site_name'] ?? 'Admin'); ?></strong>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="d-flex align-items-center">
            <div class="nav-item text-nowrap">
                <a class="nav-link px-3" href="logout.php">
                    <i class="bi bi-box-arrow-right me-1"></i> Çıkış Yap
                </a>
            </div>
        </div>
    </div>
</nav>