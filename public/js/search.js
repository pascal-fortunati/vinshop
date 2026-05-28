/**
 * Système de recherche avec autocomplete
 */

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    const searchForm = document.getElementById('searchForm');
    
    let currentFocus = -1;
    let timeoutId = null;

    // Fonction pour effectuer la recherche
    function performSearch(query) {
        if (query.length < 2) {
            searchResults.classList.add('d-none');
            searchResults.innerHTML = '';
            return;
        }

        // Annuler la recherche précédente si elle existe
        if (timeoutId) {
            clearTimeout(timeoutId);
        }

        // Délai de 300ms avant de lancer la recherche (debounce)
        timeoutId = setTimeout(() => {
            fetch(`/api/products/search?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    displayResults(data.products);
                })
                .catch(error => {
                    console.error('Erreur de recherche:', error);
                });
        }, 300);
    }

    // Fonction pour afficher les résultats
    function displayResults(products) {
        if (products.length === 0) {
            searchResults.innerHTML = `
                <div class="p-3 text-center text-muted">
                    <i class="fas fa-search"></i> Aucun produit trouvé
                </div>
            `;
            searchResults.classList.remove('d-none');
            return;
        }

        const html = products.map((product, index) => `
            <a href="${product.url}" 
               class="search-result-item d-flex align-items-center p-2 text-decoration-none text-dark border-bottom"
               data-index="${index}">
                <img src="/public/uploads/${product.image}" 
                     alt="${product.name}" 
                     class="rounded me-3"
                     style="width: 50px; height: 50px; object-fit: cover;">
                <div class="flex-grow-1">
                    <div class="fw-bold">${product.name}</div>
                    <small class="text-muted">
                        <i class="fas fa-tag"></i> ${product.category}
                    </small>
                </div>
                <div class="text-end">
                    <span class="badge bg-primary">${parseFloat(product.price).toFixed(2)} €</span>
                </div>
            </a>
        `).join('');

        searchResults.innerHTML = html + `
            <div class="p-2 text-center bg-light">
                <small>
                    <a href="/products/search?q=${encodeURIComponent(searchInput.value)}" class="text-decoration-none">
                        Voir tous les résultats <i class="fas fa-arrow-right"></i>
                    </a>
                </small>
            </div>
        `;
        
        searchResults.classList.remove('d-none');
        currentFocus = -1;
    }

    // Événement sur l'input de recherche
    searchInput.addEventListener('input', function(e) {
        performSearch(e.target.value.trim());
    });

    // Gestion du focus et blur
    searchInput.addEventListener('focus', function() {
        if (searchInput.value.trim().length >= 2) {
            performSearch(searchInput.value.trim());
        }
    });

    // Cacher les résultats quand on clique ailleurs
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.add('d-none');
        }
    });

    // Navigation au clavier
    searchInput.addEventListener('keydown', function(e) {
        const items = searchResults.querySelectorAll('.search-result-item');
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            currentFocus++;
            addActive(items);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            currentFocus--;
            addActive(items);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (currentFocus > -1 && items[currentFocus]) {
                items[currentFocus].click();
            } else {
                searchForm.submit();
            }
        } else if (e.key === 'Escape') {
            searchResults.classList.add('d-none');
            currentFocus = -1;
        }
    });

    // Fonction pour ajouter la classe active
    function addActive(items) {
        if (!items || items.length === 0) return;
        
        removeActive(items);
        
        if (currentFocus >= items.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = items.length - 1;
        
        items[currentFocus].classList.add('active');
    }

    // Fonction pour retirer la classe active
    function removeActive(items) {
        items.forEach(item => item.classList.remove('active'));
    }

    // Hover sur les résultats
    searchResults.addEventListener('mouseover', function(e) {
        const item = e.target.closest('.search-result-item');
        if (item) {
            const items = searchResults.querySelectorAll('.search-result-item');
            removeActive(items);
            item.classList.add('active');
            currentFocus = parseInt(item.dataset.index);
        }
    });
});
