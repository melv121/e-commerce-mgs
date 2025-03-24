-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 21 mars 2025 à 14:39
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mgs_store`
--

-- --------------------------------------------------------

--
-- Structure de la table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `session_id`, `created_at`) VALUES
(1, NULL, 'trriijqnhv9mkl9v6o1tlauk53', '2025-03-18 11:08:13');

-- --------------------------------------------------------

--
-- Structure de la table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `image`, `parent_id`, `created_at`, `updated_at`) VALUES
(1, 'Homme', 'Produits pour hommes', 'assets/images/category-men.jpg', NULL, '2025-03-21 13:38:42', '2025-03-21 13:38:42'),
(2, 'Femme', 'Produits pour femmes', 'assets/images/category-women.jpg', NULL, '2025-03-21 13:38:42', '2025-03-21 13:38:42'),
(3, 'Enfant', 'Produits pour enfants', 'assets/images/category-kids.jpg', NULL, '2025-03-21 13:38:42', '2025-03-21 13:38:42');

-- --------------------------------------------------------

--
-- Structure de la table `newsletters`
--

CREATE TABLE `newsletters` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `shipping_address` text DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `featured` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock`, `image`, `category_id`, `featured`, `created_at`, `updated_at`) VALUES
(1, 'Jogging Nike Homme', 'Jogging de sport Nike pour homme.', 59.99, 50, 'assets/images/products/men/jogging-nike.jpg', 1, 1, '2025-03-21 13:38:42', '2025-03-21 13:38:42'),
(2, 'Jogging Adidas Homme', 'Jogging de sport Adidas pour homme.', 54.99, 50, 'assets/images/products/men/jogging-adidas.jpg', 1, 1, '2025-03-21 13:38:42', '2025-03-21 13:38:42'),
(3, 'Jogging Nike Femme', 'Jogging de sport Nike pour femme.', 59.99, 50, 'assets/images/products/women/jogging-nike.jpg', 2, 1, '2025-03-21 13:38:42', '2025-03-21 13:38:42'),
(4, 'Jogging Adidas Femme', 'Jogging de sport Adidas pour femme.', 54.99, 50, 'assets/images/products/women/jogging-adidas.jpg', 2, 1, '2025-03-21 13:38:42', '2025-03-21 13:38:42'),
(5, 'T-shirt Teddy Smith Homme', 'T-shirt Teddy Smith pour homme.', 29.99, 100, 'assets/images/products/men/tshirt-teddy-smith.jpg', 1, 1, '2025-03-21 13:38:42', '2025-03-21 13:38:42'),
(6, 'T-shirt Nike Homme', 'T-shirt Nike pour homme.', 34.99, 100, 'assets/images/products/men/tshirt-nike.jpg', 1, 1, '2025-03-21 13:38:42', '2025-03-21 13:38:42'),
(7, 'T-shirt Adidas Homme', 'T-shirt Adidas pour homme.', 32.99, 100, 'assets/images/products/men/tshirt-adidas.jpg', 1, 1, '2025-03-21 13:38:42', '2025-03-21 13:38:42'),
(8, 'T-shirt Teddy Smith Femme', 'T-shirt Teddy Smith pour femme.', 29.99, 100, 'assets/images/products/women/tshirt-teddy-smith.jpg', 2, 1, '2025-03-21 13:38:42', '2025-03-21 13:38:42'),
(9, 'T-shirt Nike Femme', 'T-shirt Nike pour femme.', 34.99, 100, 'assets/images/products/women/tshirt-nike.jpg', 2, 1, '2025-03-21 13:38:42', '2025-03-21 13:38:42'),
(10, 'T-shirt Adidas Femme', 'T-shirt Adidas pour femme.', 32.99, 100, 'assets/images/products/women/tshirt-adidas.jpg', 2, 1, '2025-03-21 13:38:42', '2025-03-21 13:38:42'),
(11, 'T-shirt Teddy Smith Enfant', 'T-shirt Teddy Smith pour enfant.', 24.99, 100, 'assets/images/products/kids/tshirt-teddy-smith.jpg', 3, 1, '2025-03-21 13:38:42', '2025-03-21 13:38:42'),
(12, 'T-shirt Nike Enfant', 'T-shirt Nike pour enfant.', 29.99, 100, 'assets/images/products/kids/tshirt-nike.jpg', 3, 1, '2025-03-21 13:38:42', '2025-03-21 13:38:42'),
(13, 'Legging Nike Femme', 'Legging de sport Nike pour femme.', 49.99, 50, 'assets/images/products/women/legging-nike.jpg', 2, 1, '2025-03-21 13:38:42', '2025-03-21 13:38:42'),
(14, 'Legging Adidas Femme', 'Legging de sport Adidas pour femme.', 44.99, 50, 'assets/images/products/women/legging-adidas.jpg', 2, 1, '2025-03-21 13:38:42', '2025-03-21 13:38:42'),
(15, 'Legging Nike Enfant', 'Legging de sport Nike pour enfant.', 39.99, 50, 'assets/images/products/kids/legging-nike.jpg', 3, 1, '2025-03-21 13:38:42', '2025-03-21 13:38:42'),
(16, 'Legging Adidas Enfant', 'Legging de sport Adidas pour enfant.', 34.99, 50, 'assets/images/products/kids/legging-adidas.jpg', 3, 1, '2025-03-21 13:38:42', '2025-03-21 13:38:42'),
(17, 'Chaussures Running Nike Homme', 'Chaussures de running Nike pour homme.', 89.99, 30, 'assets/images/products/men/shoes-nike.jpg', 1, 1, '2025-03-21 13:38:42', '2025-03-21 13:38:42'),
(18, 'Chaussures Running Adidas Homme', 'Chaussures de running Adidas pour homme.', 84.99, 30, 'assets/images/products/men/shoes-adidas.jpg', 1, 1, '2025-03-21 13:38:42', '2025-03-21 13:38:42'),
(19, 'Chaussures Running Nike Femme', 'Chaussures de running Nike pour femme.', 89.99, 30, 'assets/images/products/women/shoes-nike.jpg', 2, 1, '2025-03-21 13:38:42', '2025-03-21 13:38:42'),
(20, 'Chaussures Running Adidas Femme', 'Chaussures de running Adidas pour femme.', 84.99, 30, 'assets/images/products/women/shoes-adidas.jpg', 2, 1, '2025-03-21 13:38:42', '2025-03-21 13:38:42'),
(21, 'Chaussures Running Nike Enfant', 'Chaussures de running Nike pour enfant.', 69.99, 30, 'assets/images/products/kids/shoes-nike.jpg', 3, 1, '2025-03-21 13:38:42', '2025-03-21 13:38:42'),
(22, 'Chaussures Running Adidas Enfant', 'Chaussures de running Adidas pour enfant.', 64.99, 30, 'assets/images/products/kids/shoes-adidas.jpg', 3, 1, '2025-03-21 13:38:42', '2025-03-21 13:38:42'),
(23, 'Pull Teddy Smith Homme', 'Pull Teddy Smith pour homme.', 49.99, 40, 'assets/images/products/men/pull-teddy-smith.jpg', 1, 1, '2025-03-21 13:38:42', '2025-03-21 13:38:42'),
(24, 'Pull Nike Homme', 'Pull Nike pour homme.', 54.99, 40, 'assets/images/products/men/pull-nike.jpg', 1, 1, '2025-03-21 13:38:42', '2025-03-21 13:38:42'),
(25, 'Pull Adidas Homme', 'Pull Adidas pour homme.', 52.99, 40, 'assets/images/products/men/pull-adidas.jpg', 1, 1, '2025-03-21 13:38:42', '2025-03-21 13:38:42');

-- --------------------------------------------------------

--
-- Structure de la table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('client','admin') DEFAULT 'client',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `loyalty_points` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `first_name`, `last_name`, `address`, `city`, `postal_code`, `country`, `phone`, `role`, `created_at`, `updated_at`, `loyalty_points`) VALUES
(1, 'admin', 'admin@mgsstore.com', '$2y$10$WbhTNhlyEBQKLYFYTXwgP.xly7Pfsdve6nC9XWevfET1J5J1ihCZe', 'Admin', 'MGS', NULL, NULL, NULL, NULL, NULL, 'admin', '2025-03-12 10:26:58', '2025-03-12 10:26:58', 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Index pour la table `newsletters`
--
ALTER TABLE `newsletters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Index pour la table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Index pour la table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `newsletters`
--
ALTER TABLE `newsletters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
