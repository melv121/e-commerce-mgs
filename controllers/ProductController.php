<?php
class ProductController {
    
    private $db;
    
    public function __construct() {
        // Initialiser la connexion à la base de données
        require_once 'config/database.php';
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function category($category = null, $subcategory = null) {
        $pageTitle = "Produits";
        $filters = [];
        $products = [];
        
        // Déterminer la catégorie principale
        if ($category) {
            switch ($category) {
                case 'homme':
                    $pageTitle = "Collection Homme";
                    break;
                case 'femme':
                    $pageTitle = "Collection Femme";
                    break;
                case 'enfant':
                    $pageTitle = "Collection Enfant";
                    break;
                default:
                    $pageTitle = "Tous les produits";
            }
            
            // Ajouter le sous-titre pour la sous-catégorie
            if ($subcategory) {
                switch ($subcategory) {
                    case 'chaussures':
                        $pageTitle .= " - Chaussures";
                        break;
                    case 'vetements':
                        $pageTitle .= " - Vêtements";
                        break;
                    case 'accessoires':
                        $pageTitle .= " - Accessoires";
                        break;
                }
            }
        }
        
        // Récupérer les produits basés sur la catégorie et sous-catégorie
        $products = $this->getProductsByCategoryAndSubcategory($category, $subcategory);
        
        // Récupérer les filtres disponibles
        $filters = $this->getAvailableFilters($category, $subcategory);
        
        // Charger la vue
        require_once 'views/templates/header.php';
        require_once 'views/products/category.php';
        require_once 'views/templates/footer.php';
    }
    
    public function detail($id) {
        $pageTitle = "Détail du produit";
        
        // Utiliser le modèle Product pour récupérer les détails
        require_once 'models/Product.php';
        $productModel = new Product($this->db);
        $product = $productModel->getById($id);
        
        if (!$product) {
            // Produit non trouvé
            header("Location: " . BASE_URL . "/404");
            exit;
        }
        
        $pageTitle = $product['name'];
        
        // Définir l'image par défaut si nécessaire
        if (!isset($product['image']) || empty($product['image'])) {
            $product['image'] = $this->getProductImageByType($product['name'], $product['category_name']);
        }
        
        // Ajouter les attributs comme la note et la marque
        $product['rating'] = isset($product['rating']) ? $product['rating'] : rand(3, 5);
        $product['brand'] = $this->extractBrandFromName($product['name']);
        
        // Récupérer les produits similaires
        $similarProducts = $this->getSimilarProducts($product['category_id'], $id);
        
        // Charger la vue
        require_once 'views/templates/header.php';
        require_once 'views/products/detail.php';
        require_once 'views/templates/footer.php';
    }
    
    public function nouveautes() {
        $pageTitle = "Nouveautés";
        $products = $this->getNewProducts();
        
        require_once 'views/templates/header.php';
        require_once 'views/products/nouveautes.php';
        require_once 'views/templates/footer.php';
    }

    public function promotions() {
        $pageTitle = "Promotions";
        $products = $this->getPromotionalProducts();
        
        require_once 'views/templates/header.php';
        require_once 'views/products/promotions.php';
        require_once 'views/templates/footer.php';
    }

    private function getProductsByCategoryAndSubcategory($category = null, $subcategory = null) {
        try {
            // Convertir le nom de catégorie en ID de catégorie
            $categoryId = $this->getCategoryIdByName($category);
            
            // Construire la requête de base
            $query = "SELECT p.*, c.name as category_name 
                      FROM products p 
                      LEFT JOIN categories c ON p.category_id = c.id 
                      WHERE 1=1";
            $params = [];
            
            // Ajouter le filtre de catégorie si spécifié
            if ($categoryId) {
                $query .= " AND p.category_id = ?";
                $params[] = $categoryId;
            }
            
            // Ajouter le filtre de sous-catégorie si spécifié
            if ($subcategory) {
                switch ($subcategory) {
                    case 'chaussures':
                        $query .= " AND (p.name LIKE '%chaussure%' OR p.name LIKE '%basket%')";
                        break;
                    case 'vetements':
                        $query .= " AND (p.name LIKE '%t-shirt%' OR p.name LIKE '%pantalon%' OR p.name LIKE '%pull%' OR p.name LIKE '%sweat%' OR p.name LIKE '%veste%')";
                        break;
                    case 'accessoires':
                        $query .= " AND (p.name LIKE '%sac%' OR p.name LIKE '%casquette%' OR p.name LIKE '%bonnet%' OR p.name LIKE '%gant%')";
                        break;
                }
            }
            
            $query .= " ORDER BY p.name ASC";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Si aucun produit n'est trouvé, utiliser des données simulées
            if (empty($products)) {
                return $this->getSimulatedProducts($category, $subcategory);
            }
            
            // Récupérer les informations sur les produits en promotion
            $promoProducts = $this->getPromotionalProductsInfo();
            
            // Ajouter des champs supplémentaires pour la compatibilité avec la vue
            foreach ($products as &$product) {
                $product['brand'] = $this->extractBrandFromName($product['name']);
                $product['rating'] = rand(3, 5); // Attribution d'une note aléatoire entre 3 et 5
                
                // Assurer que chaque produit a une image appropriée
                if (!isset($product['image']) || empty($product['image']) || strpos($product['image'], 'default') !== false) {
                    $product['image'] = $this->getProductImageByType($product['name'], $category);
                }
                
                // Vérifier si le produit est dans la liste des promotions
                $productId = $product['id'];
                if (isset($promoProducts[$productId])) {
                    $product['promotion'] = true;
                    $product['discount'] = $promoProducts[$productId]['discount'];
                    $product['sale_price'] = $promoProducts[$productId]['sale_price'];
                }
            }
            
            return $products;
        } catch (PDOException $e) {
            // En cas d'erreur, retourner un tableau vide et enregistrer l'erreur
            error_log("Erreur lors de la récupération des produits: " . $e->getMessage());
            return $this->getSimulatedProducts($category, $subcategory);
        }
    }

    // Méthode pour générer des produits simulés si la base de données ne retourne rien
    private function getSimulatedProducts($category, $subcategory) {
        $products = [];
        $brands = ['Nike', 'Adidas', 'Puma', 'Reebok', 'Under Armour', 'Teddy Smith'];
        $categories = ['homme', 'femme', 'enfant'];
        
        // Déterminer quelle catégorie utiliser
        $catToUse = $category ?? $categories[array_rand($categories)];
        
        // Générer entre 8 et 16 produits
        $count = rand(8, 16);
        
        for ($i = 1; $i <= $count; $i++) {
            $brand = $brands[array_rand($brands)];
            $price = rand(30, 150);
            $isPromotion = (rand(1, 4) === 1); // 25% de chance d'être en promo
            $discount = rand(10, 30);
            $salePrice = $isPromotion ? round($price * (1 - $discount / 100), 2) : null;
            
            $productType = '';
            
            if ($subcategory === 'chaussures' || rand(1, 3) === 1) {
                $productType = 'Basket';
            } elseif ($subcategory === 'vetements' || rand(1, 2) === 1) {
                $types = ['T-shirt', 'Pantalon', 'Pull', 'Sweat', 'Veste'];
                $productType = $types[array_rand($types)];
            } else {
                $types = ['Sac', 'Casquette', 'Bonnet', 'Gants'];
                $productType = $types[array_rand($types)];
            }
            
            $name = "$brand $productType " . ucfirst($catToUse) . " #$i";
            
            $products[] = [
                'id' => $i,
                'name' => $name,
                'price' => $price,
                'sale_price' => $salePrice,
                'image' => $this->getProductImageByType($name, $catToUse),
                'category_name' => ucfirst($catToUse),
                'category_id' => array_search($catToUse, $categories) + 1,
                'brand' => $brand,
                'rating' => rand(3, 5),
                'promotion' => $isPromotion,
                'discount' => $isPromotion ? $discount : null
            ];
        }
        
        return $products;
    }
    
    private function getCategoryIdByName($categoryName) {
        if (!$categoryName) return null;
        
        try {
            $query = "SELECT id FROM categories WHERE LOWER(name) = LOWER(?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$categoryName]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result ? $result['id'] : null;
        } catch (PDOException $e) {
            return null;
        }
    }
    
    private function extractBrandFromName($name) {
        // Essayer d'extraire une marque connue du nom du produit
        $brands = ['Nike', 'Adidas', 'Puma', 'Reebok', 'Under Armour', 'Teddy Smith'];
        
        foreach ($brands as $brand) {
            if (stripos($name, $brand) !== false) {
                return $brand;
            }
        }
        
        // Par défaut, retourner la première partie du nom
        $parts = explode(' ', $name);
        return count($parts) > 0 ? $parts[0] : 'Marque';
    }

    private function getAvailableFilters($category, $subcategory) {
        // Dans une application réelle, ces filtres seraient générés dynamiquement
        // en fonction des produits disponibles dans la catégorie
        
        return [
            'price' => [
                'title' => 'Prix',
                'type' => 'range',
                'min' => 0,
                'max' => 300,
                'values' => [0, 300]
            ],
            'brand' => [
                'title' => 'Marque',
                'type' => 'checkbox',
                'options' => [
                    ['id' => 'nike', 'name' => 'Nike', 'count' => 42],
                    ['id' => 'adidas', 'name' => 'Adidas', 'count' => 36],
                    ['id' => 'puma', 'name' => 'Puma', 'count' => 28],
                    ['id' => 'reebok', 'name' => 'Reebok', 'count' => 21],
                    ['id' => 'ua', 'name' => 'Under Armour', 'count' => 19]
                ]
            ],
            'color' => [
                'title' => 'Couleur',
                'type' => 'color',
                'options' => [
                    ['id' => 'black', 'name' => 'Noir', 'code' => '#000000', 'count' => 30],
                    ['id' => 'white', 'name' => 'Blanc', 'code' => '#FFFFFF', 'count' => 25],
                    ['id' => 'red', 'name' => 'Rouge', 'code' => '#FF0000', 'count' => 18],
                    ['id' => 'blue', 'name' => 'Bleu', 'code' => '#0000FF', 'count' => 22],
                    ['id' => 'green', 'name' => 'Vert', 'code' => '#008000', 'count' => 15]
                ]
            ],
            'size' => [
                'title' => 'Taille',
                'type' => 'button',
                'options' => [
                    ['id' => 'xs', 'name' => 'XS', 'count' => 12],
                    ['id' => 's', 'name' => 'S', 'count' => 30],
                    ['id' => 'm', 'name' => 'M', 'count' => 45],
                    ['id' => 'l', 'name' => 'L', 'count' => 40],
                    ['id' => 'xl', 'name' => 'XL', 'count' => 28],
                    ['id' => 'xxl', 'name' => 'XXL', 'count' => 15]
                ]
            ],
            'feature' => [
                'title' => 'Caractéristiques',
                'type' => 'checkbox',
                'options' => [
                    ['id' => 'new', 'name' => 'Nouveauté', 'count' => 24],
                    ['id' => 'sale', 'name' => 'Promotion', 'count' => 18],
                    ['id' => 'exclusive', 'name' => 'Exclusivité', 'count' => 12],
                    ['id' => 'limited', 'name' => 'Édition limitée', 'count' => 8]
                ]
            ]
        ];
    }
    
    private function getProductById($id) {
        // Cette fonction récupérerait normalement les détails d'un produit depuis la base de données
        // Pour l'instant, on utilise des données fictives
        
        $products = $this->getProductsByCategoryAndSubcategory(null, null);
        
        foreach ($products as $product) {
            if ($product['id'] == $id) {
                return $product;
            }
        }
        
        return null;
    }
    
    private function getSimilarProducts($categoryId, $excludeId) {
        try {
            $query = "SELECT p.*, c.name as category_name 
                      FROM products p
                      LEFT JOIN categories c ON p.category_id = c.id
                      WHERE p.category_id = ? AND p.id != ?
                      ORDER BY RAND() 
                      LIMIT 4";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$categoryId, $excludeId]);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // S'assurer que chaque produit a une image appropriée et d'autres attributs nécessaires
            foreach ($products as &$product) {
                if (!isset($product['image']) || empty($product['image']) || strpos($product['image'], 'default') !== false) {
                    $product['image'] = $this->getProductImageByType($product['name'], $product['category_name']);
                }
                $product['rating'] = rand(3, 5);
                $product['brand'] = $this->extractBrandFromName($product['name']);
            }
            
            return $products;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des produits similaires: " . $e->getMessage());
            return [];
        }
    }

    private function getNewProducts() {
        try {
            $query = "SELECT * FROM products WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) ORDER BY created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Ajouter des informations supplémentaires à chaque produit
            foreach ($products as &$product) {
                // S'assurer que l'image est valide
                if (!isset($product['image']) || empty($product['image']) || strpos($product['image'], 'default') !== false) {
                    $product['image'] = $this->getProductImageByType($product['name'], $product['category_name'] ?? null);
                }
                
                // Ajouter une note aléatoire
                $product['rating'] = $product['rating'] ?? rand(3, 5);
                
                // Ajouter la marque extraite du nom du produit
                $product['brand'] = $this->extractBrandFromName($product['name']);
            }
            
            return $products;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des nouveaux produits: " . $e->getMessage());
            return [];
        }
    }

    private function getPromotionalProducts() {
        try {
            // Liste de produits spécifiques en promotion avec leurs discounts (ajustez selon vos besoins)
            $promotionProducts = [
                ["name" => "T-shirt Adidas Homme", "discount" => 15, "id" => 7],
                ["name" => "Chaussures Running Nike Enfant", "discount" => 15, "id" => 12],
                ["name" => "Chaussures Running Adidas Homme", "discount" => 22, "id" => 8],
                ["name" => "T-shirt Nike Enfant", "discount" => 24, "id" => 9],
                ["name" => "T-shirt Teddy Smith Enfant", "discount" => 18, "id" => 11]
            ];
            
            // Récupérer les informations complètes pour ces produits
            $products = [];
            
            foreach ($promotionProducts as $promo) {
                // Chercher le produit dans la base de données
                $query = "SELECT * FROM products WHERE id = ? OR name LIKE ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$promo['id'], '%' . $promo['name'] . '%']);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($product) {
                    // Si le produit existe, ajouter les informations de promotion
                    $product['promotion'] = true;
                    $product['discount'] = $promo['discount'];
                    $product['sale_price'] = $product['price'] * (1 - $promo['discount']/100);
                    
                    // S'assurer que l'image est valide
                    if (!isset($product['image']) || empty($product['image']) || strpos($product['image'], 'default') !== false) {
                        $product['image'] = $this->getProductImageByType($product['name']);
                    }
                    
                    $product['rating'] = rand(3, 5);
                    $product['brand'] = $this->extractBrandFromName($product['name']);
                    
                    $products[] = $product;
                }
            }
            
            // Si moins de 5 produits sont trouvés, ajouter des produits simulés
            if (count($products) < 5) {
                $simulatedProducts = [
                    [
                        'id' => 101,
                        'name' => 'T-shirt Adidas Homme',
                        'price' => 32.99,
                        'discount' => 15,
                        'category_name' => 'Homme'
                    ],
                    [
                        'id' => 102,
                        'name' => 'Chaussures Running Nike Enfant',
                        'price' => 69.99,
                        'discount' => 15,
                        'category_name' => 'Enfant'
                    ],
                    [
                        'id' => 103,
                        'name' => 'Chaussures Running Adidas Homme',
                        'price' => 84.99,
                        'discount' => 22,
                        'category_name' => 'Homme'
                    ],
                    [
                        'id' => 104,
                        'name' => 'T-shirt Nike Enfant',
                        'price' => 24.99,
                        'discount' => 24,
                        'category_name' => 'Enfant'
                    ],
                    [
                        'id' => 105,
                        'name' => 'T-shirt Teddy Smith Enfant',
                        'price' => 29.99,
                        'discount' => 18,
                        'category_name' => 'Enfant'
                    ]
                ];
                
                // Ajouter seulement les produits manquants
                $needed = 5 - count($products);
                for ($i = 0; $i < $needed; $i++) {
                    $simulatedProduct = $simulatedProducts[$i];
                    $simulatedProduct['promotion'] = true;
                    $simulatedProduct['sale_price'] = $simulatedProduct['price'] * (1 - $simulatedProduct['discount']/100);
                    $simulatedProduct['image'] = $this->getProductImageByType($simulatedProduct['name'], $simulatedProduct['category_name']);
                    $simulatedProduct['rating'] = rand(3, 5);
                    $simulatedProduct['brand'] = $this->extractBrandFromName($simulatedProduct['name']);
                    
                    $products[] = $simulatedProduct;
                }
            }
            
            return $products;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des produits en promotion: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère une URL d'image appropriée selon le type de produit
     */
    private function getProductImageByType($productName, $category = null) {
        $productName = strtolower($productName);
        
        // Vérifier si le dossier assets/images/products existe
        $productImagesDir = __DIR__ . '/../assets/images/products';
        if (!file_exists($productImagesDir)) {
            // Créer le répertoire s'il n'existe pas
            mkdir($productImagesDir, 0777, true);
        }
        
        // Vérifier si une image locale existe
        $localImage = $this->findLocalProductImage($productName);
        if ($localImage) {
            return $localImage;
        }
        
        // Catégorie Homme
        if ($category == 'homme' || stripos($productName, 'homme') !== false) {
            if (stripos($productName, 't-shirt') !== false || stripos($productName, 'tshirt') !== false) {
                return 'https://img01.ztat.net/article/spp-media-p1/d391a1e74fb93c4ca9233abc5b398e07/6d2a1e1f22d340c6a3613517a9a19990.jpg';
            } elseif (stripos($productName, 'jogging') !== false || stripos($productName, 'pantalon') !== false) {
                return 'https://img01.ztat.net/article/spp-media-p1/90f79c32c61a3c908e9d087e2e5f7bd6/a7ae2e20b7bc468bb633a4c8bad1116a.jpg';
            } elseif (stripos($productName, 'pull') !== false || stripos($productName, 'sweat') !== false) {
                return 'https://img01.ztat.net/article/spp-media-p1/2110f69713af364bb513fcef71f8825e/5b3fc9e2ee374087b83e137b5ddddf1d.jpg';
            } elseif (stripos($productName, 'chaussure') !== false || stripos($productName, 'basket') !== false) {
                return 'https://img01.ztat.net/article/spp-media-p1/b84642a1c2ac3c57832d6b53e9b4b246/8ab325f647de412a9d0d9235505a6362.jpg';
            } else {
                return 'https://img01.ztat.net/article/spp-media-p1/28de753a1dee35ec8d0f1423bad67d73/0a451d88c7d9422887c00469a2bbdf91.jpg';
            }
        } 
        // Catégorie Femme
        elseif ($category == 'femme' || stripos($productName, 'femme') !== false) {
            if (stripos($productName, 't-shirt') !== false || stripos($productName, 'tshirt') !== false) {
                return 'https://img01.ztat.net/article/spp-media-p1/368246237c5638d68f4cead474378a99/2bf5dca2b8c740a6a655ab0426f9202f.jpg';
            } elseif (stripos($productName, 'jogging') !== false || stripos($productName, 'pantalon') !== false || stripos($productName, 'legging') !== false) {
                return 'https://img01.ztat.net/article/spp-media-p1/bd6ff26137bb4e42a3a7ddb5df76d9c5/a3d573de1b8c45e89800b9dabba4603e.jpg';
            } elseif (stripos($productName, 'pull') !== false || stripos($productName, 'sweat') !== false) {
                return 'https://img01.ztat.net/article/spp-media-p1/5a3a5c1526b14338b1bd4e952c482936/69e242c0e4764c58a10d3851cde2b03b.jpg';
            } elseif (stripos($productName, 'chaussure') !== false || stripos($productName, 'basket') !== false) {
                return 'https://img01.ztat.net/article/spp-media-p1/77a0fc929fb53ffc8611ecb1bddaf0af/ca4cca3b97214897a3a4390ee24ee278.jpg';
            } else {
                return 'https://img01.ztat.net/article/spp-media-p1/82d5f6fd19153e038771cb42231e4bfb/0718e2163d5d4cfdadc10a0a7fa2ce65.jpg';
            }
        }
        // Catégorie Enfant
        elseif ($category == 'enfant' || stripos($productName, 'enfant') !== false || stripos($productName, 'kid') !== false) {
            if (stripos($productName, 't-shirt') !== false || stripos($productName, 'tshirt') !== false) {
                return 'https://img01.ztat.net/article/spp-media-p1/11fe90359cd6377382393eb3e75e7f90/8b5390dd67ad4678bbad2f44b37f5bcc.jpg';
            } elseif (stripos($productName, 'jogging') !== false || stripos($productName, 'pantalon') !== false) {
                return 'https://img01.ztat.net/article/spp-media-p1/ae65ca33847d3fdda1a2c9a9cedfb070/35cae9e14d4648bea2b189dfafffce2d.jpg';
            } elseif (stripos($productName, 'pull') !== false || stripos($productName, 'sweat') !== false) {
                return 'https://img01.ztat.net/article/spp-media-p1/587cd811e2ec3cab9b366eb3dc697dc3/a2d67a0143f845ea87a869d2acc25d51.jpg';
            } elseif (stripos($productName, 'chaussure') !== false || stripos($productName, 'basket') !== false) {
                return 'https://img01.ztat.net/article/spp-media-p1/f6d1e2f5907634a6a7238b92064d8423/dcd23ac36b604d0c94eb1a22cbc49168.jpg';
            } else {
                return 'https://img01.ztat.net/article/spp-media-p1/e45aafc631ef38f8a3f71a52e661248b/546be1cbe0d24b84a8cb3a9aa07ff601.jpg';
            }
        }
        // Par défaut - déterminé par le nom du produit
        else {
            if (stripos($productName, 't-shirt') !== false || stripos($productName, 'tshirt') !== false) {
                return 'https://img01.ztat.net/article/spp-media-p1/e5367096bb8d3bada56d1a205a32344a/8cbb4a78b5954c76a34f3cab483cbd27.jpg';
            } elseif (stripos($productName, 'jogging') !== false || stripos($productName, 'pantalon') !== false) {
                return 'https://img01.ztat.net/article/spp-media-p1/9e0c33b4cd5e353fa0bf3aa59454ab7d/3df4e5cbb9c441c2bd17a3cc05fc2311.jpg';
            } elseif (stripos($productName, 'pull') !== false || stripos($productName, 'sweat') !== false) {
                return 'https://img01.ztat.net/article/spp-media-p1/2e694f1365cd387e9fb05a8778b07c7e/220c539ef9e548c59a6c5a3c4339f457.jpg';
            } elseif (stripos($productName, 'chaussure') !== false || stripos($productName, 'basket') !== false) {
                return 'https://img01.ztat.net/article/spp-media-p1/c4d563e7b0793b07b7b33df9650bc752/0a74d7b2bbd5452a99c1e9637d0cf95c.jpg';
            } else {
                return 'https://img01.ztat.net/article/spp-media-p1/7df9956fe3273e838eac51439d778fb2/29f6f982120b42c9b7c958db65eb3d17.jpg';
            }
        }
    }

    /**
     * Cherche une image locale correspondant au nom du produit
     */
    private function findLocalProductImage($productName) {
        $baseDir = __DIR__ . '/../assets/images/products/';
        $fallbackImage = 'assets/images/products/default.jpg';
        
        // Si le répertoire n'existe pas, retourner l'image par défaut
        if (!is_dir($baseDir)) {
            return $fallbackImage;
        }
        
        // Normaliser le nom du produit pour la recherche
        $normalizedName = strtolower(str_replace([' ', '-', '_'], '', $productName));
        
        // Chercher dans le dossier products
        $files = scandir($baseDir);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            
            $normalizedFile = strtolower(str_replace([' ', '-', '_', '.jpg', '.png', '.jpeg'], '', $file));
            
            // Si le nom de fichier contient le nom du produit normalisé
            if (strpos($normalizedFile, $normalizedName) !== false ||
                strpos($normalizedName, $normalizedFile) !== false) {
                return 'assets/images/products/' . $file;
            }
        }
        
        // Chercher dans le dossier variants si le dossier existe
        $variantsDir = $baseDir . 'variants/';
        if (is_dir($variantsDir)) {
            $files = scandir($variantsDir);
            foreach ($files as $file) {
                if ($file === '.' || $file === '..') continue;
                
                $normalizedFile = strtolower(str_replace([' ', '-', '_', '.jpg', '.png', '.jpeg'], '', $file));
                
                // Si le nom de fichier contient le nom du produit normalisé
                if (strpos($normalizedFile, $normalizedName) !== false ||
                    strpos($normalizedName, $normalizedFile) !== false) {
                    return 'assets/images/products/variants/' . $file;
                }
            }
        }
        
        // Aucune image locale trouvée
        return null;
    }

    // Nouvelle méthode pour obtenir les informations sur tous les produits en promotion
    private function getPromotionalProductsInfo() {
        try {
            // On va récupérer les 5 produits en promotion
            $promotionalProducts = $this->getPromotionalProducts();
            
            // Créer un tableau indexé par ID de produit
            $result = [];
            foreach ($promotionalProducts as $product) {
                $result[$product['id']] = [
                    'discount' => $product['discount'],
                    'sale_price' => $product['sale_price']
                ];
            }
            
            return $result;
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des informations sur les promotions: " . $e->getMessage());
            return [];
        }
    }
}
?>
