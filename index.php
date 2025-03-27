<?php
// Point d'entrée principal de l'application
session_start();

// Définir la constante BASE_URL
define('BASE_URL', '/mgs_store');

// Afficher les erreurs en mode développement
error_reporting(E_ALL);
ini_set('display_errors', 1);

// DEBUG: Vérifier l'état de la session du panier
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Charger les routes
$routes = require_once 'config/routes.php';

// Récupérer l'URL demandée
$requestUri = $_SERVER['REQUEST_URI'];

// Supprimer BASE_URL de l'URI
$requestUri = str_replace(BASE_URL, '', $requestUri);

// Supprimer les paramètres de requête
$requestUri = strtok($requestUri, '?');

// Supprimer le slash de début si présent
$requestUri = ltrim($requestUri, '/');

// Récupérer le contrôleur et l'action à partir des routes
$controller = null;
$action = null;
$params = [];

// Rechercher la route correspondante
foreach ($routes as $pattern => $route) {
    // Version simplifiée et corrigée de la conversion du modèle en expression régulière
    $regexPattern = str_replace('/', '\/', $pattern);
    $regexPattern = preg_replace('/:([a-zA-Z0-9_]+)/', '([^\/]+)', $regexPattern);
    
    // Si la route correspond
    if (preg_match('/^' . $regexPattern . '$/', $requestUri, $matches)) {
        $controller = $route[0];
        $action = $route[1];
        
        // Récupérer les paramètres
        array_shift($matches); // Supprimer la correspondance complète
        $params = $matches;
        
        break;
    }
}

// Si aucune route ne correspond, utiliser la route 404
if (!$controller) {
    $controller = 'ErrorController';
    $action = 'notFound';
}

// Vérifier si le fichier du contrôleur existe
if (!file_exists('controllers/' . $controller . '.php')) {
    error_log("Controller file not found: controllers/{$controller}.php");
    $controller = 'ErrorController';
    $action = 'notFound';
}

// Charger et instancier le contrôleur
require_once 'controllers/' . $controller . '.php';
$controllerInstance = new $controller();

// Appeler l'action avec les paramètres
call_user_func_array([$controllerInstance, $action], $params);
?>
