// Letakkan ini di dalam file public/js/script.js

// Feather Icons
feather.replace();

// Toggle class active untuk hamburger menu
const navbarNav = document.querySelector(".navbar .isi-navbar");
const hamburgerMenu = document.querySelector("#hamburger-menu");

hamburgerMenu.onclick = (e) => {
    navbarNav.classList.toggle("active");
    e.preventDefault();
};

// Klik di luar elemen untuk menghilangkan nav
document.addEventListener("click", function (e) {
    if (!hamburgerMenu.contains(e.target) && !navbarNav.contains(e.target)) {
        navbarNav.classList.remove("active");
    }
});

// Kode untuk filter dan animasi kamar
//===========================================
// [BARU] Kode untuk Halaman Kamar
//===========================================
document.addEventListener("DOMContentLoaded", () => {
    // --- 1. Logika Filter Kamar ---
    const filterContainer = document.querySelector(".filter-bar");
    if (filterContainer) {
        const filterButtons = filterContainer.querySelectorAll(".filter-btn");
        const roomCards = document.querySelectorAll(".rooms-grid .room-card");

        filterButtons.forEach((button) => {
            button.addEventListener("click", () => {
                // Hapus class active dari semua tombol, lalu tambahkan ke yang di-klik
                filterButtons.forEach((btn) => btn.classList.remove("active"));
                button.classList.add("active");

                const filterValue = button.getAttribute("data-filter");

                roomCards.forEach((card) => {
                    const cardStatus = card.getAttribute("data-status");
                    // const cardType = card.getAttribute('data-type'); // Jika Anda pakai filter tipe

                    // Logika untuk menampilkan/menyembunyikan kartu
                    if (filterValue === "all" || filterValue === cardStatus) {
                        card.style.display = "flex";
                    } else {
                        card.style.display = "none";
                    }
                });
            });
        });
    }

    // --- 2. Logika Animasi Masuk (Scroll) ---
    const animatedCards = document.querySelectorAll(".room-card");
    if (animatedCards.length > 0) {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        // Tambahkan delay berdasarkan urutan elemen untuk efek stagger
                        entry.target.style.transitionDelay = `${index * 200}ms`; // Delay lebih lambat
                        entry.target.classList.add("is-visible");
                        observer.unobserve(entry.target); // Hentikan observasi setelah animasi berjalan
                    }
                });
            },
            {
                threshold: 0.1, // Muncul saat 10% elemen terlihat
            }
        );

        animatedCards.forEach((card) => {
            observer.observe(card);
        });
    }
});

// Tambahkan di script.js
document.addEventListener("DOMContentLoaded", function () {
    // Initialize all carousels
    const carousels = document.querySelectorAll(".carousel");
    carousels.forEach((carousel) => {
        // Set options
        const options = {
            interval: false, // Disable auto sliding
            touch: true, // Enable touch/swipe
            ride: false, // Disable auto cycle
        };

        // Initialize Bootstrap carousel with options
        new bootstrap.Carousel(carousel, options);

        // Add touch support
        let touchStartX = 0;
        let touchEndX = 0;

        carousel.addEventListener(
            "touchstart",
            (e) => {
                touchStartX = e.touches[0].clientX;
            },
            false
        );

        carousel.addEventListener(
            "touchend",
            (e) => {
                touchEndX = e.changedTouches[0].clientX;
                handleSwipe(carousel);
            },
            false
        );

        function handleSwipe(carousel) {
            const swipeThreshold = 50;
            const diff = touchEndX - touchStartX;

            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    // Swipe right - go to previous
                    bootstrap.Carousel.getInstance(carousel).prev();
                } else {
                    // Swipe left - go to next
                    bootstrap.Carousel.getInstance(carousel).next();
                }
            }
        }
    });
});
