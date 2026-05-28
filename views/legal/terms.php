<div class="container my-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <nav aria-label="Fil d'Ariane">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('/') ?>">Accueil</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Mentions Légales</li>
                </ol>
            </nav>

            <h1 class="mb-4"><i class="fas fa-gavel"></i> Mentions Légales</h1>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4"><i class="fas fa-building"></i> Éditeur du site</h2>
                    <address>
                        <strong>VinShop SAS</strong><br>
                        Capital social : 10 000 €<br>
                        SIRET : 123 456 789 00010<br>
                        RCS : Paris B 123 456 789<br>
                        TVA intracommunautaire : FR12345678900<br>
                        <br>
                        <i class="fas fa-map-marker-alt"></i> 123 Rue du Commerce<br>
                        75001 Paris, France<br>
                        <br>
                        <i class="fas fa-phone"></i> Tél : +33 1 23 45 67 89<br>
                        <i class="fas fa-envelope"></i> Email : <a href="mailto:contact@vinshop.fr">contact@vinshop.fr</a>
                    </address>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4"><i class="fas fa-user-tie"></i> Directeur de publication</h2>
                    <p>
                        <strong>Nom :</strong> Jean Dupont<br>
                        <strong>Qualité :</strong> Président<br>
                        <strong>Contact :</strong> <a href="mailto:direction@vinshop.fr">direction@vinshop.fr</a>
                    </p>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4"><i class="fas fa-server"></i> Hébergement</h2>
                    <address>
                        <strong>OVH SAS</strong><br>
                        2 rue Kellermann<br>
                        59100 Roubaix, France<br>
                        <i class="fas fa-phone"></i> Tél : 1007<br>
                        <i class="fas fa-globe"></i> <a href="https://www.ovh.com" target="_blank" rel="noopener">www.ovh.com</a>
                    </address>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4"><i class="fas fa-copyright"></i> Propriété intellectuelle</h2>
                    <p>
                        L'ensemble du contenu de ce site (textes, images, vidéos, logos) est la propriété
                        exclusive de VinShop SAS ou de ses partenaires. Toute reproduction, même partielle,
                        est strictement interdite sans autorisation préalable.
                    </p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Protection :</strong> Ce site est protégé par les lois françaises et
                        internationales sur le droit d'auteur et la propriété intellectuelle.
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4"><i class="fas fa-shield-alt"></i> Données personnelles</h2>
                    <p>
                        Conformément au RGPD et à la loi Informatique et Libertés, vous disposez d'un droit
                        d'accès, de rectification et de suppression des données vous concernant.
                    </p>
                    <p>
                        Pour plus d'informations, consultez notre
                        <a href="<?= url('/legal/privacy') ?>">Politique de Confidentialité</a>.
                    </p>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4"><i class="fas fa-exclamation-circle"></i> Limitation de responsabilité</h2>
                    <p>
                        VinShop met tout en œuvre pour offrir des informations fiables et à jour. Toutefois,
                        nous ne pouvons garantir l'exactitude, la complétude ou l'actualité des informations
                        diffusées sur ce site.
                    </p>
                    <p>
                        VinShop ne saurait être tenu responsable des dommages directs ou indirects résultant
                        de l'accès au site ou de l'utilisation de celui-ci.
                    </p>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4"><i class="fas fa-link"></i> Liens hypertextes</h2>
                    <p>
                        Ce site peut contenir des liens vers des sites externes. VinShop n'exerce aucun
                        contrôle sur ces sites et décline toute responsabilité quant à leur contenu.
                    </p>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4"><i class="fas fa-balance-scale"></i> Loi applicable</h2>
                    <p>
                        Les présentes mentions légales sont soumises au droit français. En cas de litige,
                        et à défaut d'accord amiable, les tribunaux français seront seuls compétents.
                    </p>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4"><i class="fas fa-phone-volume"></i> Médiation de la consommation</h2>
                    <p>
                        Conformément à l'article L.612-1 du Code de la consommation, nous proposons un
                        dispositif de médiation de la consommation.
                    </p>
                    <address>
                        <strong>Médiateur de la consommation :</strong><br>
                        Centre de Médiation de la Consommation<br>
                        <i class="fas fa-envelope"></i> contact@cm2c.net<br>
                        <i class="fas fa-globe"></i> <a href="https://www.cm2c.net" target="_blank" rel="noopener">www.cm2c.net</a>
                    </address>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="<?= url('/') ?>" class="btn btn-primary">
                    <i class="fas fa-home"></i> Retour à l'accueil
                </a>
                <a href="<?= url('/legal/privacy') ?>" class="btn btn-outline-primary">
                    <i class="fas fa-shield-alt"></i> Politique de Confidentialité
                </a>
            </div>
        </div>
    </div>
</div>