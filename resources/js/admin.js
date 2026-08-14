import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

document.querySelectorAll('[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (!window.confirm(form.dataset.confirm)) {
            event.preventDefault();
        }
    });
});

const adminSidebar = document.getElementById('fmAdminSidebar');
const adminBackdrop = document.querySelector('[data-admin-backdrop]');
const adminMenuToggle = document.querySelector('[data-admin-menu-toggle]');

function closeAdminSidebar() {
    adminSidebar?.classList.remove('is-open');
    adminBackdrop?.classList.remove('is-open');
    adminMenuToggle?.setAttribute('aria-expanded', 'false');
}

if (adminSidebar && adminBackdrop && adminMenuToggle) {
    adminMenuToggle.addEventListener('click', () => {
        const isOpen = adminSidebar.classList.toggle('is-open');
        adminBackdrop.classList.toggle('is-open', isOpen);
        adminMenuToggle.setAttribute('aria-expanded', String(isOpen));
    });

    adminBackdrop.addEventListener('click', closeAdminSidebar);
    adminSidebar.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeAdminSidebar));
}

function submitDynamicForm(url, fields, fileInputs = {}) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const form = document.createElement('form');
    form.method = 'POST';
    form.enctype = 'multipart/form-data';
    form.action = url;
    form.style.display = 'none';

    for (const [name, value] of Object.entries({ _token: csrfToken, ...fields })) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value ?? '';
        form.appendChild(input);
    }

    for (const [name, sourceInput] of Object.entries(fileInputs)) {
        if (!sourceInput?.files?.length) {
            continue;
        }
        const fileInput = document.createElement('input');
        fileInput.type = 'file';
        fileInput.name = name;
        fileInput.style.display = 'none';
        const transfer = new DataTransfer();
        for (const file of sourceInput.files) {
            transfer.items.add(file);
        }
        fileInput.files = transfer.files;
        form.appendChild(fileInput);
    }

    document.body.appendChild(form);
    form.submit();
}

document.querySelectorAll('[data-admin-delete-image], [data-admin-delete-offer]').forEach((button) => {
    button.addEventListener('click', () => {
        if (button.dataset.confirm && !window.confirm(button.dataset.confirm)) {
            return;
        }

        submitDynamicForm(button.dataset.url, { _method: 'DELETE' });
    });
});

document.querySelectorAll('[data-admin-add-offer]').forEach((button) => {
    button.addEventListener('click', () => {
        const label = document.getElementById(button.dataset.labelInput)?.value.trim();
        const quantity = document.getElementById(button.dataset.quantityInput)?.value.trim();
        const price = document.getElementById(button.dataset.priceInput)?.value.trim();

        if (!label || !quantity || !price) {
            window.alert('Preencha o rótulo, a quantidade e o preço.');
            return;
        }

        const imageInput = document.getElementById(button.dataset.imageInput);
        submitDynamicForm(button.dataset.url, { label, quantity, price }, { image_path: imageInput });
    });
});

document.querySelectorAll('[data-admin-update-offer-image]').forEach((button) => {
    button.addEventListener('click', () => {
        const imageInput = document.getElementById(button.dataset.imageInput);

        if (!imageInput?.files?.length) {
            window.alert('Escolha uma imagem primeiro.');
            return;
        }

        submitDynamicForm(button.dataset.url, { _method: 'PUT' }, { image_path: imageInput });
    });
});
