<h1 class="mb-4"><i class="fas fa-shopping-cart"></i> Finaliser ma commande</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-truck"></i> Adresse de livraison</h5>
            </div>
            <div class="card-body">
                <form id="checkout-form">
                    <?= csrfField() ?>

                    <div class="mb-3">
                        <label for="shipping_address" class="form-label">Adresse complète *</label>
                        <textarea class="form-control"
                            id="shipping_address"
                            name="shipping_address"
                            rows="3"
                            required><?= e($user['address']) ?></textarea>
                        <small class="text-muted">Rue, ville, code postal, pays</small>
                    </div>

                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Mode de paiement *</label>
                        <select class="form-select" id="payment_method" name="payment_method" required>
                            <option value="card">Carte bancaire</option>
                            <option value="paypal">PayPal</option>
                            <option value="transfer">Virement bancaire</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes / Instructions</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-receipt"></i> Récapitulatif</h5>
            </div>
            <div class="card-body">
                <?php foreach ($items as $item): ?>
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <strong><?= e($item['name']) ?></strong><br>
                            <small class="text-muted"><?= $item['quantity'] ?> × <?= formatPrice($item['price']) ?></small>
                        </div>
                        <div>
                            <strong><?= formatPrice($item['subtotal']) ?></strong>
                        </div>
                    </div>
                    <hr>
                <?php endforeach; ?>

                <!-- Code Promo -->
                <div class="mb-3">
                    <label for="coupon_code" class="form-label fw-bold">
                        <i class="fas fa-ticket-alt"></i> Code Promo
                    </label>
                    <div class="input-group">
                        <input type="text"
                            class="form-control text-uppercase"
                            id="coupon_code"
                            placeholder="ENTRER VOTRE CODE"
                            style="font-weight: 500; letter-spacing: 1px;">
                        <button class="btn btn-outline-primary" type="button" id="apply_coupon">
                            Appliquer
                        </button>
                    </div>
                    <div id="coupon_message" class="mt-2"></div>
                </div>

                <hr>

                <!-- Totaux -->
                <div class="d-flex justify-content-between mb-2">
                    <span>Sous-total:</span>
                    <span id="subtotal"><?= formatPrice($total) ?></span>
                </div>

                <div id="discount_row" class="d-flex justify-content-between mb-2 text-success" style="display: none !important;">
                    <span>
                        <i class="fas fa-tag"></i> Réduction (<span id="discount_label"></span>):
                    </span>
                    <span id="discount_amount">- <?= formatPrice(0) ?></span>
                    <button type="button" class="btn btn-link btn-sm text-danger p-0 ms-2" id="remove_coupon" title="Retirer le code promo">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <hr>

                <div class="d-flex justify-content-between mb-3">
                    <h5>Total:</h5>
                    <h4 class="text-success" id="total_amount"><?= formatPrice($total) ?></h4>
                </div>

                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-success btn-lg" id="validate-order">
                        <i class="fas fa-check"></i> Valider la commande
                    </button>
                    <a href="<?= url('/') ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Continuer mes achats
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('js/coupon.js') ?>"></script>
<script>
    document.getElementById('validate-order').addEventListener('click', function() {
        const form = document.getElementById('checkout-form');

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const formData = new FormData(form);

        Swal.fire({
            title: 'Confirmation',
            text: 'Confirmer votre commande ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Oui, commander !',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('<?= url('/orders/process') ?>', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        // Toujours parser le JSON, même en cas d'erreur HTTP
                        return response.json().then(data => ({
                            ok: response.ok,
                            status: response.status,
                            data: data
                        }));
                    })
                    .then(result => {
                        if (result.ok && result.data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Commande validée !',
                                html: `Votre commande #${result.data.order_id} a été enregistrée.<br>Vous recevrez un email de confirmation à <strong><?= e($user['email']) ?></strong>`,
                                confirmButtonText: 'Voir mes commandes'
                            }).then(() => {
                                window.location.href = '<?= url('/orders/history') ?>';
                            });
                        } else {
                            // Afficher l'erreur (stock insuffisant ou autre)
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur lors de la commande',
                                html: result.data.message || 'Une erreur est survenue',
                                showCancelButton: true,
                                confirmButtonText: '<i class="fas fa-shopping-cart"></i> Ajuster mon panier',
                                cancelButtonText: 'Fermer',
                                confirmButtonColor: '#0d6efd'
                            }).then((swalResult) => {
                                if (swalResult.isConfirmed) {
                                    // Ouvrir le offcanvas du panier
                                    const cartOffcanvas = new bootstrap.Offcanvas(document.getElementById('cartOffcanvas'));
                                    cartOffcanvas.show();
                                }
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: 'Une erreur technique est survenue'
                        });
                    });
            }
        });
    });
</script>