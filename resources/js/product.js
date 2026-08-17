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
const cartFlag = document.querySelector('[data-fm-cart-flag]');
const addedModalEl = document.querySelector('[data-fm-added-modal]');

const upsellModal = upsellModalEl && window.bootstrap ? new window.bootstrap.Modal(upsellModalEl) : null;
const addedModal = addedModalEl && window.bootstrap ? new window.bootstrap.Modal(addedModalEl) : null;

const selectOffer = (value) => {
    offerRadios.forEach((radio) => {
        radio.checked = radio.value === value;
    });
    offers.forEach((offer) => {
        const radio = offer.querySelector('[data-fm-offer-radio]');
        offer.classList.toggle('active', radio.value === value);
    });
};

const getUpsellsForCurrentSelection = () => {
    const checkedRadio = Array.from(offerRadios).find((radio) => radio.checked);
    if (!checkedRadio) return [];

    const currentOffer = checkedRadio.closest('[data-fm-offer]');
    const currentQuantity = Number(currentOffer.dataset.offerQuantity);

    return Array.from(offers)
        .filter((offer) => Number(offer.dataset.offerQuantity) > currentQuantity)
        .sort((a, b) => Number(a.dataset.offerQuantity) - Number(b.dataset.offerQuantity));
};

const showUpsellModal = (upsells) => {
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
};

// So o clique explicito em "Finalizar Compra" (em qualquer um dos popups)
// adiciona de facto ao carrinho — seleccionar uma oferta ou clicar em
// "Comprar Agora" apenas decide qual popup mostrar, nunca adiciona sozinho.
if (upsellModal && upsellOptionsEl && addForm && cartFlag) {
    const upsellFinishBtn = document.querySelector('[data-fm-upsell-finish]');
    if (upsellFinishBtn) {
        upsellFinishBtn.addEventListener('click', () => {
            cartFlag.value = '1';
            upsellModal.hide();
            addForm.requestSubmit();
        });
    }
}

if (addedModal && addForm && cartFlag) {
    const addedFinishBtn = document.querySelector('[data-fm-added-finish]');
    if (addedFinishBtn) {
        addedFinishBtn.addEventListener('click', () => {
            cartFlag.value = '1';
            addedModal.hide();
            addForm.requestSubmit();
        });
    }
}

const showRelevantPopup = () => {
    const upsells = (offerRadios.length && upsellModal && upsellOptionsEl)
        ? getUpsellsForCurrentSelection()
        : [];

    if (upsells.length > 0) {
        showUpsellModal(upsells);
    } else if (addedModal) {
        addedModal.show();
    }
};

if (offerRadios.length) {
    offerRadios.forEach((radio) => {
        radio.addEventListener('change', () => {
            if (radio.checked) showRelevantPopup();
        });
    });
}

if (addForm) {
    addForm.addEventListener('submit', (event) => {
        if (cartFlag && cartFlag.value === '1') return;

        event.preventDefault();
        showRelevantPopup();
    });
}
