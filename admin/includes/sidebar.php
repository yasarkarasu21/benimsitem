<?php
// admin/includes/sidebar.php
?>
<nav id="sidebarMenu" class="sidebar col-md-3 col-lg-2 d-md-block">
    <div class="sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo isActive('index.php'); ?>" href="index.php">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo isActive('slider.php'); ?>" href="slider.php">
                    <i class="bi bi-images"></i> Slider
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo isActive('portfolio.php'); ?>" href="portfolio.php">
                    <i class="bi bi-briefcase"></i> Portfolio
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo isActive('services.php'); ?>" href="services.php">
                    <i class="bi bi-gear"></i> Hizmetler
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo isActive('skills.php'); ?>" href="skills.php">
                    <i class="bi bi-star"></i> Yetenekler
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo isActive('youtube.php'); ?>" href="youtube.php">
                    <i class="bi bi-youtube"></i> YouTube
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo isActive('instagram.php'); ?>" href="instagram.php">
                    <i class="bi bi-instagram"></i> Instagram
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo isActive('messages.php'); ?>" href="messages.php">
                    <i class="bi bi-envelope"></i> Mesajlar
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo isActive('settings.php'); ?>" href="settings.php">
                    <i class="bi bi-sliders"></i> Ayarlar
                </a>
            </li>
        </ul>
    </div>
</nav>