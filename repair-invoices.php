<?php
// Script pour diagnostiquer et réparer les factures manquantes
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('BASE_URL', '/mgs_store');

echo "<h1>Réparation des factures manquantes</h1>";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    echo "<p>Vous devez être connecté pour utiliser cet outil.</p>";
    echo "<p><a href='" . BASE_URL . "/auth/login' class='btn'>Se connecter</a></p>";
    exit;
}

// Connexion à la base de données
require_once 'config/database.php';
$database = new Database();
$db = $database->getConnection();

if (!$db) {
    die("Erreur de connexion à la base de données.");
}

// Récupérer toutes les commandes de l'utilisateur qui n'ont pas de facture
try {
    $userId = $_SESSION['user']['id'];
    
    // Vérifier d'abord si la table invoices existe
    $tableCheck = $db->query("SHOW TABLES LIKE 'invoices'");
    if ($tableCheck->rowCount() == 0) {
        echo "<p>La table 'invoices' n'existe pas. Création de la table...</p>";
        
        $sql = "CREATE TABLE IF NOT EXISTS invoices (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            invoice_number VARCHAR(20) NOT NULL,
            amount DECIMAL(10, 2) NOT NULL,
            created_at DATETIME NOT NULL,
            INDEX (order_id),
            UNIQUE (invoice_number)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        $db->exec($sql);
        echo "<p>Table 'invoices' créée avec succès.</p>";
    }
    
    $query = "SELECT o.* FROM orders o
              LEFT JOIN invoices i ON o.id = i.order_id
              WHERE o.user_id = ? AND i.id IS NULL";
    $stmt = $db->prepare($query);
    $stmt->execute([$userId]);
    
    $ordersWithoutInvoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($ordersWithoutInvoices)) {
        echo "<p>Toutes vos commandes ont des factures associées.</p>";
    } else {
        echo "<p>Commandes sans facture trouvées: " . count($ordersWithoutInvoices) . "</p>";
        
        if (isset($_POST['generate'])) {
            require_once 'controllers/InvoiceController.php';
            $invoiceController = new InvoiceController();
            
            $success = 0;
            $errors = 0;
            
            foreach ($ordersWithoutInvoices as $order) {
                $result = $invoiceController->generate($order['id']);
                if ($result) {
                    $success++;
                } else {
                    $errors++;
                }
            }
            
            echo "<div class='alert " . ($errors > 0 ? 'alert-warning' : 'alert-success') . "'>";
            echo "<p>Résultat de la génération des factures:</p>";
            echo "<ul>";
            echo "<li>Factures générées avec succès: $success</li>";
            if ($errors > 0) {
                echo "<li>Échecs: $errors</li>";
            }
            echo "</ul>";
            echo "</div>";
            
            // Rafraîchir la page pour mettre à jour la liste
            echo "<script>setTimeout(function() { window.location.href = 'repair-invoices.php'; }, 3000);</script>";
        } else {
            echo "<table class='table'>";
            echo "<thead><tr><th>ID Commande</th><th>Numéro</th><th>Date</th><th>Montant</th></tr></thead>";
            echo "<tbody>";
            
            foreach ($ordersWithoutInvoices as $order) {
                echo "<tr>";
                echo "<td>" . $order['id'] . "</td>";
                echo "<td>" . (isset($order['order_number']) ? $order['order_number'] : '-') . "</td>";
                echo "<td>" . date('d/m/Y', strtotime($order['created_at'])) . "</td>";
                echo "<td>" . number_format($order['total_amount'], 2, ',', ' ') . " €</td>";
                echo "</tr>";
            }
            
            echo "</tbody></table>";
            
            echo "<form method='post'>";
            echo "<button type='submit' name='generate' class='btn'>Générer les factures manquantes</button>";
            echo "</form>";
        }
    }
    
} catch (PDOException $e) {
    echo "<p>Erreur: " . $e->getMessage() . "</p>";
}

// Ajouter un lien pour retourner à la page des factures
echo "<p><a href='" . BASE_URL . "/invoice' class='btn'>Retour à mes factures</a></p>";
?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}
h1 {
    color: #ff0000;
}
.btn {
    display: inline-block;
    background-color: #ff0000;
    color: white;
    padding: 10px 15px;
    text-decoration: none;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 20px;
}
.btn:hover {
    background-color: #121212;
}
.table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}
.table th, .table td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    text-align: left;
}
.table th {
    background-color: #f5f5f5;
}
.alert {
    padding: 15px;
    border-radius: 4px;
    margin: 20px 0;
}
.alert-success {
    background-color: #d4edda;
    color: #155724;
}
.alert-warning {
    background-color: #fff3cd;
    color: #856404;
}
</style>
