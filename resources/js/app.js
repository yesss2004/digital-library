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

const navDropdown = document.querySelector('[data-nav-dropdown]');

if (navDropdown instanceof HTMLElement) {
    const toggle = navDropdown.querySelector('.nav-dropdown-toggle');
    const menu = navDropdown.querySelector('.nav-dropdown-menu');

    if (toggle instanceof HTMLButtonElement && menu instanceof HTMLElement) {
        const isOpen = () => navDropdown.classList.contains('is-open');
        const mobileQuery = window.matchMedia('(max-width: 640px)');

        const resetMenuPosition = () => {
            menu.style.removeProperty('position');
            menu.style.removeProperty('top');
            menu.style.removeProperty('left');
            menu.style.removeProperty('right');
            menu.style.removeProperty('max-height');
        };

        const closeMenu = ({ focusToggle = false } = {}) => {
            if (!isOpen()) {
                return;
            }

            navDropdown.classList.remove('is-open');
            toggle.setAttribute('aria-expanded', 'false');
            resetMenuPosition();

            if (focusToggle) {
                toggle.focus();
            }
        };

        const openMenu = () => {
            navDropdown.classList.add('is-open');
            toggle.setAttribute('aria-expanded', 'true');

            if (mobileQuery.matches) {
                const rect = toggle.getBoundingClientRect();
                const top = rect.bottom + 10;

                menu.style.position = 'fixed';
                menu.style.top = `${top}px`;
                menu.style.left = '12px';
                menu.style.right = '12px';
                menu.style.maxHeight = `min(420px, 62vh, calc(100vh - ${top + 12}px))`;
            }
        };

        toggle.addEventListener('click', () => {
            if (isOpen()) {
                closeMenu();
            } else {
                openMenu();
            }
        });

        document.addEventListener('click', (event) => {
            if (isOpen() && !navDropdown.contains(event.target)) {
                closeMenu();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && isOpen()) {
                closeMenu({ focusToggle: true });
            }
        });

        window.addEventListener('resize', () => closeMenu());
        window.addEventListener('scroll', () => closeMenu(), { passive: true });
    }
}
