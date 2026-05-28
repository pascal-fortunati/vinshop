<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <i class="fas fa-store"></i>
        <?php if (isset($currentCategory)): ?>
            Produits - <?= e($currentCategory) ?>
        <?php else: ?>
            Tous les produits
        <?php endif; ?>
    </h1>
    <?php if (!empty($filters)): ?>
        <a href="<?= url('/products') ?>" class="btn btn-outline-danger">
            <i class="fas fa-times"></i> Réinitialiser les filtres
        </a>
    <?php endif; ?>
</div>

<!-- Panneau de Filtres Avancés -->
<div class="card mb-4 shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="fas fa-sliders-h"></i> Filtres Avancés
            <button class="btn btn-sm btn-light float-end" type="button" data-bs-toggle="collapse" data-bs-target="#filterPanel">
                <i class="fas fa-chevron-down"></i>
            </button>
        </h5>
    </div>
    <div class="collapse show" id="filterPanel">
        <div class="card-body">
            <form id="filterForm" method="GET" action="<?= url('/products') ?>">
                <div class="row">
                    <!-- Catégorie -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-tags"></i> Catégorie
                        </label>
                        <select name="category" class="form-select" id="categoryFilter">
                            <option value="">Toutes les catégories</option>
                            <option value="Vêtements" <?= ($filters['category'] ?? '') === 'Vêtements' ? 'selected' : '' ?>>Vêtements</option>
                            <option value="Électronique" <?= ($filters['category'] ?? '') === 'Électronique' ? 'selected' : '' ?>>Électronique</option>
                            <option value="Maison" <?= ($filters['category'] ?? '') === 'Maison' ? 'selected' : '' ?>>Maison</option>
                            <option value="Sport" <?= ($filters['category'] ?? '') === 'Sport' ? 'selected' : '' ?>>Sport</option>
                        </select>
                    </div>

                    <!-- Tri -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-sort"></i> Trier par
                        </label>
                        <select name="sort" class="form-select" id="sortFilter">
                            <option value="date_desc" <?= ($filters['sort'] ?? 'date_desc') === 'date_desc' ? 'selected' : '' ?>>Plus récents</option>
                            <option value="date_asc" <?= ($filters['sort'] ?? '') === 'date_asc' ? 'selected' : '' ?>>Plus anciens</option>
                            <option value="price_asc" <?= ($filters['sort'] ?? '') === 'price_asc' ? 'selected' : '' ?>>Prix croissant</option>
                            <option value="price_desc" <?= ($filters['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Prix décroissant</option>
                            <option value="name_asc" <?= ($filters['sort'] ?? '') === 'name_asc' ? 'selected' : '' ?>>Nom A-Z</option>
                            <option value="name_desc" <?= ($filters['sort'] ?? '') === 'name_desc' ? 'selected' : '' ?>>Nom Z-A</option>
                            <option value="popularity" <?= ($filters['sort'] ?? '') === 'popularity' ? 'selected' : '' ?>>Popularité</option>
                        </select>
                    </div>

                    <!-- Stock -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-box"></i> Disponibilité
                        </label>
                        <select name="stock_status" class="form-select" id="stockFilter">
                            <option value="">Tous les produits</option>
                            <option value="in_stock" <?= ($filters['stock_status'] ?? '') === 'in_stock' ? 'selected' : '' ?>>En stock uniquement</option>
                            <option value="out_of_stock" <?= ($filters['stock_status'] ?? '') === 'out_of_stock' ? 'selected' : '' ?>>Rupture de stock</option>
                        </select>
                    </div>

                    <!-- Boutons -->
                    <div class="col-md-3 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i> Appliquer
                        </button>
                    </div>
                </div>

                <!-- Slider de Prix -->
                <div class="row">
                    <div class="col-12">
                        <label class="form-label fw-bold">
                            <i class="fas fa-euro-sign"></i> Fourchette de prix
                        </label>
                        <div class="px-3">
                            <div id="priceSlider"></div>
                            <div class="d-flex justify-content-between mt-2">
                                <span class="badge bg-secondary">
                                    Min: <span id="minPriceLabel"><?= formatPrice($filters['min_price'] ?? $priceRange['min_price']) ?></span>
                                </span>
                                <span class="badge bg-secondary">
                                    Max: <span id="maxPriceLabel"><?= formatPrice($filters['max_price'] ?? $priceRange['max_price']) ?></span>
                                </span>
                            </div>
                            <input type="hidden" name="min_price" id="minPriceInput" value="<?= $filters['min_price'] ?? $priceRange['min_price'] ?>">
                            <input type="hidden" name="max_price" id="maxPriceInput" value="<?= $filters['max_price'] ?? $priceRange['max_price'] ?>">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Résultats -->
<div class="mb-3">
    <span class="text-muted">
        <i class="fas fa-info-circle"></i>
        <?= count($products) ?> produit<?= count($products) > 1 ? 's' : '' ?> trouvé<?= count($products) > 1 ? 's' : '' ?>
    </span>
</div>

<?php if (empty($products)): ?>
    <div class="alert alert-info text-center py-5">
        <i class="fas fa-search fa-3x mb-3 d-block"></i>
        <h4>Aucun produit trouvé</h4>
        <p class="mb-3">Aucun produit ne correspond à vos critères de recherche.</p>
        <a href="<?= url('/products') ?>" class="btn btn-primary">
            <i class="fas fa-redo"></i> Réinitialiser les filtres
        </a>
    </div>
<?php else: ?>
    <div class="row" id="productsContainer">
        <?php foreach ($products as $product): ?>
            <div class="col-md-4 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm product-card position-relative">
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
                            style="height: 200px; object-fit: cover; cursor: pointer;">
                    </a>
                    <div class="card-body">
                        <span class="badge bg-secondary mb-2"><?= e($product['category']) ?></span>
                        <h5 class="card-title"><?= e($product['name']) ?></h5>
                        <p class="card-text text-muted small"><?= truncate($product['description'], 60) ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="text-primary mb-0"><?= formatPrice($product['price']) ?></h5>
                            <span class="badge bg-info"><?= e($product['condition']) ?></span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-grid">
                            <button class="btn btn-primary btn-sm add-to-cart" data-product-id="<?= $product['id'] ?>">
                                <i class="fas fa-cart-plus"></i> Ajouter au panier
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (!empty($pagination) && $pagination['last_page'] > 1): ?>
    <?php
    // Préserver les filtres dans la query string
    $queryParams = $_GET;
    // Fonctionpour générer l'URL d'une page donnée
    function pageUrl($page)
    {
        $qp = $_GET;
        $qp['page'] = $page;
        return url('/products') . '?' . http_build_query($qp);
    }

    $startItem = ($pagination['current'] - 1) * $pagination['per_page'] + 1;
    $endItem = min($pagination['current'] * $pagination['per_page'], $pagination['total']);
    ?>

    <div class="d-flex justify-content-between align-items-center my-4">
        <div class="text-muted">
            Affichage de <strong><?= $startItem ?></strong> à <strong><?= $endItem ?></strong> sur <strong><?= $pagination['total'] ?></strong> produits
        </div>
        <nav aria-label="Pagination">
            <ul class="pagination mb-0">
                <li class="page-item <?= $pagination['current'] <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= $pagination['current'] <= 1 ? '#' : pageUrl($pagination['current'] - 1) ?>" aria-label="Précédent">&laquo;</a>
                </li>

                <?php
                $maxLinks = 7; // nombre maximum de liens visibles
                $start = max(1, $pagination['current'] - intval($maxLinks / 2));
                $end = min($pagination['last_page'], $start + $maxLinks - 1);
                if ($end - $start + 1 < $maxLinks) {
                    $start = max(1, $end - $maxLinks + 1);
                }
                for ($p = $start; $p <= $end; $p++):
                ?>
                    <li class="page-item <?= $p == $pagination['current'] ? 'active' : '' ?>">
                        <a class="page-link" href="<?= pageUrl($p) ?>"><?= $p ?></a>
                    </li>
                <?php endfor; ?>

                <li class="page-item <?= $pagination['current'] >= $pagination['last_page'] ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= $pagination['current'] >= $pagination['last_page'] ? '#' : pageUrl($pagination['current'] + 1) ?>" aria-label="Suivant">&raquo;</a>
                </li>
            </ul>
        </nav>
    </div>
<?php endif; ?>
<!-- Scripts pour les filtres avancés -->
<script>
    // Passer les données PHP au JavaScript
    const priceRange = {
        min: <?= $priceRange['min_price'] ?? 0 ?>,
        max: <?= $priceRange['max_price'] ?? 1000 ?>,
        currentMin: <?= $filters['min_price'] ?? $priceRange['min_price'] ?? 0 ?>,
        currentMax: <?= $filters['max_price'] ?? $priceRange['max_price'] ?? 1000 ?>
    };
</script>