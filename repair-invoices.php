<?php
// Script pour réparer/créer les factures manquantes
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('BASE_URL', '/mgs_store');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: " . BASE_URL . "/auth/login");
    exit;
}

// Connexion à la base de données
require_once 'config/database.php';
$database = new Database();
$db = $database->getConnection();

if (!$db) {
    die("Erreur de connexion à la base de données");
}

// Récupérer les commandes sans facture
try {
    $query = "SELECT o.* FROM orders o
              LEFT JOIN invoices i ON o.id = i.order_id
              WHERE o.user_id = ? AND i.id IS NULL";
    $stmt = $db->prepare($query);
    $stmt->execute([$_SESSION['user']['id']]);
    $ordersWithoutInvoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($ordersWithoutInvoices)) {
        $_SESSION['success_message'] = "Toutes vos commandes ont déjà des factures associées.";
        header("Location: " . BASE_URL . "/invoice");
        exit;
    }
    
    $generatedCount = 0;
    
    // Générer des factures pour chaque commande
    foreach ($ordersWithoutInvoices as $order) {
        // Générer un numéro de facture unique
        $invoiceNumber = 'F' . date('Ymd') . str_pad($order['id'], 4, '0', STR_PAD_LEFT);
        
        // Insérer la facture dans la base de données
        $query = "INSERT INTO invoices (order_id, invoice_number, amount, created_at) 
                  VALUES (?, ?, ?, NOW())";
        $stmt = $db->prepare($query);
        $stmt->execute([$order['id'], $invoiceNumber, $order['total_amount']]);
        
        $generatedCount++;
    }
    
    $_SESSION['success_message'] = "{$generatedCount} facture(s) ont été créées avec succès.";
    header("Location: " . BASE_URL . "/invoice");
    exit;
    
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Erreur lors de la création des factures: " . $e->getMessage();
    header("Location: " . BASE_URL . "/invoice");
    exit;
}
?>
