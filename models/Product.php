<?php
class Product {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getById($id) {
        try {
            $query = "SELECT p.*, c.name as category_name FROM products p 
                     LEFT JOIN categories c ON p.category_id = c.id 
                     WHERE p.id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Si le produit existe, récupérer ses variantes
            if ($product) {
                $product['variants'] = $this->getProductVariants($id);
            }
            
            return $product;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération du produit: " . $e->getMessage());
            return false;
        }
    }
    
    public function getProductVariants($productId) {
        try {
            // Vérifier si la table des variantes existe
            $tables = $this->db->query("SHOW TABLES LIKE 'product_variants'")->fetchAll();
            if (empty($tables)) {
                // Si la table n'existe pas, on charge et exécute le script SQL
                $sql = file_get_contents(__DIR__ . '/../sql/product_variants.sql');
                $this->db->exec($sql);
                
                // Charger aussi les variantes pour Teddy Smith (ID 5)
                $teddySmithVariants = file_get_contents(__DIR__ . '/../sql/teddy_smith_variants.sql');
                $this->db->exec($teddySmithVariants);
                
                // Charger les variantes pour Adidas (ID 7)
                $adidasVariants = file_get_contents(__DIR__ . '/../sql/adidas_variants.sql');
                $this->db->exec($adidasVariants);
                
                // Charger les variantes pour Nike Femme (ID 9)
                $nikeFemmeVariants = file_get_contents(__DIR__ . '/../sql/nike_femme_variants.sql');
                $this->db->exec($nikeFemmeVariants);
                
                // Charger les variantes pour Adidas Femme (ID 10)
                $adidasFemmeVariants = file_get_contents(__DIR__ . '/../sql/adidas_femme_variants.sql');
                $this->db->exec($adidasFemmeVariants);
                
                // Charger les variantes pour Teddy Smith Enfant (ID 11)
                $teddySmithEnfantVariants = file_get_contents(__DIR__ . '/../sql/teddy_smith_enfant_variants.sql');
                $this->db->exec($teddySmithEnfantVariants);
                
                // Charger les variantes pour Nike Enfant (ID 12)
                $nikeEnfantVariants = file_get_contents(__DIR__ . '/../sql/nike_enfant_variants.sql');
                $this->db->exec($nikeEnfantVariants);
            }
            
            // Récupérer les variantes pour ce produit
            $query = "SELECT * FROM product_variants WHERE product_id = ? ORDER BY color";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$productId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des variantes: " . $e->getMessage());
            return [];
        }
    }
}
