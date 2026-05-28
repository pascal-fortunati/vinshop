<div class="container my-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <nav aria-label="Fil d'Ariane">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('/') ?>">Accueil</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Politique des Cookies</li>
                </ol>
            </nav>

            <h1 class="mb-4"><i class="fas fa-cookie-bite"></i> Politique des Cookies</h1>

            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                Vous pouvez gérer vos préférences de cookies à tout moment en cliquant sur le bouton ci-dessous :
                <div class="mt-2">
                    <button type="button" class="btn btn-sm btn-primary" id="open-cookie-settings">
                        <i class="fas fa-cog"></i> Gérer mes cookies
                    </button>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4"><i class="fas fa-question-circle"></i> Qu'est-ce qu'un cookie ?</h2>
                    <p>
                        Un cookie est un petit fichier texte déposé sur votre ordinateur ou appareil mobile
                        lors de la visite d'un site web. Il permet au site de mémoriser vos actions et
                        préférences (connexion, langue, taille de police, etc.) pendant une durée déterminée.
                    </p>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4"><i class="fas fa-list"></i> Types de cookies utilisés</h2>

                    <h3 class="h5 mt-4"><i class="fas fa-star text-warning"></i> Cookies essentiels (obligatoires)</h3>
                    <p>Ces cookies sont nécessaires au fonctionnement du site et ne peuvent pas être désactivés.</p>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Cookie</th>
                                    <th>Finalité</th>
                                    <th>Durée</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>PHPSESSID</code></td>
                                    <td>Gestion de la session utilisateur</td>
                                    <td>Session (jusqu'à fermeture du navigateur)</td>
                                </tr>
                                <tr>
                                    <td><code>cart_token</code></td>
                                    <td>Mémorisation du panier</td>
                                    <td>30 jours</td>
                                </tr>
                                <tr>
                                    <td><code>vinshop_cookie_consent</code></td>
                                    <td>Enregistrement de vos choix de cookies</td>
                                    <td>365 jours</td>
                                </tr>
                                <tr>
                                    <td><code>theme_preference</code></td>
                                    <td>Mémorisation du mode sombre/clair</td>
                                    <td>365 jours</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h3 class="h5 mt-4"><i class="fas fa-chart-line text-info"></i> Cookies analytiques (optionnels)</h3>
                    <p>Ces cookies nous aident à comprendre comment les visiteurs utilisent notre site.</p>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Cookie</th>
                                    <th>Fournisseur</th>
                                    <th>Finalité</th>
                                    <th>Durée</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>_ga</code></td>
                                    <td>Google Analytics</td>
                                    <td>Analyse du trafic et du comportement</td>
                                    <td>2 ans</td>
                                </tr>
                                <tr>
                                    <td><code>_gid</code></td>
                                    <td>Google Analytics</td>
                                    <td>Distinction des visiteurs</td>
                                    <td>24 heures</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h3 class="h5 mt-4"><i class="fas fa-bullhorn text-danger"></i> Cookies marketing (optionnels)</h3>
                    <p>Ces cookies sont utilisés pour afficher des publicités pertinentes.</p>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Cookie</th>
                                    <th>Fournisseur</th>
                                    <th>Finalité</th>
                                    <th>Durée</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>_fbp</code></td>
                                    <td>Facebook Pixel</td>
                                    <td>Publicité ciblée</td>
                                    <td>3 mois</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4"><i class="fas fa-sliders-h"></i> Comment gérer vos cookies ?</h2>

                    <h3 class="h5 mt-3"><i class="fas fa-cog"></i> Sur notre site</h3>
                    <p>
                        Vous pouvez à tout moment modifier vos préférences en cliquant sur le bouton
                        "Gérer mes cookies" présent en haut de cette page.
                    </p>

                    <h3 class="h5 mt-3"><i class="fas fa-browser"></i> Via votre navigateur</h3>
                    <p>Vous pouvez configurer votre navigateur pour refuser les cookies :</p>
                    <ul>
                        <li><a href="https://support.google.com/chrome/answer/95647" target="_blank" rel="noopener">
                                <i class="fab fa-chrome"></i> Google Chrome</a></li>
                        <li><a href="https://support.mozilla.org/fr/kb/activer-desactiver-cookies" target="_blank" rel="noopener">
                                <i class="fab fa-firefox"></i> Mozilla Firefox</a></li>
                        <li><a href="https://support.apple.com/fr-fr/guide/safari/sfri11471/mac" target="_blank" rel="noopener">
                                <i class="fab fa-safari"></i> Safari</a></li>
                        <li><a href="https://support.microsoft.com/fr-fr/microsoft-edge" target="_blank" rel="noopener">
                                <i class="fab fa-edge"></i> Microsoft Edge</a></li>
                    </ul>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Attention :</strong> La désactivation des cookies essentiels peut affecter
                        le fonctionnement du site (impossibilité de se connecter, panier non sauvegardé, etc.).
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4"><i class="fas fa-shield-alt"></i> Protection de votre vie privée</h2>
                    <p>
                        Nous nous engageons à utiliser les cookies de manière responsable et transparente.
                        Les données collectées via les cookies ne sont jamais vendues à des tiers.
                    </p>
                    <p>
                        Pour en savoir plus sur la gestion de vos données personnelles, consultez notre
                        <a href="<?= url('/legal/privacy') ?>">Politique de Confidentialité</a>.
                    </p>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4"><i class="fas fa-envelope"></i> Contact</h2>
                    <p>
                        Pour toute question concernant notre utilisation des cookies, vous pouvez nous contacter :
                    </p>
                    <address>
                        <i class="fas fa-envelope"></i> Email : <a href="mailto:contact@vinshop.fr">contact@vinshop.fr</a><br>
                        <i class="fas fa-phone"></i> Téléphone : +33 1 23 45 67 89
                    </address>
                </div>
            </div>

            <div class="text-center mt-5">
                <button type="button" class="btn btn-primary" id="open-cookie-settings-bottom">
                    <i class="fas fa-cog"></i> Gérer mes cookies
                </button>
                <a href="<?= url('/') ?>" class="btn btn-outline-primary">
                    <i class="fas fa-home"></i> Retour à l'accueil
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Réouvrir le banner de cookies
    document.getElementById('open-cookie-settings')?.addEventListener('click', () => {
        document.cookie = 'vinshop_cookie_consent=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        location.reload();
    });

    document.getElementById('open-cookie-settings-bottom')?.addEventListener('click', () => {
        document.cookie = 'vinshop_cookie_consent=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        location.reload();
    });
</script>