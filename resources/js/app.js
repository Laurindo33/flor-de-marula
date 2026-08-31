import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

document.querySelectorAll('[data-fm-search]').forEach((wrapper) => {
    const toggle = wrapper.querySelector('[data-fm-search-toggle]');
    const input = wrapper.querySelector('[data-fm-search-input]');

    toggle.addEventListener('click', () => {
        const isOpen = wrapper.classList.toggle('is-open');
        if (isOpen) {
            input.focus();
        } else if (!input.value) {
            wrapper.classList.remove('is-open');
        }
    });

    document.addEventListener('click', (event) => {
        if (!wrapper.contains(event.target) && !input.value) {
            wrapper.classList.remove('is-open');
        }
    });
});

document.querySelectorAll('[data-carousel-dots]').forEach((dotsContainer) => {
    const carousel = dotsContainer.previousElementSibling;
    if (!carousel || carousel.children.length === 0) return;

    const dots = Array.from(dotsContainer.querySelectorAll('[data-carousel-dot]'));
    const items = Array.from(carousel.children);

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            items[index].scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
        });
    });

    let ticking = false;
    carousel.addEventListener('scroll', () => {
        if (ticking) return;
        ticking = true;
        requestAnimationFrame(() => {
            const center = carousel.scrollLeft + carousel.clientWidth / 2;
            let closest = 0;
            let closestDistance = Infinity;
            items.forEach((item, index) => {
                const distance = Math.abs((item.offsetLeft + item.offsetWidth / 2) - center);
                if (distance < closestDistance) {
                    closestDistance = distance;
                    closest = index;
                }
            });
            dots.forEach((dot, index) => dot.classList.toggle('is-active', index === closest));
            ticking = false;
        });
    });
});

document.querySelectorAll('[data-fm-testimonials-toggle]').forEach((button) => {
    const grid = document.querySelector('[data-fm-testimonials-grid]');
    if (!grid) return;

    button.addEventListener('click', () => {
        const expanded = grid.classList.toggle('is-expanded');
        button.textContent = expanded ? button.dataset.lessLabel : button.dataset.moreLabel;
        if (!expanded) {
            grid.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    });
});

