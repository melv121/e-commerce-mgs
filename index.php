<?php
session_start();
// Point d'entrée principal de l'application MVC

// Charger la configuration de la base de données
require_once 'config/database.php';

// Définir le chemin de base
define('BASE_PATH', __DIR__);
define('BASE_URL', 'http://localhost/mgs_store');

// Analyser l'URL pour déterminer le contrôleur et l'action
$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);
$path = str_replace('/mgs_store/', '', $path);

// Initialiser les paramètres par défaut
$params = [];

// Route par défaut
if ($path == '' || $path == '/') {
    $controller = 'Home';
    $action = 'index';
} else {
    // Analyse des autres routes
    $parts = explode('/', $path);
    $controller = isset($parts[0]) && $parts[0] != '' ? ucfirst($parts[0]) : 'Home';
    $action = isset($parts[1]) && $parts[1] != '' ? $parts[1] : 'index';
    
    // Paramètres supplémentaires pour les catégories
    $params = array_slice($parts, 2);
}

// Charger le contrôleur approprié
$controllerFile = 'controllers/' . $controller . 'Controller.php';
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controllerClass = $controller . 'Controller';
    $controllerInstance = new $controllerClass();
    
    // Vérifier si l'action existe
    if (method_exists($controllerInstance, $action)) {
        // Vérifier si les paramètres sont vides avant d'appeler la fonction
        if (!empty($params)) {
            call_user_func_array([$controllerInstance, $action], $params);
        } else {
            // Appeler l'action sans paramètres si aucun n'est fourni
            $controllerInstance->$action();
        }
    } else {
        // Action non trouvée
        require_once 'views/404.php';
    }
} else {
    // Contrôleur non trouvé
    require_once 'views/404.php';
}
?>
