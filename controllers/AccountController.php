<?php
class AccountController {
    private $db;
    
    public function __construct() {
        require_once 'config/database.php';
        $database = new Database();
        $this->db = $database->getConnection();
        
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }
    }

    public function index() {
        $pageTitle = "Mon compte";
        $orders = $this->getUserOrders($_SESSION['user']['id']);
        $loyaltyPoints = $this->getLoyaltyPoints($_SESSION['user']['id']);
        
        require_once 'views/templates/header.php';
        require_once 'views/account/dashboard.php';
        require_once 'views/templates/footer.php';
    }

    public function orders() {
        $pageTitle = "Mes commandes";
        $orders = $this->getUserOrders($_SESSION['user']['id']);
        
        require_once 'views/templates/header.php';
        require_once 'views/account/orders.php';
        require_once 'views/templates/footer.php';
    }

    // ...autres méthodes...
}
