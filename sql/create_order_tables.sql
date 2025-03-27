-- Création de la table orders si elle n'existe pas
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    order_number VARCHAR(20) UNIQUE,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
    payment_method VARCHAR(50) NOT NULL,
    shipping_address TEXT,
    created_at DATETIME NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX (user_id),
    INDEX (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Création de la table order_items si elle n'existe pas
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (order_id),
    INDEX (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Ajout de données de test si aucune commande n'existe
INSERT INTO orders (user_id, order_number, total_amount, status, payment_method, created_at)
SELECT 1, 'ORD20230001', 149.99, 'completed', 'credit_card', NOW()
FROM dual
WHERE NOT EXISTS (SELECT 1 FROM orders LIMIT 1);

-- Récupérer l'ID de la commande de test
SET @test_order_id = LAST_INSERT_ID();

-- Ajouter des articles à la commande de test
INSERT INTO order_items (order_id, product_id, quantity, price)
SELECT @test_order_id, 1, 2, 59.99
FROM dual
WHERE @test_order_id > 0;

INSERT INTO order_items (order_id, product_id, quantity, price)
SELECT @test_order_id, 2, 1, 29.99
FROM dual
WHERE @test_order_id > 0;
