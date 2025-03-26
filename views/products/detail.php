<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Accueil</a></li>
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/product/category/<?php echo strtolower($product['category_name']); ?>"><?php echo $product['category_name']; ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $product['name']; ?></li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-6">
            <div class="product-gallery">
                <div class="main-image mb-3">
                    <!-- Correction pour afficher correctement l'image même sans variantes -->
                    <img src="<?php echo strpos($product['image'] ?? '', 'http') === 0 ? $product['image'] : (BASE_URL . '/' . $product['image']); ?>" 
                         id="main-product-image" 
                         class="img-fluid" 
                         alt="<?php echo $product['name']; ?>"
                         onerror="this.onerror=null; this.src='<?php echo BASE_URL; ?>/assets/images/products/default.jpg';">
                </div>
                
                <?php if (!empty($product['variants'])): ?>
                <div class="variant-thumbnails d-flex">
                    <?php foreach($product['variants'] as $variant): ?>
                    <div class="variant-thumb me-2 mb-2" data-variant-id="<?php echo $variant['id']; ?>">
                        <img src="<?php echo BASE_URL . '/' . $variant['image']; ?>" 
                             class="img-thumbnail" 
                             alt="<?php echo $product['name'] . ' - ' . $variant['color']; ?>"
                             style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                             onclick="changeMainImage('<?php echo BASE_URL . '/' . $variant['image']; ?>', '<?php echo $variant['color']; ?>')">
                        <span class="color-dot" style="background-color: <?php echo $variant['color_code']; ?>"></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-6">
            <div class="product-details">
                <h1 class="mb-2"><?php echo $product['name']; ?></h1>
                <?php if (isset($product['promotion']) && $product['promotion']): ?>
                    <div class="product-badge-detail bg-danger mb-3">-<?= $product['discount'] ?? 20 ?>%</div>
                <?php endif; ?>
                <div class="product-meta mb-3">
                    <span class="product-brand"><?php echo ucfirst($product['brand']); ?></span>
                    <span class="product-rating">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <?php if($i <= $product['rating']): ?>
                                <i class="fas fa-star"></i>
                            <?php elseif($i - 0.5 <= $product['rating']): ?>
                                <i class="fas fa-star-half-alt"></i>
                            <?php else: ?>
                                <i class="far fa-star"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                        <span class="rating-count">(<?php echo rand(15, 100); ?> avis)</span>
                    </span>
                </div>
                
                <div class="product-price mb-4">
                    <?php if (isset($product['promotion']) && $product['promotion']): ?>
                        <span class="current-price text-danger"><?php echo number_format(($product['price'] * (1 - ($product['discount'] ?? 20)/100)), 2, ',', ' '); ?> €</span>
                        <span class="old-price text-muted text-decoration-line-through"><?php echo number_format($product['price'], 2, ',', ' '); ?> €</span>
                    <?php else: ?>
                        <span class="current-price"><?php echo number_format($product['price'], 2, ',', ' '); ?> €</span>
                    <?php endif; ?>
                </div>

                <div class="product-description mb-4">
                    <p><?php echo $product['description'] ?? 'Aucune description disponible pour ce produit.'; ?></p>
                </div>

                <?php if (!empty($product['variants'])): ?>
                <div class="product-variants mb-4">
                    <h5>Couleur: <span id="selected-color"><?php echo $product['variants'][0]['color']; ?></span></h5>
                    <div class="color-options d-flex flex-wrap">
                        <?php foreach($product['variants'] as $index => $variant): ?>
                        <div class="color-option me-2 mb-2 <?php echo $index === 0 ? 'active' : ''; ?>" 
                             data-variant-id="<?php echo $variant['id']; ?>"
                             data-color="<?php echo $variant['color']; ?>"
                             data-image="<?php echo BASE_URL . '/' . $variant['image']; ?>"
                             onclick="selectColor(this)">
                            <span class="color-circle" style="background-color: <?php echo $variant['color_code']; ?>; border: 1px solid #ddd;"></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="product-sizes mb-4">
                    <h5>Taille</h5>
                    <div class="size-options d-flex flex-wrap">
                        <div class="size-option me-2 mb-2 active">S</div>
                        <div class="size-option me-2 mb-2">M</div>
                        <div class="size-option me-2 mb-2">L</div>
                        <div class="size-option me-2 mb-2">XL</div>
                    </div>
                </div>

                <div class="product-actions d-flex align-items-center mb-4">
                    <div class="quantity-selector me-3">
                        <button class="btn btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                        <input type="number" class="form-control d-inline-block mx-2 text-center" style="width: 60px;" value="1" min="1" max="99">
                        <button class="btn btn-outline-secondary quantity-btn" data-action="increase">+</button>
                    </div>
                    <button class="btn btn-primary flex-grow-1 add-to-cart-btn" data-product-id="<?php echo $product['id']; ?>">
                        <i class="fas fa-shopping-cart me-2"></i> Ajouter au panier
                    </button>
                </div>

                <div class="product-additional-info">
                    <div class="product-info-item mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i> En stock
                    </div>
                    <div class="product-info-item mb-2">
                        <i class="fas fa-truck me-2"></i> Livraison gratuite dès 50€
                    </div>
                    <div class="product-info-item">
                        <i class="fas fa-undo-alt me-2"></i> Retours gratuits sous 30 jours
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Produits similaires -->
    <section class="similar-products mt-5">
        <h2 class="section-title mb-4">Vous aimerez aussi</h2>
        <div class="row">
            <?php foreach($similarProducts as $similarProduct): ?>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="product-card">
                    <div class="product-image">
                        <!-- Corriger l'affichage des images -->
                        <img src="<?php echo strpos($similarProduct['image'], 'http') === 0 ? $similarProduct['image'] : (BASE_URL . '/' . $similarProduct['image']); ?>" 
                             alt="<?php echo $similarProduct['name']; ?>" class="img-fluid">
                        <div class="product-overlay">
                            <a href="<?php echo BASE_URL; ?>/product/detail/<?php echo $similarProduct['id']; ?>" class="btn"><i class="fas fa-eye"></i></a>
                            <a href="#" class="btn add-to-cart-btn" data-product-id="<?php echo $similarProduct['id']; ?>"><i class="fas fa-shopping-cart"></i></a>
                            <a href="#" class="btn"><i class="fas fa-heart"></i></a>
                        </div>
                    </div>
                    <div class="product-info p-3">
                        <p class="product-category"><?php echo ucfirst($similarProduct['brand']); ?></p>
                        <h5 class="product-title"><?php echo $similarProduct['name']; ?></h5>
                        <p class="product-price"><?php echo number_format($similarProduct['price'], 2, ',', ' '); ?> €</p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<script>
