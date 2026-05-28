// Gestion du mode sombre/clair
document.addEventListener('DOMContentLoaded', function () {
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');

    // Récupérer le thème depuis localStorage (par défaut: light)
    let currentTheme = localStorage.getItem('theme') || 'light';

    // Appliquer le thème au chargement
    applyTheme(currentTheme);

    // Toggle au clic
    if (themeToggle) {
        themeToggle.addEventListener('click', function (e) {
            e.preventDefault();
            currentTheme = currentTheme === 'light' ? 'dark' : 'light';
            applyTheme(currentTheme);
            localStorage.setItem('theme', currentTheme);
            document.cookie = "theme=" + currentTheme + ";path=/;max-age=31536000";
        });
    }

    function applyTheme(theme) {
        // Changer l'icône
        if (themeIcon) {
            if (theme === 'dark') {
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
                themeToggle.setAttribute('title', 'Mode clair');
            } else {
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
                themeToggle.setAttribute('title', 'Mode sombre');
            }
        }

        // Ajouter une classe au body pour les styles personnalisés
        document.body.classList.remove('theme-light', 'theme-dark');
        document.body.classList.add('theme-' + theme);

        // Animation de transition
        document.body.style.transition = 'background-color 0.3s ease, color 0.3s ease';
    }

    // Détecter les préférences système (optionnel)
    if (!localStorage.getItem('theme')) {
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            currentTheme = 'dark';
            applyTheme(currentTheme);
            localStorage.setItem('theme', currentTheme);
        }
    }

    // Écouter les changements de préférences système
    if (window.matchMedia) {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            if (!localStorage.getItem('theme')) {
                const newTheme = e.matches ? 'dark' : 'light';
                applyTheme(newTheme);
            }
        });
    }

    // Synchroniser le localStorage avec la classe du body au chargement
    if (document.body.classList.contains('theme-dark')) {
        localStorage.setItem('theme', 'dark');
        document.cookie = "theme=dark;path=/;max-age=31536000";
    } else {
        localStorage.setItem('theme', 'light');
        document.cookie = "theme=light;path=/;max-age=31536000";
    }
});
