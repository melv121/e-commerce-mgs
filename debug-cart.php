<?php
// Script de débogage pour le panier
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('BASE_URL', '/mgs_store');

echo "<h1>Débogage du panier</h1>";

echo "<h2>Contenu de la session</h2>";
echo "<pre>";
var_dump($_SESSION);
echo "</pre>";

echo "<h2>État actuel du panier</h2>";

if (!isset($_SESSION['cart'])) {
    echo "<p>Le panier n'existe pas dans la session.</p>";
    echo "<p>Création d'un panier vide...</p>";
    $_SESSION['cart'] = [];
} elseif (!is_array($_SESSION['cart'])) {
    echo "<p>Le panier existe mais n'est pas un tableau. Valeur actuelle: </p>";
    echo "<pre>" . print_r($_SESSION['cart'], true) . "</pre>";
    echo "<p>Réinitialisation du panier...</p>";
    $_SESSION['cart'] = [];
} else {
    echo "<p>Le panier existe et contient " . count($_SESSION['cart']) . " articles.</p>";
    echo "<pre>" . print_r($_SESSION['cart'], true) . "</pre>";
}

// Option pour réinitialiser le panier
if (isset($_GET['reset'])) {
    $_SESSION['cart'] = [];
    echo "<p>Le panier a été réinitialisé.</p>";
    echo "<script>setTimeout(function() { window.location.href = 'debug-cart.php'; }, 1000);</script>";
}

// Option pour ajouter un produit de test
if (isset($_GET['add_test'])) {
    // Connexion à la base de données pour obtenir un produit valide
    require_once 'config/database.php';
    $database = new Database();
    $db = $database->getConnection();
    
    try {
        $query = "SELECT * FROM products LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($product) {
            $price = $product['price'];
            if (!empty($product['discount'])) {
                $price = $price * (1 - ($product['discount'] / 100));
            }
            
            // Ajouter le produit au panier
            $_SESSION['cart'][] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $price,
                'quantity' => 1,
                'image' => $product['image']
            ];
            
            echo "<p>Produit de test ajouté au panier avec succès.</p>";
        } else {
            echo "<p>Aucun produit trouvé dans la base de données.</p>";
        }
    } catch (PDOException $e) {
        echo "<p>Erreur lors de la récupération du produit: " . $e->getMessage() . "</p>";
    }
    
    echo "<script>setTimeout(function() { window.location.href = 'debug-cart.php'; }, 1000);</script>";
}

// Options de débogage
echo "<div style='margin-top: 20px;'>";
echo "<a href='debug-cart.php?reset=1' class='button'>Réinitialiser le panier</a> | ";
echo "<a href='debug-cart.php?add_test=1' class='button'>Ajouter un produit de test</a> | ";
echo "<a href='" . BASE_URL . "/cart' class='button'>Voir le panier</a>";
echo "</div>";

// Styles CSS pour améliorer la lisibilité
echo "<style>
body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
h1, h2 { color: #333; }
pre { background-color: #f5f5f5; padding: 10px; border-radius: 5px; overflow: auto; }
.button { display: inline-block; padding: 8px 15px; background-color: #007bff; color: white; 
          text-decoration: none; border-radius: 5px; margin-right: 10px; }
.button:hover { background-color: #0056b3; }
</style>";

?>
