<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-cog"></i> Administration des produits</h1>
    <a href="<?= url('/admin/products/create') ?>" class="btn btn-success">
        <i class="fas fa-plus"></i> Ajouter un produit
    </a>
</div>

<?php if (isset($_GET['success'])): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Succès!',
            text: '<?= $_GET['success'] === 'created' ? 'Produit créé avec succès' : ($_GET['success'] === 'updated' ? 'Produit modifié avec succès' : 'Produit supprimé avec succès') ?>',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>État</th>
                        <th>Prix</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td>
                                <img src="<?= asset('uploads/' . $product['image']) ?>"
                                    style="width: 50px; height: 50px; object-fit: cover;"
                                    class="rounded">
                            </td>
                            <td><?= e($product['name']) ?></td>
                            <td><span class="badge bg-secondary"><?= e($product['category']) ?></span></td>
                            <td><span class="badge bg-info"><?= e($product['condition']) ?></span></td>
                            <td class="fw-bold text-primary"><?= formatPrice($product['price']) ?></td>
                            <td><?= formatDate($product['created_at'], 'd/m/Y') ?></td>
                            <td>
                                <a href="<?= url('/admin/products/edit/' . $product['id']) ?>"
                                    class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-danger delete-product"
                                    data-product-id="<?= $product['id'] ?>"
                                    data-product-name="<?= e($product['name']) ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
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
    renderPagination($pagination, '/admin/products');
}
?>

<script>
    document.querySelectorAll('.delete-product').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const productName = this.dataset.productName;

            Swal.fire({
                title: 'Êtes-vous sûr?',
                text: `Voulez-vous vraiment supprimer "${productName}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer!',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '<?= url('/admin/products/delete') ?>';

                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '<?= $_SESSION['csrf_token'] ?? '' ?>';

                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'id';
                    input.value = productId;

                    form.appendChild(csrfInput);
                    form.appendChild(input);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
</script>