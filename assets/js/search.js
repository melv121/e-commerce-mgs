document.addEventListener('DOMContentLoaded', function() {
    // Données de démonstration des vêtements gaming
    const clothingProducts = [
        { id: 1, name: "T-shirt", price: 29.99, category: "T-shirts", image: "/assets/images/products/tshirt-valorant.jpg" },
        { id: 2, name: "Hoodie", price: 59.99, category: "Sweats", image: "/assets/images/products/hoodie-lol.jpg" },
        { id: 3, name: "Casquette", price: 24.99, category: "Accessoires", image: "/assets/images/products/cap-csgo.jpg" },
        { id: 4, name: "T-shirt", price: 24.99, category: "T-shirts", image: "/assets/images/products/tshirt-minecraft.jpg" },
        { id: 5, name: "Pantalon", price: 39.99, category: "Pantalons", image: "/assets/images/products/pants-gaming.jpg" },
        { id: 6, name: "Bonnet", price: 19.99, category: "Accessoires", image: "/assets/images/products/beanie-fortnite.jpg" }
    ];

    const searchInput = document.querySelector('.search-input');
    const searchResults = document.querySelector('.search-results');

    // Fonction de recherche
    function performSearch(query) {
        if (query.length < 2) {
            searchResults.innerHTML = '<div class="p-3 text-center">Tapez au moins 2 caractères...</div>';
            return;
        }

        const results = clothingProducts.filter(product => 
            product.name.toLowerCase().includes(query.toLowerCase()) ||
            product.category.toLowerCase().includes(query.toLowerCase())
        );

        if (results.length === 0) {
            searchResults.innerHTML = '<div class="p-3 text-center">Aucun vêtement trouvé</div>';
            return;
        }

        searchResults.innerHTML = results.map(product => `
            <div class="search-item">
                <img src="${product.image}" alt="${product.name}" onerror="this.src='/assets/images/products/default.jpg'">
                <div class="search-item-info">
                    <h5>${product.name}</h5>
                    <p class="product-category">${product.category}</p>
                    <p class="product-price">${product.price.toFixed(2)} €</p>
                </div>
            </div>
        `).join('');
    }

    // Event listeners
    searchInput.addEventListener('input', (e) => performSearch(e.target.value));

    // Reset search on modal close
    const searchModal = document.getElementById('searchModal');
    searchModal.addEventListener('hidden.bs.modal', () => {
        searchInput.value = '';
        searchResults.innerHTML = '<div class="p-3 text-center">Commencez à taper pour rechercher...</div>';
    });

    searchModal.addEventListener('shown.bs.modal', () => {
        searchInput.focus();
    });
});
