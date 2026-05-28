/**
 * Gestion des codes promo au checkout
 */

let appliedCoupon = null;
let originalAmount = 0;

document.addEventListener('DOMContentLoaded', function() {
    // Récupérer le montant original
    const totalElement = document.getElementById('total_amount');
    if (totalElement) {
        const totalText = totalElement.textContent.replace(/[^\d,]/g, '').replace(',', '.');
        originalAmount = parseFloat(totalText);
    }

    // Appliquer un code promo
    const applyButton = document.getElementById('apply_coupon');
    if (applyButton) {
        applyButton.addEventListener('click', applyCoupon);
    }

    // Retirer un code promo
    const removeButton = document.getElementById('remove_coupon');
    if (removeButton) {
        removeButton.addEventListener('click', removeCoupon);
    }

    // Enter pour appliquer
    const couponInput = document.getElementById('coupon_code');
    if (couponInput) {
        couponInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                applyCoupon();
            }
        });
    }
});

function applyCoupon() {
    const couponInput = document.getElementById('coupon_code');
    const code = couponInput.value.trim().toUpperCase();

    if (!code) {
        showCouponMessage('Veuillez entrer un code promo', 'warning');
        return;
    }

    // Désactiver le bouton pendant la requête
    const applyButton = document.getElementById('apply_coupon');
    applyButton.disabled = true;
    applyButton.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

    fetch('/coupons/validate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'code=' + encodeURIComponent(code) + '&amount=' + originalAmount
    })
    .then(response => response.json())
    .then(data => {
        applyButton.disabled = false;
        applyButton.innerHTML = 'Appliquer';

        if (data.success) {
            appliedCoupon = data.coupon;
            updateTotals(data.totals);
            showCouponMessage(data.message, 'success');
            couponInput.value = '';
            couponInput.disabled = true;
            applyButton.disabled = true;
        } else {
            showCouponMessage(data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        applyButton.disabled = false;
        applyButton.innerHTML = 'Appliquer';
        showCouponMessage('Une erreur est survenue', 'danger');
    });
}

function removeCoupon() {
    appliedCoupon = null;

    // Réinitialiser les totaux
    document.getElementById('subtotal').textContent = formatPrice(originalAmount);
    document.getElementById('total_amount').textContent = formatPrice(originalAmount);

    // Masquer la ligne de réduction
    const discountRow = document.getElementById('discount_row');
    discountRow.style.display = 'none';

    // Réactiver le champ de saisie
    const couponInput = document.getElementById('coupon_code');
    const applyButton = document.getElementById('apply_coupon');
    couponInput.disabled = false;
    applyButton.disabled = false;

    showCouponMessage('Code promo retiré', 'info');
}

function updateTotals(totals) {
    // Afficher la ligne de réduction
    const discountRow = document.getElementById('discount_row');
    discountRow.style.display = 'flex';

    // Mettre à jour le label de réduction
    const discountLabel = document.getElementById('discount_label');
    if (appliedCoupon.type === 'percentage') {
        discountLabel.textContent = appliedCoupon.code + ' -' + appliedCoupon.value + '%';
    } else {
        discountLabel.textContent = appliedCoupon.code;
    }

    // Mettre à jour les montants
    document.getElementById('discount_amount').textContent = '- ' + totals.formatted_discount;
    document.getElementById('total_amount').textContent = totals.formatted_total;
}

function showCouponMessage(message, type) {
    const messageDiv = document.getElementById('coupon_message');
    messageDiv.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show py-2 px-3 mb-0 d-flex align-items-center" role="alert">
            <small class="flex-grow-1">${message}</small>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer" style="font-size: 0.7rem; padding: 0.25rem;"></button>
        </div>
    `;

    // Auto-masquer après 5 secondes
    setTimeout(() => {
        const alert = messageDiv.querySelector('.alert');
        if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 5000);
}

function formatPrice(amount) {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'EUR'
    }).format(amount);
}

// Exporter pour utilisation dans le formulaire de commande
window.getAppliedCoupon = function() {
    return appliedCoupon ? appliedCoupon.code : null;
};

window.getDiscountAmount = function() {
    return appliedCoupon ? appliedCoupon.discount : 0;
};
