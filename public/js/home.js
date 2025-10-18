document.addEventListener("DOMContentLoaded", function() {

    // 1. Navbar Scroll Effect
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    }

    // 2. Smooth Scrolling for Anchor Links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();

            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // 3. Initialize AOS (Animate On Scroll) Library
    // Pastikan AOS sudah di-load di layouts/main.blade.php
    AOS.init({
        duration: 800, // Durasi animasi dalam milidetik
        once: true, // Animasi hanya terjadi sekali
        offset: 120, // Jarak trigger animasi dari bawah layar
        easing: 'ease-in-out',
    });

});

