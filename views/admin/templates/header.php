<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - MGS Admin' : 'MGS Admin'; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Admin CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/admin-style.css?v=<?php echo time(); ?>">
    <!-- Ajout de la variable globale BASE_URL pour JavaScript -->
    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';
    </script>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>MGS Admin</h3>
            </div>

            <ul class="list-unstyled components">
                <li class="<?php echo !isset($currentPage) || $currentPage === 'dashboard' ? 'active' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>/admin">
                        <i class="fas fa-tachometer-alt me-2"></i> Tableau de bord
                    </a>
                </li>
                <li class="<?php echo isset($currentPage) && $currentPage === 'products' ? 'active' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>/admin/products">
                        <i class="fas fa-box me-2"></i> Produits
                    </a>
                </li>
                <li class="<?php echo isset($currentPage) && $currentPage === 'orders' ? 'active' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>/admin/orders">
                        <i class="fas fa-shopping-bag me-2"></i> Commandes
                    </a>
                </li>
                <li class="<?php echo isset($currentPage) && $currentPage === 'users' ? 'active' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>/admin/users">
                        <i class="fas fa-users me-2"></i> Utilisateurs
                    </a>
                </li>
            </ul>

            <ul class="list-unstyled CTAs">
                <li>
                    <a href="<?php echo BASE_URL; ?>" class="visit-site">
                        <i class="fas fa-globe me-2"></i> Visiter le site
                    </a>
                </li>
                <li>
                    <a href="<?php echo BASE_URL; ?>/auth/logout" class="logout">
                        <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-primary">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="ms-auto d-flex align-items-center">
                        <?php if (isset($_SESSION['user'])): ?>
                            <div class="dropdown">
                                <a class="btn dropdown-toggle" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle me-2"></i> <?php echo $_SESSION['user']['username']; ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/auth/profile"><i class="fas fa-user me-2"></i> Mon profil</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/auth/logout"><i class="fas fa-sign-out-alt me-2"></i> Déconnexion</a></li>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </nav>

            <div class="content-wrapper">
                <?php if (isset($_SESSION['admin_error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['admin_error']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['admin_error']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['admin_success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['admin_success']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['admin_success']); ?>
                <?php endif; ?>
