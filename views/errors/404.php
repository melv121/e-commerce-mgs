<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="error-page">
                <h1 class="display-1 fw-bold">404</h1>
                <h2 class="mb-4">Oups ! Page non trouvée</h2>
                <p class="lead mb-5">La page que vous recherchez n'existe pas ou a été déplacée.</p>
                <a href="<?php echo BASE_URL; ?>" class="btn btn-primary"><i class="fas fa-home me-2"></i> Retour à l'accueil</a>
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
</style>
