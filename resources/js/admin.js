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

document.querySelectorAll('[data-admin-delete-image]').forEach((button) => {
    button.addEventListener('click', () => {
        if (button.dataset.confirm && !window.confirm(button.dataset.confirm)) {
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = button.dataset.url;
        form.style.display = 'none';
        form.innerHTML = `
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="_method" value="DELETE">
        `;
        document.body.appendChild(form);
        form.submit();
    });
});
