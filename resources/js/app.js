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

const settingsNav = document.querySelector('.settings-nav');

if (settingsNav instanceof HTMLElement) {
    const links = Array.from(settingsNav.querySelectorAll('a[href^="#"]'));
    const sections = links
        .map((link) => document.querySelector(link.getAttribute('href')))
        .filter((section) => section instanceof HTMLElement);

    const setActive = (id) => {
        links.forEach((link) => {
            const isActive = link.getAttribute('href') === `#${id}`;
            link.classList.toggle('is-active', isActive);

            if (isActive) {
                link.setAttribute('aria-current', 'true');
            } else {
                link.removeAttribute('aria-current');
            }
        });
    };

    if (sections.length > 0) {
        setActive(sections[0].id);
    }

    if (sections.length > 0 && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver(
            (entries) => {
                const visible = entries
                    .filter((entry) => entry.isIntersecting)
                    .sort((a, b) => b.intersectionRatio - a.intersectionRatio)[0];

                if (visible) {
                    setActive(visible.target.id);
                }
            },
            { rootMargin: '-15% 0px -55% 0px', threshold: [0, 0.25, 0.5, 0.75] },
        );

        sections.forEach((section) => observer.observe(section));
    }

    links.forEach((link) => {
        link.addEventListener('click', () => setActive(link.getAttribute('href').slice(1)));
    });
}
