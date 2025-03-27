<?php 
    $productKey = isset($similarProduct) ? 'similarProduct' : 'product';
    $currentProduct = $$productKey;
    
    $displayPrice = $currentProduct['price'];
    if (!empty($currentProduct['discount'])) {
        $displayPrice = $displayPrice * (1 - $currentProduct['discount'] / 100);
    }
?>
<div class="col-lg-3 col-md-4 col-sm-6 mb-4">
    <div class="card product-card h-100">
        <div class="product-image">
            <a href="<?php echo BASE_URL; ?>/product/detail/<?php echo $currentProduct['id']; ?>">
                <img src="<?php echo BASE_URL . '/' . $currentProduct['image']; ?>" alt="<?php echo htmlspecialchars($currentProduct['name']); ?>" class="img-fluid">
            </a>
            <?php if (!empty($currentProduct['discount'])): ?>
                <div class="product-badge bg-danger">-<?php echo $currentProduct['discount']; ?>%</div>
            <?php endif; ?>
            <div class="product-actions">
                <form action="<?php echo BASE_URL; ?>/cart/add/<?php echo $currentProduct['id']; ?>" method="post" class="add-to-cart-form" data-product-id="<?php echo $currentProduct['id']; ?>">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn-add-to-cart" data-bs-toggle="tooltip" title="Ajouter au panier">
                        <i class="fas fa-shopping-cart"></i>
                    </button>
                </form>
                <button class="btn-product-action" data-bs-toggle="tooltip" title="Ajouter aux favoris">
                    <i class="far fa-heart"></i>
                </button>
                <a href="<?php echo BASE_URL; ?>/product/detail/<?php echo $currentProduct['id']; ?>" class="btn-product-action" data-bs-toggle="tooltip" title="Voir les détails">
                    <i class="fas fa-eye"></i>
                </a>
            </div>
        </div>
        <div class="card-body">
            <h5 class="card-title">
                <a href="<?php echo BASE_URL; ?>/product/detail/<?php echo $currentProduct['id']; ?>" class="text-decoration-none text-dark">
                    <?php echo htmlspecialchars($currentProduct['name']); ?>
                </a>
            </h5>
            
            <!-- Suppression des variantes de couleur ici -->
            
            <div class="d-flex justify-content-between align-items-center mt-2">
                <?php if (isset($currentProduct['discount']) && $currentProduct['discount'] > 0): ?>
                    <div>
                        <span class="text-muted text-decoration-line-through"><?php echo number_format($currentProduct['price'], 2, ',', ' '); ?> €</span>
                        <span class="text-danger"><?php echo number_format($currentProduct['price'] * (1 - $currentProduct['discount'] / 100), 2, ',', ' '); ?> €</span>
                    </div>
                <?php else: ?>
                    <span><?php echo number_format($currentProduct['price'], 2, ',', ' '); ?> €</span>
                <?php endif; ?>
                
                <?php if ($currentProduct['stock'] > 0): ?>
                    <span class="badge bg-success">En stock</span>
                <?php else: ?>
                    <span class="badge bg-danger">Rupture</span>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-footer bg-white border-top-0">
            <a href="<?php echo BASE_URL; ?>/product/detail/<?php echo $currentProduct['id']; ?>" class="btn btn-sm btn-outline-primary w-100">Voir le produit</a>
        </div>
    </div>
</div>
