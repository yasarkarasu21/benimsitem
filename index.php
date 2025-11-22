<?php
session_start();
require_once 'config/database.php';
require_once 'config/functions.php';

// Fetch all data from the database
$settings = getSiteSettings();
$slider_images = getSliderImages(); // <-- Yeni fonksiyon
$about = getAboutContent();
$services = getServices();
$portfolio_items = getPortfolio(); // Get all portfolio items
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo clean($settings['site_title'] ?? 'Profesyonel Portfolyo'); ?></title>
    <meta name="description" content="<?php echo clean($settings['site_description'] ?? ''); ?>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/dynamic-styles.php">
    <style>
        .hero-section {
            position: relative;
            overflow: hidden;
        }
        .carousel-item {
            height: 100vh;
            background-size: cover;
            background-position: center;
        }
        .carousel-caption {
            bottom: 20%;
            background: rgba(0,0,0,0.4);
            padding: 2rem;
            border-radius: 1rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top" style="background: transparent; border: none;">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"><?php echo clean($settings['site_name'] ?? 'Logo'); ?></a>
            <div class="ms-auto">
                 <button id="themeToggle" class="btn" type="button">
                    <i class="bi bi-moon-stars-fill"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Slider -->
    <section id="hero" class="hero-section p-0">
        <div id="tsparticles" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 0;"></div>
        
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" style="z-index: 1;">
            <div class="carousel-inner">
                <?php if (!empty($slider_images)): ?>
                    <?php foreach ($slider_images as $key => $slide): ?>
                    <div class="carousel-item <?php echo $key == 0 ? 'active' : ''; ?>" style="background-image: url('<?php echo clean($slide['image_path']); ?>')">
                        <div class="carousel-caption d-none d-md-block text-center">
                            <?php if (!empty($slide['title'])): ?>
                                <h1 class="display-3 fw-bolder"><?php echo clean($slide['title']); ?></h1>
                            <?php endif; ?>
                            <?php if (!empty($slide['subtitle'])): ?>
                                <p class="lead mt-3"><?php echo clean($slide['subtitle']); ?></p>
                            <?php endif; ?>
                            <div class="d-flex gap-3 justify-content-center mt-4">
                                 <a href="#portfolio" class="btn btn-primary btn-lg">Projelerimi Gör</a>
                                 <a href="#contact" class="btn btn-outline-light btn-lg">İletişime Geç</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="carousel-item active" style="background-color: #667eea;">
                         <div class="carousel-caption d-none d-md-block text-center">
                            <h1 class="display-3 fw-bolder">Slider'a Resim Ekleyin</h1>
                            <p class="lead mt-3">Admin panelinden slider bölümüne giderek yeni resimler ekleyebilirsiniz.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if (count($slider_images) > 1): ?>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- Portfolio Section -->
    <?php if (!empty($portfolio_items)): ?>
    <section id="portfolio" class="content-section">
        <div class="container">
            <h2 class="section-title">Portfolyo</h2>
            
            <!-- Filters -->
            <div class="portfolio-filters">
                <button class="filter-btn active" data-filter="all">Tümü</button>
                <?php 
                $categories = array_unique(array_column($portfolio_items, 'category'));
                foreach ($categories as $category): 
                    if (!empty($category)):
                ?>
                <button class="filter-btn" data-filter="<?php echo strtolower(clean($category)); ?>"><?php echo clean($category); ?></button>
                <?php 
                    endif;
                endforeach; 
                ?>
            </div>

            <!-- Grid -->
            <div class="portfolio-grid">
                <?php foreach ($portfolio_items as $project): ?>
                <div class="portfolio-item" data-category="<?php echo strtolower(clean($project['category'])); ?>">
                    <?php if ($project['image']): ?>
                    <img src="<?php echo clean($project['image']); ?>" alt="<?php echo clean($project['title']); ?>" class="portfolio-item-image">
                    <?php endif; ?>
                    <div class="portfolio-item-content">
                        <?php if ($project['category']): ?>
                        <div class="category-badge"><?php echo clean($project['category']); ?></div>
                        <?php endif; ?>
                        <h4><?php echo clean($project['title']); ?></h4>
                        <p><?php echo truncate(clean($project['description']), 120); ?></p>
                        <div class="mt-3 d-flex gap-2">
                            <?php if ($project['project_url']): ?>
                            <a href="<?php echo clean($project['project_url']); ?>" class="btn btn-sm btn-primary" target="_blank">Demo</a>
                            <?php endif; ?>
                            <?php if ($project['github_url']): ?>
                            <a href="<?php echo clean($project['github_url']); ?>" class="btn btn-sm btn-outline-secondary" target="_blank">GitHub</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

     <!-- Other sections can be placed here, following the new design philosophy -->
     <!-- For example, an About/Timeline or Testimonials section -->

    <!-- Contact Section -->
    <section id="contact" class="content-section" style="background: var(--surface-light)">
         <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <h2 class="section-title">İletişim</h2>
                    <form action="contact_process.php" method="POST">
                        <div class="mb-3">
                            <input type="text" class="form-control" name="name" placeholder="Adınız" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" class="form-control" name="email" placeholder="E-posta Adresiniz" required>
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" name="message" rows="6" placeholder="Mesajınız" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Mesajı Gönder</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-4 text-center">
        <p class="text-muted small">© <?php echo date('Y'); ?> <?php echo clean($settings['site_name']); ?>. Tüm Hakları Saklıdır.</p>
    </footer>

    <!-- tsParticles CDN -->
    <script src="https://cdn.jsdelivr.net/npm/tsparticles@3.1.0/tsparticles.bundle.min.js"></script>
    
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // --- Theme Toggle ---
        const themeToggle = document.getElementById('themeToggle');
        const body = document.body;
        if (localStorage.getItem('theme') === 'dark') {
            body.classList.add('dark-theme');
            themeToggle.querySelector('i').classList.replace('bi-moon-stars-fill', 'bi-sun-fill');
        }
        themeToggle.addEventListener('click', () => {
            body.classList.toggle('dark-theme');
            const isDark = body.classList.contains('dark-theme');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            themeToggle.querySelector('i').classList.toggle('bi-moon-stars-fill');
            themeToggle.querySelector('i').classList.toggle('bi-sun-fill');
        });

        // --- tsParticles Initialization ---
        tsParticles.load("tsparticles", {
            background: {
                color: { value: body.classList.contains('dark-theme') ? '#111827' : '#ffffff' }
            },
            fpsLimit: 60,
            interactivity: {
                events: {
                    onHover: { enable: true, mode: "repulse" },
                    resize: true
                },
                modes: {
                    repulse: { distance: 100, duration: 0.4 }
                }
            },
            particles: {
                color: { value: "#888" },
                links: {
                    color: "#888",
                    distance: 150,
                    enable: true,
                    opacity: 0.2,
                    width: 1
                },
                move: {
                    direction: "none",
                    enable: true,
                    outModes: { default: "bounce" },
                    random: false,
                    speed: 1,
                    straight: false
                },
                number: {
                    density: { enable: true, area: 800 },
                    value: 80
                },
                opacity: { value: 0.2 },
                shape: { type: "circle" },
                size: { value: { min: 1, max: 5 } }
            },
            detectRetina: true
        });

        // --- Portfolio Filter Logic ---
        const filterContainer = document.querySelector(".portfolio-filters");
        const portfolioItems = document.querySelectorAll(".portfolio-item");

        if (filterContainer) {
            filterContainer.addEventListener("click", (event) => {
                if (event.target.classList.contains("filter-btn")) {
                    // Deactivate existing active button
                    filterContainer.querySelector(".active").classList.remove("active");
                    // Activate new button
                    event.target.classList.add("active");

                    const filterValue = event.target.getAttribute("data-filter");
                    
                    portfolioItems.forEach(item => {
                        if (filterValue === 'all' || item.dataset.category.includes(filterValue)) {
                            item.classList.remove('hide');
                        } else {
                            item.classList.add('hide');
                        }
                    });
                }
            });
        }
    });
    </script>
</body>
</html>
