<div class="container my-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <nav aria-label="Fil d'Ariane">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('/') ?>">Accueil</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Politique de Confidentialité</li>
                </ol>
            </nav>

            <h1 class="mb-4"><i class="fas fa-shield-alt"></i> Politique de Confidentialité</h1>
            <p class="text-muted"><small>Dernière mise à jour : <?= date('d/m/Y') ?></small></p>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4"><i class="fas fa-info-circle"></i> Introduction</h2>
                    <p>
                        VinShop accorde une grande importance à la protection de vos données personnelles.
                        Cette politique de confidentialité vous informe sur la manière dont nous collectons,
                        utilisons et protégeons vos informations conformément au Règlement Général sur la
                        Protection des Données (RGPD).
                    </p>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4"><i class="fas fa-database"></i> Données collectées</h2>
                    <p>Nous collectons les données suivantes :</p>
                    <ul>
                        <li><strong>Données d'identification :</strong> nom, prénom, adresse email</li>
                        <li><strong>Données de connexion :</strong> identifiant, mot de passe (crypté)</li>
                        <li><strong>Données de commande :</strong> historique des achats, panier, wishlist</li>
                        <li><strong>Données de navigation :</strong> adresse IP, cookies, pages visitées</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4"><i class="fas fa-bullseye"></i> Finalités du traitement</h2>
                    <p>Vos données sont utilisées pour :</p>
                    <ul>
                        <li>Gérer votre compte utilisateur</li>
                        <li>Traiter vos commandes</li>
                        <li>Améliorer nos services</li>
                        <li>Vous envoyer des communications commerciales (avec votre consentement)</li>
                        <li>Assurer la sécurité du site</li>
                        <li>Respecter nos obligations légales</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4"><i class="fas fa-balance-scale"></i> Base légale</h2>
                    <p>Le traitement de vos données repose sur :</p>
                    <ul>
                        <li><strong>Exécution du contrat :</strong> pour la gestion des commandes</li>
                        <li><strong>Consentement :</strong> pour les communications marketing</li>
                        <li><strong>Intérêt légitime :</strong> pour l'amélioration de nos services</li>
                        <li><strong>Obligation légale :</strong> pour la conservation des données comptables</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4"><i class="fas fa-clock"></i> Durée de conservation</h2>
                    <ul>
                        <li><strong>Compte utilisateur :</strong> jusqu'à la suppression du compte + 1 an</li>
                        <li><strong>Données de commande :</strong> 10 ans (obligation légale)</li>
                        <li><strong>Cookies :</strong> 13 mois maximum</li>
                        <li><strong>Logs de connexion :</strong> 1 an</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4"><i class="fas fa-cookie-bite"></i> Cookies</h2>
                    <p>
                        Nous utilisons des cookies pour améliorer votre expérience. Vous pouvez gérer vos
                        préférences via le <a href="<?= url('/legal/cookies') ?>">gestionnaire de cookies</a>.
                    </p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Types de cookies :</strong>
                        <ul class="mb-0 mt-2">
                            <li>Cookies essentiels (session, panier)</li>
                            <li>Cookies analytiques (avec consentement)</li>
                            <li>Cookies marketing (avec consentement)</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4"><i class="fas fa-user-shield"></i> Vos droits</h2>
                    <p>Conformément au RGPD, vous disposez des droits suivants :</p>
                    <div class="row">
                        <div class="col-md-6">
                            <ul>
                                <li><i class="fas fa-eye text-primary"></i> <strong>Droit d'accès</strong></li>
                                <li><i class="fas fa-edit text-primary"></i> <strong>Droit de rectification</strong></li>
                                <li><i class="fas fa-trash text-danger"></i> <strong>Droit à l'effacement</strong></li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul>
                                <li><i class="fas fa-ban text-warning"></i> <strong>Droit d'opposition</strong></li>
                                <li><i class="fas fa-download text-info"></i> <strong>Droit à la portabilité</strong></li>
                                <li><i class="fas fa-pause text-secondary"></i> <strong>Droit à la limitation</strong></li>
                            </ul>
                        </div>
                    </div>
                    <div class="alert alert-success mt-3">
                        <i class="fas fa-envelope"></i>
                        Pour exercer vos droits, contactez-nous à :
                        <strong><a href="mailto:contact@vinshop.fr">contact@vinshop.fr</a></strong>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4"><i class="fas fa-lock"></i> Sécurité</h2>
                    <p>
                        Nous mettons en œuvre des mesures techniques et organisationnelles appropriées pour
                        protéger vos données contre tout accès non autorisé, perte ou destruction :
                    </p>
                    <ul>
                        <li>Chiffrement des mots de passe (bcrypt)</li>
                        <li>Connexion HTTPS sécurisée</li>
                        <li>Accès restreint aux données</li>
                        <li>Sauvegardes régulières</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4"><i class="fas fa-landmark"></i> Réclamation</h2>
                    <p>
                        Si vous estimez que vos droits ne sont pas respectés, vous pouvez introduire une
                        réclamation auprès de la CNIL :
                    </p>
                    <address>
                        <strong>Commission Nationale de l'Informatique et des Libertés (CNIL)</strong><br>
                        3 Place de Fontenoy<br>
                        TSA 80715<br>
                        75334 PARIS CEDEX 07<br>
                        <i class="fas fa-phone"></i> Tél : 01 53 73 22 22<br>
                        <i class="fas fa-globe"></i> <a href="https://www.cnil.fr" target="_blank" rel="noopener">www.cnil.fr</a>
                    </address>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="<?= url('/') ?>" class="btn btn-primary">
                    <i class="fas fa-home"></i> Retour à l'accueil
                </a>
            </div>
        </div>
    </div>
</div>