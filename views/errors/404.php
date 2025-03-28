<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="error-page">
                <h1 class="display-1 fw-bold">404</h1>
                <h2 class="mb-4">Oups ! Page non trouvée</h2>
                <p class="lead mb-3">La page que vous recherchez n'existe pas ou a été déplacée.</p>
                
                <div class="error-details mt-4 mb-4 text-start p-4 bg-light rounded">
                    <h5>Informations de débogage :</h5>
                    <p>URL demandée : <code><?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?></code></p>
                    
                    <?php if (strpos($_SERVER['REQUEST_URI'], 'product/detail')): ?>
                        <div class="alert alert-warning">
                            <p><strong>Conseils de dépannage pour les produits :</strong></p>
                            <ul class="mb-0">
                                <li>Vérifiez que l'identifiant du produit est valide (nombre entier positif)</li>
                                <li>Vérifiez que le produit existe dans la base de données</li>
                                <li>Exécutez l'outil de diagnostic : <a href="<?php echo BASE_URL; ?>/emergency-repair.php" class="alert-link">Diagnostic & Réparation d'Urgence</a></li>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="mt-4">
                    <a href="<?php echo BASE_URL; ?>" class="btn btn-primary btn-lg me-2">
                        <i class="fas fa-home me-2"></i> Retour à l'accueil
                    </a>
                    
                    <?php if (strpos($_SERVER['REQUEST_URI'], 'product')): ?>
                        <a href="<?php echo BASE_URL; ?>/product/category" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-tshirt me-2"></i> Voir tous nos produits
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-page {
    padding: 60px 0;
}
.error-page h1 {
    font-size: 10rem;
    color: #f8f9fa;
    text-shadow: 4px 4px 0 #ff0000;
    margin-bottom: 20px;
}
.error-page h2 {
    font-weight: 700;
    color: #212529;
}
.error-details {
    background-color: #f8f9fa;
    border-left: 4px solid #ff0000;
}
</style>
