<?php
// Fichier pour gérer directement les erreurs 404
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('BASE_URL', '/mgs_store');
define('DEBUG_MODE', true);

// Définir le code de statut HTTP 404
http_response_code(404);

// Définir le titre de la page
$pageTitle = "Page non trouvée";

// Inclure les templates et la vue
require_once 'views/templates/header.php';
require_once 'views/errors/404.php';
require_once 'views/templates/footer.php';
?>
