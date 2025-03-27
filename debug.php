<?php
// Fichier de débogage temporaire
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Tester la connexion à la base de données
require_once 'config/database.php';
$database = new Database();
$db = $database->getConnection();

echo "Connexion à la base de données réussie.";

// Tester les tables
$tables = ["users", "products", "orders", "order_items", "invoices"]; 
foreach ($tables as $table) {
    $query = "SELECT COUNT(*) FROM $table";
    try {
        $stmt = $db->prepare($query);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        echo "<p>Table $table: $count enregistrements</p>";
    } catch (PDOException $e) {
        echo "<p>Erreur avec la table $table: " . $e->getMessage() . "</p>";
    }
}

// Tester la génération de facture
require_once 'controllers/InvoiceController.php';
$invoiceController = new InvoiceController();

// Récupérer une commande existante
$query = "SELECT id FROM orders LIMIT 1";
$stmt = $db->prepare($query);
$stmt->execute();
$orderId = $stmt->fetchColumn();

if ($orderId) {
    echo "<p>Test de génération de facture pour la commande #$orderId...</p>";
    $result = $invoiceController->generate($orderId);
    echo $result ? "Facture générée avec succès." : "Échec de la génération de la facture.";
} else {
    echo "<p>Aucune commande trouvée pour tester la génération de facture.</p>";
}
?>
