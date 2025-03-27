<?php
class CartController {
    private $db;
    
    public function __construct() {
        require_once 'config/database.php';
        $database = new Database();
        $this->db = $database->getConnection();
        
        // Initialiser le panier s'il n'existe pas
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        // Pour le débogage - ajoutez ceci temporairement
        error_log('SESSION CART: ' . print_r($_SESSION['cart'], true));
    }
    
    // Affiche le contenu du panier
    public function index() {
        $pageTitle = "Votre panier";
        $cart = $this->getCart();
        
        require_once 'views/templates/header.php';
        require_once 'views/cart/index.php';
        require_once 'views/templates/footer.php';
    }
    
    // Ajoute un produit au panier
    public function add($productId) {
        // Log pour le débogage
        error_log('Adding product ID: ' . $productId . ' to cart');
        
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        
        if ($quantity <= 0) {
            $quantity = 1;
        }
        
        // Récupérer les informations du produit
        $product = $this->getProductById($productId);
        
        if (!$product) {
            // Produit non trouvé
            $_SESSION['error_message'] = "Produit non trouvé.";
            header("Location: " . BASE_URL . "/cart");
            exit;
        }
        
        // Vérifier le stock disponible
        if ($product['stock'] < $quantity) {
            $_SESSION['error_message'] = "Stock insuffisant. Seulement " . $product['stock'] . " disponible(s).";
            header("Location: " . BASE_URL . "/product/detail/" . $productId);
            exit;
        }
        
        // Calculer le prix avec remise s'il y a lieu
        $price = $product['price'];
        if (!empty($product['discount'])) {
            $price = $price * (1 - ($product['discount'] / 100));
        }
        
        // Vérifier si le produit est déjà dans le panier
        $found = false;
        
        // S'assurer que $_SESSION['cart'] est un tableau
        if (!is_array($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $productId) {
                // Mettre à jour la quantité
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            // Ajouter le produit au panier
            $_SESSION['cart'][] = [
                'id' => $productId,
                'name' => $product['name'],
                'price' => $price,
                'quantity' => $quantity,
                'image' => $product['image'],
                'stock' => $product['stock'] // Ajouter cette information pour les vérifications ultérieures
            ];
        }
        
        // Message de confirmation
        $_SESSION['success_message'] = "Produit ajouté au panier avec succès!";
        
        // Log pour le débogage
        error_log('Updated cart: ' . print_r($_SESSION['cart'], true));
        
        // Redirection en fonction du contexte (AJAX ou non)
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            // Requête AJAX - retourner un JSON
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'Produit ajouté au panier',
                'cart_count' => $this->getCartItemCount(),
                'cart' => $_SESSION['cart'] // Pour le débogage
            ]);
            exit;
        } else {
            // Requête normale - rediriger
            header("Location: " . BASE_URL . "/cart");
            exit;
        }
    }
    
    // Met à jour la quantité d'un produit dans le panier
    public function update($productId) {
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        
        if ($quantity <= 0) {
            // Si la quantité est 0 ou négative, supprimer le produit
            return $this->remove($productId);
        }
        
        // Vérifier le stock disponible
        $product = $this->getProductById($productId);
        
        if (!$product) {
            $_SESSION['error_message'] = "Produit non trouvé.";
            header("Location: " . BASE_URL . "/cart");
            exit;
        }
        
        if ($product['stock'] < $quantity) {
            $_SESSION['error_message'] = "Stock insuffisant. Seulement " . $product['stock'] . " disponible(s).";
            header("Location: " . BASE_URL . "/cart");
            exit;
        }
        
        // Mettre à jour la quantité
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $productId) {
                $item['quantity'] = $quantity;
                break;
            }
        }
        
        // Message de confirmation
        $_SESSION['success_message'] = "Panier mis à jour avec succès!";
        
        // Redirection
        header("Location: " . BASE_URL . "/cart");
        exit;
    }
    
    // Supprime un produit du panier
    public function remove($productId) {
        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
            header("Location: " . BASE_URL . "/cart");
            exit;
        }
        
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] == $productId) {
                unset($_SESSION['cart'][$key]);
                break;
            }
        }
        
        // Réindexer le tableau
        $_SESSION['cart'] = array_values($_SESSION['cart']);
        
        // Message de confirmation
        $_SESSION['success_message'] = "Produit retiré du panier avec succès!";
        
        // Redirection
        header("Location: " . BASE_URL . "/cart");
        exit;
    }
    
    // Vide le panier
    public function clear() {
        $_SESSION['cart'] = [];
        
        // Message de confirmation
        $_SESSION['success_message'] = "Votre panier a été vidé.";
        
        // Redirection
        header("Location: " . BASE_URL . "/cart");
        exit;
    }
    
    // Récupère le nombre d'articles dans le panier
    public function getCartItemCount() {
        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            return 0;
        }
        
        $count = 0;
        
        foreach ($_SESSION['cart'] as $item) {
            $count += $item['quantity'];
        }
        
        return $count;
    }
    
    // Récupère le contenu complet du panier
    // Changé de private à public pour permettre l'accès depuis d'autres contrôleurs
    public function getCart() {
        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        $cart = [
            'items' => $_SESSION['cart'],
            'subtotal' => 0,
            'shipping' => 0,
            'total' => 0
        ];
        
        // Calculer le sous-total
        foreach ($cart['items'] as $item) {
            $cart['subtotal'] += $item['price'] * $item['quantity'];
        }
        
        // Calculer les frais d'expédition (par exemple, gratuit au-dessus de 50€)
        $cart['shipping'] = ($cart['subtotal'] >= 50) ? 0 : 4.99;
        
        // Calculer le total
        $cart['total'] = $cart['subtotal'] + $cart['shipping'];
        
        return $cart;
    }
    
    // Récupère les informations d'un produit
    private function getProductById($productId) {
        try {
            $query = "SELECT * FROM products WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$productId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération du produit: " . $e->getMessage());
            return false;
        }
    }

    // Vérifie si le panier est valide avant le checkout
    public function validateForCheckout() {
        $cart = $this->getCart();
        $isValid = true;
        $errorMessages = [];
        
        // Vérifier si le panier est vide
        if (empty($cart['items'])) {
            return [
                'valid' => false,
                'errors' => ["Votre panier est vide. Ajoutez des produits avant de passer à la caisse."]
            ];
        }
        
        // Vérifier les stocks pour chaque produit
        foreach ($cart['items'] as $item) {
            $currentProduct = $this->getProductById($item['id']);
            
            if (!$currentProduct) {
                $isValid = false;
                $errorMessages[] = "Un produit dans votre panier n'est plus disponible.";
                continue;
            }
            
            if ($currentProduct['stock'] < $item['quantity']) {
                $isValid = false;
                $errorMessages[] = "Stock insuffisant pour '{$item['name']}'. Seulement {$currentProduct['stock']} disponible(s).";
            }
        }
        
        return [
            'valid' => $isValid,
            'errors' => $errorMessages
        ];
    }
}
?>