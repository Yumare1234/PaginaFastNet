document.addEventListener('DOMContentLoaded', () => {
    const observerOptions = {
        threshold: 0.15
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.scroll-reveal, .fade-in').forEach(el => {
        observer.observe(el);
    });

    const navLinks = [...document.querySelectorAll('nav a[href^="#"]')];
    const sections = navLinks
        .map(link => document.querySelector(link.getAttribute('href')))
        .filter(Boolean);

    if (sections.length > 0) {
        const sectionObserver = new IntersectionObserver((entries) => {
            const visibleEntry = entries
                .filter(entry => entry.isIntersecting)
                .sort((a, b) => b.intersectionRatio - a.intersectionRatio)[0];

            if (!visibleEntry) return;

            const currentId = visibleEntry.target.id;

            navLinks.forEach(link => {
                const isActive = link.getAttribute('href') === `#${currentId}`;
                link.classList.toggle('active', isActive);
                if (isActive) {
                    link.setAttribute('aria-current', 'page');
                } else {
                    link.removeAttribute('aria-current');
                }
            });
        }, {
            rootMargin: '-20% 0px -55% 0px',
            threshold: [0.15, 0.35, 0.6]
        });

        sections.forEach(section => sectionObserver.observe(section));
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const track = document.getElementById('servicesTrack');
    const prevBtn = document.getElementById('prevServiceBtn');
    const nextBtn = document.getElementById('nextServiceBtn');
    const dotsContainer = document.getElementById('carouselDots');
    const container = document.getElementById('carouselContainer');
    const cards = track.querySelectorAll('.service-card-item');

    let autoPlayInterval = null;

    cards.forEach((_, index) => {
        const dot = document.createElement('div');
        dot.classList.add('dot');
        if (index === 0) dot.classList.add('active');
        dot.addEventListener('click', () => scrollToCard(index));
        dotsContainer.appendChild(dot);
    });

    const dots = dotsContainer.querySelectorAll('.dot');

    function getCardWidth() {
        return cards[0].offsetWidth + 24;
    }

    function scrollToCard(index) {
        track.scrollTo({ left: getCardWidth() * index, behavior: 'smooth' });
    }

    function nextSlide() {
        const maxScroll = track.scrollWidth - track.clientWidth;
        if (track.scrollLeft >= maxScroll - 10) {
            track.scrollTo({ left: 0, behavior: 'smooth' });
        } else {
            track.scrollBy({ left: getCardWidth(), behavior: 'smooth' });
        }
    }

    function prevSlide() {
        if (track.scrollLeft <= 10) {
            track.scrollTo({ left: track.scrollWidth, behavior: 'smooth' });
        } else {
            track.scrollBy({ left: -getCardWidth(), behavior: 'smooth' });
        }
    }

    nextBtn.addEventListener('click', nextSlide);
    prevBtn.addEventListener('click', prevSlide);

    track.addEventListener('scroll', () => {
        const activeIndex = Math.round(track.scrollLeft / getCardWidth());
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === activeIndex);
        });
    });

    function startAutoPlay() {
        stopAutoPlay();
        autoPlayInterval = setInterval(nextSlide, 2000);
    }

    function stopAutoPlay() {
        if (autoPlayInterval) {
            clearInterval(autoPlayInterval);
        }
    }

    container.addEventListener('mouseenter', stopAutoPlay);
    container.addEventListener('mouseleave', startAutoPlay);
    container.addEventListener('touchstart', stopAutoPlay, { passive: true });
    container.addEventListener('touchend', startAutoPlay, { passive: true });

    startAutoPlay();
});

function redirigirFormulario(event) {
    event.preventDefault();
    window.location.href = 'pages/public/contratar.html';
}
