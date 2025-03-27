-- Création de la table des factures
CREATE TABLE IF NOT EXISTS invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    invoice_number VARCHAR(20) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    created_at DATETIME NOT NULL,
    INDEX (order_id),
    UNIQUE (invoice_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Ajout de la colonne order_number à la table orders si elle n'existe pas déjà
ALTER TABLE orders ADD COLUMN IF NOT EXISTS order_number VARCHAR(20) UNIQUE;

-- Ajouter des numéros de commande aux commandes existantes qui n'en ont pas
UPDATE orders SET order_number = CONCAT('CMD', DATE_FORMAT(created_at, '%Y%m%d'), LPAD(id, 4, '0'))
WHERE order_number IS NULL;

-- Ajouter la contrainte pour que order_number ne soit pas NULL pour les futures commandes
ALTER TABLE orders MODIFY COLUMN order_number VARCHAR(20) NOT NULL;
