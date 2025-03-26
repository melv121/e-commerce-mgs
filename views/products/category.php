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
                            <?php include __DIR__ . '/../partials/product-card.php'; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
