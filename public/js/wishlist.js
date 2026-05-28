/**
 * Gestion de la Wishlist (Liste de souhaits)
 */

// Fonction pour basculer un produit dans la wishlist
function toggleWishlist(productId, button) {
    const isInWishlist = button.classList.contains('in-wishlist');
    const action = isInWishlist ? 'remove' : 'add';
    
    fetch(`/wishlist/${action}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'product_id=' + productId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mettre à jour l'apparence du bouton
            if (action === 'add') {
                button.classList.add('in-wishlist');
                button.innerHTML = '<i class="fas fa-heart"></i>';
                button.classList.remove('btn-outline-danger');
                button.classList.add('btn-danger');
            } else {
                button.classList.remove('in-wishlist');
                button.innerHTML = '<i class="far fa-heart"></i>';
                button.classList.remove('btn-danger');
                button.classList.add('btn-outline-danger');
            }
            
            // Animation du cœur
            button.style.transform = 'scale(1.3)';
            setTimeout(() => {
                button.style.transform = 'scale(1)';
            }, 200);
            
            // Mettre à jour le badge
            const wishlistBadge = document.getElementById('wishlist-badge');
            if (wishlistBadge) {
                wishlistBadge.textContent = data.count;
            }
            
            // Toast notification
            const toast = Swal.mixin({
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
            
            toast.fire({
                icon: 'success',
                title: data.message
            });
        } else {
            // Si non connecté, proposer de se connecter
            if (data.message.includes('connecté')) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Connexion requise',
                    text: data.message,
                    showCancelButton: true,
                    confirmButtonText: 'Se connecter',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/login';
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: data.message
                });
            }
        }
    })
    .catch(error => {
        console.error('Erreur wishlist:', error);
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: 'Une erreur est survenue'
        });
    });
}

// Initialiser les boutons wishlist au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // Récupérer les IDs des produits dans la wishlist
    fetch('/wishlist/ids')
        .then(response => response.json())
        .then(data => {
            const wishlistIds = data.ids || [];
            
            // Marquer les produits déjà dans la wishlist
            document.querySelectorAll('.wishlist-btn').forEach(button => {
                const productId = parseInt(button.dataset.productId);
                if (wishlistIds.includes(productId)) {
                    button.classList.add('in-wishlist');
                    button.innerHTML = '<i class="fas fa-heart"></i>';
                    button.classList.remove('btn-outline-danger');
                    button.classList.add('btn-danger');
                }
            });
        })
        .catch(error => console.error('Erreur chargement wishlist:', error));
    
    // Gérer les clics sur les boutons wishlist
    document.addEventListener('click', function(e) {
        const wishlistBtn = e.target.closest('.wishlist-btn');
        if (wishlistBtn) {
            e.preventDefault();
            const productId = wishlistBtn.dataset.productId;
            toggleWishlist(productId, wishlistBtn);
        }
    });
});
