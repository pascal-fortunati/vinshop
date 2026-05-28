<h1 class="mb-4"><i class="fas fa-user-circle"></i> Mon Profil</h1>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-user-circle fa-5x text-primary"></i>
                </div>
                <h4><?= e($user['username']) ?></h4>
                <p class="text-muted"><?= e($user['email']) ?></p>
                <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : 'primary' ?>">
                    <?= $user['role'] === 'admin' ? 'Administrateur' : 'Utilisateur' ?>
                </span>
                <hr>
                <p class="small text-muted">
                    <i class="fas fa-calendar"></i> Membre depuis le<br>
                    <?= formatDate($user['created_at'], 'd/m/Y') ?>
                </p>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-link"></i> Liens rapides</h5>
                <div class="d-grid gap-2">
                    <a href="<?= url('/orders/history') ?>" class="btn btn-outline-primary">
                        <i class="fas fa-history"></i> Mes commandes
                    </a>
                    <a href="<?= url('/products') ?>" class="btn btn-outline-success">
                        <i class="fas fa-shopping-bag"></i> Continuer mes achats
                    </a>
                    <?php if ($user['role'] === 'admin'): ?>
                        <a href="<?= url('/admin') ?>" class="btn btn-outline-danger">
                            <i class="fas fa-cog"></i> Administration
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-edit"></i> Modifier mes informations</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= url('/profile/update') ?>">
                    <?= csrfField() ?>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">
                                <i class="fas fa-user"></i> Prénom *
                            </label>
                            <input type="text"
                                class="form-control <?= hasError('first_name') ? 'is-invalid' : '' ?>"
                                id="first_name"
                                name="first_name"
                                value="<?= old('first_name', $user['first_name']) ?>"
                                required>
                            <?php if (hasError('first_name')): ?>
                                <div class="invalid-feedback"><?= e(errors('first_name')[0]) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">
                                <i class="fas fa-user"></i> Nom *
                            </label>
                            <input type="text"
                                class="form-control <?= hasError('last_name') ? 'is-invalid' : '' ?>"
                                id="last_name"
                                name="last_name"
                                value="<?= old('last_name', $user['last_name']) ?>"
                                required>
                            <?php if (hasError('last_name')): ?>
                                <div class="invalid-feedback"><?= e(errors('last_name')[0]) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i> Email
                        </label>
                        <input type="email"
                            class="form-control"
                            id="email"
                            value="<?= e($user['email']) ?>"
                            disabled>
                        <small class="text-muted">L'email ne peut pas être modifié</small>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">
                            <i class="fas fa-phone"></i> Téléphone
                        </label>
                        <input type="tel"
                            class="form-control <?= hasError('phone') ? 'is-invalid' : '' ?>"
                            id="phone"
                            name="phone"
                            value="<?= old('phone', $user['phone'] ?? '') ?>">
                        <?php if (hasError('phone')): ?>
                            <div class="invalid-feedback"><?= e(errors('phone')[0]) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">
                            <i class="fas fa-map-marker-alt"></i> Adresse complète
                        </label>
                        <textarea class="form-control <?= hasError('address') ? 'is-invalid' : '' ?>"
                            id="address"
                            name="address"
                            rows="3"><?= old('address', $user['address'] ?? '') ?></textarea>
                        <small class="text-muted">Cette adresse sera utilisée pour vos commandes</small>
                        <?php if (hasError('address')): ?>
                            <div class="invalid-feedback"><?= e(errors('address')[0]) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Section sécurité -->
        <div class="card mt-3">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-lock"></i> Sécurité</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong><i class="fas fa-shield-alt"></i> Statut du compte :</strong></p>
                        <span class="badge bg-success">Actif</span>
                    </div>
                    <div class="col-md-6">
                        <p><strong><i class="fas fa-clock"></i> Dernière connexion :</strong></p>
                        <p class="text-muted"><?= formatDate('now', 'd/m/Y à H:i') ?></p>
                    </div>
                </div>
                <hr>
                <p class="text-muted mb-0">
                    <i class="fas fa-info-circle"></i>
                    Pour changer votre mot de passe ou supprimer votre compte, contactez l'administrateur.
                </p>
            </div>
        </div>
    </div>
</div>