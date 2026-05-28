<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg mt-5">
            <div class="card-header bg-primary text-white text-center">
                <h3 class="mb-0"><i class="fas fa-sign-in-alt"></i> Connexion</h3>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="<?= url('/login') ?>">
                    <?= csrfField() ?>

                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i> Email
                        </label>
                        <input type="email"
                            class="form-control <?= hasError('email') ? 'is-invalid' : '' ?>"
                            id="email"
                            name="email"
                            value="<?= old('email') ?>"
                            required
                            autofocus>
                        <?php if (hasError('email')): ?>
                            <div class="invalid-feedback">
                                <?= e(errors('email')[0]) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i> Mot de passe
                        </label>
                        <input type="password"
                            class="form-control <?= hasError('password') ? 'is-invalid' : '' ?>"
                            id="password"
                            name="password"
                            required>
                        <?php if (hasError('password')): ?>
                            <div class="invalid-feedback">
                                <?= e(errors('password')[0]) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt"></i> Se connecter
                        </button>
                    </div>
                </form>

                <hr>

                <div class="text-center">
                    <p class="mb-0">Pas encore de compte ?</p>
                    <a href="<?= url('/register') ?>" class="btn btn-outline-success">
                        <i class="fas fa-user-plus"></i> Créer un compte
                    </a>
                </div>

                <div class="alert alert-info mt-3">
                    <strong><i class="fas fa-info-circle"></i> Comptes de test :</strong><br>
                    <small>
                        Admin: <code>admin@vinshop.com</code> / <code>password123</code><br>
                        User: <code>john@example.com</code> / <code>password123</code>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>