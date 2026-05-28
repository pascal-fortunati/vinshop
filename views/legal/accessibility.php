<div class="container my-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <nav aria-label="Fil d'Ariane">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('/') ?>">Accueil</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Déclaration d'Accessibilité</li>
                </ol>
            </nav>

            <h1 class="mb-4"><i class="fas fa-universal-access"></i> Déclaration d'Accessibilité</h1>
            <p class="lead">VinShop s'engage à rendre son site web accessible conformément au RGAA (Référentiel Général d'Amélioration de l'Accessibilité).</p>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4"><i class="fas fa-check-circle text-success"></i> État de conformité</h2>
                    <p>
                        Ce site web est <strong>partiellement conforme</strong> avec le RGAA 4.1 en raison
                        des non-conformités et des dérogations énumérées ci-dessous.
                    </p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Niveau visé :</strong> RGAA 4.1 niveau AA (double A)
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4"><i class="fas fa-thumbs-up"></i> Contenus accessibles</h2>
                    <ul>
                        <li><i class="fas fa-check text-success"></i> Navigation au clavier dans l'ensemble du site</li>
                        <li><i class="fas fa-check text-success"></i> Contrastes de couleurs conformes (ratio 4.5:1 minimum)</li>
                        <li><i class="fas fa-check text-success"></i> Textes alternatifs sur les images importantes</li>
                        <li><i class="fas fa-check text-success"></i> Structure sémantique HTML5 (headings, landmarks)</li>
                        <li><i class="fas fa-check text-success"></i> Formulaires avec labels associés</li>
                        <li><i class="fas fa-check text-success"></i> Messages d'erreur explicites</li>
                        <li><i class="fas fa-check text-success"></i> Lien d'évitement "Aller au contenu principal"</li>
                        <li><i class="fas fa-check text-success"></i> Focus visible sur les éléments interactifs</li>
                        <li><i class="fas fa-check text-success"></i> Compatibilité avec les lecteurs d'écran</li>
                        <li><i class="fas fa-check text-success"></i> Respect des préférences utilisateur (reduced-motion)</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4"><i class="fas fa-exclamation-triangle text-warning"></i> Contenus non accessibles</h2>
                    <h3 class="h5">Non-conformités</h3>
                    <ul>
                        <li>Certaines images décoratives ne sont pas marquées comme telles</li>
                        <li>Quelques vidéos ne disposent pas encore de sous-titres</li>
                        <li>Certains PDF ne sont pas encore accessibles</li>
                    </ul>
                    <div class="alert alert-warning">
                        <i class="fas fa-tools"></i>
                        <strong>En cours d'amélioration :</strong> Nous travaillons activement à corriger
                        ces non-conformités dans les prochaines mises à jour.
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4"><i class="fas fa-tools"></i> Technologies utilisées</h2>
                    <p>Les technologies suivantes sont utilisées pour ce site :</p>
                    <ul>
                        <li>HTML5</li>
                        <li>CSS3</li>
                        <li>JavaScript</li>
                        <li>ARIA (Accessible Rich Internet Applications)</li>
                        <li>Bootstrap 5 (framework accessible)</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4"><i class="fas fa-desktop"></i> Environnements de test</h2>
                    <p>Les vérifications de conformité ont été réalisées avec :</p>
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="h6"><i class="fas fa-browser"></i> Navigateurs</h3>
                            <ul>
                                <li>Chrome (dernière version)</li>
                                <li>Firefox (dernière version)</li>
                                <li>Safari (dernière version)</li>
                                <li>Edge (dernière version)</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h3 class="h6"><i class="fas fa-assistive-listening-systems"></i> Technologies d'assistance</h3>
                            <ul>
                                <li>NVDA (Windows)</li>
                                <li>JAWS (Windows)</li>
                                <li>VoiceOver (macOS/iOS)</li>
                                <li>TalkBack (Android)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4"><i class="fas fa-keyboard"></i> Raccourcis clavier</h2>
                    <p>Vous pouvez naviguer dans le site avec votre clavier :</p>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Touche</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><kbd>Tab</kbd></td>
                                    <td>Aller au lien/champ suivant</td>
                                </tr>
                                <tr>
                                    <td><kbd>Shift</kbd> + <kbd>Tab</kbd></td>
                                    <td>Revenir au lien/champ précédent</td>
                                </tr>
                                <tr>
                                    <td><kbd>Entrée</kbd></td>
                                    <td>Activer le lien/bouton</td>
                                </tr>
                                <tr>
                                    <td><kbd>Espace</kbd></td>
                                    <td>Cocher une case / Activer un bouton</td>
                                </tr>
                                <tr>
                                    <td><kbd>Échap</kbd></td>
                                    <td>Fermer une fenêtre modale</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4"><i class="fas fa-comments"></i> Retour d'information et contact</h2>
                    <p>
                        Si vous rencontrez un problème d'accessibilité vous empêchant d'accéder à un contenu
                        ou une fonctionnalité du site, merci de nous le signaler :
                    </p>
                    <div class="alert alert-success">
                        <i class="fas fa-envelope"></i>
                        Email : <strong><a href="mailto:accessibilite@vinshop.fr">accessibilite@vinshop.fr</a></strong><br>
                        <i class="fas fa-phone"></i>
                        Téléphone : <strong>+33 1 23 45 67 89</strong><br>
                        <i class="fas fa-map-marker-alt"></i>
                        Courrier : <strong>VinShop - Service Accessibilité, 123 Rue du Commerce, 75001 Paris</strong>
                    </div>
                    <p>
                        Nous nous efforcerons de vous apporter une réponse rapide et une solution alternative
                        le cas échéant.
                    </p>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4"><i class="fas fa-gavel"></i> Voies de recours</h2>
                    <p>
                        Si vous constatez un défaut d'accessibilité vous empêchant d'accéder à un contenu ou
                        une fonctionnalité du site, que vous nous le signalez et que vous ne parvenez pas à
                        obtenir une réponse de notre part, vous êtes en droit de faire parvenir vos doléances
                        ou une demande de saisine au Défenseur des droits :
                    </p>
                    <ul>
                        <li><a href="https://formulaire.defenseurdesdroits.fr/" target="_blank" rel="noopener">
                                Formulaire en ligne</a></li>
                        <li>Par téléphone : 09 69 39 00 00 (appel gratuit)</li>
                        <li>Par courrier : Défenseur des droits, Libre réponse 71120, 75342 Paris CEDEX 07</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4"><i class="fas fa-calendar-alt"></i> Date de la déclaration</h2>
                    <p>
                        Cette déclaration d'accessibilité a été établie le <strong><?= date('d/m/Y') ?></strong>.
                    </p>
                    <p class="mb-0">
                        <small class="text-muted">
                            Elle a été mise à jour le <?= date('d/m/Y') ?> suite à un audit interne.
                        </small>
                    </p>
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