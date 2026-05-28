<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= url('/') ?>">Accueil</a></li>
        <li class="breadcrumb-item"><a href="<?= url('/products') ?>">Produits</a></li>
        <li class="breadcrumb-item active"><?= e($product['name']) ?></li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-6 mb-4">
        <img src="<?= asset('uploads/' . $product['image']) ?>"
            class="img-fluid rounded shadow"
            alt="<?= e($product['name']) ?>">
    </div>
    <div class="col-md-6">
        <span class="badge bg-secondary mb-2"><?= e($product['category']) ?></span>
        <h1 class="mb-3"><?= e($product['name']) ?></h1>

        <!-- Note moyenne -->
        <?php if ($reviewCount > 0): ?>
            <div class="mb-3">
                <?= renderStars($averageRating, 5, true) ?>
                <span class="text-muted ms-2">(<?= $reviewCount ?> avis)</span>
            </div>
        <?php endif; ?>

        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="text-primary mb-0"><?= formatPrice($product['price']) ?></h2>
                    <span class="badge bg-info fs-6"><?= e($product['condition']) ?></span>
                </div>
                <hr>
                <h5>Description</h5>
                <p class="text-muted"><?= nl2br(e($product['description'])) ?></p>
                <hr>
                <div class="mb-3">
                    <i class="fas fa-clock text-muted"></i>
                    <small class="text-muted">Publié le <?= formatDate($product['created_at'], 'd/m/Y') ?></small>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2">
            <div class="d-flex gap-2">
                <button class="btn btn-primary btn-lg flex-grow-1 add-to-cart" data-product-id="<?= $product['id'] ?>">
                    <i class="fas fa-cart-plus"></i> Ajouter au panier
                </button>
                <button class="wishlist-btn wishlist-btn-large btn btn-outline-danger btn-lg"
                    data-product-id="<?= $product['id'] ?>"
                    title="Ajouter aux favoris">
                    <i class="far fa-heart"></i>
                </button>
            </div>
            <a href="<?= url('/products') ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Retour aux produits
            </a>
        </div>
    </div>
</div>

