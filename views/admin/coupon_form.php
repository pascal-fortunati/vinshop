<?php
$isEdit = isset($coupon);
$pageTitle = $isEdit ? 'Modifier le Code Promo' : 'Créer un Code Promo';
$actionUrl = $isEdit ? url('/admin/coupons/update/' . $coupon['id']) : url('/admin/coupons/store');
?>

<div class="mb-4">
    <h1>
        <i class="fas fa-ticket-alt"></i> <?= $pageTitle ?>
    </h1>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?= $actionUrl ?>" id="couponForm">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="code" class="form-label">
                                Code Promo <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control text-uppercase"
                                id="code"
                                name="code"
                                value="<?= $isEdit ? e($coupon['code']) : '' ?>"
                                required
                                style="font-weight: bold; letter-spacing: 1px;">
                            <small class="text-muted">Ex: NOEL2025, PROMO10, BIENVENUE</small>
                        </div>

                        <div class="col-md-6">
                            <label for="type" class="form-label">
                                Type de Réduction <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="type" name="type" required onchange="updateValueLabel()">
                                <option value="percentage" <?= $isEdit && $coupon['type'] === 'percentage' ? 'selected' : '' ?>>
                                    Pourcentage (%)
                                </option>
                                <option value="fixed" <?= $isEdit && $coupon['type'] === 'fixed' ? 'selected' : '' ?>>
                                    Montant Fixe (€)
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="value" class="form-label">
                                <span id="valueLabel">Valeur</span> <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number"
                                    class="form-control"
                                    id="value"
                                    name="value"
                                    value="<?= $isEdit ? number_format($coupon['value'], 2, '.', '') : '' ?>"
                                    step="0.01"
                                    min="0.01"
                                    required>
                                <span class="input-group-text" id="valueUnit">%</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="min_amount" class="form-label">
                                Montant Minimum de Commande
                            </label>
                            <div class="input-group">
                                <input type="number"
                                    class="form-control"
                                    id="min_amount"
                                    name="min_amount"
                                    value="<?= $isEdit && $coupon['min_amount'] ? number_format($coupon['min_amount'], 2, '.', '') : '' ?>"
                                    step="0.01"
                                    min="0">
                                <span class="input-group-text">€</span>
                            </div>
                            <small class="text-muted">Laisser vide pour aucune limite</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="max_uses" class="form-label">
                            Nombre Maximum d'Utilisations
                        </label>
                        <input type="number"
                            class="form-control"
                            id="max_uses"
                            name="max_uses"
                            value="<?= $isEdit && $coupon['max_uses'] ? $coupon['max_uses'] : '' ?>"
                            min="1">
                        <small class="text-muted">Laisser vide pour illimité</small>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="valid_from" class="form-label">
                                Date de Début
                            </label>
                            <input type="datetime-local"
                                class="form-control"
                                id="valid_from"
                                name="valid_from"
                                value="<?= $isEdit && $coupon['valid_from'] ? date('Y-m-d\TH:i', strtotime($coupon['valid_from'])) : '' ?>">
                        </div>

                        <div class="col-md-6">
                            <label for="valid_until" class="form-label">
                                Date de Fin
                            </label>
                            <input type="datetime-local"
                                class="form-control"
                                id="valid_until"
                                name="valid_until"
                                value="<?= $isEdit && $coupon['valid_until'] ? date('Y-m-d\TH:i', strtotime($coupon['valid_until'])) : '' ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">
                            Description
                        </label>
                        <textarea class="form-control"
                            id="description"
                            name="description"
                            rows="3"><?= $isEdit ? e($coupon['description']) : '' ?></textarea>
                        <small class="text-muted">Description interne (non visible par les clients)</small>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input"
                                type="checkbox"
                                id="active"
                                name="active"
                                <?= $isEdit && $coupon['active'] ? 'checked' : ($isEdit ? '' : 'checked') ?>>
                            <label class="form-check-label" for="active">
                                Code Promo Actif
                            </label>
                        </div>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input"
                                type="checkbox"
                                id="first_order_only"
                                name="first_order_only"
                                <?= $isEdit && isset($coupon['first_order_only']) && $coupon['first_order_only'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="first_order_only">
                                <i class="fas fa-user-plus text-info"></i> Réservé aux nouveaux clients
                            </label>
                            <small class="form-text text-muted d-block">
                                Si coché, le code ne sera valide que pour les clients qui n'ont jamais passé de commande
                            </small>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?= $isEdit ? 'Mettre à Jour' : 'Créer' ?>
                        </button>
                        <a href="<?= url('/admin/coupons') ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-info-circle"></i> Aide
                </h5>
                <ul class="small mb-0">
                    <li><strong>Code:</strong> Utilisé par les clients au checkout</li>
                    <li><strong>Pourcentage:</strong> Ex: 10 = 10% de réduction</li>
                    <li><strong>Montant Fixe:</strong> Ex: 5 = 5€ de réduction</li>
                    <li><strong>Montant Min:</strong> Commande minimale requise</li>
                    <li><strong>Max Utilisations:</strong> Limite le nombre de fois que le code peut être utilisé</li>
                    <li><strong>Dates:</strong> Période de validité du code</li>
                </ul>
            </div>
        </div>

        <?php if ($isEdit): ?>
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-chart-bar"></i> Statistiques
                    </h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <strong>Utilisé:</strong>
                            <span class="badge bg-primary"><?= $coupon['times_used'] ?> fois</span>
                        </li>
                        <li class="mb-2">
                            <strong>Créé le:</strong>
                            <?= date('d/m/Y à H:i', strtotime($coupon['created_at'])) ?>
                        </li>
                        <?php if ($coupon['updated_at'] != $coupon['created_at']): ?>
                            <li class="mb-2">
                                <strong>Modifié le:</strong>
                                <?= date('d/m/Y à H:i', strtotime($coupon['updated_at'])) ?>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function updateValueLabel() {
        const type = document.getElementById('type').value;
        const valueLabel = document.getElementById('valueLabel');
        const valueUnit = document.getElementById('valueUnit');
        const valueInput = document.getElementById('value');

        if (type === 'percentage') {
            valueLabel.textContent = 'Pourcentage de Réduction';
            valueUnit.textContent = '%';
            valueInput.max = '100';
        } else {
            valueLabel.textContent = 'Montant de Réduction';
            valueUnit.textContent = '€';
            valueInput.removeAttribute('max');
        }
    }

    // Initialiser au chargement
    updateValueLabel();

    // Convertir le code en majuscules automatiquement
    document.getElementById('code').addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
</script>