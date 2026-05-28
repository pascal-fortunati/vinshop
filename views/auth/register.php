<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-lg mt-5 mb-5">
            <div class="card-header bg-success text-white text-center">
                <h3 class="mb-0"><i class="fas fa-user-plus"></i> Créer un compte</h3>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="<?= url('/register') ?>">
                    <?= csrfField() ?>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label">Nom d'utilisateur *</label>
                            <input type="text"
                                class="form-control <?= hasError('username') ? 'is-invalid' : '' ?>"
                                id="username"
                                name="username"
                                value="<?= old('username') ?>"
                                required>
                            <?php if (hasError('username')): ?>
                                <div class="invalid-feedback"><?= e(errors('username')[0]) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email"
                                class="form-control <?= hasError('email') ? 'is-invalid' : '' ?>"
                                id="email"
                                name="email"
                                value="<?= old('email') ?>"
                                required>
                            <?php if (hasError('email')): ?>
                                <div class="invalid-feedback"><?= e(errors('email')[0]) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">Prénom *</label>
                            <input type="text"
                                class="form-control <?= hasError('first_name') ? 'is-invalid' : '' ?>"
                                id="first_name"
                                name="first_name"
                                value="<?= old('first_name') ?>"
                                required>
                            <?php if (hasError('first_name')): ?>
                                <div class="invalid-feedback"><?= e(errors('first_name')[0]) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Nom *</label>
                            <input type="text"
                                class="form-control <?= hasError('last_name') ? 'is-invalid' : '' ?>"
                                id="last_name"
                                name="last_name"
                                value="<?= old('last_name') ?>"
                                required>
                            <?php if (hasError('last_name')): ?>
                                <div class="invalid-feedback"><?= e(errors('last_name')[0]) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Mot de passe * (min. 6 caractères)</label>
                            <input type="password"
                                class="form-control <?= hasError('password') ? 'is-invalid' : '' ?>"
                                id="password"
                                name="password"
                                required
                                minlength="6">
                            <?php if (hasError('password')): ?>
                                <div class="invalid-feedback"><?= e(errors('password')[0]) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="confirm_password" class="form-label">Confirmer le mot de passe *</label>
                            <input type="password"
                                class="form-control <?= hasError('confirm_password') ? 'is-invalid' : '' ?>"
                                id="confirm_password"
                                name="confirm_password"
                                required
                                minlength="6">
                            <?php if (hasError('confirm_password')): ?>
                                <div class="invalid-feedback"><?= e(errors('confirm_password')[0]) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Téléphone</label>
                        <input type="tel"
                            class="form-control <?= hasError('phone') ? 'is-invalid' : '' ?>"
                            id="phone"
                            name="phone"
                            value="<?= old('phone') ?>">
                        <?php if (hasError('phone')): ?>
                            <div class="invalid-feedback"><?= e(errors('phone')[0]) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Adresse</label>
                        <textarea class="form-control <?= hasError('address') ? 'is-invalid' : '' ?>"
                            id="address"
                            name="address"
                            rows="2"><?= old('address') ?></textarea>
                        <?php if (hasError('address')): ?>
                            <div class="invalid-feedback"><?= e(errors('address')[0]) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-user-plus"></i> Créer mon compte
                        </button>
                    </div>
                </form>

                <hr>

                <div class="text-center">
                    <p class="mb-0">Vous avez déjà un compte ?</p>
                    <a href="<?= url('/login') ?>" class="btn btn-outline-primary">
                        <i class="fas fa-sign-in-alt"></i> Se connecter
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>