// Fonction pour changer l'image principale lors du clic sur une vignette
function changeMainImage(imageSrc, colorName) {
    document.getElementById('main-product-image').src = imageSrc;
    document.getElementById('selected-color').textContent = colorName;
}

// Fonction pour sélectionner une couleur
function selectColor(element) {
    // Supprimer la classe active de toutes les options
    document.querySelectorAll('.color-option').forEach(option => {
        option.classList.remove('active');
    });
    
    // Ajouter la classe active à l'option sélectionnée
    element.classList.add('active');
    
    // Mettre à jour l'image principale et le nom de la couleur
    const image = element.getAttribute('data-image');
    const color = element.getAttribute('data-color');
    
    changeMainImage(image, color);
}

// Gestion de la quantité
document.addEventListener('DOMContentLoaded', function() {
    const quantityBtns = document.querySelectorAll('.quantity-btn');
    const quantityInput = document.querySelector('.quantity-selector input');
    
    quantityBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const action = this.getAttribute('data-action');
            let currentValue = parseInt(quantityInput.value);
            
            if (action === 'increase') {
                if (currentValue < 99) {
                    quantityInput.value = currentValue + 1;
                }
            } else if (action === 'decrease') {
                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                }
            }
        });
    });
    
    // Gestion de la sélection de taille
    const sizeOptions = document.querySelectorAll('.size-option');
    sizeOptions.forEach(option => {
        option.addEventListener('click', function() {
            sizeOptions.forEach(o => o.classList.remove('active'));
            this.classList.add('active');
        });
    });
});
</script>

<style>
.product-gallery .main-image {
    border: 1px solid #eee;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.color-options .color-option {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    cursor: pointer;
    position: relative;
    border: 2px solid transparent;
    margin-right: 8px;
}

.color-options .color-option.active {
    border-color: #000;
}

.color-options .color-circle {
    display: block;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    /* Ajout d'une bordure claire pour les couleurs claires comme le jaune */
    box-shadow: 0 0 0 1px rgba(0,0,0,0.1);
}

/* Style spécifique pour le jaune qui peut être difficile à voir */
.color-options .color-circle[style*="FFFF00"] {
    border: 1px solid #ccc;
}

.size-options .size-option {
    min-width: 40px;
    height: 40px;
    border: 1px solid #ddd;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    border-radius: 5px;
}

.size-options .size-option.active {
    background-color: #212529;
    color: white;
    border-color: #212529;
}

.variant-thumbnails .variant-thumb {
    position: relative;
}

.variant-thumbnails .color-dot {
    position: absolute;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    bottom: 5px;
    right: 5px;
    border: 1px solid white;
}

.product-meta {
    color: #6c757d;
    display: flex;
    gap: 15px;
}

.product-rating .fas, .product-rating .far {
    color: gold;
}

.current-price {
    font-size: 1.5rem;
    font-weight: bold;
}

.old-price {
    text-decoration: line-through;
    margin-left: 10px;
}
</style>
