import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.data('navbarStore', () => ({
        open: false,
        darkMode: localStorage.theme === 'dark' || (!localStorage.theme && window.matchMedia('(prefers-color-scheme: dark)').matches),

        init() {
            this.applyTheme();
        },

        toggleTheme() {
            this.darkMode = !this.darkMode;
            localStorage.theme = this.darkMode ? 'dark' : 'light';
            this.applyTheme();
        },

        applyTheme() {
            document.documentElement.classList.toggle('dark', this.darkMode);
        },
    }));
});

Alpine.start();
