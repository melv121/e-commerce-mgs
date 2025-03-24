<?php
class CheckoutController {
    private $db;
    
    public function __construct() {
        require_once 'config/database.php';
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function index() {
        // Include the CartController class
        require_once 'controllers/CartController.php';
        
        // Vérifier si le panier est vide
        $cartController = new CartController();
        $cartItems = $this->getCartItems();
        
        if (empty($cartItems)) {
            header('Location: ' . BASE_URL . '/cart');
            exit;
        }

        $total = $this->calculateTotal($cartItems);
        $shipping = $total >= 50 ? 0 : 4.99;
        $grandTotal = $total + $shipping;
        
        $pageTitle = "Paiement";
        
        require_once 'views/templates/header.php';
        require_once 'views/checkout/index.php';
        require_once 'views/templates/footer.php';
    }
    
    public function process() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $paymentMethod = $_POST['payment_method'] ?? '';
            $cartItems = $this->getCartItems();
            
            if (empty($cartItems)) {
                header('Location: ' . BASE_URL . '/cart');
                exit;
            }
            
            $total = $this->calculateTotal($cartItems);
            $shipping = $total >= 50 ? 0 : 4.99;
            $grandTotal = $total + $shipping;
            
            // Dans une application réelle, nous traiterions ici le paiement
            // par exemple en intégrant Stripe, PayPal, etc.
            
            // Pour l'exemple, simulons un paiement réussi
            $orderId = $this->createOrder($grandTotal, $paymentMethod);
            
            if ($orderId) {
                // Rediriger vers la page de confirmation
                header('Location: ' . BASE_URL . '/checkout/confirmation/' . $orderId);
                exit;
            } else {
                // Rediriger en cas d'échec avec message d'erreur
                $_SESSION['checkout_error'] = "Une erreur est survenue lors du traitement de votre commande.";
                header('Location: ' . BASE_URL . '/checkout');
                exit;
            }
        }
    }
    
    public function confirmation($orderId = null) {
        if (!$orderId) {
            header('Location: ' . BASE_URL . '/cart');
            exit;
        }
        
        // Récupérer les informations de la commande
        $order = $this->getOrderById($orderId);
        
        if (!$order) {
            header('Location: ' . BASE_URL . '/cart');
            exit;
        }
        
        $pageTitle = "Confirmation de commande";
        
        require_once 'views/templates/header.php';
        require_once 'views/checkout/confirmation.php';
        require_once 'views/templates/footer.php';
    }
    
    private function getCartItems() {
        try {
            $userId = isset($_SESSION['user']) ? $_SESSION['user']['id'] : null;
            $sessionId = session_id();

            $query = "SELECT ci.*, p.name, p.price, p.image 
                    FROM cart_items ci
                    JOIN cart c ON ci.cart_id = c.id
                    JOIN products p ON ci.product_id = p.id
                    WHERE " . ($userId ? "c.user_id = ?" : "c.session_id = ?");

            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId ?? $sessionId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération du panier: " . $e->getMessage());
            return [];
        }
    }

    private function calculateTotal($items) {
        return array_reduce($items, function($total, $item) {
            return $total + ($item['price'] * $item['quantity']);
        }, 0);
    }
    
    private function createOrder($total, $paymentMethod) {
        try {
            $userId = isset($_SESSION['user']) ? $_SESSION['user']['id'] : null;
            $sessionId = session_id();
            
            // Insérer la commande
            $query = "INSERT INTO orders (user_id, total_amount, status, payment_method, created_at) 
                      VALUES (?, ?, ?, ?, NOW())";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId, $total, 'pending', $paymentMethod]);
            
            $orderId = $this->db->lastInsertId();
            
            // Récupérer les articles du panier
            $cartQuery = "SELECT ci.*, p.price FROM cart_items ci
                         JOIN cart c ON ci.cart_id = c.id
                         JOIN products p ON ci.product_id = p.id
                         WHERE " . ($userId ? "c.user_id = ?" : "c.session_id = ?");
            $cartStmt = $this->db->prepare($cartQuery);
            $cartStmt->execute([$userId ?? $sessionId]);
            $cartItems = $cartStmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Insérer les articles dans la commande
            foreach ($cartItems as $item) {
                $query = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                          VALUES (?, ?, ?, ?)";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$orderId, $item['product_id'], $item['quantity'], $item['price']]);
            }
            
            // Vider le panier
            $query = "DELETE ci FROM cart_items ci 
                      JOIN cart c ON ci.cart_id = c.id 
                      WHERE " . ($userId ? "c.user_id = ?" : "c.session_id = ?");
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId ?? $sessionId]);
            
            return $orderId;
        } catch (PDOException $e) {
            error_log("Erreur lors de la création de la commande: " . $e->getMessage());
            return false;
        }
    }
    
    private function getOrderById($orderId) {
        try {
            $query = "SELECT o.*, u.username, u.email FROM orders o 
                      LEFT JOIN users u ON o.user_id = u.id 
                      WHERE o.id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$orderId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de la commande: " . $e->getMessage());
            return false;
        }
    }
}
?>
