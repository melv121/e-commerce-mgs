<?php
class CartController {
    private $db;
    
    public function __construct() {
        require_once 'config/database.php';
        $database = new Database();
        $this->db = $database->getConnection();
        $this->initializeCart();
    }

    private function initializeCart() {
        try {
            $sessionId = session_id();
            $userId = isset($_SESSION['user']) ? $_SESSION['user']['id'] : null;

            // Vérifier si la table existe
            $tables = $this->db->query("SHOW TABLES LIKE 'cart'")->fetchAll();
            if (empty($tables)) {
                // Si la table n'existe pas, on charge et exécute le script SQL
                $sql = file_get_contents(__DIR__ . '/../sql/cart_tables.sql');
                $this->db->exec($sql);
            }

            // Vérifier si un panier existe déjà
            $query = "SELECT id FROM cart WHERE " . ($userId ? "user_id = ?" : "session_id = ?");
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId ?? $sessionId]);
            $cart = $stmt->fetch(PDO::FETCH_ASSOC);

            // Si aucun panier n'existe, en créer un nouveau
            if (!$cart) {
                $query = "INSERT INTO cart (user_id, session_id) VALUES (?, ?)";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$userId, $sessionId]);
            }
        } catch (PDOException $e) {
            error_log("Erreur lors de l'initialisation du panier: " . $e->getMessage());
            // En mode développement, vous pouvez décommenter la ligne suivante
            // throw $e;
        }
    }

    public function index() {
        $pageTitle = "Mon panier";
        $cartItems = $this->getCartItems();
        $total = $this->calculateTotal($cartItems);
        
        require_once 'views/templates/header.php';
        require_once 'views/cart/index.php';
        require_once 'views/templates/footer.php';
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'] ?? 0;
            $quantity = $_POST['quantity'] ?? 1;
            
            $this->addToCart($productId, $quantity);
            
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                echo json_encode(['success' => true]);
                exit;
            }
            
            header('Location: ' . BASE_URL . '/cart');
            exit;
        }
    }
    
    // Ajouter la méthode manquante pour l'ajout au panier
    private function addToCart($productId, $quantity) {
        try {
            // Vérifier si le produit existe
            $query = "SELECT id FROM products WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$productId]);
            
            if (!$stmt->fetch()) {
                return false; // Le produit n'existe pas
            }
            
            // Récupérer le panier actuel
            $userId = isset($_SESSION['user']) ? $_SESSION['user']['id'] : null;
            $sessionId = session_id();
            
            $query = "SELECT id FROM cart WHERE " . ($userId ? "user_id = ?" : "session_id = ?");
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId ?? $sessionId]);
            $cart = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$cart) {
                // Créer un nouveau panier si nécessaire
                $query = "INSERT INTO cart (user_id, session_id) VALUES (?, ?)";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$userId, $sessionId]);
                $cartId = $this->db->lastInsertId();
            } else {
                $cartId = $cart['id'];
            }
            
            // Vérifier si le produit est déjà dans le panier
            $query = "SELECT id, quantity FROM cart_items WHERE cart_id = ? AND product_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$cartId, $productId]);
            $cartItem = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($cartItem) {
                // Mettre à jour la quantité si le produit est déjà dans le panier
                $newQuantity = $cartItem['quantity'] + $quantity;
                $query = "UPDATE cart_items SET quantity = ? WHERE id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$newQuantity, $cartItem['id']]);
            } else {
                // Ajouter le produit au panier
                $query = "INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$cartId, $productId, $quantity]);
            }
            
            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de l'ajout au panier: " . $e->getMessage());
            return false;
        }
    }

    // Ajouter des méthodes pour mettre à jour et supprimer des éléments du panier
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $itemId = $data['itemId'] ?? 0;
            $quantity = $data['quantity'] ?? 1;
            
            $success = $this->updateCartItem($itemId, $quantity);
            
            header('Content-Type: application/json');
            echo json_encode(['success' => $success]);
            exit;
        }
    }

    public function remove() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $itemId = $data['itemId'] ?? 0;
            
            $success = $this->removeCartItem($itemId);
            
            header('Content-Type: application/json');
            echo json_encode(['success' => $success]);
            exit;
        }
    }

    private function updateCartItem($itemId, $quantity) {
        try {
            $query = "UPDATE cart_items SET quantity = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$quantity, $itemId]);
            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour du panier: " . $e->getMessage());
            return false;
        }
    }

    private function removeCartItem($itemId) {
        try {
            $query = "DELETE FROM cart_items WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$itemId]);
            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression d'un article du panier: " . $e->getMessage());
            return false;
        }
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
            // En cas d'erreur, retourner un tableau vide
            error_log("Erreur lors de la récupération du panier: " . $e->getMessage());
            return [];
        }
    }

    private function calculateTotal($items) {
        return array_reduce($items, function($total, $item) {
            return $total + ($item['price'] * $item['quantity']);
        }, 0);
    }
}
