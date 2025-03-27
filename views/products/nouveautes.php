<div class="nouveautes-hero py-5 mb-5" style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('<?php echo BASE_URL; ?>/assets/images/nouveautes-bg.jpg') center/cover;">
    <div class="container text-center text-white">
        <h1 class="display-4 fw-bold mb-4">Nouveautés</h1>
        <p class="lead mb-0">Découvrez nos dernières collections</p>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <div class="col-12 mb-4">
            <h1 class="display-5 fw-bold">Nouvelles Collections</h1>
            <p class="lead">Découvrez nos derniers articles et tendances du moment.</p>
            
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Accueil</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Nouvelles Collections</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <?php if (!empty($products)): ?>
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-md-3 col-6 mb-4">
                    <div class="card h-100 product-card">
                        <div class="product-image">
                            <a href="<?php echo BASE_URL; ?>/product/detail/<?php echo $product['id']; ?>">
                                <img src="<?php echo BASE_URL . '/' . $product['image']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            </a>
                            <?php if (isset($product['discount']) && $product['discount'] > 0): ?>
                                <div class="product-badge bg-danger text-white">-<?php echo $product['discount']; ?>%</div>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="<?php echo BASE_URL; ?>/product/detail/<?php echo $product['id']; ?>" class="text-decoration-none text-dark">
                                    <?php echo htmlspecialchars($product['name']); ?>
                                </a>
                            </h5>
                            <p class="card-text text-muted small"><?php echo htmlspecialchars($product['category_name'] ?? 'Non catégorisé'); ?></p>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <?php if (isset($product['discount']) && $product['discount'] > 0): ?>
                                    <div>
                                        <span class="text-muted text-decoration-line-through"><?php echo number_format($product['price'], 2, ',', ' '); ?> €</span>
                                        <span class="text-danger fw-bold"><?php echo number_format($product['price'] * (1 - $product['discount'] / 100), 2, ',', ' '); ?> €</span>
                                    </div>
                                <?php else: ?>
                                    <span class="fw-bold"><?php echo number_format($product['price'], 2, ',', ' '); ?> €</span>
                                <?php endif; ?>
                                
                                <?php if ($product['stock'] > 0): ?>
                                    <span class="badge bg-success">En stock</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Rupture</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <div class="d-grid">
                                <a href="<?php echo BASE_URL; ?>/product/detail/<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-primary">Voir le produit</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> Aucun nouveau produit disponible pour le moment.
        </div>
    <?php endif; ?>
</div>
