<?php
class CheckoutController {
    private $db;
    
    public function __construct() {
        require_once 'config/database.php';
        $database = new Database();
        $this->db = $database->getConnection();
        
        // Initialiser le panier s'il n'existe pas
        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }
    
    // Affiche la page de paiement
    public function index() {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            // Enregistrer l'URL actuelle pour rediriger après connexion
            $_SESSION['redirect_after_login'] = BASE_URL . "/checkout";
            
            // Rediriger vers la page de connexion
            $_SESSION['info_message'] = "Veuillez vous connecter ou vous inscrire pour continuer vos achats.";
            header("Location: " . BASE_URL . "/auth/login");
            exit;
        }
        
        // Récupérer le panier
        require_once 'controllers/CartController.php';
        $cartController = new CartController();
        
        // Valider le panier
        $validation = $cartController->validateForCheckout();
        if (!$validation['valid']) {
            // Stocker les erreurs et rediriger vers le panier
            foreach ($validation['errors'] as $error) {
                $_SESSION['error_message'] = $error;
            }
            header("Location: " . BASE_URL . "/cart");
            exit;
        }
        
        $cart = $cartController->getCart(); // Maintenant cette méthode est accessible car elle est publique
        
        // Récupérer les informations de l'utilisateur
        $user = $_SESSION['user'];
        
        // Passer les variables à la vue
        $pageTitle = "Paiement";
        
        require_once 'views/templates/header.php';
        require_once 'views/checkout/index.php';
        require_once 'views/templates/footer.php';
    }
    
    // Traite la commande
    public function process() {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "/auth/login");
            exit;
        }
        
        // Vérifier si le panier est vide
        if (empty($_SESSION['cart'])) {
            $_SESSION['error_message'] = "Votre panier est vide.";
            header("Location: " . BASE_URL . "/cart");
            exit;
        }
        
        // Vérifier la méthode de la requête
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/checkout");
            exit;
        }
        
        // Récupérer le panier
        require_once 'controllers/CartController.php';
        $cartController = new CartController();
        $cart = $cartController->getCart();
        
        // Valider le panier avant de traiter la commande
        $validation = $cartController->validateForCheckout();
        if (!$validation['valid']) {
            foreach ($validation['errors'] as $error) {
                $_SESSION['error_message'] = $error;
            }
            header("Location: " . BASE_URL . "/cart");
            exit;
        }
        
        // Récupérer les données du formulaire
        $shippingAddress = isset($_POST['same_address']) ? $_POST['billing_address'] : $_POST['shipping_address'];
        $shippingCity = isset($_POST['same_address']) ? $_POST['billing_city'] : $_POST['shipping_city'];
        $shippingPostalCode = isset($_POST['same_address']) ? $_POST['billing_postal_code'] : $_POST['shipping_postal_code'];
        $shippingCountry = isset($_POST['same_address']) ? $_POST['billing_country'] : $_POST['shipping_country'];
        
        $paymentMethod = $_POST['payment_method'] ?? '';
        
        // Vérifier la méthode de paiement
        if (!in_array($paymentMethod, ['credit_card', 'paypal'])) {
            $_SESSION['error_message'] = "Méthode de paiement invalide.";
            header("Location: " . BASE_URL . "/checkout");
            exit;
        }
        
        // Valider le paiement (simulé pour cet exemple)
        $paymentSuccessful = $this->processPayment($cart['total'], $paymentMethod);
        
        if (!$paymentSuccessful) {
            $_SESSION['error_message'] = "Le paiement a échoué. Veuillez réessayer.";
            header("Location: " . BASE_URL . "/checkout");
            exit;
        }
        
        // Créer la commande
        try {
            // Générer un numéro de commande unique
            $orderNumber = 'ORD' . date('YmdHis') . rand(100, 999);
            
            // Insérer la commande dans la base de données
            $query = "INSERT INTO orders (user_id, order_number, total_amount, status, payment_method, shipping_address, created_at) 
                     VALUES (?, ?, ?, 'pending', ?, ?, NOW())";
            $stmt = $this->db->prepare($query);
            
            $shippingAddressComplete = $shippingAddress . ", " . $shippingPostalCode . " " . $shippingCity . ", " . $shippingCountry;
            
            $stmt->execute([
                $_SESSION['user']['id'],
                $orderNumber,
                $cart['total'],
                $paymentMethod,
                $shippingAddressComplete
            ]);
            
            $orderId = $this->db->lastInsertId();
            
            // Insérer les articles de la commande
            foreach ($cart['items'] as $item) {
                $query = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$orderId, $item['id'], $item['quantity'], $item['price']]);
                
                // Mettre à jour le stock
                $query = "UPDATE products SET stock = stock - ? WHERE id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$item['quantity'], $item['id']]);
            }
            
            // Créer la facture
            require_once 'controllers/InvoiceController.php';
            $invoiceController = new InvoiceController();
            $invoiceController->generate($orderId);
            
            // Vider le panier
            $_SESSION['cart'] = [];
            
            // Rediriger vers la page de confirmation
            header("Location: " . BASE_URL . "/checkout/confirmation/" . $orderId);
            exit;
            
        } catch (PDOException $e) {
            error_log("Erreur lors de la création de la commande: " . $e->getMessage());
            $_SESSION['error_message'] = "Une erreur est survenue lors de la création de votre commande. Veuillez réessayer.";
            header("Location: " . BASE_URL . "/checkout");
            exit;
        }
    }
    
    // Affiche la page de confirmation de commande
    public function confirmation($orderId) {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "/auth/login");
            exit;
        }
        
        // Récupérer les détails de la commande
        $order = $this->getOrderById($orderId);
        
        // Vérifier si la commande appartient à l'utilisateur
        if (!$order || $order['user_id'] != $_SESSION['user']['id']) {
            header("Location: " . BASE_URL . "/order/history");
            exit;
        }
        
        $pageTitle = "Confirmation de commande";
        
        require_once 'views/templates/header.php';
        require_once 'views/checkout/confirmation.php';
        require_once 'views/templates/footer.php';
    }
    
    // Traitement du paiement (simulé pour l'exemple)
    private function processPayment($amount, $method) {
        // Simuler un processus de paiement
        // Dans un environnement de production, cela serait connecté à une passerelle de paiement
        return true; // Toujours réussir pour l'exemple
    }
    
    // Récupère une commande par son ID
    private function getOrderById($orderId) {
        try {
            $query = "SELECT o.*, u.first_name, u.last_name, u.email 
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
}
?>
