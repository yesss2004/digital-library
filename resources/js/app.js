import './bootstrap';

const logoDialog = document.querySelector('#bpbd-logo-dialog');
const logoButton = document.querySelector('.brand-logo-button');
const logoCloseButton = document.querySelector('.logo-dialog-close');

if (logoDialog instanceof HTMLDialogElement && logoButton instanceof HTMLButtonElement) {
    logoButton.addEventListener('click', () => logoDialog.showModal());

    logoCloseButton?.addEventListener('click', () => logoDialog.close());

    logoDialog.addEventListener('click', (event) => {
        if (event.target === logoDialog) {
            logoDialog.close();
        }
    });
}
