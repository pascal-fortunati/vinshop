<h1 class="mb-4"><i class="fas fa-users"></i> Gestion des utilisateurs</h1>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Nom complet</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Inscription</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><strong>@<?= e($user['username']) ?></strong></td>
                            <td><?= e($user['first_name'] . ' ' . $user['last_name']) ?></td>
                            <td><?= e($user['email']) ?></td>
                            <td>
                                <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : 'primary' ?>">
                                    <?= $user['role'] === 'admin' ? 'Admin' : 'Utilisateur' ?>
                                </span>
                            </td>
                            <td><?= formatDate($user['created_at'], 'd/m/Y') ?></td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-info view-user-details"
                                    data-user-id="<?= $user['id'] ?>"
                                    title="Voir les détails">
                                    <i class="fas fa-eye"></i>
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
    renderPagination($pagination, '/admin/users');
}
?>

<!-- Modal Détails Utilisateur -->
<div class="modal fade" id="userDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-circle"></i> Détails de l'utilisateur
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="userDetailsContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Récupérer les détails d'un utilisateur
        document.querySelectorAll('.view-user-details').forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.dataset.userId;
                const modal = new bootstrap.Modal(document.getElementById('userDetailsModal'));
                const content = document.getElementById('userDetailsContent');

                // Afficher le loader
                content.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            `;

                // Ouvrir la modal
                modal.show();

                // Charger les données
                fetch('<?= url('/admin/users/details/') ?>' + userId)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            displayUserDetails(data.user, data.stats);
                        } else {
                            content.innerHTML = `
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> ${data.message}
                            </div>
                        `;
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        content.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> Une erreur est survenue
                        </div>
                    `;
                    });
            });
        });

        function displayUserDetails(user, stats) {
            const content = document.getElementById('userDetailsContent');
            content.innerHTML = `
            <div class="row">
                <!-- Informations personnelles -->
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header bg-primary text-white">
                            <i class="fas fa-user"></i> Informations personnelles
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="fw-bold" style="width: 40%;">Username:</td>
                                    <td>@${user.username}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Nom complet:</td>
                                    <td>${user.first_name} ${user.last_name}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Email:</td>
                                    <td>${user.email}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Téléphone:</td>
                                    <td>${user.phone || '<em class="text-muted">Non renseigné</em>'}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Adresse:</td>
                                    <td>${user.address || '<em class="text-muted">Non renseignée</em>'}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Rôle:</td>
                                    <td>
                                        <span class="badge bg-${user.role === 'admin' ? 'danger' : 'primary'}">
                                            ${user.role === 'admin' ? 'Administrateur' : 'Utilisateur'}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Inscription:</td>
                                    <td>${new Date(user.created_at).toLocaleDateString('fr-FR', {
                                        year: 'numeric',
                                        month: 'long',
                                        day: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    })}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Statistiques -->
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header bg-success text-white">
                            <i class="fas fa-chart-bar"></i> Statistiques
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <div class="p-3 bg-light rounded">
                                        <h3 class="mb-0 text-primary">${stats.total_orders}</h3>
                                        <small class="text-muted">Commandes</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="p-3 bg-light rounded">
                                        <h3 class="mb-0 text-success">${stats.total_spent_formatted}</h3>
                                        <small class="text-muted">Dépensé</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded">
                                        <h3 class="mb-0 text-info">${stats.wishlist_count}</h3>
                                        <small class="text-muted">Favoris</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded">
                                        <h3 class="mb-0 text-warning">${stats.reviews_count}</h3>
                                        <small class="text-muted">Avis</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    ${stats.recent_orders.length > 0 ? `
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <i class="fas fa-shopping-cart"></i> Dernières commandes
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                ${stats.recent_orders.map(order => `
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>#${order.id}</strong>
                                                <br>
                                                <small class="text-muted">${new Date(order.created_at).toLocaleDateString('fr-FR')}</small>
                                            </div>
                                            <div class="text-end">
                                                <strong class="text-success">${order.total_formatted}</strong>
                                                <br>
                                                <span class="badge bg-secondary">${order.status}</span>
                                            </div>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                    ` : ''}
                </div>
            </div>
        `;
        }
    });
</script>