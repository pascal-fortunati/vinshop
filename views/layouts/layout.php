<?php
require_once __DIR__ . '/../../models/Cart.php';
require_once __DIR__ . '/../../models/Wishlist.php';
$cart = new Cart();
$cartCount = $cart->getCount();
$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

// Compteur wishlist
$wishlistCount = 0;
if ($isLoggedIn) {
    $wishlist = new Wishlist();
    $wishlistCount = $wishlist->count($_SESSION['user_id']);
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'VinShop - Marketplace' ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/5.3.8/lux/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.css">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <?= $styles ?? '' ?>
</head>


<body class="<?= (isset($_COOKIE['theme']) && $_COOKIE['theme'] === 'dark') ? 'theme-dark' : '' ?>">
    <!-- RGAA - Lien d'évitement -->
    <a href="#main-content" class="skip-to-main">Aller au contenu principal</a>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary" role="navigation" aria-label="Navigation principale">
        <div class="container">
            <a class="navbar-brand" href="<?= url('/') ?>">
                <i class="fas fa-shopping-bag"></i> VinShop
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= activeRoute('/') ?>" href="<?= url('/') ?>">
                            <i class="fas fa-home"></i> Accueil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= activeRoute('products') ?>" href="<?= url('/products') ?>">
                            <i class="fas fa-store"></i> Produits
                        </a>
                    </li>
                    <?php if ($isAdmin): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle <?= activeSection('/admin') ?>" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-cog"></i> Administration
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item <?= activeRoute('/admin/dashboard') ?>" href="<?= url('/admin/dashboard') ?>">
                                        <i class="fas fa-tachometer-alt"></i> Dashboard
                                    </a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item <?= activeRoute('/admin/products') ?>" href="<?= url('/admin/products') ?>">
                                        <i class="fas fa-box"></i> Produits
                                    </a></li>
                                <li><a class="dropdown-item <?= activeRoute('/admin/orders') ?>" href="<?= url('/admin/orders') ?>">
                                        <i class="fas fa-shopping-cart"></i> Commandes
                                    </a></li>
                                <li><a class="dropdown-item <?= activeRoute('/admin/reviews') ?>" href="<?= url('/admin/reviews') ?>">
                                        <i class="fas fa-comments"></i> Avis
                                    </a></li>
                                <li><a class="dropdown-item <?= activeRoute('/admin/coupons') ?>" href="<?= url('/admin/coupons') ?>">
                                        <i class="fas fa-ticket-alt"></i> Codes Promo
                                    </a></li>
                                <li><a class="dropdown-item <?= activeRoute('/admin/users') ?>" href="<?= url('/admin/users') ?>">
                                        <i class="fas fa-users"></i> Utilisateurs
                                    </a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>

                <!-- Barre de recherche -->
                <form class="d-flex position-relative" id="searchForm" action="<?= url('/products/search') ?>" method="GET">
                    <div class="position-relative">
                        <input
                            type="search"
                            name="q"
                            id="searchInput"
                            class="form-control form-control-sm"
                            placeholder="Rechercher..."
                            autocomplete="off"
                            aria-label="Recherche">
                        <div id="searchResults" class="position-absolute w-100 bg-white shadow-lg rounded-bottom d-none" style="top: 100%; left: 0; z-index: 1050; max-height: 400px; overflow-y: auto;">
                            <!-- Les résultats de l'autocomplete seront chargés ici -->
                        </div>
                    </div>
                    <button class="btn btn-light btn-sm ms-1" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>

                <ul class="navbar-nav">
                    <?php if ($isLoggedIn): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle <?= activeSection('/profile') || activeSection('/orders') ? 'active' : '' ?>" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> <?= e($_SESSION['username'] ?? 'Utilisateur') ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item <?= activeRoute('/profile') ?>" href="<?= url('/profile') ?>">
                                        <i class="fas fa-user-circle"></i> Mon Profil
                                    </a></li>
                                <li><a class="dropdown-item <?= activeSection('/orders') ?>" href="<?= url('/orders/history') ?>">
                                        <i class="fas fa-history"></i> Mes Commandes
                                    </a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="<?= url('/logout') ?>">
                                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                                    </a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url('/login') ?>">
                                <i class="fas fa-sign-in-alt"></i> Connexion
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url('/register') ?>">
                                <i class="fas fa-user-plus"></i> Inscription
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Toggle Mode Sombre -->
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="themeToggle" title="Mode sombre">
                            <i class="fas fa-moon" id="themeIcon"></i>
                        </a>
                    </li>

                    <!-- Wishlist (connecté uniquement) -->
                    <?php if ($isLoggedIn): ?>
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="<?= url('/wishlist') ?>" title="Ma liste de souhaits">
                                <i class="fas fa-heart"></i>
                                <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle" id="wishlist-badge">
                                    <?= $wishlistCount ?>
                                </span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Panier -->
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="#" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas" title="Mon panier">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle" id="cart-badge">
                                <?= $cartCount ?>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenu Principal -->
    <main id="main-content" class="<?= $mainClass ?? 'container my-4' ?>" role="main">
        <?= $content ?? '' ?>
    </main>

    <!-- Offcanvas Panier -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="cartOffcanvasLabel">
                <i class="fas fa-shopping-cart"></i> Mon Panier
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div id="cart-items">
                <!-- Le contenu du panier sera chargé ici par JavaScript -->
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer-modern mt-5" role="contentinfo">
        <div class="footer-main py-5">
            <div class="container">
                <div class="row g-4">
                    <!-- Colonne Marque -->
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-brand mb-4">
                            <h4 class="mb-3">
                                <i class="fas fa-shopping-bag"></i> VinShop
                            </h4>
                            <p class="text-white-50 mb-3">
                                Votre marketplace de confiance pour des produits de qualité.
                                Livraison rapide et service client à votre écoute.
                            </p>
                            <div class="footer-social">
                                <a href="#" class="social-link" aria-label="Facebook" title="Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="social-link" aria-label="Twitter" title="Twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="social-link" aria-label="Instagram" title="Instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#" class="social-link" aria-label="LinkedIn" title="LinkedIn">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Colonne Navigation -->
                    <div class="col-lg-2 col-md-6">
                        <h6 class="footer-title mb-3">Navigation</h6>
                        <ul class="footer-links list-unstyled">
                            <li><a href="<?= url('/') ?>"><i class="fas fa-chevron-right"></i> Accueil</a></li>
                            <li><a href="<?= url('/products') ?>"><i class="fas fa-chevron-right"></i> Produits</a></li>
                            <?php if ($isLoggedIn): ?>
                                <li><a href="<?= url('/orders/history') ?>"><i class="fas fa-chevron-right"></i> Mes commandes</a></li>
                                <li><a href="<?= url('/wishlist') ?>"><i class="fas fa-chevron-right"></i> Ma wishlist</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <!-- Colonne Informations Légales -->
                    <div class="col-lg-3 col-md-6">
                        <h6 class="footer-title mb-3">Informations légales</h6>
                        <nav aria-label="Navigation du pied de page">
                            <ul class="footer-links list-unstyled">
                                <li>
                                    <a href="<?= url('/legal/terms') ?>">
                                        <i class="fas fa-gavel"></i> Mentions légales
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= url('/legal/privacy') ?>">
                                        <i class="fas fa-shield-alt"></i> Confidentialité
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= url('/legal/cookies') ?>">
                                        <i class="fas fa-cookie-bite"></i> Cookies
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= url('/legal/accessibility') ?>" class="accessibility-badge">
                                        <i class="fas fa-universal-access"></i> Accessibilité
                                        <span class="badge bg-info ms-2">AA</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                    <!-- Colonne Contact -->
                    <div class="col-lg-3 col-md-6">
                        <h6 class="footer-title mb-3">Contactez-nous</h6>
                        <ul class="footer-contact list-unstyled">
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <span>123 Rue du Commerce<br>75001 Paris, France</span>
                            </li>
                            <li>
                                <i class="fas fa-phone"></i>
                                <a href="tel:+33123456789">+33 1 23 45 67 89</a>
                            </li>
                            <li>
                                <i class="fas fa-envelope"></i>
                                <a href="mailto:contact@vinshop.fr">contact@vinshop.fr</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom py-3">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="mb-0 text-white-50 small">
                            <i class="fas fa-copyright"></i> <?= date('Y') ?> VinShop - Tous droits réservés
                        </p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <p class="mb-0 text-white-50 small">
                            Made with <i class="fas fa-heart text-danger"></i> in France
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.8/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.3/sweetalert2.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.js"></script>
    <script src="<?= asset('js/cookies.js') ?>"></script>
    <script src="<?= asset('js/theme.js') ?>"></script>
    <script src="<?= asset('js/cart.js') ?>"></script>
    <script src="<?= asset('js/search.js') ?>"></script>
    <script src="<?= asset('js/wishlist.js') ?>"></script>
    <script src="<?= asset('js/filters.js') ?>"></script>
    <?= $scripts ?? '' ?>

    <!-- Messages Flash (Toasts) -->
    <?php renderFlashMessages(); ?>
</body>

</html>