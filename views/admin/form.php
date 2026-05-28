<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-<?= isset($product) ? 'edit' : 'plus' ?>"></i>
                        <?= isset($product) ? 'Modifier le produit' : 'Ajouter un produit' ?>
                    </h3>
                </div>
                <div class="card-body">
                    <form method="POST"
                        action="<?= url('/admin/products/' . (isset($product) ? 'update' : 'store')) ?>"
                        enctype="multipart/form-data">

                        <?php if (isset($product)): ?>
                            <input type="hidden" name="id" value="<?= e($product['id']) ?>">
                            <input type="hidden" name="current_image" value="<?= e($product['image']) ?>">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="name" class="form-label">Nom du produit *</label>
                            <input type="text"
                                class="form-control"
                                id="name"
                                name="name"
                                value="<?= isset($product) ? e($product['name']) : '' ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control"
                                id="description"
                                name="description"
                                rows="4"
                                required><?= isset($product) ? e($product['description']) : '' ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="price" class="form-label">Prix (€) *</label>
                                <input type="number"
                                    class="form-control"
                                    id="price"
                                    name="price"
                                    step="0.01"
                                    value="<?= isset($product) ? e($product['price']) : '' ?>"
                                    required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="stock" class="form-label">Stock *</label>
                                <input type="number"
                                    class="form-control"
                                    id="stock"
                                    name="stock"
                                    min="0"
                                    value="<?= isset($product) ? e($product['stock']) : 1 ?>"
                                    required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="category" class="form-label">Catégorie *</label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="">Choisir...</option>
                                    <option value="Vêtements" <?= isset($product) && $product['category'] === 'Vêtements' ? 'selected' : '' ?>>Vêtements</option>
                                    <option value="Électronique" <?= isset($product) && $product['category'] === 'Électronique' ? 'selected' : '' ?>>Électronique</option>
                                    <option value="Maison" <?= isset($product) && $product['category'] === 'Maison' ? 'selected' : '' ?>>Maison</option>
                                    <option value="Sport" <?= isset($product) && $product['category'] === 'Sport' ? 'selected' : '' ?>>Sport</option>
                                    <option value="Livres" <?= isset($product) && $product['category'] === 'Livres' ? 'selected' : '' ?>>Livres</option>
                                    <option value="Jouets" <?= isset($product) && $product['category'] === 'Jouets' ? 'selected' : '' ?>>Jouets</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="condition" class="form-label">État *</label>
                            <select class="form-select" id="condition" name="condition" required>
                                <option value="">Choisir...</option>
                                <option value="Neuf" <?= isset($product) && $product['condition'] === 'Neuf' ? 'selected' : '' ?>>Neuf</option>
                                <option value="Comme neuf" <?= isset($product) && $product['condition'] === 'Comme neuf' ? 'selected' : '' ?>>Comme neuf</option>
                                <option value="Très bon état" <?= isset($product) && $product['condition'] === 'Très bon état' ? 'selected' : '' ?>>Très bon état</option>
                                <option value="Bon état" <?= isset($product) && $product['condition'] === 'Bon état' ? 'selected' : '' ?>>Bon état</option>
                                <option value="État correct" <?= isset($product) && $product['condition'] === 'État correct' ? 'selected' : '' ?>>État correct</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">
                                Image <?= isset($product) ? '(laisser vide pour conserver l\'image actuelle)' : '*' ?>
                            </label>
                            <input type="file"
                                class="form-control"
                                id="image"
                                name="image"
                                accept="image/*"
                                <?= !isset($product) ? 'required' : '' ?>>
                            <?php if (isset($product)): ?>
                                <div class="mt-2">
                                    <img src="<?= asset('uploads/' . e($product['image'])) ?>"
                                        class="img-thumbnail"
                                        style="max-width: 200px;">
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                            <a href="<?= url('/admin/products') ?>" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>