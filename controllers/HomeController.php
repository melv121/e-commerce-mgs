<?php
class HomeController {
    private $db;
    
    public function __construct() {
        // Initialiser la connexion à la base de données
        require_once 'config/database.php';
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function index() {
        // Titre de la page
        $pageTitle = "MGS Store - Accueil";
        
        // Récupération des produits en vedette depuis la base de données
        $featuredProducts = $this->getFeaturedProducts();
        
        // Charger la vue
        require_once 'views/templates/header.php';
        require_once 'views/home/index.php';
        require_once 'views/templates/footer.php';
    }
    
    private function getFeaturedProducts() {
        try {
            // Récupérer les produits récemment ajoutés et marqués comme "featured"
            $query = "SELECT * FROM products WHERE featured = 1 ORDER BY created_at DESC LIMIT 4";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Ajouter des champs supplémentaires si nécessaires pour la compatibilité avec la vue
            foreach ($products as &$product) {
                // Si l'image n'est pas définie, utiliser une image par défaut
                if (!isset($product['image']) || empty($product['image'])) {
                    $product['image'] = 'assets/images/products/default.jpg';
                }
                
                // Ajouter l'attribut "new" pour les produits récents (moins de 7 jours)
                $dateCreation = new DateTime($product['created_at']);
                $now = new DateTime();
                $interval = $dateCreation->diff($now);
                $product['new'] = ($interval->days < 7);
            }
            
            return $products;
        } catch (PDOException $e) {
            // En cas d'erreur, retourner des produits par défaut
            error_log("Erreur lors de la récupération des produits vedette: " . $e->getMessage());
            return [
                [
                    'id' => 1,
                    'name' => 'T-shirt Street Black',
                    'price' => 39.99,
                    'image' => 'assets/images/product1.jpg',
                    'description' => 'T-shirt style urbain en coton premium.'
                ],
                [
                    'id' => 2,
                    'name' => 'Hoodie Urban',
                    'price' => 69.99,
                    'image' => 'assets/images/product2.jpg',
                    'description' => 'Sweat à capuche confortable pour un style décontracté.'
                ],
                [
                    'id' => 3,
                    'name' => 'Sneakers MGS Classic',
                    'price' => 89.99,
                    'image' => 'assets/images/product3.jpg',
                    'description' => 'Baskets tendance pour compléter votre look.'
                ],
                [
                    'id' => 4,
                    'name' => 'Casquette Logo MGS',
                    'price' => 29.99,
                    'image' => 'assets/images/product4.jpg',
                    'description' => 'Casquette avec logo brodé MGS Store.'
                ]
            ];
        }
    }
}
?>
