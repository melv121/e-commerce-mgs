<?php
class ErrorController {
    
    public function notFound() {
        $pageTitle = "Page non trouvée";
        
        // Définir le code de statut HTTP 404
        header("HTTP/1.0 404 Not Found");
        
        // Charger la vue 404
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
