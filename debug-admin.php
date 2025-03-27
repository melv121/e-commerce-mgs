<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Chemin de base de l'application
define('BASE_URL', '/mgs_store');

// Vérifier si les sessions fonctionnent correctement
echo "<h2>Vérification des sessions</h2>";
echo "<pre>";
var_dump($_SESSION);
echo "</pre>";

// Créer un utilisateur admin si nécessaire
echo "<h2>Création d'un administrateur si nécessaire</h2>";
require_once 'config/database.php';
$database = new Database();
$db = $database->getConnection();

if (!$db) {
    die("Erreur: Impossible de se connecter à la base de données");
}

echo "Connexion à la base de données réussie.<br>";

// Création de l'utilisateur admin
require_once 'models/User.php';
$userModel = new User($db);
$adminCreated = $userModel->createAdminIfNotExists();

if ($adminCreated) {
    echo "Administrateur créé avec succès.<br>";
} else {
    echo "L'administrateur existe déjà ou n'a pas pu être créé.<br>";
}

// Vérifier que la table orders existe
echo "<h2>Vérification des tables de commande</h2>";
try {
    $tables = ['orders', 'order_items'];
    foreach ($tables as $table) {
        $query = "SHOW TABLES LIKE '$table'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $tableExists = $stmt->rowCount() > 0;
        
        echo "Table '$table': " . ($tableExists ? "Existe" : "N'existe pas") . "<br>";
        
        if (!$tableExists) {
            echo "Exécution du script de création des tables commandes...<br>";
            // Charger et exécuter le script SQL
            $sql = file_get_contents('sql/create_order_tables.sql');
            $db->exec($sql);
            echo "Tables créées avec succès.<br>";
            break; // Une seule exécution suffit car le script crée toutes les tables
        }
    }
    
    // Afficher le nombre de commandes
    $query = "SELECT COUNT(*) FROM orders";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $orderCount = $stmt->fetchColumn();
    
    echo "Nombre de commandes: $orderCount<br>";
    
    // Si l'utilisateur n'est pas connecté en tant qu'admin, proposer de le connecter
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        echo "<h2>Connexion automatique en tant qu'administrateur</h2>";
        echo "<p>Vous n'êtes pas connecté en tant qu'administrateur. Cliquez sur le bouton ci-dessous pour vous connecter:</p>";
        echo "<form method='post'>";
        echo "<input type='submit' name='admin_login' value='Se connecter en tant qu'admin'>";
        echo "</form>";
        
        if (isset($_POST['admin_login'])) {
            // Récupérer l'utilisateur admin
            $query = "SELECT * FROM users WHERE role = 'admin' LIMIT 1";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($admin) {
                // Supprimer le mot de passe
                unset($admin['password']);
                
                // Connecter l'utilisateur
                $_SESSION['user'] = $admin;
                
                echo "<p>Vous êtes maintenant connecté en tant qu'administrateur. <a href='" . BASE_URL . "/admin'>Accéder au tableau de bord</a></p>";
                
                // Rafraîchir la page pour mettre à jour l'affichage des sessions
                echo "<script>setTimeout(function() { window.location.reload(); }, 2000);</script>";
            } else {
                echo "<p>Aucun administrateur trouvé dans la base de données.</p>";
            }
        }
    } else {
        echo "<p>Vous êtes déjà connecté en tant qu'administrateur. <a href='" . BASE_URL . "/admin'>Accéder au tableau de bord</a></p>";
    }
    
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
}
?>
