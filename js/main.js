document.addEventListener('DOMContentLoaded', () => {
    const mainContent = document.getElementById('main-content');
    const navLinks = document.querySelectorAll('.nav-link');
    const navbarBrand = document.querySelector('.navbar-brand');

    // Bölümleri dinamik olarak yükleyen fonksiyon
    const loadContent = async (section) => {
        // Eğer section belirtilmemişse 'hero' olarak ayarla
        const sectionName = section || 'hero';
        try {
            // İlgili HTML dosyasını fetch ile al
            const response = await fetch(`${sectionName}.html`);
            if (response.ok) {
                mainContent.innerHTML = await response.text();
                
                // Yüklenen bölüme özel script'leri (varsa) yeniden başlat
                if (sectionName === 'portfolio' && typeof initPortfolioFilter === 'function') {
                    initPortfolioFilter();
                }
                if (sectionName === 'contact' && typeof initContactForm === 'function') {
                    initContactForm();
                }
                // Genel animasyonları her içerik yüklendiğinde başlat
                if (typeof initAnimations === 'function') {
                    initAnimations();
                }
            } else {
                mainContent.innerHTML = `<p class="text-center text-danger">Hata: '${sectionName}' bölümü yüklenemedi.</p>`;
            }
        } catch (error) {
            mainContent.innerHTML = `<p class="text-center text-danger">Hata: '${sectionName}' bölümü getirilemedi.</p>`;
            console.error(`'${sectionName}' bölümü getirilirken hata:`, error);
        }
    };

    // Navigasyon tıklamalarını yöneten fonksiyon
    const handleNavigation = (e) => {
        e.preventDefault();
        const link = e.currentTarget;
        const section = link.hash.substring(1);

        // Tarayıcı geçmişine yeni bir durum ekle (URL'i güncelle)
        window.history.pushState({ section }, `${section}`, link.hash);

        // İçeriği yükle
        loadContent(section);

        // Aktif menü öğesini güncelle
        navLinks.forEach(navLink => navLink.classList.remove('active'));
        link.classList.add('active');
    };
    
    // Navigasyon linklerine tıklama olayını ekle
    navLinks.forEach(link => {
        link.addEventListener('click', handleNavigation);
    });

    // Marka logosuna tıklanınca anasayfayı yükle
    navbarBrand.addEventListener('click', (e) => {
        handleNavigation({ currentTarget: document.querySelector('a.nav-link[href="#hero"]'), preventDefault: () => {} });
    });

    // Sayfa yüklendiğinde veya geri/ileri butonları kullanıldığında doğru içeriği gösteren router
    const router = () => {
        const section = window.location.hash.substring(1) || 'hero';
        loadContent(section);

        // Aktif linki ayarla
        navLinks.forEach(link => {
            link.classList.toggle('active', link.hash === `#${section}` || (section === 'hero' && link.hash === '#'));
        });
    };

    // Tarayıcı geçmişinde gezinmeyi dinle (geri/ileri butonları)
    window.addEventListener('popstate', (e) => {
        const section = e.state ? e.state.section : 'hero';
        loadContent(section);
    });

    // Sayfa ilk yüklendiğinde router'ı çalıştır
    router();


    // --- Mevcut Tema ve Navbar Davranışları ---
    
    // Tema değiştirici
    const initThemeToggle = () => {
        const themeToggle = document.getElementById('themeToggle');
        if (!themeToggle) return;
        themeToggle.addEventListener('click', () => {
            document.body.classList.toggle('dark-theme');
            const icon = themeToggle.querySelector('i');
            icon.classList.toggle('bi-moon-stars-fill', !document.body.classList.contains('dark-theme'));
            icon.classList.toggle('bi-sun-fill', document.body.classList.contains('dark-theme'));
        });
    };

    // Navbar scroll davranışı
    const initNavbar = () => {
        const mainNav = document.getElementById('mainNav');
        if (mainNav) {
            window.addEventListener('scroll', () => {
                mainNav.classList.toggle('scrolled', window.scrollY > 50);
            });
        }
    };

    initThemeToggle();
    initNavbar();
});
