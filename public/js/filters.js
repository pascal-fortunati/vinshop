// Filtres avancés pour les produits
document.addEventListener('DOMContentLoaded', function () {
    const priceSliderContainer = document.getElementById('priceSlider');

    if (priceSliderContainer) {
        // Récupérer les valeurs min/max depuis les inputs
        const minPriceInput = document.getElementById('minPriceInput');
        const maxPriceInput = document.getElementById('maxPriceInput');
        const minPriceLabel = document.getElementById('minPriceLabel');
        const maxPriceLabel = document.getElementById('maxPriceLabel');

        // Valeurs de la plage globale (absolue)
        const absoluteMin = typeof priceRange !== 'undefined' ? parseFloat(priceRange.min) : 0;
        const absoluteMax = typeof priceRange !== 'undefined' ? parseFloat(priceRange.max) : 1000;
        
        // Valeurs actuellement sélectionnées (depuis les filtres)
        const currentMin = typeof priceRange !== 'undefined' && priceRange.currentMin !== undefined 
            ? parseFloat(priceRange.currentMin) 
            : parseFloat(minPriceInput.value) || absoluteMin;
        const currentMax = typeof priceRange !== 'undefined' && priceRange.currentMax !== undefined 
            ? parseFloat(priceRange.currentMax) 
            : parseFloat(maxPriceInput.value) || absoluteMax;

        console.log('Initialisation du slider:', {
            absoluteMin, absoluteMax, currentMin, currentMax
        });

        // Créer le slider avec noUiSlider
        if (typeof noUiSlider !== 'undefined') {
            noUiSlider.create(priceSliderContainer, {
                start: [currentMin, currentMax],
                connect: true,
                step: 1,
                tooltips: [
                    {
                        to: function(value) {
                            return formatPrice(value);
                        }
                    },
                    {
                        to: function(value) {
                            return formatPrice(value);
                        }
                    }
                ],
                range: {
                    'min': absoluteMin,
                    'max': absoluteMax
                },
                format: {
                    to: function (value) {
                        return Math.round(value);
                    },
                    from: function (value) {
                        return Number(value);
                    }
                }
            });

            // Mettre à jour les labels et inputs lors du changement
            priceSliderContainer.noUiSlider.on('update', function (values, handle) {
                const value = values[handle];
                if (handle === 0) {
                    minPriceInput.value = value;
                    minPriceLabel.textContent = formatPrice(value);
                } else {
                    maxPriceInput.value = value;
                    maxPriceLabel.textContent = formatPrice(value);
                }
            });

            // Appliquer automatiquement les filtres lors du relâchement du slider
            priceSliderContainer.noUiSlider.on('change', function () {
                // Optionnel: soumettre automatiquement le formulaire
                // document.getElementById('filterForm').submit();
            });
        } else {
            console.warn('noUiSlider non chargé. Utilisation d\'inputs de type range.');
            // Fallback: utiliser des inputs range HTML5
            createFallbackSlider();
        }
    }

    // Appliquer automatiquement les filtres lors du changement de select
    const filterSelects = document.querySelectorAll('#categoryFilter, #sortFilter, #stockFilter');
    filterSelects.forEach(select => {
        select.addEventListener('change', function () {
            document.getElementById('filterForm').submit();
        });
    });

    // Fonction de formatage du prix
    function formatPrice(price) {
        return new Intl.NumberFormat('fr-FR', {
            style: 'currency',
            currency: 'EUR'
        }).format(price);
    }

    // Fallback si noUiSlider n'est pas disponible
    function createFallbackSlider() {
        const minPriceInput = document.getElementById('minPriceInput');
        const maxPriceInput = document.getElementById('maxPriceInput');
        const minPriceLabel = document.getElementById('minPriceLabel');
        const maxPriceLabel = document.getElementById('maxPriceLabel');

        const container = document.getElementById('priceSlider');
        container.innerHTML = `
            <div class="row">
                <div class="col-6">
                    <label class="form-label small">Prix minimum</label>
                    <input type="range" class="form-range" id="minPriceRange" 
                           min="${minPriceInput.value}" max="${maxPriceInput.value}" 
                           value="${minPriceInput.value}">
                </div>
                <div class="col-6">
                    <label class="form-label small">Prix maximum</label>
                    <input type="range" class="form-range" id="maxPriceRange" 
                           min="${minPriceInput.value}" max="${maxPriceInput.value}" 
                           value="${maxPriceInput.value}">
                </div>
            </div>
        `;

        const minRange = document.getElementById('minPriceRange');
        const maxRange = document.getElementById('maxPriceRange');

        minRange.addEventListener('input', function () {
            minPriceInput.value = this.value;
            minPriceLabel.textContent = formatPrice(this.value);
        });

        maxRange.addEventListener('input', function () {
            maxPriceInput.value = this.value;
            maxPriceLabel.textContent = formatPrice(this.value);
        });
    }

    // Animation de chargement lors de la soumission du formulaire
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        filterForm.addEventListener('submit', function () {
            const productsContainer = document.getElementById('productsContainer');
            if (productsContainer) {
                productsContainer.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                        <p class="mt-3 text-muted">Application des filtres...</p>
                    </div>
                `;
            }
        });
    }
});
