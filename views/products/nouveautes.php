<div class="nouveautes-hero py-5 mb-5" style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('<?php echo BASE_URL; ?>/assets/images/nouveautes-bg.jpg') center/cover;">
    <div class="container text-center text-white">
        <h1 class="display-4 fw-bold mb-4">Nouveautés</h1>
        <p class="lead mb-0">Découvrez nos dernières collections</p>
    </div>
</div>

<div class="container py-5">
    <h1 class="mb-4">Nouveautés</h1>
    
    <div class="row g-4">
        <?php foreach ($products as $product): ?>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="product-card animate-slide-up">
                <div class="product-badge bg-success">Nouveau</div>
                <div class="product-image">
                    <!-- Corriger l'affichage des images -->
                    <img src="<?= strpos($product['image'], 'http') === 0 ? $product['image'] : (BASE_URL . '/' . $product['image']) ?>" alt="<?= $product['name'] ?>">
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
                    <span class="product-category"><?= $product['category_name'] ?? 'Catégorie' ?></span>
                    <h3 class="product-title"><?= $product['name'] ?></h3>
                    <div class="product-rating">
                        <?php 
                        $rating = $product['rating'] ?? 4;
                        for($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-<?= ($i <= $rating) ? 'star' : (($i - 0.5 <= $rating) ? 'star-half-alt' : 'star') ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <p class="product-price"><?= number_format($product['price'], 2, ',', ' ') ?> €</p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
