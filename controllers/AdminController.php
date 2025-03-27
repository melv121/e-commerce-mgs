<?php
class AdminController {
    private $db;
    
    public function __construct() {
        require_once 'config/database.php';
        $database = new Database();
        $this->db = $database->getConnection();
        
        // Vérifier si l'utilisateur est connecté et est un administrateur
        if (!$this->isAdmin()) {
            // Ajouter un message d'erreur explicite pour faciliter le débogage
            $_SESSION['error_message'] = "Accès refusé. Vous devez être administrateur pour accéder à cette section.";
            header("Location: " . BASE_URL . "/auth/login");
            exit;
        }
    }
    
    // Vérifier si l'utilisateur est administrateur - méthode plus souple
    private function isAdmin() {
        // Vérification de base pour s'assurer que l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            return false;
        }
        
        // Vérification explicite que l'utilisateur a un rôle défini
        if (!isset($_SESSION['user']['role'])) {
            return false;
        }
        
        // Vérification du rôle d'administrateur (mode moins strict)
        return ($_SESSION['user']['role'] === 'admin' || $_SESSION['user']['role'] === 'administrator');
    }
    
    // Page d'accueil du tableau de bord admin
    public function index() {
        // Définir la page active pour le menu
        $currentPage = 'dashboard';
        $pageTitle = "Tableau de bord administrateur";
        
        // Récupérer des statistiques pour le tableau de bord
        $stats = $this->getDashboardStats();
        
        require_once 'views/admin/templates/header.php';
        require_once 'views/admin/dashboard.php';
        require_once 'views/admin/templates/footer.php';
    }
    
    // Gestion des produits
    public function products() {
        // Définir la page active pour le menu
        $currentPage = 'products';
        $pageTitle = "Gestion des produits";
        
        // Récupérer tous les produits
        $products = $this->getAllProducts();
        
        require_once 'views/admin/templates/header.php';
        require_once 'views/admin/products/index.php';
        require_once 'views/admin/templates/footer.php';
    }
    
    // Formulaire d'ajout de produit
    public function addProduct() {
        $pageTitle = "Ajouter un produit";
        
        // Récupérer toutes les catégories pour le formulaire
        $categories = $this->getAllCategories();
        
        require_once 'views/admin/templates/header.php';
        require_once 'views/admin/products/add.php';
        require_once 'views/admin/templates/footer.php';
    }
    
    // Traitement du formulaire d'ajout de produit
    public function processAddProduct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0;
            $category_id = $_POST['category_id'] ?? 1;
            $stock = $_POST['stock'] ?? 0;
            $discount = $_POST['discount'] ?? null;
            
            // Traitement de l'image
            $image = 'assets/images/products/default.jpg'; // Image par défaut
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $targetDir = "assets/images/products/";
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                
                $fileName = basename($_FILES['image']['name']);
                $targetFilePath = $targetDir . $fileName;
                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
                
                // Vérifier le format de l'image
                $allowTypes = array('jpg', 'jpeg', 'png', 'gif');
                if (in_array($fileType, $allowTypes)) {
                    // Télécharger le fichier
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                        $image = $targetFilePath;
                    }
                }
            }
            
            // Insérer le produit dans la base de données
            try {
                $query = "INSERT INTO products (name, description, price, category_id, image, stock, discount, created_at) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$name, $description, $price, $category_id, $image, $stock, $discount]);
                
                $_SESSION['admin_success'] = "Le produit a été ajouté avec succès.";
                header("Location: " . BASE_URL . "/admin/products");
                exit;
            } catch (PDOException $e) {
                $_SESSION['admin_error'] = "Erreur lors de l'ajout du produit: " . $e->getMessage();
                header("Location: " . BASE_URL . "/admin/addProduct");
                exit;
            }
        } else {
            header("Location: " . BASE_URL . "/admin/addProduct");
            exit;
        }
    }
    
    // Formulaire de modification de produit
    public function editProduct($id) {
        $pageTitle = "Modifier un produit";
        
        // Récupérer le produit
        $product = $this->getProductById($id);
        
        if (!$product) {
            $_SESSION['admin_error'] = "Produit non trouvé.";
            header("Location: " . BASE_URL . "/admin/products");
            exit;
        }
        
        // Récupérer toutes les catégories pour le formulaire
        $categories = $this->getAllCategories();
        
        require_once 'views/admin/templates/header.php';
        require_once 'views/admin/products/edit.php';
        require_once 'views/admin/templates/footer.php';
    }
    
    // Traitement du formulaire de modification de produit
    public function processEditProduct($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0;
            $category_id = $_POST['category_id'] ?? 1;
            $stock = $_POST['stock'] ?? 0;
            $discount = $_POST['discount'] ?? null;
            
            // Récupérer l'image actuelle
            $currentProduct = $this->getProductById($id);
            $image = $currentProduct['image'];
            
            // Traitement de la nouvelle image si elle existe
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $targetDir = "assets/images/products/";
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                
                $fileName = basename($_FILES['image']['name']);
                $targetFilePath = $targetDir . $fileName;
                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
                
                // Vérifier le format de l'image
                $allowTypes = array('jpg', 'jpeg', 'png', 'gif');
                if (in_array($fileType, $allowTypes)) {
                    // Télécharger le fichier
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                        $image = $targetFilePath;
                    }
                }
            }
            
            // Mettre à jour le produit dans la base de données
            try {
                $query = "UPDATE products SET 
                          name = ?, 
                          description = ?, 
                          price = ?, 
                          category_id = ?, 
                          image = ?, 
                          stock = ?, 
                          discount = ?,
                          updated_at = NOW()
                          WHERE id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$name, $description, $price, $category_id, $image, $stock, $discount, $id]);
                
                $_SESSION['admin_success'] = "Le produit a été mis à jour avec succès.";
                header("Location: " . BASE_URL . "/admin/products");
                exit;
            } catch (PDOException $e) {
                $_SESSION['admin_error'] = "Erreur lors de la mise à jour du produit: " . $e->getMessage();
                header("Location: " . BASE_URL . "/admin/editProduct/" . $id);
                exit;
            }
        } else {
            header("Location: " . BASE_URL . "/admin/editProduct/" . $id);
            exit;
        }
    }
    
    // Supprimer un produit
    public function deleteProduct($id) {
        try {
            $query = "DELETE FROM products WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id]);
            
            $_SESSION['admin_success'] = "Le produit a été supprimé avec succès.";
        } catch (PDOException $e) {
            $_SESSION['admin_error'] = "Erreur lors de la suppression du produit: " . $e->getMessage();
        }
        
        header("Location: " . BASE_URL . "/admin/products");
        exit;
    }
    
    // Gestion des commandes
    public function orders() {
        // Définir la page active pour le menu
        $currentPage = 'orders';
        $pageTitle = "Gestion des commandes";
        
        // Récupérer toutes les commandes
        try {
            $orders = $this->getAllOrders();
        } catch (Exception $e) {
            $_SESSION['admin_error'] = "Erreur lors de la récupération des commandes: " . $e->getMessage();
            $orders = [];
        }
        
        require_once 'views/admin/templates/header.php';
        require_once 'views/admin/orders/index.php';
        require_once 'views/admin/templates/footer.php';
    }
    
    // Détails d'une commande
    public function orderDetail($id) {
        $pageTitle = "Détail de la commande";
        
        // Récupérer la commande
        $order = $this->getOrderById($id);
        
        if (!$order) {
            $_SESSION['admin_error'] = "Commande non trouvée.";
            header("Location: " . BASE_URL . "/admin/orders");
            exit;
        }
        
        require_once 'views/admin/templates/header.php';
        require_once 'views/admin/orders/detail.php';
        require_once 'views/admin/templates/footer.php';
    }
    
    // Mettre à jour le statut d'une commande
    public function updateOrderStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = $_POST['status'] ?? '';
            
            try {
                $query = "UPDATE orders SET status = ? WHERE id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$status, $id]);
                
                $_SESSION['admin_success'] = "Le statut de la commande a été mis à jour avec succès.";
            } catch (PDOException $e) {
                $_SESSION['admin_error'] = "Erreur lors de la mise à jour du statut: " . $e->getMessage();
            }
            
            header("Location: " . BASE_URL . "/admin/orderDetail/" . $id);
            exit;
        } else {
            header("Location: " . BASE_URL . "/admin/orders");
            exit;
        }
    }
    
    // Gestion des utilisateurs
    public function users() {
        $pageTitle = "Gestion des utilisateurs";
        
        // Récupérer tous les utilisateurs
        $users = $this->getAllUsers();
        
        require_once 'views/admin/templates/header.php';
        require_once 'views/admin/users/index.php';
        require_once 'views/admin/templates/footer.php';
    }
    
    // Détails d'un utilisateur
    public function userDetail($id) {
        $pageTitle = "Détail de l'utilisateur";
        
        // Récupérer l'utilisateur
        $user = $this->getUserById($id);
        
        if (!$user) {
            $_SESSION['admin_error'] = "Utilisateur non trouvé.";
            header("Location: " . BASE_URL . "/admin/users");
            exit;
        }
        
        // Récupérer les commandes de l'utilisateur
        $orders = $this->getUserOrders($id);
        
        require_once 'views/admin/templates/header.php';
        require_once 'views/admin/users/detail.php';
        require_once 'views/admin/templates/footer.php';
    }
    
    // Mettre à jour le rôle d'un utilisateur
    public function updateUserRole($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $role = $_POST['role'] ?? '';
            
            try {
                $query = "UPDATE users SET role = ? WHERE id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$role, $id]);
                
                $_SESSION['admin_success'] = "Le rôle de l'utilisateur a été mis à jour avec succès.";
            } catch (PDOException $e) {
                $_SESSION['admin_error'] = "Erreur lors de la mise à jour du rôle: " . $e->getMessage();
            }
            
            header("Location: " . BASE_URL . "/admin/userDetail/" . $id);
            exit;
        } else {
            header("Location: " . BASE_URL . "/admin/users");
            exit;
        }
    }
    
    // Récupérer des statistiques pour le tableau de bord
    private function getDashboardStats() {
        $stats = [];
        
        try {
            // Nombre total de produits
            $query = "SELECT COUNT(*) FROM products";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stats['total_products'] = $stmt->fetchColumn();
            
            // Nombre total de commandes
            $query = "SELECT COUNT(*) FROM orders";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stats['total_orders'] = $stmt->fetchColumn();
            
            // Nombre total d'utilisateurs
            $query = "SELECT COUNT(*) FROM users";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stats['total_users'] = $stmt->fetchColumn();
            
            // Chiffre d'affaires total
            $query = "SELECT SUM(total_amount) FROM orders WHERE status != 'cancelled'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stats['total_revenue'] = $stmt->fetchColumn() ?? 0;
            
            // Commandes récentes
            $query = "SELECT o.*, u.username, u.email 
                      FROM orders o 
                      JOIN users u ON o.user_id = u.id 
                      ORDER BY o.created_at DESC 
                      LIMIT 5";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stats['recent_orders'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Utilisateurs récents
            $query = "SELECT * FROM users ORDER BY created_at DESC LIMIT 5";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stats['recent_users'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des statistiques: " . $e->getMessage());
        }
        
        return $stats;
    }
    
    // Récupérer tous les produits
    private function getAllProducts() {
        try {
            $query = "SELECT p.*, c.name as category_name 
                      FROM products p 
                      LEFT JOIN categories c ON p.category_id = c.id 
                      ORDER BY p.id DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des produits: " . $e->getMessage());
            return [];
        }
    }
    
    // Récupérer un produit par son ID
    private function getProductById($id) {
        try {
            $query = "SELECT p.*, c.name as category_name 
                      FROM products p 
                      LEFT JOIN categories c ON p.category_id = c.id 
                      WHERE p.id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération du produit: " . $e->getMessage());
            return false;
        }
    }
    
    // Récupérer toutes les catégories
    private function getAllCategories() {
        try {
            $query = "SELECT * FROM categories ORDER BY name";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des catégories: " . $e->getMessage());
            return [];
        }
    }
    
    // Récupérer toutes les commandes - méthode plus robuste
    private function getAllOrders() {
        try {
            // Vérifions d'abord si la table existe
            $tableCheck = $this->db->query("SHOW TABLES LIKE 'orders'");
            if ($tableCheck->rowCount() == 0) {
                throw new Exception("La table 'orders' n'existe pas dans la base de données.");
            }
            
            // Vérifier la structure de la table
            $columnCheck = $this->db->query("SHOW COLUMNS FROM orders LIKE 'user_id'");
            if ($columnCheck->rowCount() == 0) {
                throw new Exception("La colonne 'user_id' n'existe pas dans la table 'orders'.");
            }
            
            // Requête avec une gestion plus robuste des erreurs et des jointures
            $query = "SELECT o.*, 
                      COALESCE(u.username, 'Utilisateur inconnu') as username, 
                      COALESCE(u.email, 'Email inconnu') as email 
                      FROM orders o 
                      LEFT JOIN users u ON o.user_id = u.id 
                      ORDER BY o.created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur SQL lors de la récupération des commandes: " . $e->getMessage());
            throw new Exception("Erreur de base de données: " . $e->getMessage());
        }
    }
    
    // Récupérer une commande par son ID
    private function getOrderById($id) {
        try {
            $query = "SELECT o.*, u.username, u.first_name, u.last_name, u.email, u.address, u.city, u.postal_code, u.country, u.phone 
                      FROM orders o 
                      JOIN users u ON o.user_id = u.id 
                      WHERE o.id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($order) {
                // Récupérer les articles de la commande
                $query = "SELECT oi.*, p.name, p.sku, p.image 
                          FROM order_items oi 
                          JOIN products p ON oi.product_id = p.id 
                          WHERE oi.order_id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$id]);
                $order['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            
            return $order;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de la commande: " . $e->getMessage());
            return false;
        }
    }
    
    // Récupérer tous les utilisateurs
    private function getAllUsers() {
        try {
            $query = "SELECT * FROM users ORDER BY created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des utilisateurs: " . $e->getMessage());
            return [];
        }
    }
    
    // Récupérer un utilisateur par son ID
    private function getUserById($id) {
        try {
            $query = "SELECT * FROM users WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de l'utilisateur: " . $e->getMessage());
            return false;
        }
    }
    
    // Récupérer les commandes d'un utilisateur
    private function getUserOrders($userId) {
        try {
            $query = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des commandes de l'utilisateur: " . $e->getMessage());
            return [];
        }
    }
}
?>
