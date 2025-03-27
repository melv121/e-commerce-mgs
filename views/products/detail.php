<div class="container py-5">
    <div class="row">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Accueil</a></li>
                <?php if(isset($product['category_slug']) && isset($product['category_name'])): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo BASE_URL; ?>/product/category/<?php echo htmlspecialchars($product['category_slug']); ?>">
                        <?php echo ucfirst(htmlspecialchars($product['category_name'])); ?>
                    </a>
                </li>
                <?php endif; ?>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($product['name']); ?></li>
            </ol>
        </nav>
        
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error_message']; ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success_message']; ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
        
        <!-- Détail du produit -->
        <div class="col-md-6">
            <div class="product-image-gallery">
                <div class="product-main-image mb-3">
                    <img 
                        src="<?php echo BASE_URL; ?>/<?php echo $product['image']; ?>" 
                        alt="<?php echo $product['name']; ?>" 
                        class="img-fluid rounded"
                    >
                    <?php if ($product['discount']): ?>
                        <div class="product-badge-detail bg-danger text-white">-<?php echo $product['discount']; ?>%</div>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($product['gallery'])): ?>
                <div class="product-thumbnails row">
                    <?php foreach ($product['gallery'] as $image): ?>
                    <div class="col-3">
                        <img 
                            src="<?php echo BASE_URL; ?>/<?php echo $image['thumbnail']; ?>" 
                            alt="<?php echo $product['name']; ?> - Vue <?php echo $image['position']; ?>"
                            class="img-fluid rounded thumbnail-image"
                            data-full="<?php echo BASE_URL; ?>/<?php echo $image['full']; ?>"
                        >
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="col-md-6">
            <h1 class="product-title mb-3"><?php echo $product['name']; ?></h1>
            
            <div class="product-price mb-4">
                <?php if ($product['discount']): ?>
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
                <?php echo nl2br($product['description']); ?>
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
            
            <?php if ($product['stock'] > 0): ?>
                <!-- Formulaire d'ajout au panier -->
                <form action="<?php echo BASE_URL; ?>/cart/add/<?php echo $product['id']; ?>" method="post" class="add-to-cart-form" data-product-id="<?php echo $product['id']; ?>">
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
            <?php endif; ?>
            
            <div class="product-additional-info mt-5">
                <div class="row">
                    <div class="col-md-4 product-info-item">
                        <i class="fas fa-truck animated-icon"></i>
                        <p>Livraison gratuite<br>à partir de 50€</p>
                    </div>
                    <div class="col-md-4 product-info-item">
                        <i class="fas fa-exchange-alt animated-icon"></i>
                        <p>Retours gratuits<br>sous 30 jours</p>
                    </div>
                    <div class="col-md-4 product-info-item">
                        <i class="fas fa-shield-alt animated-icon"></i>
                        <p>Garantie<br>2 ans</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Produits similaires -->
    <?php if (!empty($similarProducts)): ?>
    <div class="row mt-5">
        <div class="col-12">
            <h2 class="section-title mb-4">Produits similaires</h2>
        </div>
        
        <?php foreach ($similarProducts as $similarProduct): ?>
            <div class="col-md-3">
                <?php include 'views/partials/product-card.php'; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Galerie d'images
    const thumbnails = document.querySelectorAll('.thumbnail-image');
    const mainImage = document.querySelector('.product-main-image img');
    
    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
            const fullImageUrl = this.getAttribute('data-full');
            mainImage.src = fullImageUrl;
        });
    });
});
</script>
