// Hamburger Menu Toggle
        const hamburger = document.querySelector('#hamburger-menu');
        const navbarNav = document.querySelector('.isi-navbar');

        hamburger.addEventListener('click', function(e) {
            navbarNav.classList.toggle('active');
            e.preventDefault();
        });

        // Click outside to close menu
        document.addEventListener('click', function(e) {
            if (!hamburger.contains(e.target) && !navbarNav.contains(e.target)) {
                navbarNav.classList.remove('active');
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
                // Close mobile menu if open
                navbarNav.classList.remove('active');
            });
        });

        // Navbar background on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 100) {
                navbar.style.backgroundColor = 'rgba(240, 248, 255, 0.95)';
                navbar.style.backdropFilter = 'blur(10px)';
            } else {
                navbar.style.backgroundColor = 'var(--accent)';
                navbar.style.backdropFilter = 'none';
            }
        });
