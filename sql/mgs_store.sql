-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 27 mars 2025 à 16:21
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
(1, NULL, 'trriijqnhv9mkl9v6o1tlauk53', '2025-03-18 11:08:13'),
(2, NULL, 'q809nso6kc0b5sjh92rqjgnqe9', '2025-03-21 14:50:11'),
(3, 3, 'q809nso6kc0b5sjh92rqjgnqe9', '2025-03-25 11:59:19'),
(4, 1, 'q809nso6kc0b5sjh92rqjgnqe9', '2025-03-26 17:56:09');

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
-- Structure de la table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `invoice_number` varchar(20) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `order_number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `status`, `shipping_address`, `payment_method`, `created_at`, `updated_at`, `order_number`) VALUES
(1, 3, 54.99, 'pending', NULL, 'credit_card', '2025-03-26 17:45:06', '2025-03-26 17:45:06', ''),
(2, 1, 69.98, 'pending', '7 rue jeanne champilou, 45000 saint jean de braye, FR', 'credit_card', '2025-03-26 18:49:37', '2025-03-26 18:49:37', 'ORD20250326194937292');

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

--
-- Déchargement des données de la table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `created_at`) VALUES
(1, 1, 2, 1, 54.99, '2025-03-26 17:45:06'),
(2, 2, 9, 2, 34.99, '2025-03-26 18:49:37');

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `discount` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock`, `image`, `category_id`, `featured`, `created_at`, `updated_at`, `discount`) VALUES
(1, 'Jogging Nike Homme', 'Jogging de sport Nike pour homme.', 59.99, 50, 'assets/images/jogging-nike.jpg', 1, 1, '2025-03-21 13:38:42', '2025-03-24 12:50:30', NULL),
(2, 'Jogging Adidas Homme', 'Jogging de sport Adidas pour homme.', 54.99, 50, 'assets/images/jogging-adidas.jpg', 1, 1, '2025-03-21 13:38:42', '2025-03-24 12:51:55', NULL),
(3, 'Jogging Nike Femme', 'Jogging de sport Nike pour femme.', 59.99, 50, 'assets/images/jogging-nikef.jpg', 2, 1, '2025-03-21 13:38:42', '2025-03-24 12:53:31', NULL),
(4, 'Jogging Adidas Femme', 'Jogging de sport Adidas pour femme.', 54.99, 50, 'assets/images/jogging-adidasf.jpg', 2, 1, '2025-03-21 13:38:42', '2025-03-24 12:53:20', NULL),
(5, 'T-shirt Teddy Smith Homme', 'T-shirt Teddy Smith pour homme.', 29.99, 100, 'assets/images/teeshirtteddyhomme1.jpg', 1, 1, '2025-03-21 13:38:42', '2025-03-24 13:47:32', NULL),
(6, 'T-shirt Nike Homme', 'T-shirt Nike pour homme.', 34.99, 100, 'assets/images/teeshirtnikehomme.jpg', 1, 1, '2025-03-21 13:38:42', '2025-03-24 13:38:53', NULL),
(7, 'T-shirt Adidas Homme', 'T-shirt Adidas pour homme.', 32.99, 100, 'assets/images/teeshirtadidashomme1.jpg', 1, 1, '2025-03-21 13:38:42', '2025-03-24 13:56:20', NULL),
(9, 'T-shirt Nike Femme', 'T-shirt Nike pour femme.', 34.99, 98, 'assets/images/teeshirtnikefemme.jpg', 2, 1, '2025-03-21 13:38:42', '2025-03-26 18:49:37', NULL),
(10, 'T-shirt Adidas Femme', 'T-shirt Adidas pour femme.', 32.99, 100, 'assets/images/teeshirtadidasfemme.jpg', 2, 1, '2025-03-21 13:38:42', '2025-03-24 14:56:38', NULL),
(11, 'T-shirt Teddy Smith Enfant', 'T-shirt Teddy Smith pour enfant.', 24.99, 100, 'assets/images/teeshirtteddyenfant.jpg', 3, 1, '2025-03-21 13:38:42', '2025-03-24 15:05:38', NULL),
(12, 'T-shirt Nike Enfant', 'T-shirt Nike pour enfant.', 29.99, 100, 'assets/images/teeshirtnikenfant2.jpg', 3, 1, '2025-03-21 13:38:42', '2025-03-24 15:15:25', NULL),
(13, 'Legging Nike Femme', 'Legging de sport Nike pour femme.', 49.99, 50, 'assets/images/leggingnikefemme.jpg', 2, 1, '2025-03-21 13:38:42', '2025-03-24 15:20:57', NULL),
(14, 'Legging Adidas Femme', 'Legging de sport Adidas pour femme.', 44.99, 50, 'assets/images/leggingadidasfemme.jpg', 2, 1, '2025-03-21 13:38:42', '2025-03-24 15:19:20', NULL),
(15, 'Legging Nike Enfant', 'Legging de sport Nike pour enfant.', 39.99, 50, 'assets/images/leggingnikenfant.jpg', 3, 1, '2025-03-21 13:38:42', '2025-03-24 15:19:34', NULL),
(16, 'Legging Adidas Enfant', 'Legging de sport Adidas pour enfant.', 34.99, 50, 'assets/images/leggingadidasenfant.jpg', 3, 1, '2025-03-21 13:38:42', '2025-03-24 15:19:45', NULL),
(17, 'Chaussures Running Nike Homme', 'Chaussures de running Nike pour homme.', 89.99, 30, 'assets/images/chaussurehomme.png', 1, 1, '2025-03-21 13:38:42', '2025-03-24 15:35:16', NULL),
(18, 'Chaussures Running Adidas Homme', 'Chaussures de running Adidas pour homme.', 84.99, 30, 'assets/images/chaussureadidashomme.jpg', 1, 1, '2025-03-21 13:38:42', '2025-03-24 15:34:00', NULL),
(19, 'Chaussures Running Nike Femme', 'Chaussures de running Nike pour femme.', 89.99, 30, 'assets/images/chaussurefemme.png', 2, 1, '2025-03-21 13:38:42', '2025-03-24 15:36:49', NULL),
(20, 'Chaussures Running Adidas Femme', 'Chaussures de running Adidas pour femme.', 84.99, 30, 'assets/images/chaussureadidasfemme.jpg', 2, 1, '2025-03-21 13:38:42', '2025-03-24 15:36:44', NULL),
(21, 'Chaussures Running Nike Enfant', 'Chaussures de running Nike pour enfant.', 69.99, 30, 'assets/images/chaussurenikenfant.jpg', 3, 1, '2025-03-21 13:38:42', '2025-03-24 15:38:54', NULL),
(22, 'Chaussures Running Adidas Enfant', 'Chaussures de running Adidas pour enfant.', 64.99, 30, 'assets/images/chaussureadidasenfant.jpg', 3, 1, '2025-03-21 13:38:42', '2025-03-24 15:39:09', NULL),
(23, 'Pull Teddy Smith Homme', 'Pull Teddy Smith pour homme.', 49.99, 40, 'assets/images/products/men/pull-teddy-smith.jpg', 1, 1, '2025-03-21 13:38:42', '2025-03-21 13:38:42', NULL),
(24, 'Pull Nike Homme', 'Pull Nike pour homme.', 54.99, 40, 'assets/images/pullnikehomme.jpg', 1, 1, '2025-03-21 13:38:42', '2025-03-26 16:59:50', NULL),
(25, 'Pull Adidas Homme', 'Pull Adidas pour homme.', 52.99, 40, 'assets/images/products/men/pull-adidas.jpg', 1, 1, '2025-03-21 13:38:42', '2025-03-21 13:38:42', NULL),
(26, 'Survêtement Under Armour Homme Classic', 'Ensemble de survêtement Under Armour conçu pour le confort et la performance. Matière respirante et extensible pour une liberté de mouvement optimale.', 89.99, 70, 'assets/images/underarmour_survetement_classic.jpg', 1, 0, '2025-03-26 16:57:03', '2025-03-26 16:57:23', 20);