<!-- Section Avis et Commentaires -->
<div class="row mt-5">
    <div class="col-12">
        <h3 class="mb-4">
            <i class="fas fa-comments"></i> Avis des clients
            <?php if ($reviewCount > 0): ?>
                <span class="badge bg-primary"><?= $reviewCount ?></span>
            <?php endif; ?>
        </h3>

        <!-- Formulaire d'ajout d'avis -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if (!$hasReviewed): ?>
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-star"></i> Laisser un avis
                    </div>
                    <div class="card-body">
                        <form id="reviewForm">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

                            <div class="mb-3">
                                <label class="form-label fw-bold">Votre note *</label>
                                <div class="star-selector" id="starSelector">
                                    <i class="far fa-star star-selectable" data-value="1"></i>
                                    <i class="far fa-star star-selectable" data-value="2"></i>
                                    <i class="far fa-star star-selectable" data-value="3"></i>
                                    <i class="far fa-star star-selectable" data-value="4"></i>
                                    <i class="far fa-star star-selectable" data-value="5"></i>
                                </div>
                                <input type="hidden" name="rating" id="ratingInput" required>
                                <small class="text-muted">Cliquez sur les étoiles pour noter</small>
                            </div>

                            <div class="mb-3">
                                <label for="comment" class="form-label fw-bold">Votre commentaire (optionnel)</label>
                                <textarea name="comment" id="comment" class="form-control" rows="4"
                                    placeholder="Partagez votre expérience avec ce produit..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Publier mon avis
                            </button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-check-circle"></i> Vous avez déjà laissé un avis pour ce produit.
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="alert alert-warning">
                <i class="fas fa-sign-in-alt"></i>
                <a href="<?= url('/login') ?>">Connectez-vous</a> pour laisser un avis.
            </div>
        <?php endif; ?>

        <!-- Liste des avis -->
        <?php if (count($reviews) > 0): ?>
            <div class="reviews-list">
                <?php foreach ($reviews as $review): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">
                                        <i class="fas fa-user-circle text-primary"></i>
                                        <?= e($review['username']) ?>
                                    </h6>
                                    <?= renderStars($review['rating'], 5, false) ?>
                                </div>
                                <small class="text-muted">
                                    <?= formatDate($review['created_at'], 'd/m/Y à H:i') ?>
                                </small>
                            </div>
                            <?php if (!empty($review['comment'])): ?>
                                <p class="mt-3 mb-0"><?= nl2br(e($review['comment'])) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php elseif (!isset($_SESSION['user_id']) || $hasReviewed): ?>
            <div class="alert alert-light text-center">
                <i class="fas fa-inbox"></i> Aucun avis pour le moment. Soyez le premier à donner votre avis !
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Produits similaires -->
<div class="mt-5">
    <h3 class="mb-4"><i class="fas fa-tags"></i> Vous pourriez aussi aimer</h3>
    <div class="row">
        <?php
        $productModel = new Product();
        $allProducts = $productModel->getAll();
        $similarProducts = array_filter($allProducts, function ($p) use ($product) {
            return $p['category'] === $product['category'] && $p['id'] !== $product['id'];
        });
        $similarProducts = array_slice($similarProducts, 0, 3);

        foreach ($similarProducts as $similar): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="<?= asset('uploads/' . $similar['image']) ?>"
                        class="card-img-top"
                        alt="<?= e($similar['name']) ?>"
                        style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?= e($similar['name']) ?></h5>
                        <p class="text-primary fw-bold"><?= formatPrice($similar['price']) ?></p>
                        <a href="<?= url('/products/' . $similar['id']) ?>"
                            class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye"></i> Voir
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
$scripts = <<<'JAVASCRIPT'
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion du sélecteur d'étoiles
    const starSelector = document.getElementById('starSelector');
    if (starSelector) {
        const stars = starSelector.querySelectorAll('.star-selectable');
        const ratingInput = document.getElementById('ratingInput');
        let selectedRating = 0;

        stars.forEach((star, index) => {
            // Hover effect
            star.addEventListener('mouseenter', function() {
                highlightStars(index + 1);
            });

            // Click to select
            star.addEventListener('click', function() {
                selectedRating = index + 1;
                ratingInput.value = selectedRating;
                highlightStars(selectedRating);
            });
        });

        // Reset on mouse leave if no rating selected
        starSelector.addEventListener('mouseleave', function() {
            if (selectedRating > 0) {
                highlightStars(selectedRating);
            } else {
                resetStars();
            }
        });

        function highlightStars(count) {
            stars.forEach((star, index) => {
                if (index < count) {
                    star.classList.remove('far');
                    star.classList.add('fas', 'text-warning');
                } else {
                    star.classList.remove('fas', 'text-warning');
                    star.classList.add('far');
                }
            });
        }

        function resetStars() {
            stars.forEach(star => {
                star.classList.remove('fas', 'text-warning');
                star.classList.add('far');
            });
        }
    }

    // Gestion du formulaire d'avis
    const reviewForm = document.getElementById('reviewForm');
    if (reviewForm) {
        reviewForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const rating = document.getElementById('ratingInput').value;
            if (!rating) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Note requise',
                    text: 'Veuillez sélectionner une note en cliquant sur les étoiles'
                });
                return;
            }

            const formData = new FormData(reviewForm);

            fetch('/reviews/store', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Merci !',
                        text: data.message
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Une erreur est survenue lors de l\'envoi de votre avis'
                });
            });
        });
    }

    // Bouton Ajouter au panier
    const addToCartBtn = document.querySelector('.add-to-cart');
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'product_id=' + productId + '&quantity=1'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('cart-badge').textContent = data.count;
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Produit ajouté !',
                        text: data.message,
                        showCancelButton: true,
                        confirmButtonText: 'Voir le panier',
                        cancelButtonText: 'Continuer mes achats'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const cartOffcanvas = new bootstrap.Offcanvas(document.getElementById('cartOffcanvas'));
                            cartOffcanvas.show();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: data.message
                    });
                }
            });
        });
    }
});
</script>
JAVASCRIPT;
?>