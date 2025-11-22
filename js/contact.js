function initContactForm() {
    const form = document.getElementById('contactForm');
    if (!form) return;

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const alertPlaceholder = document.getElementById('contactAlert');

        if (form.checkValidity()) {
            // Form geçerli, gönderme simülasyonu
            alertPlaceholder.innerHTML = '<div class="alert alert-success">Mesajınız başarıyla gönderildi!</div>';
            form.reset();
        } else {
            // Form geçersiz
            alertPlaceholder.innerHTML = '<div class="alert alert-danger">Lütfen tüm alanları doldurun.</div>';
        }
        
        // Form doğrulama stillerini ekle
        form.classList.add('was-validated');
    });
}
