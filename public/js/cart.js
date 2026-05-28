document.addEventListener('DOMContentLoaded', function () {
    // Charger le panier au chargement
    loadCart();

    // Ajouter au panier
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.dataset.productId;

            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${productId}&quantity=1`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Ajouté!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        updateCartBadge(data.cart_count);
                        loadCart();
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
        });
    });

    // Charger le panier
    function loadCart() {
        fetch('/cart/get')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderCart(data.items, data.total, data.count);
                    updateCartBadge(data.count);
                }
            })
            .catch(error => console.error('Erreur:', error));
    }

    // Afficher le panier
    function renderCart(items, total, count) {
        const cartContainer = document.getElementById('cart-items');

        if (items.length === 0) {
            cartContainer.innerHTML = `
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart fa-3x mb-3 text-muted"></i>
                    <p class="text-muted">Votre panier est vide</p>
                    <a href="/products" class="btn btn-primary">
                        <i class="fas fa-store"></i> Voir les produits
                    </a>
                </div>
            `;
            return;
        }

        let html = '';
        items.forEach(item => {
            html += `
                <div class="cart-item">
                    <div class="d-flex gap-3">
                        <img src="/public/uploads/${item.image}" class="cart-item-image" alt="${item.name}">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">${item.name}</h6>
                            <p class="text-muted small mb-2">${item.category}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="quantity-controls">
                                    <button class="btn btn-sm btn-outline-secondary decrease-qty" data-product-id="${item.id}">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" class="form-control form-control-sm quantity-input" 
                                           value="${item.quantity}" readonly>
                                    <button class="btn btn-sm btn-outline-secondary increase-qty" data-product-id="${item.id}">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <button class="btn btn-sm btn-danger remove-from-cart" data-product-id="${item.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div class="mt-2">
                                <strong class="text-primary">${parseFloat(item.subtotal).toFixed(2)} €</strong>
                                <small class="text-muted">(${parseFloat(item.price).toFixed(2)} € × ${item.quantity})</small>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        html += `
            <div class="mt-4 pt-3 border-top">
                <div class="d-flex justify-content-between mb-3">
                    <h5>Total:</h5>
                    <h4 class="text-primary">${parseFloat(total).toFixed(2)} €</h4>
                </div>
                <div class="d-grid gap-2">
                    <a href="/orders/checkout" class="btn btn-success btn-lg">
                        <i class="fas fa-credit-card"></i> Passer commande
                    </a>
                    <button class="btn btn-outline-danger clear-cart-btn">
                        <i class="fas fa-trash-alt"></i> Vider le panier
                    </button>
                </div>
            </div>
        `;

        cartContainer.innerHTML = html;
        attachCartEvents();
    }

    // Attacher les événements
    function attachCartEvents() {
        // Augmenter quantité
        document.querySelectorAll('.increase-qty').forEach(btn => {
            btn.addEventListener('click', function () {
                const productId = this.dataset.productId;
                const input = this.previousElementSibling;
                const newQty = parseInt(input.value) + 1;
                updateQuantity(productId, newQty);
            });
        });

        // Diminuer quantité
        document.querySelectorAll('.decrease-qty').forEach(btn => {
            btn.addEventListener('click', function () {
                const productId = this.dataset.productId;
                const input = this.nextElementSibling;
                const newQty = Math.max(1, parseInt(input.value) - 1);
                updateQuantity(productId, newQty);
            });
        });

        // Retirer du panier
        document.querySelectorAll('.remove-from-cart').forEach(btn => {
            btn.addEventListener('click', function () {
                const productId = this.dataset.productId;

                Swal.fire({
                    title: 'Retirer cet article ?',
                    text: 'Voulez-vous vraiment retirer cet article ?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Oui, retirer',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        removeFromCart(productId);
                    }
                });
            });
        });

        // Vider le panier
        const clearBtn = document.querySelector('.clear-cart-btn');
        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                Swal.fire({
                    title: 'Vider le panier ?',
                    text: 'Tous les articles seront supprimés',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Oui, vider',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        clearCart();
                    }
                });
            });
        }
    }

    // Mettre à jour la quantité
    function updateQuantity(productId, quantity) {
        fetch('/cart/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `product_id=${productId}&quantity=${quantity}`
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadCart();
                }
            })
            .catch(error => console.error('Erreur:', error));
    }

    // Retirer du panier
    function removeFromCart(productId) {
        fetch('/cart/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `product_id=${productId}`
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Retiré !',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    loadCart();
                }
            })
            .catch(error => console.error('Erreur:', error));
    }

    // Vider le panier
    function clearCart() {
        fetch('/cart/get')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.items.length > 0) {
                    // Créer un tableau de promesses pour supprimer chaque article
                    const promises = data.items.map(item =>
                        fetch('/cart/remove', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: `product_id=${item.id}`
                        })
                    );

                    // Attendre que tous les articles soient supprimés
                    Promise.all(promises).then(() => {
                        loadCart();
                        Swal.fire({
                            icon: 'success',
                            title: 'Panier vidé !',
                            text: 'Tous les articles ont été supprimés',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    });
                }
            })
            .catch(error => console.error('Erreur:', error));
    }

    // Mettre à jour le badge du panier
    function updateCartBadge(count) {
        const badge = document.getElementById('cart-badge');
        if (badge) {
            badge.textContent = count;

            // Animation simple du badge
            if (count > 0) {
                badge.style.transition = 'transform 0.3s ease';
                badge.style.transform = 'scale(1.3)';
                setTimeout(() => {
                    badge.style.transform = 'scale(1)';
                }, 300);
            }
        }
    }
});