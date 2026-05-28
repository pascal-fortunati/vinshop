<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-comments"></i> Modération des avis</h1>
    <?php if ($pendingCount > 0): ?>
        <span class="badge bg-warning text-dark fs-5"><?= $pendingCount ?> en attente</span>
    <?php endif; ?>
</div>

<!-- Statistiques -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-warning text-dark">
            <div class="card-body text-center">
                <h3><?= $pendingCount ?></h3>
                <p class="mb-0"><i class="fas fa-clock"></i> En attente</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h3><?= count(array_filter($reviews, fn($r) => $r['status'] === 'approved')) ?></h3>
                <p class="mb-0"><i class="fas fa-check"></i> Approuvés</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <h3><?= count(array_filter($reviews, fn($r) => $r['status'] === 'rejected')) ?></h3>
                <p class="mb-0"><i class="fas fa-times"></i> Rejetés</p>
            </div>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="card mb-4">
    <div class="card-body">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-outline-primary active" data-filter="all">
                Tous (<?= count($reviews) ?>)
            </button>
            <button type="button" class="btn btn-outline-warning" data-filter="pending">
                En attente (<?= $pendingCount ?>)
            </button>
            <button type="button" class="btn btn-outline-success" data-filter="approved">
                Approuvés (<?= count(array_filter($reviews, fn($r) => $r['status'] === 'approved')) ?>)
            </button>
            <button type="button" class="btn btn-outline-danger" data-filter="rejected">
                Rejetés (<?= count(array_filter($reviews, fn($r) => $r['status'] === 'rejected')) ?>)
            </button>
        </div>
    </div>
</div>

<!-- Liste des avis -->
<?php if (empty($reviews)): ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> Aucun avis pour le moment.
    </div>
<?php else: ?>
    <?php foreach ($reviews as $review): ?>
        <div class="card mb-3 review-item" data-status="<?= $review['status'] ?>">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <strong><?= e($review['username']) ?></strong>
                    <span class="text-muted">sur</span>
                    <a href="<?= url('/products/' . $review['product_id']) ?>" target="_blank">
                        <?= e($review['product_name']) ?>
                    </a>
                </div>
                <div>
                    <?php if ($review['status'] === 'pending'): ?>
                        <span class="badge bg-warning text-dark">En attente</span>
                    <?php elseif ($review['status'] === 'approved'): ?>
                        <span class="badge bg-success">Approuvé</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Rejeté</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <?= renderStars($review['rating'], 5, true) ?>
                    <small class="text-muted ms-2">
                        <i class="fas fa-calendar"></i>
                        <?= formatDate($review['created_at'], 'd/m/Y à H:i') ?>
                    </small>
                </div>
                <?php if (!empty($review['comment'])): ?>
                    <p class="mb-0"><?= nl2br(e($review['comment'])) ?></p>
                <?php else: ?>
                    <p class="text-muted mb-0 fst-italic">Aucun commentaire</p>
                <?php endif; ?>
            </div>
            <div class="card-footer bg-transparent">
                <div class="btn-group btn-group-sm" role="group">
                    <?php if ($review['status'] !== 'approved'): ?>
                        <button type="button"
                            class="btn btn-success moderate-btn"
                            data-id="<?= $review['id'] ?>"
                            data-action="approve">
                            <i class="fas fa-check"></i> Approuver
                        </button>
                    <?php endif; ?>
                    <?php if ($review['status'] !== 'rejected'): ?>
                        <button type="button"
                            class="btn btn-warning moderate-btn"
                            data-id="<?= $review['id'] ?>"
                            data-action="reject">
                            <i class="fas fa-times"></i> Rejeter
                        </button>
                    <?php endif; ?>
                    <button type="button"
                        class="btn btn-danger moderate-btn"
                        data-id="<?= $review['id'] ?>"
                        data-action="delete">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php
$scripts = <<<'JAVASCRIPT'
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filtres
    const filterButtons = document.querySelectorAll('[data-filter]');
    const reviewItems = document.querySelectorAll('.review-item');

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            // Filter reviews
            reviewItems.forEach(item => {
                if (filter === 'all' || item.dataset.status === filter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // Modération
    const moderateButtons = document.querySelectorAll('.moderate-btn');
    
    moderateButtons.forEach(button => {
        button.addEventListener('click', function() {
            const reviewId = this.dataset.id;
            const action = this.dataset.action;
            const reviewCard = this.closest('.review-item');

            const actionText = {
                'approve': 'approuver',
                'reject': 'rejeter',
                'delete': 'supprimer'
            };

            Swal.fire({
                title: 'Confirmation',
                text: `Voulez-vous vraiment ${actionText[action]} cet avis ?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Oui',
                cancelButtonText: 'Annuler',
                confirmButtonColor: action === 'delete' ? '#dc3545' : '#0d6efd'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('review_id', reviewId);
                    formData.append('action', action);

                    fetch('/admin/reviews/moderate', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Succès',
                                text: data.message,
                                timer: 2000
                            }).then(() => {
                                if (action === 'delete') {
                                    reviewCard.remove();
                                } else {
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                text: data.message
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: 'Une erreur est survenue'
                        });
                    });
                }
            });
        });
    });
});
</script>
JAVASCRIPT;
?>