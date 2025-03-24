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
                    <?php foreach($products as $product): ?>
                    <div class="col-md-4">
                        <div class="product-card">
                            <?php if(isset($product['sale']) && $product['sale']): ?>
                            <div class="product-badge">Promo</div>
                            <?php endif; ?>
                            <div class="product-image">
                                <img src="<?php echo BASE_URL . '/' . $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="img-fluid">
                                <div class="product-overlay">
                                    <a href="<?php echo BASE_URL; ?>/product/detail/<?php echo $product['id']; ?>" class="btn"><i class="fas fa-eye"></i></a>
                                    <a href="#" class="btn add-to-cart-btn"><i class="fas fa-shopping-cart"></i></a>
                                    <a href="#" class="btn"><i class="fas fa-heart"></i></a>
                                </div>
                            </div>
                            <div class="product-info p-3">
                                <p class="product-category"><?php echo ucfirst($product['brand']); ?></p>
                                <h5 class="product-title"><?php echo $product['name']; ?></h5>
                                <div class="product-rating">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <?php if($i <= $product['rating']): ?>
                                            <i class="fas fa-star"></i>
                                        <?php elseif($i - 0.5 <= $product['rating']): ?>
                                            <i class="fas fa-star-half-alt"></i>
                                        <?php else: ?>
                                            <i class="far fa-star"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                                <p class="product-price"><?php echo number_format($product['price'], 2, ',', ' '); ?> €</p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
