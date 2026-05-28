<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <i class="fas fa-ticket-alt"></i> Gestion des Codes Promo
    </h1>
    <a href="<?= url('/admin/coupons/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouveau Code Promo
    </a>
</div>

<?php if (empty($coupons)): ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> Aucun code promo créé pour le moment.
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Type</th>
                            <th>Valeur</th>
                            <th>Montants</th>
                            <th>Utilisations</th>
                            <th>Validité</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($coupons as $coupon): ?>
                            <?php
                            $stats = $coupon['stats'];
                            $status = $stats['status'];
                            $statusClass = [
                                'active' => 'success',
                                'inactive' => 'secondary',
                                'expired' => 'danger',
                                'upcoming' => 'info'
                            ][$status];
                            $statusText = [
                                'active' => 'Actif',
                                'inactive' => 'Inactif',
                                'expired' => 'Expiré',
                                'upcoming' => 'À venir'
                            ][$status];
                            ?>
                            <tr>
                                <td>
                                    <strong class="text-primary"><?= e($coupon['code']) ?></strong>
                                    <?php if ($coupon['description']): ?>
                                        <br><small class="text-muted"><?= e($coupon['description']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($coupon['type'] === 'percentage'): ?>
                                        <span class="badge bg-info">
                                            <i class="fas fa-percent"></i> Pourcentage
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">
                                            <i class="fas fa-euro-sign"></i> Montant Fixe
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong>
                                        <?php if ($coupon['type'] === 'percentage'): ?>
                                            <?= number_format($coupon['value'], 0) ?>%
                                        <?php else: ?>
                                            <?= formatPrice($coupon['value']) ?>
                                        <?php endif; ?>
                                    </strong>
                                </td>
                                <td>
                                    <?= $coupon['min_amount'] ? formatPrice($coupon['min_amount']) : '—' ?>
                                </td>
                                <td>
                                    <span class="text-<?= $coupon['max_uses'] && $coupon['times_used'] >= $coupon['max_uses'] ? 'danger' : 'muted' ?>">
                                        <?= $coupon['times_used'] ?>
                                        <?php if ($coupon['max_uses']): ?>
                                            / <?= $coupon['max_uses'] ?>
                                        <?php else: ?>
                                            / ∞
                                        <?php endif; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($coupon['valid_from']): ?>
                                        <small>Du <?= date('d/m/Y', strtotime($coupon['valid_from'])) ?></small><br>
                                    <?php endif; ?>
                                    <?php if ($coupon['valid_until']): ?>
                                        <small>Au <?= date('d/m/Y', strtotime($coupon['valid_until'])) ?></small>
                                    <?php else: ?>
                                        <small class="text-muted">Sans limite</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $statusClass ?>">
                                        <?= $statusText ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <!-- Toggle Active -->
                                        <form method="POST" action="<?= url('/admin/coupons/toggle/' . $coupon['id']) ?>" style="display: inline;">
                                            <button type="submit" class="btn btn-<?= $coupon['active'] ? 'warning' : 'success' ?>"
                                                title="<?= $coupon['active'] ? 'Désactiver' : 'Activer' ?>">
                                                <i class="fas fa-<?= $coupon['active'] ? 'eye-slash' : 'eye' ?>"></i>
                                            </button>
                                        </form>

                                        <!-- Edit -->
                                        <a href="<?= url('/admin/coupons/edit/' . $coupon['id']) ?>"
                                            class="btn btn-primary" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <!-- Delete -->
                                        <form method="POST" action="<?= url('/admin/coupons/delete/' . $coupon['id']) ?>"
                                            style="display: inline;"
                                            onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce code promo ?')">
                                            <button type="submit" class="btn btn-danger" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<style>
    .table td {
        vertical-align: middle;
    }

    .btn-group-sm .btn i {
        font-size: 0.875rem;
        width: 1em;
        text-align: center;
    }

    .btn-group-sm .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
</style>