<?php
// Point d'entrée principal de l'application
session_start();

// Définir la constante BASE_URL
define('BASE_URL', '/mgs_store');

// Afficher les erreurs en mode développement
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Journalisation des erreurs
ini_set('log_errors', 1);
ini_set('error_log', 'php-errors.log');

// DEBUG: Vérifier l'état de la session du panier
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// SOLUTION DE CONTOURNEMENT: Gérer directement les requêtes de détail de produit
$uri = $_SERVER['REQUEST_URI'];
if (preg_match('#/mgs_store/product/detail/(\d+)#', $uri, $matches)) {
    $productId = $matches[1];
    
    // Connexion à la base de données
    require_once 'config/database.php';
    $database = new Database();
    $db = $database->getConnection();
    
    if ($db) {
        try {
            // Récupérer les informations du produit - REQUÊTE MODIFIÉE sans c.slug
            $query = "SELECT p.*, c.name as category_name 
                      FROM products p 
                      LEFT JOIN categories c ON p.category_id = c.id 
                      WHERE p.id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$productId]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($product) {
                // Ajouter une valeur slug générée si elle est nécessaire
                if (!isset($product['category_slug']) && isset($product['category_name'])) {
                    $product['category_slug'] = strtolower(preg_replace('/[^a-zA-Z0-9]/', '-', $product['category_name']));
                }
                
                // Récupérer la galerie d'images
                try {
                    $galleryQuery = "SELECT * FROM product_gallery WHERE product_id = ? ORDER BY position";
                    $stmt = $db->prepare($galleryQuery);
                    $stmt->execute([$productId]);
                    $product['gallery'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (Exception $e) {
                    $product['gallery'] = [];
                }
                
                // Récupérer les variantes
                try {
                    $variantsQuery = "SELECT * FROM product_variants WHERE product_id = ? ORDER BY position";
                    $stmt = $db->prepare($variantsQuery);
                    $stmt->execute([$productId]);
                    $variants = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    // Si on trouve des variantes dans la base de données, on les utilise
                    if (!empty($variants)) {
                        $product['variants'] = $variants;
                        $product['has_variants'] = true;
                    } else {
                        // Vérifier si c'est un t-shirt (toutes catégories confondues)
                        $productName = strtolower($product['name']);
                        $isTshirt = (strpos($productName, 'tee shirt') !== false || 
                                    strpos($productName, 't-shirt') !== false || 
                                    strpos($productName, 'tshirt') !== false ||
                                    strpos($productName, 'chemise') !== false ||
                                    strpos($productName, 'polo') !== false);
                        
                        if ($isTshirt) {
                            // Déterminer les variantes en fonction de la marque
                            if (stripos($productName, 'nike') !== false) {
                                // Variantes Nike
                                $product['variants'] = [
                                    [
                                        'id' => 'var_' . $productId . '_1',
                                        'product_id' => $productId,
                                        'color' => '#87CEEB',  // Bleu ciel (au lieu de noir)
                                        'color_name' => 'Bleu ciel',
                                        'image' => $product['image'],
                                        'position' => 1
                                    ],
                                    [
                                        'id' => 'var_' . $productId . '_2',
                                        'product_id' => $productId,
                                        'color' => '#FFD700',  // Jaune (au lieu de blanc)
                                        'color_name' => 'Jaune',
                                        'image' => $product['image'],
                                        'position' => 2
                                    ],
                                    [
                                        'id' => 'var_' . $productId . '_3',
                                        'product_id' => $productId,
                                        'color' => '#FFC0CB',  // Rose (au lieu de rouge)
                                        'color_name' => 'Rose',
                                        'image' => $product['image'],
                                        'position' => 3
                                    ],
                                    [
                                        'id' => 'var_' . $productId . '_4',
                                        'product_id' => $productId,
                                        'color' => '#FF0000',  // Rouge (au lieu de bleu)
                                        'color_name' => 'Rouge',
                                        'image' => $product['image'],
                                        'position' => 4
                                    ]
                                ];
                            } else if (stripos($productName, 'adidas') !== false) {
                                // Variantes Adidas
                                $product['variants'] = [
                                    [
                                        'id' => 'var_' . $productId . '_1',
                                        'product_id' => $productId,
                                        'color' => '#000000',
                                        'color_name' => 'Noir',
                                        'image' => $product['image'],
                                        'position' => 1
                                    ],
                                    [
                                        'id' => 'var_' . $productId . '_2',
                                        'product_id' => $productId,
                                        'color' => '#FFFFFF',
                                        'color_name' => 'Blanc',
                                        'image' => $product['image'],
                                        'position' => 2
                                    ],
                                    [
                                        'id' => 'var_' . $productId . '_3',
                                        'product_id' => $productId,
                                        'color' => '#0000FF',
                                        'color_name' => 'Bleu',
                                        'image' => $product['image'],
                                        'position' => 3
                                    ]
                                ];
                            } else if (stripos($productName, 'teddy smith') !== false) {
                                // Variantes Teddy Smith
                                $product['variants'] = [
                                    [
                                        'id' => 'var_' . $productId . '_1',
                                        'product_id' => $productId,
                                        'color' => '#000000',
                                        'color_name' => 'Noir',
                                        'image' => $product['image'],
                                        'position' => 1
                                    ],
                                    [
                                        'id' => 'var_' . $productId . '_2',
                                        'product_id' => $productId,
                                        'color' => '#808080',
                                        'color_name' => 'Gris',
                                        'image' => $product['image'],
                                        'position' => 2
                                    ],
                                    [
                                        'id' => 'var_' . $productId . '_3',
                                        'product_id' => $productId,
                                        'color' => '#A0522D',
                                        'color_name' => 'Marron',
                                        'image' => $product['image'],
                                        'position' => 3
                                    ]
                                ];
                            } else {
                                // Variantes génériques pour tous les t-shirts
                                $product['variants'] = [
                                    [
                                        'id' => 'var_' . $productId . '_1',
                                        'product_id' => $productId,
                                        'color' => '#000000',
                                        'color_name' => 'Noir',
                                        'image' => $product['image'],
                                        'position' => 1
                                    ],
                                    [
                                        'id' => 'var_' . $productId . '_2',
                                        'product_id' => $productId,
                                        'color' => '#FFFFFF',
                                        'color_name' => 'Blanc',
                                        'image' => $product['image'],
                                        'position' => 2
                                    ],
                                    [
                                        'id' => 'var_' . $productId . '_3',
                                        'product_id' => $productId,
                                        'color' => '#0000FF',
                                        'color_name' => 'Bleu',
                                        'image' => $product['image'],
                                        'position' => 3
                                    ],
                                    [
                                        'id' => 'var_' . $productId . '_4',
                                        'product_id' => $productId,
                                        'color' => '#FF0000',
                                        'color_name' => 'Rouge',
                                        'image' => $product['image'],
                                        'position' => 4
                                    ]
                                ];
                            }
                            $product['has_variants'] = true;
                        } else {
                            $product['has_variants'] = false;
                        }
                    }
                } catch (Exception $e) {
                    error_log("Erreur lors de la récupération des variantes: " . $e->getMessage());
                    $product['variants'] = [];
                    $product['has_variants'] = false;
                }
                
                // Récupérer des produits similaires
                try {
                    $similarQuery = "SELECT * FROM products WHERE id != ? AND category_id = ? LIMIT 4";
                    $stmt = $db->prepare($similarQuery);
                    $stmt->execute([$productId, $product['category_id']]);
                    $similarProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (Exception $e) {
                    $similarProducts = [];
                }
                
                // Définir le titre de la page
                $pageTitle = $product['name'];
                
                // Afficher la page
                require_once 'views/templates/header.php';
                require_once 'views/products/detail.php';
                require_once 'views/templates/footer.php';
                exit;
            }
        } catch (Exception $e) {
            error_log("Erreur dans l'accès direct au produit: " . $e->getMessage());
            // Continuer avec le routage normal
        }
    }
}

// Journaliser les requêtes pour débogage
error_log('Requête reçue: ' . $_SERVER['REQUEST_URI']);

// Charger les routes
$routes = require_once 'config/routes.php';

// Récupérer l'URL demandée
$requestUri = $_SERVER['REQUEST_URI'];
error_log('URI brute: ' . $requestUri);

// Supprimer BASE_URL de l'URI
$requestUri = str_replace(BASE_URL, '', $requestUri);
error_log('URI sans BASE_URL: ' . $requestUri);

// Supprimer les paramètres de requête
$requestUri = strtok($requestUri, '?');
error_log('URI sans paramètres: ' . $requestUri);

// Supprimer le slash de début si présent
$requestUri = ltrim($requestUri, '/');
error_log('URI finale: ' . $requestUri);

// Récupérer le contrôleur et l'action à partir des routes
$controller = null;
$action = null;
$params = [];

// Rechercher la route correspondante
foreach ($routes as $pattern => $route) {
    // Version améliorée de la conversion du modèle en expression régulière
    $regexPattern = str_replace('/', '\/', $pattern);
    $regexPattern = preg_replace('/:([a-zA-Z0-9_]+)/', '([^\/]+)', $regexPattern);
    
    error_log('Tentative de correspondance avec route: ' . $pattern . ' (regex: ' . $regexPattern . ')');
    
    // Si la route correspond
    if (preg_match('/^' . $regexPattern . '$/', $requestUri, $matches)) {
        error_log('Correspondance trouvée avec route: ' . $pattern);
        $controller = $route[0];
        $action = $route[1];
        
        // Récupérer les paramètres
        array_shift($matches); // Supprimer la correspondance complète
        $params = $matches;
        
        error_log('Contrôleur: ' . $controller . ', Action: ' . $action . ', Params: ' . json_encode($params));
        break;
    }
}

// Si aucune route ne correspond, utiliser la route 404
if (!$controller) {
    error_log('Aucune route correspondante trouvée, redirection vers 404');
    $controller = 'ErrorController';
    $action = 'notFound';
}

// Vérifier si le fichier du contrôleur existe
if (!file_exists('controllers/' . $controller . '.php')) {
    error_log("Fichier du contrôleur non trouvé: controllers/{$controller}.php");
    $controller = 'ErrorController';
    $action = 'notFound';
}

try {
    // Charger et instancier le contrôleur
    require_once 'controllers/' . $controller . '.php';
    
    if (!class_exists($controller)) {
        error_log("La classe du contrôleur n'existe pas: {$controller}");
        throw new Exception("Contrôleur non trouvé: {$controller}");
    }
    
    $controllerInstance = new $controller();
    
    if (!method_exists($controllerInstance, $action)) {
        error_log("La méthode n'existe pas dans le contrôleur: {$controller}->{$action}");
        throw new Exception("Action non trouvée: {$action}");
    }
    
    // Appeler l'action avec les paramètres
    call_user_func_array([$controllerInstance, $action], $params);
    
} catch (Exception $e) {
    error_log("Erreur lors du traitement de la requête: " . $e->getMessage());
    
    // Fallback vers la page d'erreur
    require_once 'controllers/ErrorController.php';
    $errorController = new ErrorController();
    $errorController->notFound();
}
?>
