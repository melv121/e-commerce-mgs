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
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/color-theme.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/cart-notification.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/product-variants.css?v=<?php echo time(); ?>">
    
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
                <img src="<?php echo BASE_URL; ?>/assets/images/logomgs.png" alt="MGS Logo" height="40">
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
                    <?php if (isset($_SESSION['user'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/order/history">Mes commandes</a>
                    </li>
                    <?php endif; ?>
                </ul>
                <div class="d-flex nav-icons">
                    <a href="#" class="text-dark me-3" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fas fa-search"></i></a>
                    <a href="#" class="text-dark me-3" id="userIcon" data-bs-toggle="modal" data-bs-target="#authModal"><i class="fas fa-user"></i></a>
                    <a href="<?php echo BASE_URL; ?>/cart" class="text-dark position-relative">
                        <i class="fas fa-shopping-cart"></i>
                        <?php
                        // Récupérer le nombre d'articles dans le panier
                        require_once 'controllers/CartController.php';
                        $cartController = new CartController();
                        $cartCount = $cartController->getCartItemCount();
                        ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-counter"><?php echo $cartCount; ?></span>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Modal d'authentification -->
    <div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title" id="authModalLabel">Mon compte</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <?php if (isset($_SESSION['user'])): ?>
                        <!-- Utilisateur connecté -->
                        <div class="text-center mb-4">
                            <div class="avatar-circle mx-auto mb-3">
                                <span class="avatar-initials"><?php echo strtoupper(substr($_SESSION['user']['first_name'] ?? $_SESSION['user']['username'], 0, 1)); ?></span>
                            </div>
                            <h5 class="mb-0">Bonjour, <?php echo $_SESSION['user']['first_name'] ?? $_SESSION['user']['username']; ?></h5>
                            <p class="text-muted"><?php echo $_SESSION['user']['email']; ?></p>
                        </div>
                        <div class="list-group list-group-flush">
                            <a href="<?php echo BASE_URL; ?>/auth/profile" class="list-group-item list-group-item-action">
                                <i class="fas fa-user-circle me-2"></i> Mon profil
                            </a>
                            <a href="<?php echo BASE_URL; ?>/order/history" class="list-group-item list-group-item-action">
                                <i class="fas fa-shopping-bag me-2"></i> Mes commandes
                            </a>
                            <a href="<?php echo BASE_URL; ?>/auth/logout" class="list-group-item list-group-item-action text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                            </a>
                        </div>
                    <?php else: ?>
                        <!-- Utilisateur non connecté -->
                        <ul class="nav nav-tabs mb-4" id="authTab" role="tablist">
                            <li class="nav-item flex-fill" role="presentation">
                                <button class="nav-link active w-100" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab" aria-controls="login" aria-selected="true">Connexion</button>
                            </li>
                            <li class="nav-item flex-fill" role="presentation">
                                <button class="nav-link w-100" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab" aria-controls="register" aria-selected="false">Inscription</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="authTabContent">
                            <!-- Onglet Connexion -->
                            <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
                                <form id="loginForm" action="<?php echo BASE_URL; ?>/auth/processLogin" method="post">
                                    <div class="mb-3">
                                        <label for="loginEmail" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="loginEmail" name="email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="loginPassword" class="form-label">Mot de passe</label>
                                        <input type="password" class="form-control" id="loginPassword" name="password" required>
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="rememberMe" name="remember_me">
                                        <label class="form-check-label" for="rememberMe">Se souvenir de moi</label>
                                    </div>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">Se connecter</button>
                                    </div>
                                </form>
                            </div>
                            
                            <!-- Onglet Inscription -->
                            <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab">
                                <form id="registerForm" action="<?php echo BASE_URL; ?>/auth/processRegister" method="post">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="registerFirstName" class="form-label">Prénom</label>
                                            <input type="text" class="form-control" id="registerFirstName" name="first_name">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="registerLastName" class="form-label">Nom</label>
                                            <input type="text" class="form-control" id="registerLastName" name="last_name">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="registerUsername" class="form-label">Nom d'utilisateur*</label>
                                        <input type="text" class="form-control" id="registerUsername" name="username" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="registerEmail" class="form-label">Email*</label>
                                        <input type="email" class="form-control" id="registerEmail" name="email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="registerPassword" class="form-label">Mot de passe*</label>
                                        <input type="password" class="form-control" id="registerPassword" name="password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="registerPasswordConfirm" class="form-label">Confirmer le mot de passe*</label>
                                        <input type="password" class="form-control" id="registerPasswordConfirm" name="password_confirm" required>
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="termsCheck" name="terms" required>
                                        <label class="form-check-label" for="termsCheck">J'accepte les <a href="#">termes et conditions</a>*</label>
                                    </div>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">S'inscrire</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    <main class="mt-5 pt-4">
