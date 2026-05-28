<h1 class="mb-4"><i class="fas fa-tachometer-alt"></i> Tableau de bord</h1>

<!-- Statistiques -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3><?= $stats['total_orders'] ?></h3>
                        <p class="mb-0">Commandes</p>
                    </div>
                    <div>
                        <i class="fas fa-shopping-cart fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3><?= formatPrice($stats['total_revenue']) ?></h3>
                        <p class="mb-0">Chiffre d'affaires</p>
                    </div>
                    <div>
                        <i class="fas fa-euro-sign fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3><?= $stats['total_products'] ?></h3>
                        <p class="mb-0">Produits</p>
                    </div>
                    <div>
                        <i class="fas fa-box fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3><?= $stats['total_users'] ?></h3>
                        <p class="mb-0">Utilisateurs</p>
                    </div>
                    <div>
                        <i class="fas fa-users fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Commandes par statut -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Commandes par statut</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php foreach ($stats['by_status'] as $stat): ?>
                        <?php
                        $statusInfo = [
                            'pending' => ['text' => 'En attente', 'class' => 'warning', 'icon' => 'clock'],
                            'processing' => ['text' => 'En traitement', 'class' => 'info', 'icon' => 'cog'],
                            'shipped' => ['text' => 'Expédiées', 'class' => 'primary', 'icon' => 'truck'],
                            'delivered' => ['text' => 'Livrées', 'class' => 'success', 'icon' => 'check-circle'],
                            'cancelled' => ['text' => 'Annulées', 'class' => 'danger', 'icon' => 'times-circle']
                        ];
                        $info = $statusInfo[$stat['status']] ?? ['text' => $stat['status'], 'class' => 'secondary', 'icon' => 'question'];
                        ?>
                        <div class="col-md-2">
                            <div class="text-center p-3 border rounded">
                                <i class="fas fa-<?= $info['icon'] ?> fa-2x text-<?= $info['class'] ?> mb-2"></i>
                                <h4><?= $stat['count'] ?></h4>
                                <p class="mb-0 small"><?= $info['text'] ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dernières commandes -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-list"></i> Dernières commandes</h5>
                <a href="<?= url('/admin/orders') ?>" class="btn btn-sm btn-light">
                    Voir tout
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Client</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentOrders as $order): ?>
                                <tr>
                                    <td><strong>#<?= $order['id'] ?></strong></td>
                                    <td><?= e($order['first_name'] . ' ' . $order['last_name']) ?></td>
                                    <td><?= formatDate($order['created_at'], 'd/m/Y H:i') ?></td>
                                    <td><strong><?= formatPrice($order['total']) ?></strong></td>
                                    <td>
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
                                    </td>
                                    <td>
                                        <a href="<?= url('/admin/orders/' . $order['id']) ?>"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>