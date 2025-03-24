<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'MGS'; ?></title>
    <!-- Meta pour éviter la mise en cache -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Custom CSS with version parameter to prevent caching -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css?v=<?php echo time(); ?>">
    <!-- Ajout de la variable globale BASE_URL pour JavaScript -->
    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';
    </script>
</head>
<body>
    <!-- Topbar -->
    <div class="topbar bg-dark text-white py-2 d-none d-md-block">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-md-4">
                    <small><i class="fas fa-truck me-2"></i>Livraison gratuite dès 50€</small>
                </div>
                <div class="col-md-4 text-end">
                    <a href="#" class="text-white me-3 small"><i class="fas fa-map-marker-alt me-1"></i>Nos magasins</a>
                    <a href="#" class="text-white small"><i class="fas fa-headset me-1"></i>Support</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
        <div class="container">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>">
                <span class="text-danger">MGS</span> 
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?php echo BASE_URL; ?>">Accueil</a>
                    </li>
                    
                    <?php
                    // Récupérer les catégories depuis la base de données
                    try {
                        $db = new PDO("mysql:host=localhost;dbname=mgs_store;charset=utf8", "root", "");
                        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        
                        $stmt = $db->query("SELECT * FROM categories WHERE parent_id IS NULL ORDER BY name");
                        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        foreach ($categories as $category) {
                            $categoryName = strtolower($category['name']);
                            ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <?php echo $category['name']; ?>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/product/category/<?php echo $categoryName; ?>/vetements">Vêtements</a></li>
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/product/category/<?php echo $categoryName; ?>/chaussures">Chaussures</a></li>
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/product/category/<?php echo $categoryName; ?>/accessoires">Accessoires</a></li>
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/product/category/<?php echo $categoryName; ?>">Tous les produits</a></li>
                                </ul>
                            </li>
                            <?php
                        }
                    } catch (PDOException $e) {
                        // En cas d'erreur, afficher les liens par défaut
                        ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Homme</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/product/category/homme/vetements">Vêtements</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/product/category/homme/chaussures">Chaussures</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/product/category/homme/accessoires">Accessoires</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/product/category/homme">Collections</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Femme</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/product/category/femme/vetements">Vêtements</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/product/category/femme/chaussures">Chaussures</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/product/category/femme/accessoires">Accessoires</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/product/category/femme">Collections</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Enfant</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/product/category/enfant/vetements">Vêtements</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/product/category/enfant/chaussures">Chaussures</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/product/category/enfant/accessoires">Accessoires</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/product/category/enfant">Collections</a></li>
                            </ul>
                        </li>
                        <?php
                    }
                    ?>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/product/nouveautes">Nouveautés</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/product/promotions">Promotions</a>
                    </li>
                </ul>
                <div class="d-flex nav-icons">
                    <a href="#" class="text-dark me-3"><i class="fas fa-search"></i></a>
                    <a href="#" class="text-dark me-3"><i class="fas fa-user"></i></a>
                    <a href="<?php echo BASE_URL; ?>/cart" class="text-dark position-relative">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-counter">0</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Contenu principal -->
    <main class="mt-5 pt-4">
