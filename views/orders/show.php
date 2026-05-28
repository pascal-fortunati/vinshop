<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-file-invoice"></i> Commande #<?= $order['id'] ?></h1>
    <a href="<?= url('/orders/history') ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-shopping-bag"></i> Articles commandés</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Prix unitaire</th>
                                <th>Quantité</th>
                                <th>Sous-total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td>
                                        <strong><?= e($item['product_name']) ?></strong>
                                    </td>
                                    <td><?= formatPrice($item['product_price']) ?></td>
                                    <td><?= $item['quantity'] ?></td>
                                    <td><strong><?= formatPrice($item['subtotal']) ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td>
                                    <h5 class="text-success mb-0"><?= formatPrice($order['total']) ?></h5>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations</h5>
            </div>
            <div class="card-body">
                <p><strong>Date:</strong><br><?= formatDate($order['created_at'], 'd/m/Y à H:i') ?></p>
                <p><strong>Statut:</strong><br>
                    <?php
                    $statusLabels = [
                        'pending' => ['text' => 'En attente', 'class' => 'warning'],
                        'processing' => ['text' => 'En traitement', 'class' => 'info'],
                        'shipped' => ['text' => 'Expédiée', 'class' => 'primary'],
                        'delivered' => ['text' => 'Livrée', 'class' => 'success'],
                        'cancelled' => ['text' => 'Annulée', 'class' => 'danger']
                    ];
                    $status = $statusLabels[$order['status']];
                    ?>
                    <span class="badge bg-<?= $status['class'] ?>"><?= $status['text'] ?></span>
                </p>
                <p><strong>Paiement:</strong><br>
                    <?php
                    $payment = [
                        'card' => 'Carte bancaire',
                        'paypal' => 'PayPal',
                        'transfer' => 'Virement bancaire'
                    ];
                    echo $payment[$order['payment_method']] ?? $order['payment_method'];
                    ?>
                </p>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-truck"></i> Livraison</h5>
            </div>
            <div class="card-body">
                <p><strong>Adresse:</strong></p>
                <p><?= nl2br(e($order['shipping_address'])) ?></p>
                <?php if (!empty($order['notes'])): ?>
                    <hr>
                    <p><strong>Notes:</strong></p>
                    <p class="text-muted"><?= nl2br(e($order['notes'])) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>