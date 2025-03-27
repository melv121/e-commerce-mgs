<?php
class ErrorController {
    
    public function notFound() {
        $pageTitle = "Page non trouvée";
        
        http_response_code(404);
        
        require_once 'views/templates/header.php';
        require_once 'views/errors/404.php';
        require_once 'views/templates/footer.php';
    }
    
    public function serverError() {
        $pageTitle = "Erreur serveur";
        
        http_response_code(500);
        
        require_once 'views/templates/header.php';
        require_once 'views/errors/500.php';
        require_once 'views/templates/footer.php';
    }
    
    public function forbidden() {
        $pageTitle = "Accès interdit";
        
        http_response_code(403);
        
        require_once 'views/templates/header.php';
        require_once 'views/errors/403.php';
        require_once 'views/templates/footer.php';
    }
}
?>
