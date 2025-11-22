// Basit site etkileşimleri: smooth scroll, iletişim formu simülasyonu, portföy modal doldurma
(function(){
  'use strict';

  // Tema yönetimi
  const themeKey = 'site-theme';
  const body = document.body;
  const themeToggle = document.getElementById('themeToggle');

  function applyTheme(theme) {
    if (theme === 'dark') {
      body.classList.add('dark-theme');
      if (themeToggle) themeToggle.innerHTML = '<i class="bi bi-sun-fill"></i>';
    } else {
      body.classList.remove('dark-theme');
      if (themeToggle) themeToggle.innerHTML = '<i class="bi bi-moon-stars-fill"></i>';
    }
    localStorage.setItem(themeKey, theme);
  }

  try {
    const savedTheme = localStorage.getItem(themeKey) || 'light';
    applyTheme(savedTheme);
  } catch (e) {
    applyTheme('light');
  }

  if (themeToggle) {
    themeToggle.addEventListener('click', () => {
      const currentTheme = body.classList.contains('dark-theme') ? 'dark' : 'light';
      applyTheme(currentTheme === 'dark' ? 'light' : 'dark');
    });
  }

  // Navbar scrolled glass effect
  const mainNav = document.getElementById('mainNav');
  function onScrollNav() {
    if (!mainNav) return;
    if (window.scrollY > 28) mainNav.classList.add('scrolled'); else mainNav.classList.remove('scrolled');
  }
  window.addEventListener('scroll', onScrollNav, { passive: true });
  onScrollNav();

  // Smooth scroll for internal anchors (account for fixed header)
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      const href = this.getAttribute('href');
      if (href === '#' || href === '') return;
      const target = document.querySelector(href);
      if (target) {
        e.preventDefault();
        const offset = Math.max(72, Math.round(window.innerWidth * 0.02));
        const top = target.getBoundingClientRect().top + window.scrollY - offset;
        window.scrollTo({ top, behavior: 'smooth' });
      }
    });
  });

  // Hero subtle parallax on scroll
  const heroImg = document.querySelector('.hero-image-wrapper img');
  function heroParallax() {
    if (!heroImg) return;
    const scrolled = window.scrollY;
    const move = Math.min(80, scrolled * 0.12);
    heroImg.style.transform = `translateY(${move}px) scale(1.02)`;
  }
  window.addEventListener('scroll', heroParallax, { passive: true });
  heroParallax();

  // Kaydırma animasyonları
  const scrollObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll('.animate-on-scroll').forEach(el => {
    scrollObserver.observe(el);
  });

  // Portföy filtreleme
  const filterButtons = document.querySelectorAll('[data-filter]');
  const portfolioItems = document.querySelectorAll('.portfolio-item');
  const portfolioGrid = document.querySelector('.portfolio-grid');

  if (filterButtons.length > 0) {
    filterButtons.forEach(button => {
      button.addEventListener('click', () => {
        const filterValue = button.getAttribute('data-filter');
        
        filterButtons.forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');

        portfolioGrid.style.height = portfolioGrid.offsetHeight + 'px';

        portfolioItems.forEach(item => {
          if (filterValue === 'all' || item.dataset.category === filterValue) {
            item.classList.remove('filtered-out');
          } else {
            item.classList.add('filtered-out');
          }
        });
        
        setTimeout(() => {
            portfolioGrid.style.height = '';
        }, 350);
      });
    });
  }

  // İletişim formu
  const contactForm = document.getElementById('contactForm');
  if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
      e.preventDefault();
      e.stopPropagation();
      if (!contactForm.checkValidity()) {
        contactForm.classList.add('was-validated');
        return;
      }
      
      const alertPlaceholder = document.getElementById('contactAlert');
      alertPlaceholder.innerHTML = '<div class="alert alert-info">Gönderiliyor...</div>';
      
      setTimeout(() => {
        alertPlaceholder.innerHTML = '<div class="alert alert-success">Mesajınız başarıyla gönderildi!</div>';
        contactForm.reset();
        contactForm.classList.remove('was-validated');
      }, 1000);
    });
  }

  // Slider için otomatik geçiş süresi ayarı
  const sliderElement = document.querySelector('#carouselExampleIndicators');
  if (sliderElement) {
    const slider = new bootstrap.Carousel(sliderElement, {
      interval: 5000, // 5 saniyede bir geçiş
      ride: 'carousel'
    });
  }

})();