-- --------------------------------------------------------

--
-- Structure de la table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `color` varchar(50) DEFAULT NULL,
  `color_code` varchar(7) DEFAULT NULL,
  `size` varchar(10) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `color`, `color_code`, `size`, `image`, `price`, `stock`, `created_at`) VALUES
(1, 6, 'Rouge', '#FF0000', 'M', 'assets/images/teeshirtnikehomme4.jpg', NULL, 50, '2025-03-24 13:07:59'),
(2, 6, 'Rose', '#FF69B4', 'M', 'assets/images/teeshirtnikehomme3.jpg', NULL, 45, '2025-03-24 13:07:59'),
(3, 6, 'Jaune', '#FFFF00', 'M', 'assets/images/teeshirtnikehomme2.jpg', NULL, 30, '2025-03-24 13:07:59'),
(4, 6, 'bleu', '#0000FF', 'M', 'assets/images/teeshirtnikehomme.jpg', NULL, 25, '2025-03-24 13:07:59'),
(10, 5, 'Noir', '#000000', 'M', 'assets/images/teeshirtteddyhomme3.jpg', NULL, 40, '2025-03-24 13:44:34'),
(11, 5, 'Blanc', '##FFFFF', 'M', 'assets/images/teeshirtteddyhomme2.jpg', NULL, 35, '2025-03-24 13:44:34'),
(12, 5, 'Gris', '#808080', 'M', 'assets/images/teeshirtteddyhomme1.jpg', NULL, 30, '2025-03-24 13:44:34'),
(18, 7, 'bleu turquoise', '#25c8bf', 'M', 'assets/images/teeshirtadidashomme1.jpg', NULL, 45, '2025-03-24 14:01:48'),
(19, 7, 'vert', '#6bff33', 'M', 'assets/images/teeshirtadidashomme2.jpg', NULL, 40, '2025-03-24 14:01:48'),
(20, 7, 'rouge', '#ff3c33', 'M', 'assets/images/teeshirtadidashomme3.jpg', NULL, 38, '2025-03-24 14:01:48'),
(21, 7, 'bleu', '#33bbff', 'M', 'assets/images/teeshirtadidashomme4.jpg', NULL, 35, '2025-03-24 14:01:48'),
(22, 7, 'Bleu marine', '2e3490', 'M', 'assets/images/teeshirtadidashomme5.jpg', NULL, 30, '2025-03-24 14:01:48'),
(23, 9, 'Noir', '#000000', 'M', 'assets/images/teeshirtnikefemme.jpg', NULL, 50, '2025-03-24 14:48:19'),
(24, 9, 'Rose', '#FF69B4', 'M', 'assets/images/teeshirtnikefemme2.jpg', NULL, 45, '2025-03-24 14:48:19'),
(25, 9, 'Gris', '#808080', 'M', 'assets/images/teeshirtnikefemme3.jpg', NULL, 40, '2025-03-24 14:48:19'),
(26, 9, 'Bleu clair', '#87CEFA', 'M', 'assets/images/teeshirtnikefemme4.jpg', NULL, 35, '2025-03-24 14:48:19'),
(27, 10, 'Blanc', '#FFFFFF', 'M', 'assets/images/teeshirtadidasfemme.jpg', NULL, 50, '2025-03-24 14:55:51'),
(28, 10, 'Rose', '#FF69B4', 'M', 'assets/images/teeshirtadidasfemme2.jpg', NULL, 45, '2025-03-24 14:55:51'),
(29, 10, 'Bleu', '#0000FF', 'M', 'assets/images/teeshirtadidasfemme3.jpg', NULL, 40, '2025-03-24 14:55:51'),
(30, 11, 'Noir', '#000000', 'S', 'assets/images/teeshirtteddyenfant.jpg', NULL, 35, '2025-03-24 15:05:06'),
(31, 11, 'Vert', '#008000', 'S', 'assets/images/teeshirtteddyenfant2.jpg', NULL, 30, '2025-03-24 15:05:06'),
(32, 11, 'Jaune', '#FFFF00', 'S', 'assets/images/teeshirtteddyenfant3.jpg', NULL, 28, '2025-03-24 15:05:06'),
(33, 11, 'Rouge', '#FF0000', 'S', 'assets/images/teeshirtteddyenfant4.jpg', NULL, 25, '2025-03-24 15:05:06'),
(34, 12, 'Beige', '#F5F5DC', 'S', 'assets/images/teeshirtnikenfant.jpg', NULL, 40, '2025-03-24 15:15:04'),
(35, 12, 'Blanc', '#FFFFFF', 'S', 'assets/images/teeshirtnikenfant2.jpg', NULL, 38, '2025-03-24 15:15:04'),
(36, 12, 'Noir', '#000000', 'S', 'assets/images/teeshirtnikenfant3.jpg', NULL, 35, '2025-03-24 15:15:04'),
(37, 12, 'Bleu', '#0000FF', 'S', 'assets/images/teeshirtnikenfant4.jpg', NULL, 32, '2025-03-24 15:15:04');

