<div class="container py-5">
    <div class="row">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Accueil</a></li>
                <?php if(isset($product['category_name'])): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo BASE_URL; ?>/product/category/<?php echo isset($product['category_slug']) ? htmlspecialchars($product['category_slug']) : strtolower(preg_replace('/[^a-zA-Z0-9]/', '-', $product['category_name'])); ?>">
                        <?php echo ucfirst(htmlspecialchars($product['category_name'])); ?>
                    </a>
                </li>
                <?php endif; ?>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($product['name']); ?></li>
            </ol>
        </nav>
        
        <!-- Détail du produit -->
        <div class="col-md-6">
            <div class="product-image-gallery">
                <div class="product-main-image mb-3">
                    <img 
                        src="<?php echo BASE_URL . '/' . htmlspecialchars($product['image']); ?>" 
                        alt="<?php echo htmlspecialchars($product['name']); ?>" 
                        class="img-fluid rounded"
                        id="mainProductImage"
                    >
                    <?php if (isset($product['discount']) && $product['discount'] > 0): ?>
                        <div class="product-badge-detail bg-danger text-white">-<?php echo $product['discount']; ?>%</div>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($product['gallery'])): ?>
                <div class="product-thumbnails row">
                    <?php foreach ($product['gallery'] as $image): ?>
                    <div class="col-3">
                        <img 
                            src="<?php echo BASE_URL . '/' . htmlspecialchars($image['thumbnail']); ?>" 
                            alt="<?php echo htmlspecialchars($product['name']); ?> - Vue" 
                            class="img-fluid rounded thumbnail-image"
                            data-full="<?php echo BASE_URL . '/' . htmlspecialchars($image['full']); ?>"
                        >
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="col-md-6">
            <h1 class="product-title mb-3"><?php echo htmlspecialchars($product['name']); ?></h1>
            
            <div class="product-price mb-4">
                <?php if (isset($product['discount']) && $product['discount'] > 0): ?>
                    <span class="text-muted text-decoration-line-through">
                        <?php echo number_format($product['price'], 2, ',', ' '); ?> €
                    </span>
                    <span class="text-danger fs-3 ms-2">
                        <?php echo number_format($product['price'] * (1 - $product['discount'] / 100), 2, ',', ' '); ?> €
                    </span>
                <?php else: ?>
                    <span class="fs-3"><?php echo number_format($product['price'], 2, ',', ' '); ?> €</span>
                <?php endif; ?>
            </div>
            
            <div class="product-description mb-4">
                <?php echo nl2br(htmlspecialchars($product['description'])); ?>
            </div>
            
            <div class="product-stock mb-4">
                <?php if ($product['stock'] > 10): ?>
                    <span class="badge bg-success">En stock</span>
                <?php elseif ($product['stock'] > 0): ?>
                    <span class="badge bg-warning text-dark">Plus que <?php echo $product['stock']; ?> en stock</span>
                <?php else: ?>
                    <span class="badge bg-danger">Rupture de stock</span>
                <?php endif; ?>
            </div>
            
            <!-- Variantes de couleur -->
            <?php if (!empty($product['variants']) && isset($product['has_variants']) && $product['has_variants']): ?>
            <div class="product-variants mb-4">
                <h6>Couleurs disponibles:</h6>
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <?php foreach ($product['variants'] as $index => $variant): 
                        // Déterminer si la couleur est claire pour appliquer une classe spéciale
                        $isLightColor = false;
                        if (preg_match('/#([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})/i', $variant['color'], $matches)) {
                            $r = hexdec($matches[1]);
                            $g = hexdec($matches[2]);
                            $b = hexdec($matches[3]);
                            $isLightColor = ($r + $g + $b > 550); // Seuil arbitraire pour déterminer si la couleur est claire
                        }
                    ?>
                        <div class="product-color-variant <?php echo $isLightColor ? 'light-color' : ''; ?> <?php echo $index === 0 ? 'selected' : ''; ?>" 
                             data-variant-id="<?php echo htmlspecialchars($variant['id']); ?>"
                             data-image="<?php echo BASE_URL . '/' . htmlspecialchars($variant['image']); ?>"
                             data-color="<?php echo htmlspecialchars($variant['color']); ?>"
                             data-color-name="<?php echo htmlspecialchars($variant['color_name']); ?>"
                             style="background-color: <?php echo htmlspecialchars($variant['color']); ?>;"
                             title="<?php echo htmlspecialchars($variant['color_name']); ?>">
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Bouton de sélection de couleur -->
                <div class="color-selection-dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" id="colorDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="color-preview-dot" style="background-color: <?php echo htmlspecialchars($product['variants'][0]['color']); ?>"></span>
                        <span id="dropdownColorName"><?php echo htmlspecialchars($product['variants'][0]['color_name']); ?></span>
                    </button>
                    <ul class="dropdown-menu w-100" aria-labelledby="colorDropdown">
                        <?php foreach ($product['variants'] as $variant): ?>
                            <li>
                                <a class="dropdown-item color-dropdown-item" href="#" 
                                   data-variant-id="<?php echo htmlspecialchars($variant['id']); ?>"
                                   data-image="<?php echo BASE_URL . '/' . htmlspecialchars($variant['image']); ?>"
                                   data-color="<?php echo htmlspecialchars($variant['color']); ?>"
                                   data-color-name="<?php echo htmlspecialchars($variant['color_name']); ?>">
                                   <span class="color-preview-dot" style="background-color: <?php echo htmlspecialchars($variant['color']); ?>"></span>
                                   <?php echo htmlspecialchars($variant['color_name']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            
            <div id="selected-color-info" class="mb-4">
                <p>Couleur sélectionnée: <span id="selected-color-name"><?php echo !empty($product['variants']) ? htmlspecialchars($product['variants'][0]['color_name']) : '-'; ?></span></p>
            </div>
            <?php endif; ?>
            
            <?php if ($product['stock'] > 0): ?>
                <!-- Formulaire d'ajout au panier -->
                <form action="<?php echo BASE_URL; ?>/cart/add/<?php echo $product['id']; ?>" method="post" class="add-to-cart-form">
                    <input type="hidden" name="variant_id" id="variant_id" value="<?php echo !empty($product['variants']) ? htmlspecialchars($product['variants'][0]['id']) : ''; ?>">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="quantity" class="form-label">Quantité</label>
                            <select class="form-select" id="quantity" name="quantity">
                                <?php for ($i = 1; $i <= min(10, $product['stock']); $i++): ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg me-2">
                        <i class="fas fa-shopping-cart me-2"></i> Ajouter au panier
                    </button>
                </form>
            <?php else: ?>
                <p class="alert alert-warning">Ce produit est actuellement en rupture de stock.</p>
            <?php endif; ?>
            
            <!-- Informations supplémentaires sur le produit -->
            <div class="product-meta mt-4">
                <div class="mb-2">
                    <strong>Catégorie:</strong> 
                    <?php if(isset($product['category_name'])): ?>
                        <a href="<?php echo BASE_URL; ?>/product/category/<?php echo isset($product['category_slug']) ? htmlspecialchars($product['category_slug']) : strtolower(preg_replace('/[^a-zA-Z0-9]/', '-', $product['category_name'])); ?>">
                            <?php echo ucfirst(htmlspecialchars($product['category_name'])); ?>
                        </a>
                    <?php else: ?>
                        Non catégorisé
                    <?php endif; ?>
                </div>
                <?php if(isset($product['sku'])): ?>
                <div class="mb-2">
                    <strong>Référence:</strong> <?php echo htmlspecialchars($product['sku']); ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Produits similaires -->
    <?php if (!empty($similarProducts)): ?>
    <div class="similar-products mt-5">
        <h3 class="mb-4">Produits similaires</h3>
        <div class="row">
            <?php foreach ($similarProducts as $similarProduct): ?>
                <div class="col-md-3 col-6 mb-4">
                    <div class="card h-100">
                        <a href="<?php echo BASE_URL; ?>/product/detail/<?php echo $similarProduct['id']; ?>">
                            <img src="<?php echo BASE_URL . '/' . htmlspecialchars($similarProduct['image']); ?>" 
                                class="card-img-top" 
                                alt="<?php echo htmlspecialchars($similarProduct['name']); ?>">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="<?php echo BASE_URL; ?>/product/detail/<?php echo $similarProduct['id']; ?>" class="text-decoration-none text-dark">
                                    <?php echo htmlspecialchars($similarProduct['name']); ?>
                                </a>
                            </h5>
                            <p class="card-text">
                                <?php if (isset($similarProduct['discount']) && $similarProduct['discount'] > 0): ?>
                                    <span class="text-muted text-decoration-line-through">
                                        <?php echo number_format($similarProduct['price'], 2, ',', ' '); ?> €
                                    </span>
                                    <span class="text-danger">
                                        <?php echo number_format($similarProduct['price'] * (1 - $similarProduct['discount'] / 100), 2, ',', ' '); ?> €
                                    </span>
                                <?php else: ?>
                                    <span><?php echo number_format($similarProduct['price'], 2, ',', ' '); ?> €</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <a href="<?php echo BASE_URL; ?>/product/detail/<?php echo $similarProduct['id']; ?>" class="btn btn-sm btn-outline-primary w-100">Voir le produit</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chargement du script de gestion des variantes de produit
    if (document.querySelector('.product-color-variant')) {
        const script = document.createElement('script');
        script.src = '<?php echo BASE_URL; ?>/assets/js/product-variants.js';
        document.body.appendChild(script);
    }
    
    // Gestion des images miniatures de la galerie
    const thumbnails = document.querySelectorAll('.thumbnail-image');
    const mainImage = document.getElementById('mainProductImage');
    
    thumbnails.forEach(function(thumbnail) {
        thumbnail.addEventListener('click', function() {
            const fullImageUrl = this.getAttribute('data-full');
            if (fullImageUrl && mainImage) {
                mainImage.src = fullImageUrl;
            }
        });
    });
});
</script>
