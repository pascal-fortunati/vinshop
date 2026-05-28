<h1 class="mb-4"><i class="fas fa-history"></i> Mes commandes</h1>

<?php if (empty($orders)): ?>
    <div class="alert alert-info text-center py-5">
        <i class="fas fa-shopping-bag fa-3x mb-3"></i>
        <h4>Aucune commande</h4>
        <p>Vous n'avez pas encore passé de commande</p>
        <a href="<?= url('/products') ?>" class="btn btn-primary">
            <i class="fas fa-store"></i> Voir les produits
        </a>
    </div>
<?php else: ?>
    <?php foreach ($orders as $order): ?>
        <div class="card mb-3">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <strong>Commande #<?= $order['id'] ?></strong><br>
                        <small class="text-muted"><?= formatDate($order['created_at'], 'd/m/Y H:i') ?></small>
                    </div>
                    <div class="col-md-3">
                        <strong>Total:</strong> <?= formatPrice($order['total']) ?>
                    </div>
                    <div class="col-md-3">
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
                        <span class="badge bg-<?= $status['class'] ?>">
                            <?= $status['text'] ?>
                        </span>
                    </div>
                    <div class="col-md-3 text-end">
                        <a href="<?= url('/orders/' . $order['id']) ?>"
                            class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i> Détails
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <?php
    // Afficher la pagination
    if (isset($pagination)) {
        renderPagination($pagination, '/orders/history');
    }
    ?>
<?php endif; ?>