-- --------------------------------------------------------

--
-- Structure de la table `promotions`
--

CREATE TABLE `promotions` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `discount_percentage` int(11) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `promotions`
--

INSERT INTO `promotions` (`id`, `product_id`, `discount_percentage`, `start_date`, `end_date`, `created_at`) VALUES
(1, 26, 20, '2025-03-26 17:57:03', '2025-04-25 17:57:03', '2025-03-26 16:57:03');

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
(1, 'admin', 'admin@mgsstore.com', '$2y$10$Qk.gBdSgJhJ9t2pQy7qnM..I0GPc.eW6HgXyKVZAP/yvBsUDO1elq', 'Admin', 'MGS', NULL, NULL, NULL, NULL, NULL, 'admin', '2025-03-12 10:26:58', '2025-03-26 17:55:51', 0),
(3, 'mel20', 'melvinpradier@gmail.com', '$2y$10$2UNEsd3Ez9X7wd/IaOvPpe5RqrQ4RYWCUaHer7p/rHXjwgj7gY2ce', 'melvin', 'pradier', NULL, NULL, NULL, NULL, NULL, 'client', '2025-03-25 11:58:19', '2025-03-25 11:58:19', 0);

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
-- Index pour la table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `order_id` (`order_id`);

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
  ADD UNIQUE KEY `order_number` (`order_number`),
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
-- Index pour la table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Index pour la table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `newsletters`
--
ALTER TABLE `newsletters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT pour la table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- Contraintes pour la table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

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
