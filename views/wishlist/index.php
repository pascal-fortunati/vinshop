<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-heart text-danger"></i> Ma Liste de Souhaits</h1>
    <span class="badge bg-primary fs-5"><?= $count ?> produit<?= $count > 1 ? 's' : '' ?></span>
</div>

<?php if (empty($products)): ?>
    <div class="alert alert-info text-center py-5">
        <i class="fas fa-heart fa-3x text-muted mb-3"></i>
        <h4>Votre liste de souhaits est vide</h4>
        <p class="text-muted">Ajoutez des produits à vos favoris en cliquant sur le cœur ❤️</p>
        <a href="<?= url('/products') ?>" class="btn btn-primary mt-3">
            <i class="fas fa-shopping-bag"></i> Découvrir nos produits
        </a>
    </div>
<?php else: ?>
    <div class="row">
        <?php foreach ($products as $product): ?>
            <div class="col-md-4 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm product-card">
                    <!-- Badge "Ajouté le" -->
                    <div class="position-absolute top-0 end-0 p-2">
                        <button class="btn btn-sm btn-danger remove-from-wishlist"
                            data-product-id="<?= $product['id'] ?>"
                            data-product-name="<?= e($product['name']) ?>"
                            title="Retirer des favoris">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>

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
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="h5 text-primary mb-0">
                                    <?= number_format($product['price'], 2, ',', ' ') ?> €
                                </span>
                                <small class="text-muted">
                                    <i class="fas fa-box"></i> <?= $product['stock'] ?> en stock
                                </small>
                            </div>
                            <small class="text-muted d-block mb-2">
                                <i class="fas fa-clock"></i> Ajouté le <?= formatDate($product['added_at'], 'd/m/Y') ?>
                            </small>
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

<?php
$scripts = <<<'JAVASCRIPT'
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Retirer des favoris
    document.querySelectorAll('.remove-from-wishlist').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const productName = this.dataset.productName;
            const card = this.closest('.col-md-4');

            Swal.fire({
                title: 'Retirer des favoris ?',
                text: `Voulez-vous retirer "${productName}" de votre liste de souhaits ?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Oui, retirer',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#dc3545'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('/wishlist/remove', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'product_id=' + productId
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Animer la disparition
                            card.style.transition = 'opacity 0.3s, transform 0.3s';
                            card.style.opacity = '0';
                            card.style.transform = 'scale(0.9)';
                            
                            setTimeout(() => {
                                card.remove();
                                
                                // Mettre à jour le badge
                                document.getElementById('wishlist-badge').textContent = data.count;
                                
                                // Si plus de produits, recharger la page
                                if (data.count === 0) {
                                    location.reload();
                                }
                            }, 300);

                            Swal.fire({
                                icon: 'success',
                                title: 'Retiré !',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                text: data.message
                            });
                        }
                    });
                }
            });
        });
    });

    // Ajouter au panier
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function() {
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
                        cancelButtonText: 'Continuer'
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
    });
});
</script>
JAVASCRIPT;
?>