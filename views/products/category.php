<section class="category-header py-5">
    <div class="container">
        <h1 class="text-center mb-4"><?php echo $pageTitle; ?></h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Accueil</a></li>
                <?php if($category): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo BASE_URL; ?>/product/category/<?php echo is_array($category) ? htmlspecialchars(isset($category[0]) ? $category[0] : '') : htmlspecialchars($category); ?>">
                        <?php 
                        if (is_array($category)) {
                            echo ucfirst(isset($category[0]) ? $category[0] : 'Catégorie');
                        } else {
                            echo ucfirst($category);
                        }
                        ?>
                    </a>
                </li>
                <?php endif; ?>
                <?php if($subcategory): ?>
                <li class="breadcrumb-item active" aria-current="page">
                    <?php 
                    if (is_array($subcategory)) {
                        echo ucfirst(isset($subcategory[0]) ? $subcategory[0] : 'Sous-catégorie');
                    } else {
                        echo ucfirst($subcategory);
                    }
                    ?>
                </li>
                <?php endif; ?>
            </ol>
        </nav>
    </div>
</section>

<section class="products-grid py-5">
    <div class="container">
        <div class="row">
            <!-- Filtres -->
            <div class="col-lg-3">
                <div class="filters-wrapper">
                    <?php foreach($filters as $key => $filter): ?>
                    <div class="filter-group mb-4">
                        <h5 class="filter-title"><?php echo $filter['title']; ?></h5>
                        <?php if($filter['type'] === 'checkbox'): ?>
                            <?php foreach($filter['options'] as $option): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="<?php echo $option['id']; ?>">
                                <label class="form-check-label" for="<?php echo $option['id']; ?>">
                                    <?php echo $option['name']; ?> (<?php echo $option['count']; ?>)
                                </label>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Liste des produits -->
            <div class="col-lg-9">
                <div class="products-header d-flex justify-content-between align-items-center mb-4">
                    <div class="showing-products">
                        Affichage de <?php echo count($products); ?> produits
                    </div>
                    <div class="products-sorting">
                        <select class="form-select">
                            <option>Trier par popularité</option>
                            <option>Prix croissant</option>
                            <option>Prix décroissant</option>
                            <option>Nouveautés</option>
                        </select>
                    </div>
                </div>
                
                <div class="row g-4">
                    <?php if (empty($products)): ?>
                        <div class="col-12">
                            <div class="alert alert-info">
                                Aucun produit trouvé dans cette catégorie.
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach($products as $product): ?>
                            <div class="col-md-3 col-6 mb-4">
                                <div class="card product-card h-100">
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
                                        
                                        <!-- Suppression de l'affichage des variantes de couleur ici -->
                                        
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <?php if (isset($product['discount']) && $product['discount'] > 0): ?>
                                                <div>
                                                    <span class="text-muted text-decoration-line-through"><?php echo number_format($product['price'], 2, ',', ' '); ?> €</span>
                                                    <span class="text-danger"><?php echo number_format($product['price'] * (1 - $product['discount'] / 100), 2, ',', ' '); ?> €</span>
                                                </div>
                                            <?php else: ?>
                                                <span><?php echo number_format($product['price'], 2, ',', ' '); ?> €</span>
                                            <?php endif; ?>
                                            
                                            <?php if ($product['stock'] > 0): ?>
                                                <span class="badge bg-success">En stock</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Rupture</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white border-top-0">
                                        <a href="<?php echo BASE_URL; ?>/product/detail/<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-primary w-100">Voir le produit</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
