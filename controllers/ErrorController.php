<?php
class ErrorController {
    
    public function notFound() {
        // Définir le code de statut HTTP 404
        http_response_code(404);
        
        // Définir le titre de la page
        $pageTitle = "Page non trouvée";
        
        // Afficher la vue d'erreur 404
        require_once 'views/templates/header.php';
        require_once 'views/errors/404.php';
        require_once 'views/templates/footer.php';
    }
    
    public function serverError() {
        $pageTitle = "Erreur serveur";
        
        // Définir le code de statut HTTP 500
        header("HTTP/1.0 500 Internal Server Error");
        
        require_once 'views/templates/header.php';
        require_once 'views/errors/500.php';
        require_once 'views/templates/footer.php';
    }
    
    public function forbidden() {
        $pageTitle = "Accès interdit";
        
        // Définir le code de statut HTTP 403
        header("HTTP/1.0 403 Forbidden");
        
        require_once 'views/templates/header.php';
        require_once 'views/errors/403.php';
        require_once 'views/templates/footer.php';
    }
}
?>
