<h1 class="mb-4"><i class="fas fa-shopping-cart"></i> Gestion des commandes</h1>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Client</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Paiement</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><strong>#<?= $order['id'] ?></strong></td>
                            <td><?= e($order['first_name'] . ' ' . $order['last_name']) ?></td>
                            <td><?= e($order['email']) ?></td>
                            <td><?= formatDate($order['created_at'], 'd/m/Y H:i') ?></td>
                            <td><strong><?= formatPrice($order['total']) ?></strong></td>
                            <td>
                                <?php
                                $paymentMethods = [
                                    'card' => ['class' => 'fas', 'icon' => 'credit-card'],
                                    'paypal' => ['class' => 'fa-brands', 'icon' => 'cc-paypal'],
                                    'transfer' => ['class' => 'fas', 'icon' => 'university']
                                ];
                                $payment = $paymentMethods[$order['payment_method']] ?? ['class' => 'fas', 'icon' => 'credit-card'];
                                ?>
                                <i class="<?= $payment['class'] ?> fa-<?= $payment['icon'] ?>"></i>
                            </td>
                            <td>
                                <select class="form-select form-select-sm status-select"
                                    data-order-id="<?= $order['id'] ?>"
                                    data-current-status="<?= $order['status'] ?>">
                                    <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>En attente</option>
                                    <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>En traitement</option>
                                    <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Expédiée</option>
                                    <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : '' ?>>Livrée</option>
                                    <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Annulée</option>
                                </select>
                            </td>
                            <td>
                                <a href="<?= url('/admin/orders/' . $order['id']) ?>"
                                    class="btn btn-sm btn-primary">
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

<?php
// Afficher la pagination
if (isset($pagination)) {
    renderPagination($pagination, '/admin/orders');
}
?>

<script>
    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', function() {
            const orderId = this.dataset.orderId;
            const currentStatus = this.dataset.currentStatus;
            const newStatus = this.value;

            if (newStatus === currentStatus) return;

            Swal.fire({
                title: 'Modifier le statut?',
                text: 'Voulez-vous changer le statut de cette commande?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Oui, modifier',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('index.php?page=admin&action=update_order_status', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `order_id=${orderId}&status=${newStatus}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Statut mis à jour!',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                this.dataset.currentStatus = newStatus;
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erreur',
                                    text: data.message
                                });
                                this.value = currentStatus;
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                text: 'Une erreur est survenue'
                            });
                            this.value = currentStatus;
                        });
                } else {
                    this.value = currentStatus;
                }
            });
        });
    });
</script>