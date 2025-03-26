-- Version simplifiée qui ne nécessite pas d'accès à INFORMATION_SCHEMA
-- Vérifier si la catégorie homme existe, sinon la créer
INSERT IGNORE INTO categories (id, name, description) 
VALUES (1, 'homme', 'Vêtements et accessoires pour homme');

-- Ajouter un survêtement Under Armour dans la catégorie homme
INSERT INTO products (name, description, price, category_id, image, stock, created_at) 
VALUES 
('Survêtement Under Armour Homme Classic', 'Ensemble de survêtement Under Armour conçu pour le confort et la performance. Matière respirante et extensible pour une liberté de mouvement optimale.', 
89.99, 1, 'assets/images/products/underarmour_survetement_classic.jpg', 
70, NOW());

-- Récupérer l'ID du produit inséré
SET @product_id = LAST_INSERT_ID();

-- Tentative d'ajout de la colonne discount si elle n'existe pas
-- Cette commande peut échouer silencieusement si la colonne existe déjà
ALTER TABLE products ADD COLUMN discount INT DEFAULT NULL;

-- Mise à jour du discount (cette commande fonctionnera maintenant que la colonne existe ou a été créée)
UPDATE products SET discount = 20 WHERE id = @product_id;

-- Tentative de création de la table promotions si elle n'existe pas
CREATE TABLE IF NOT EXISTS promotions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    discount_percentage INT NOT NULL,
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Ajouter une entrée dans la table promotions
INSERT INTO promotions (product_id, discount_percentage, start_date, end_date)
VALUES (@product_id, 20, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY));

