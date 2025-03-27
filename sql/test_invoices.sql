-- Créer la table invoices si elle n'existe pas
CREATE TABLE IF NOT EXISTS invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    invoice_number VARCHAR(20) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    created_at DATETIME NOT NULL,
    INDEX (order_id),
    UNIQUE (invoice_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- S'assurer que la colonne order_number existe dans la table orders
ALTER TABLE orders ADD COLUMN IF NOT EXISTS order_number VARCHAR(20) UNIQUE;

-- Insérer une commande de test si elle n'existe pas déjà
INSERT INTO orders (user_id, total_amount, status, payment_method, created_at, order_number)
SELECT 1, 129.99, 'completed', 'card', NOW(), 'CMD202401250001'
FROM dual
WHERE NOT EXISTS (SELECT 1 FROM orders WHERE order_number = 'CMD202401250001');

-- Récupérer l'ID de la commande
SET @order_id = (SELECT id FROM orders WHERE order_number = 'CMD202401250001');

-- Insérer des articles dans la commande
INSERT INTO order_items (order_id, product_id, quantity, price)
SELECT @order_id, 1, 2, 49.99
FROM dual
WHERE NOT EXISTS (SELECT 1 FROM order_items WHERE order_id = @order_id AND product_id = 1);

INSERT INTO order_items (order_id, product_id, quantity, price)
SELECT @order_id, 2, 1, 29.99
FROM dual
WHERE NOT EXISTS (SELECT 1 FROM order_items WHERE order_id = @order_id AND product_id = 2);

-- Insérer une facture de test
INSERT INTO invoices (order_id, invoice_number, amount, created_at)
SELECT @order_id, 'F202401250001', 129.99, NOW()
FROM dual
WHERE NOT EXISTS (SELECT 1 FROM invoices WHERE invoice_number = 'F202401250001');
