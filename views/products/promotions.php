<div class="promotions-hero py-5 mb-5" style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('<?php echo BASE_URL; ?>/assets/images/promotions-bg.jpg') center/cover;">
    <div class="container text-center text-white">
        <h1 class="display-4 fw-bold mb-4">Promotions</h1>
        <p class="lead mb-0">Découvrez nos meilleures offres</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <?php foreach ($products as $product): ?>
            <?php 
            // Add promotion badge to product array
            $product['promotion'] = true;
            $product['discount'] = $product['discount'] ?? 20; // Default 20% discount if not specified
            ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="product-card animate-slide-up">
                    <div class="product-badge bg-danger">-<?= $product['discount'] ?>%</div>
                    <div class="product-image">
                        <!-- Correction pour afficher correctement les images -->
                        <img src="<?= strpos($product['image'], 'http') === 0 ? $product['image'] : (BASE_URL . '/' . $product['image']) ?>" 
                             alt="<?= $product['name'] ?? 'Product' ?>">
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
                        <span class="product-category"><?= $product['category'] ?? $product['category_name'] ?? 'Catégorie' ?></span>
                        <h3 class="product-title"><?= $product['name'] ?? 'Nom du produit' ?></h3>
                        <div class="product-rating">
                            <?php for($i = 0; $i < 5; $i++): ?>
                                <i class="fas fa-star<?= ($i < ($product['rating'] ?? 0)) ? '' : '-o' ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <p class="product-price">
                            <span class="text-decoration-line-through text-muted me-2">
                                <?= number_format($product['price'] ?? 0, 2, ',', ' ') ?> €
                            </span>
                            <span class="text-danger">
                                <?= number_format(($product['price'] * (1 - $product['discount']/100)), 2, ',', ' ') ?> €
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <h2 class="fw-bold mt-5 mb-4">Articles en promotion</h2>
    <div class="row g-4">
        <?php 
        require_once __DIR__ . '/../../config/database.php';
        
        try {
            // Use the database connection from the controller if it exists
            if (isset($db) && $db instanceof PDO) {
                // Use existing connection
            } else {
                // Direct database connection with specific credentials
                $host = "localhost";      // Usually localhost for XAMPP
                $dbname = "mgs_store";    // Your database name
                $username = "root";       // Default XAMPP username
                $password = "";           // Default XAMPP password is empty
                
                $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            
            // Modified query without the non-existent 'type' column
            $query = "SELECT * FROM products WHERE 
                      name LIKE '%shirt%' OR 
                      name LIKE '%pantalon%' OR 
                      name LIKE '%pull%' OR 
                      name LIKE '%t-shirt%' OR 
                      name LIKE '%hoodie%' 
                      LIMIT 8";
            
            $stmt = $db->prepare($query);
            $stmt->execute();
            $clothingProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($clothingProducts)) {
                // Fallback query - just get any products
                $query = "SELECT * FROM products LIMIT 8";
                $stmt = $db->prepare($query);
                $stmt->execute();
                $clothingProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            
            foreach ($clothingProducts as $product): 
                // For display purposes, set a default discount
                $product['promotion'] = true;
                $product['discount'] = $product['discount'] ?? 20; // Default discount if column doesn't exist
                ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="product-card animate-slide-up">
                        <div class="product-badge bg-danger">-<?= $product['discount'] ?>%</div>
                        <div class="product-image">
                            <!-- Correction pour afficher correctement les images -->
                            <img src="<?= strpos($product['image'], 'http') === 0 ? $product['image'] : (BASE_URL . '/' . $product['image']) ?>" 
                                 alt="<?= $product['name'] ?? 'Product' ?>">
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
                            <span class="product-category"><?= $product['category'] ?? $product['category_name'] ?? 'Catégorie' ?></span>
                            <h3 class="product-title"><?= $product['name'] ?? 'Nom du produit' ?></h3>
                            <div class="product-rating">
                                <?php for($i = 0; $i < 5; $i++): ?>
                                    <i class="fas fa-star<?= ($i < ($product['rating'] ?? 0)) ? '' : '-o' ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <p class="product-price">
                                <span class="text-decoration-line-through text-muted me-2">
                                    <?= number_format($product['price'] ?? 0, 2, ',', ' ') ?> €
                                </span>
                                <span class="text-danger">
                                    <?= number_format(($product['price'] * (1 - $product['discount']/100)), 2, ',', ' ') ?> €
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach;
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Erreur lors de la récupération des produits en promotion: ' . $e->getMessage() . '</div>';
        }
        ?>
    </div>
</div>
