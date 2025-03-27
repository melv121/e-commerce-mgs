<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('BASE_URL', '/mgs_store');

echo "<h1>Test des routes d'administration</h1>";

// Charger les routes
require_once 'config/routes.php';

// Vérifier si la route admin existe
$adminRouteExists = isset($routes['admin']);
echo "<p>Route 'admin' existe: " . ($adminRouteExists ? "Oui" : "Non") . "</p>";

// Vérifier si le contrôleur AdminController existe
$adminControllerExists = file_exists('controllers/AdminController.php');
echo "<p>Fichier 'controllers/AdminController.php' existe: " . ($adminControllerExists ? "Oui" : "Non") . "</p>";

// Vérifier si la classe AdminController est correctement définie
if ($adminControllerExists) {
    require_once 'controllers/AdminController.php';
    $classExists = class_exists('AdminController');
    echo "<p>Classe 'AdminController' existe: " . ($classExists ? "Oui" : "Non") . "</p>";
    
    // Vérifier si la méthode index existe
    if ($classExists) {
        $methods = get_class_methods('AdminController');
        echo "<p>Méthodes disponibles dans AdminController: " . implode(', ', $methods) . "</p>";
    }
}

// Simuler une requête admin
echo "<h2>Simulation d'une requête à '/admin'</h2>";

// Charger la base de données et se connecter en tant qu'admin si nécessaire
require_once 'config/database.php';
$database = new Database();
$db = $database->getConnection();

// Vérifier si l'utilisateur est connecté en tant qu'admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo "<p>Vous n'êtes pas connecté en tant qu'administrateur.</p>";
    
    // Connecter automatiquement en tant qu'admin pour le test
    $query = "SELECT * FROM users WHERE role = 'admin' LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin) {
        unset($admin['password']);
        $_SESSION['user'] = $admin;
        echo "<p>Connexion automatique en tant que: " . $admin['username'] . " (rôle: " . $admin['role'] . ")</p>";
    } else {
        echo "<p>Aucun utilisateur admin trouvé. Création d'un utilisateur admin test...</p>";
        
        // Créer un utilisateur admin
        require_once 'models/User.php';
        $userModel = new User($db);
        $result = $userModel->createAdminIfNotExists();
        
        if ($result) {
            echo "<p>Utilisateur admin créé avec succès.</p>";
            // Récupérer le nouvel admin
            $query = "SELECT * FROM users WHERE role = 'admin' LIMIT 1";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($admin) {
                unset($admin['password']);
                $_SESSION['user'] = $admin;
                echo "<p>Connexion automatique en tant que: " . $admin['username'] . " (rôle: " . $admin['role'] . ")</p>";
            }
        } else {
            echo "<p>Échec de la création de l'utilisateur admin.</p>";
        }
    }
}

// Maintenant tester le contrôleur
if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
    echo "<h3>Test du contrôleur AdminController</h3>";
    
    try {
        // Instancier le contrôleur
        $adminController = new AdminController();
        
        // Tester diverses actions
        echo "<h4>Test de AdminController::isAdmin()</h4>";
        // On ne peut pas tester directement car c'est une méthode privée
        echo "<p>Vérification indirecte via l'absence d'exception lors de l'instanciation</p>";
        
        echo "<h4>Test de AdminController::getAllOrders()</h4>";
        // On ne peut pas tester directement mais on peut vérifier si la table orders existe
        $query = "SHOW TABLES LIKE 'orders'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $tableExists = $stmt->rowCount() > 0;
        echo "<p>Table 'orders' existe: " . ($tableExists ? "Oui" : "Non") . "</p>";
        
        if (!$tableExists) {
            echo "<p>Création de la table 'orders'...</p>";
            // Exécuter le script SQL pour créer les tables manquantes
            $sql = file_get_contents('sql/create_order_tables.sql');
            $db->exec($sql);
            echo "<p>Table créée avec succès.</p>";
        }
        
        echo "<p>Test réussi. Cliquez sur le lien ci-dessous pour accéder au tableau de bord d'administration:</p>";
        echo "<a href='" . BASE_URL . "/admin' class='btn btn-primary'>Accéder à l'admin</a>";
        
    } catch (Exception $e) {
        echo "<p>Erreur lors du test: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>Vous devez être connecté en tant qu'administrateur pour tester le contrôleur.</p>";
}
?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}
h1, h2, h3, h4 {
    color: #333;
}
.btn {
    display: inline-block;
    background-color: #0066cc;
    color: white;
    padding: 10px 15px;
    text-decoration: none;
    border-radius: 4px;
    margin-top: 20px;
}
</style>
