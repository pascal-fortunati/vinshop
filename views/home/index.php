<!-- Hero Section -->
<div class="jumbotron bg-light p-5 rounded-3 mb-4">
    <h1 class="display-4">Bienvenue sur VinShop</h1>
    <p class="lead">Achetez des articles d'occasion en toute simplicité</p>
    <hr class="my-4">
    <p>Découvrez des milliers d'articles uniques à petits prix</p>
    <a class="btn btn-primary btn-lg" href="<?= url('/products') ?>" role="button">
        <i class="fas fa-shopping-bag"></i> Voir les produits
    </a>
</div>

<!-- Produits mis en avant -->
<h2 class="mb-4"><i class="fas fa-star"></i> Produits populaires</h2>

<?php if (empty($products)): ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> Aucun produit disponible pour le moment.
    </div>
<?php else: ?>
    <div class="row">
        <?php foreach (array_slice($products, 0, 6) as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm position-relative">
                    <!-- Bouton Wishlist -->
                    <button class="wishlist-btn btn btn-outline-danger"
                        data-product-id="<?= $product['id'] ?>"
                        title="Ajouter aux favoris">
                        <i class="far fa-heart"></i>
                    </button>

                    <a href="<?= productUrl($product) ?>" class="text-decoration-none">
                        <img src="<?= asset('uploads/' . $product['image']) ?>"
                            class="card-img-top"
                            alt="<?= e($product['name']) ?>"
                            style="height: 250px; object-fit: cover; cursor: pointer;">
                    </a>
                    <div class="card-body">
                        <span class="badge bg-secondary mb-2"><?= e($product['category']) ?></span>
                        <h5 class="card-title"><?= e($product['name']) ?></h5>
                        <p class="card-text text-muted"><?= truncate($product['description'], 80) ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="text-primary mb-0"><?= formatPrice($product['price']) ?></h4>
                            <span class="badge bg-info"><?= e($product['condition']) ?></span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-grid">
                            <button class="btn btn-primary add-to-cart" data-product-id="<?= $product['id'] ?>">
                                <i class="fas fa-cart-plus"></i> Ajouter au panier
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Catégories -->
<h2 class="mb-4 mt-5"><i class="fas fa-th-large"></i> Explorer par catégorie</h2>
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <a href="<?= url('/products?category=Vêtements') ?>" class="text-decoration-none">
            <div class="card text-center bg-primary text-white h-100 category-card">
                <div class="card-body">
                    <i class="fas fa-tshirt fa-3x mb-3"></i>
                    <h5>Vêtements</h5>
                    <p class="mb-0 small">Découvrir →</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="<?= url('/products?category=Électronique') ?>" class="text-decoration-none">
            <div class="card text-center bg-success text-white h-100 category-card">
                <div class="card-body">
                    <i class="fas fa-mobile-alt fa-3x mb-3"></i>
                    <h5>Électronique</h5>
                    <p class="mb-0 small">Découvrir →</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="<?= url('/products?category=Maison') ?>" class="text-decoration-none">
            <div class="card text-center bg-info text-white h-100 category-card">
                <div class="card-body">
                    <i class="fas fa-home fa-3x mb-3"></i>
                    <h5>Maison</h5>
                    <p class="mb-0 small">Découvrir →</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="<?= url('/products?category=Sport') ?>" class="text-decoration-none">
            <div class="card text-center bg-warning text-white h-100 category-card">
                <div class="card-body">
                    <i class="fas fa-futbol fa-3x mb-3"></i>
                    <h5>Sport</h5>
                    <p class="mb-0 small">Découvrir →</p>
                </div>
            </div>
        </a>
    </div>
</div>