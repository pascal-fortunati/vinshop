// Gestion du consentement des cookies (RGPD)
class CookieConsent {
    constructor() {
        this.cookieName = 'vinshop_cookie_consent';
        this.cookieDuration = 365; // jours
        this.init();
    }

    init() {
        // Vérifier si le consentement a déjà été donné
        if (!this.hasConsent()) {
            this.showBanner();
        } else {
            // Charger les scripts analytiques si consentement accepté
            const consent = this.getConsent();
            if (consent.analytics) {
                this.loadAnalytics();
            }
        }
    }

    hasConsent() {
        return this.getCookie(this.cookieName) !== null;
    }

    getConsent() {
        const cookie = this.getCookie(this.cookieName);
        if (!cookie) return null;
        try {
            return JSON.parse(cookie);
        } catch (e) {
            return null;
        }
    }

    showBanner() {
        const banner = document.createElement('div');
        banner.id = 'cookie-consent-banner';
        banner.className = 'cookie-consent-banner';
        banner.setAttribute('role', 'dialog');
        banner.setAttribute('aria-label', 'Consentement aux cookies');
        banner.innerHTML = `
            <div class="cookie-consent-content">
                <div class="cookie-consent-text">
                    <h5><i class="fas fa-cookie-bite"></i> Gestion des cookies</h5>
                    <p>
                        Nous utilisons des cookies pour améliorer votre expérience sur notre site. 
                        Certains cookies sont essentiels au fonctionnement du site, d'autres nous aident 
                        à analyser l'utilisation et à améliorer nos services.
                    </p>
                </div>
                <div class="cookie-consent-actions">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="cookie-customize">
                        <i class="fas fa-cog"></i> Personnaliser
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary" id="cookie-reject">
                        <i class="fas fa-times"></i> Refuser tout
                    </button>
                    <button type="button" class="btn btn-sm btn-success" id="cookie-accept">
                        <i class="fas fa-check"></i> Tout accepter
                    </button>
                </div>
            </div>
            <div class="cookie-consent-details d-none" id="cookie-details">
                <div class="cookie-category">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="cookie-essential" checked disabled>
                        <label class="form-check-label" for="cookie-essential">
                            <strong>Cookies essentiels</strong>
                            <small class="d-block text-muted">Nécessaires au fonctionnement du site (session, panier, préférences)</small>
                        </label>
                    </div>
                </div>
                <div class="cookie-category">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="cookie-analytics">
                        <label class="form-check-label" for="cookie-analytics">
                            <strong>Cookies analytiques</strong>
                            <small class="d-block text-muted">Nous aident à comprendre comment les visiteurs utilisent le site</small>
                        </label>
                    </div>
                </div>
                <div class="cookie-category">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="cookie-marketing">
                        <label class="form-check-label" for="cookie-marketing">
                            <strong>Cookies marketing</strong>
                            <small class="d-block text-muted">Utilisés pour afficher des publicités pertinentes</small>
                        </label>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="button" class="btn btn-sm btn-primary" id="cookie-save">
                        <i class="fas fa-save"></i> Enregistrer mes choix
                    </button>
                    <a href="legal/privacy" class="btn btn-sm btn-link">Politique de confidentialité</a>
                </div>
            </div>
        `;
        document.body.appendChild(banner);

        // Animation d'entrée
        setTimeout(() => banner.classList.add('show'), 100);

        // Event listeners
        this.attachEventListeners();
    }

    attachEventListeners() {
        document.getElementById('cookie-accept')?.addEventListener('click', () => {
            this.saveConsent({ essential: true, analytics: true, marketing: true });
            this.hideBanner();
            this.loadAnalytics();
        });

        document.getElementById('cookie-reject')?.addEventListener('click', () => {
            this.saveConsent({ essential: true, analytics: false, marketing: false });
            this.hideBanner();
        });

        document.getElementById('cookie-customize')?.addEventListener('click', () => {
            document.getElementById('cookie-details').classList.toggle('d-none');
        });

        document.getElementById('cookie-save')?.addEventListener('click', () => {
            const consent = {
                essential: true,
                analytics: document.getElementById('cookie-analytics').checked,
                marketing: document.getElementById('cookie-marketing').checked
            };
            this.saveConsent(consent);
            this.hideBanner();
            if (consent.analytics) {
                this.loadAnalytics();
            }
        });
    }

    saveConsent(consent) {
        const consentData = {
            ...consent,
            timestamp: new Date().toISOString()
        };
        this.setCookie(this.cookieName, JSON.stringify(consentData), this.cookieDuration);
    }

    hideBanner() {
        const banner = document.getElementById('cookie-consent-banner');
        if (banner) {
            banner.classList.remove('show');
            setTimeout(() => banner.remove(), 300);
        }
    }

    loadAnalytics() {
        // Charger Google Analytics ou autre outil d'analyse
        console.log('Analytics loaded (placeholder)');
        // TODO: Ajouter votre code Google Analytics ici
    }

    // Utilitaires cookies
    setCookie(name, value, days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        const expires = `expires=${date.toUTCString()}`;
        document.cookie = `${name}=${value};${expires};path=/;SameSite=Strict`;
    }

    getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }
}

// Initialiser au chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    new CookieConsent();
});
