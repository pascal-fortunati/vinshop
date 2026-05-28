<div class="row mb-4">
    <div class="col-12">
        <h1>
            <i class="fas fa-search"></i> Résultats de recherche
        </h1>
        <p class="lead">
            <?= $count ?> résultat<?= $count > 1 ? 's' : '' ?> trouvé<?= $count > 1 ? 's' : '' ?> pour "<?= e($query) ?>"
        </p>
        <a href="<?= url('/products') ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Retour aux produits
        </a>
    </div>
</div>

<?php if (empty($products)): ?>
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle"></i>
        Aucun produit ne correspond à votre recherche "<?= e($query) ?>".
        <hr>
        <p class="mb-0">
            <strong>Suggestions :</strong>
        </p>
        <ul class="mb-0">
            <li>Vérifiez l'orthographe de votre recherche</li>
            <li>Utilisez des mots-clés plus généraux</li>
            <li>Essayez avec moins de mots-clés</li>
        </ul>
    </div>

    <div class="mt-4">
        <h4>Parcourir par catégorie</h4>
        <div class="row mt-3">
            <div class="col-md-3 col-sm-6 mb-3">
                <a href="<?= url('/products?category=Vêtements') ?>" class="text-decoration-none">
                    <div class="card text-center bg-primary text-white category-card" style="height: 120px;">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <i class="fas fa-tshirt fa-2x mb-2"></i>
                            <h6 class="mb-0">Vêtements</h6>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <a href="<?= url('/products?category=Électronique') ?>" class="text-decoration-none">
                    <div class="card text-center bg-success text-white category-card" style="height: 120px;">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <i class="fas fa-mobile-alt fa-2x mb-2"></i>
                            <h6 class="mb-0">Électronique</h6>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <a href="<?= url('/products?category=Maison') ?>" class="text-decoration-none">
                    <div class="card text-center bg-info text-white category-card" style="height: 120px;">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <i class="fas fa-home fa-2x mb-2"></i>
                            <h6 class="mb-0">Maison</h6>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <a href="<?= url('/products?category=Sport') ?>" class="text-decoration-none">
                    <div class="card text-center bg-warning text-white category-card" style="height: 120px;">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <i class="fas fa-futbol fa-2x mb-2"></i>
                            <h6 class="mb-0">Sport</h6>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="row">
        <?php foreach ($products as $product): ?>
            <div class="col-md-4 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm product-card">
                    <img src="<?= asset('uploads/' . $product['image']) ?>"
                        class="card-img-top"
                        alt="<?= e($product['name']) ?>"
                        style="height: 250px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= e($product['name']) ?></h5>
                        <p class="card-text text-muted small">
                            <?= truncate(e($product['description']), 80) ?>
                        </p>
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-info"><?= e($product['category']) ?></span>
                                <span class="badge bg-<?= $product['condition'] === 'Neuf' ? 'success' : 'warning' ?>">
                                    <?= e($product['condition']) ?>
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 text-primary mb-0">
                                    <?= number_format($product['price'], 2, ',', ' ') ?> €
                                </span>
                                <small class="text-muted">
                                    <i class="fas fa-box"></i> <?= $product['stock'] ?> en stock
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-grid gap-2">
                            <a href="<?= productUrl($product) ?>" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye"></i> Voir détails
                            </a>
                            <button class="btn btn-primary btn-sm add-to-cart-btn"
                                data-product-id="<?= $product['id'] ?>"
                                data-product-name="<?= e($product['name']) ?>"
                                data-product-price="<?= $product['price'] ?>"
                                data-product-stock="<?= $product['stock'] ?>">
                                <i class="fas fa-cart-plus"></i> Ajouter au panier
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>