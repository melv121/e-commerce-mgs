<?php
class OrderController {
    private $db;
    
    public function __construct() {
        require_once 'config/database.php';
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    // Affiche l'historique des commandes de l'utilisateur connecté
    public function history() {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            $_SESSION['redirect_after_login'] = BASE_URL . "/order/history";
            header("Location: " . BASE_URL . "/auth/login");
            exit;
        }
        
        $pageTitle = "Historique des commandes";
        $orders = $this->getUserOrders($_SESSION['user']['id']);
        
        require_once 'views/templates/header.php';
        require_once 'views/orders/history.php';
        require_once 'views/templates/footer.php';
    }
    
    // Affiche les détails d'une commande spécifique
    public function detail($orderId) {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            $_SESSION['redirect_after_login'] = BASE_URL . "/order/detail/" . $orderId;
            header("Location: " . BASE_URL . "/auth/login");
            exit;
        }
        
        $order = $this->getOrderById($orderId);
        
        // Vérifier si la commande appartient à l'utilisateur connecté
        if (!$order || $order['user_id'] != $_SESSION['user']['id']) {
            header("Location: " . BASE_URL . "/order/history");
            exit;
        }
        
        $pageTitle = "Détail de la commande #" . $order['id'];
        
        require_once 'views/templates/header.php';
        require_once 'views/orders/detail.php';
        require_once 'views/templates/footer.php';
    }
    
    // Annuler une commande
    public function cancel($orderId) {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "/auth/login");
            exit;
        }
        
        $order = $this->getOrderById($orderId);
        
        // Vérifier si la commande appartient à l'utilisateur connecté
        if (!$order || $order['user_id'] != $_SESSION['user']['id']) {
            header("Location: " . BASE_URL . "/order/history");
            exit;
        }
        
        // Vérifier si la commande peut être annulée (seulement si elle est en attente ou en traitement)
        if ($order['status'] != 'pending' && $order['status'] != 'processing') {
            $_SESSION['error_message'] = "Cette commande ne peut plus être annulée.";
            header("Location: " . BASE_URL . "/order/detail/" . $orderId);
            exit;
        }
        
        // Mettre à jour le statut de la commande
        if ($this->updateOrderStatus($orderId, 'cancelled')) {
            $_SESSION['success_message'] = "Votre commande a été annulée avec succès.";
        } else {
            $_SESSION['error_message'] = "Une erreur s'est produite lors de l'annulation de votre commande.";
        }
        
        header("Location: " . BASE_URL . "/order/detail/" . $orderId);
        exit;
    }
    
    // Récupérer les commandes d'un utilisateur
    private function getUserOrders($userId) {
        try {
            $query = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des commandes: " . $e->getMessage());
            return [];
        }
    }
    
    // Récupérer une commande par son ID
    private function getOrderById($orderId) {
        try {
            $query = "SELECT o.*, u.first_name, u.last_name, u.email, u.address, u.city, u.postal_code, u.country, u.phone 
                      FROM orders o 
                      JOIN users u ON o.user_id = u.id 
                      WHERE o.id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$orderId]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($order) {
                // Récupérer les articles de la commande
                $query = "SELECT oi.*, p.name, p.image 
                          FROM order_items oi 
                          JOIN products p ON oi.product_id = p.id 
                          WHERE oi.order_id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$orderId]);
                $order['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            
            return $order;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de la commande: " . $e->getMessage());
            return false;
        }
    }
    
    // Mettre à jour le statut d'une commande
    private function updateOrderStatus($orderId, $status) {
        try {
            $query = "UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$status, $orderId]);
            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour du statut de la commande: " . $e->getMessage());
            return false;
        }
    }
}
?>
