const mainImage = document.querySelector('[data-fm-gallery-main]');
const thumbs = document.querySelectorAll('[data-fm-gallery-thumb]');

thumbs.forEach((thumb) => {
    thumb.addEventListener('click', () => {
        if (!mainImage) return;

        mainImage.src = thumb.dataset.image;

        thumbs.forEach((t) => t.classList.remove('active'));
        thumb.classList.add('active');
    });
});

const offers = document.querySelectorAll('.fm-pdp__offer');

offers.forEach((offer) => {
    offer.addEventListener('click', () => {
        offers.forEach((o) => o.classList.remove('active'));
        offer.classList.add('active');
    });
});
