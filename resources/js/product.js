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

const upsellModalEl = document.querySelector('[data-fm-upsell-modal]');
const upsellOptionsEl = document.querySelector('[data-fm-upsell-options]');
const offerRadios = document.querySelectorAll('[data-fm-offer-radio]');
const addForm = document.querySelector('[data-fm-add-form]');
const checkoutFlag = document.querySelector('[data-fm-checkout-flag]');

if (upsellModalEl && upsellOptionsEl && offerRadios.length && window.bootstrap) {
    const upsellModal = new window.bootstrap.Modal(upsellModalEl);

    const selectOffer = (value) => {
        offerRadios.forEach((radio) => {
            radio.checked = radio.value === value;
        });
        offers.forEach((offer) => {
            const radio = offer.querySelector('[data-fm-offer-radio]');
            offer.classList.toggle('active', radio.value === value);
        });
    };

    offerRadios.forEach((radio) => {
        radio.addEventListener('change', () => {
            if (!radio.checked) return;

            const currentOffer = radio.closest('[data-fm-offer]');
            const currentQuantity = Number(currentOffer.dataset.offerQuantity);

            const upsells = Array.from(offers)
                .filter((offer) => Number(offer.dataset.offerQuantity) > currentQuantity)
                .sort((a, b) => Number(a.dataset.offerQuantity) - Number(b.dataset.offerQuantity));

            if (upsells.length === 0) return;

            upsellOptionsEl.innerHTML = '';

            upsells.forEach((offer) => {
                const value = offer.querySelector('[data-fm-offer-radio]').value;

                const option = document.createElement('button');
                option.type = 'button';
                option.className = 'fm-upsell-modal__option';

                const label = document.createElement('span');
                label.className = 'fm-upsell-modal__option-label';
                label.textContent = offer.dataset.offerLabel;

                const price = document.createElement('span');
                price.className = 'fm-upsell-modal__option-price';
                price.textContent = offer.dataset.offerPriceFormatted;

                const savings = document.createElement('span');
                savings.className = 'fm-upsell-modal__option-savings';
                savings.textContent = `Poupe ${offer.dataset.offerSavingsFormatted}`;

                option.append(label, price, savings);
                option.addEventListener('click', () => {
                    selectOffer(value);
                    upsellModal.hide();
                });

                upsellOptionsEl.appendChild(option);
            });

            upsellModal.show();
        });
    });

    const finishBtn = document.querySelector('[data-fm-upsell-finish]');
    if (finishBtn && addForm && checkoutFlag) {
        finishBtn.addEventListener('click', () => {
            checkoutFlag.value = '1';
            upsellModal.hide();
            addForm.requestSubmit();
        });
    }
}

const addedModalEl = document.querySelector('[data-fm-added-modal]');

if (addForm && addedModalEl && checkoutFlag && window.bootstrap) {
    const addedModal = new window.bootstrap.Modal(addedModalEl);

    addForm.addEventListener('submit', (event) => {
        if (checkoutFlag.value === '1') return;

        event.preventDefault();

        fetch(addForm.action, {
            method: 'POST',
            body: new FormData(addForm),
            credentials: 'same-origin',
        }).then((response) => {
            if (response.ok) {
                addedModal.show();
            } else {
                addForm.submit();
            }
        }).catch(() => {
            addForm.submit();
        });
    });
}
