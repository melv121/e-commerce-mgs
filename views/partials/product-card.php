<div class="col-lg-3 col-md-4 col-sm-6 mb-4">
    <div class="product-card">
        <?php if (isset($product['new']) && $product['new']): ?>
            <span class="product-badge bg-success">Nouveau</span>
        <?php elseif (isset($product['promotion']) && $product['promotion']): ?>
            <span class="product-badge bg-danger">-<?= $product['discount'] ?? 20 ?>%</span>
        <?php endif; ?>
        
        <div class="product-image">
            <img src="<?= strpos($product['image'] ?? '', 'http') === 0 ? $product['image'] : (BASE_URL . '/' . $product['image']) ?>" 
                 alt="<?= $product['name'] ?? 'Product' ?>" 
                 onerror="this.onerror=null; this.src='<?= BASE_URL ?>/assets/images/products/default.jpg';">
            <div class="product-overlay">
                <a href="<?= BASE_URL ?>/product/detail/<?= $product['id'] ?>" class="btn" data-bs-toggle="tooltip" title="Vue rapide">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="javascript:void(0);" class="btn add-to-cart-btn" data-product-id="<?= $product['id'] ?>" data-bs-toggle="tooltip" title="Ajouter au panier">
                    <i class="fas fa-shopping-cart"></i>
                </a>
                <a href="#" class="btn" data-bs-toggle="tooltip" title="Ajouter aux favoris">
                    <i class="fas fa-heart"></i>
                </a>
            </div>
        </div>
        
        <div class="product-info">
            <span class="product-category"><?= isset($product['category_name']) ? htmlspecialchars($product['category_name']) : 'Catégorie' ?></span>
            <h3 class="product-title"><?= htmlspecialchars($product['name'] ?? 'Nom du produit') ?></h3>
            <div class="product-rating">
                <?php for($i = 0; $i < 5; $i++): ?>
                    <i class="fas fa-star<?= ($i < ($product['rating'] ?? 0)) ? '' : '-o' ?>"></i>
                <?php endfor; ?>
            </div>
            <?php if (isset($product['promotion']) && $product['promotion']): ?>
                <p class="product-price">
                    <span class="text-decoration-line-through text-muted me-2">
                        <?= number_format($product['price'] ?? 0, 2, ',', ' ') ?> €
                    </span>
                    <span class="text-danger">
                        <?php 
                        // Calculer le prix en promotion
                        $salePrice = isset($product['sale_price']) 
                            ? $product['sale_price'] 
                            : ($product['price'] * (1 - ($product['discount'] ?? 20)/100));
                        echo number_format($salePrice, 2, ',', ' '); 
                        ?> €
                    </span>
                </p>
            <?php else: ?>
                <p class="product-price"><?= number_format($product['price'] ?? 0, 2, ',', ' ') ?> €</p>
            <?php endif; ?>
        </div>
    </div>
</div>
