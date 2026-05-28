-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 27, 2026 at 09:49 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vinshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int UNSIGNED NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('percentage','fixed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'percentage',
  `value` decimal(10,2) NOT NULL,
  `min_amount` decimal(10,2) DEFAULT NULL COMMENT 'Montant minimum de commande requis',
  `max_uses` int DEFAULT NULL COMMENT 'Nombre maximum d''utilisations (NULL = illimité)',
  `times_used` int NOT NULL DEFAULT '0',
  `valid_from` datetime DEFAULT NULL,
  `valid_until` datetime DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `first_order_only` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Si 1, le coupon est réservé aux clients sans commande antérieure',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `type`, `value`, `min_amount`, `max_uses`, `times_used`, `valid_from`, `valid_until`, `active`, `first_order_only`, `description`, `created_at`, `updated_at`) VALUES
(1, 'BIENVENUE10', 'percentage', '10.00', '50.00', NULL, 0, '2025-10-16 14:49:00', '2026-10-16 14:49:00', 1, 1, 'Réduction de 10% pour les nouveaux clients', '2025-10-16 12:49:49', '2025-10-17 14:28:41'),
(2, 'SOLDES20', 'percentage', '20.00', '100.00', 100, 0, '2025-10-16 14:49:49', '2025-11-15 14:49:49', 1, 0, 'Soldes de printemps - 20% de réduction', '2025-10-16 12:49:49', '2025-10-24 11:41:54'),
(3, 'PROMO5EUR', 'fixed', '5.00', '30.00', 50, 0, '2025-10-16 14:49:49', '2025-10-31 14:49:49', 0, 0, 'Réduction de 5€ sur votre commande', '2025-10-16 12:49:49', '2025-10-20 07:00:05');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `coupon_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT '0.00',
  `status` enum('pending','processing','shipped','delivered','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `payment_method` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `shipping_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `coupon_code`, `discount_amount`, `status`, `payment_method`, `shipping_address`, `notes`, `created_at`, `updated_at`) VALUES
(1, 2, '45.00', NULL, '0.00', 'processing', 'card', '456 Avenue Client, Lyon', '', '2025-10-05 10:05:56', '2025-10-05 10:16:26'),
(2, 1, '140.00', NULL, '0.00', 'pending', 'paypal', '123 Rue Admin, Paris', 'petit note ^^', '2025-10-16 09:48:45', '2025-10-16 09:48:45'),
(3, 1, '250.00', NULL, '0.00', 'pending', 'card', '123 Rue Admin, Paris', '', '2025-10-17 14:27:12', '2025-10-17 14:27:12'),
(4, 1, '125.00', NULL, '0.00', 'pending', 'paypal', '123 Rue Admin, Paris', 'sdffsdfsdffdsfds', '2025-10-22 09:01:00', '2025-10-22 09:01:00'),
(5, 1, '350.00', NULL, '0.00', 'pending', 'paypal', '123 Rue Admin, Paris', '', '2025-11-27 10:39:27', '2025-11-27 10:39:27');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_price`, `quantity`, `subtotal`) VALUES
(1, 1, 1, 'Veste en Jean Vintage', '45.00', 1, '45.00'),
(2, 2, 3, 'Lampe Design Scandinave', '35.00', 4, '140.00'),
(3, 3, 10, 'Table de ping pong', '250.00', 1, '250.00'),
(4, 4, 8, 'Raquette de tennis', '125.00', 1, '125.00'),
(5, 5, 9, 'Billard américain', '350.00', 1, '350.00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `category` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `condition` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `stock` int DEFAULT '1',
  `seller_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `description`, `price`, `image`, `category`, `condition`, `stock`, `seller_id`, `created_at`, `updated_at`) VALUES
(1, 'Veste en Jean Vintage', 'veste-en-jean-vintage-1', 'Magnifique veste en jean vintage des années 90. Coupe classique, très bon état. Parfaite pour un look décontracté et tendance. Taille M unisexe.', '45.00', 'veste-jean.jpg', 'Vêtements', 'Très bon état', 1, 2, '2025-10-05 09:49:42', '2025-10-16 11:45:18'),
(2, 'iPhone 12 Pro 128GB', 'iphone-12-pro-128gb-2', 'iPhone 12 Pro en excellent état, 128GB de stockage. Batterie à 89% de capacité. Aucune rayure, toujours protégé par une coque. Livré avec câble Lightning et chargeur.', '549.00', 'iphone12.jpg', 'Électronique', 'Comme neuf', 1, 2, '2025-10-05 09:49:42', '2025-10-16 11:27:40'),
(3, 'Lampe Design Scandinave', 'lampe-design-scandinave-3', 'Superbe lampe de table au design scandinave minimaliste. Bois naturel et abat-jour en lin blanc. Crée une ambiance chaleureuse et cosy.', '35.00', 'lampe-scandinave.jpg', 'Maison', 'Comme neuf', 5, 3, '2025-10-05 09:49:42', '2025-10-16 11:32:08'),
(4, 'Baskets Nike Air Max 90', 'baskets-nike-air-max-90-4', 'Paire de Nike Air Max 90 blanches et grises. Taille 42. Portées quelques fois, en excellent état. Semelles comme neuves.', '85.00', 'nike-airmax.jpg', 'Vêtements', 'Très bon état', 1, 3, '2025-10-05 09:49:42', '2025-10-16 11:27:40'),
(5, 'Console Nintendo Switch', 'console-nintendo-switch-5', 'Nintendo Switch V2 avec meilleure autonomie. Complète avec dock, manettes Joy-Con rouge et bleue, câbles et boîte.', '259.00', 'nintendo-switch.jpg', 'Électronique', 'Bon état', 1, 2, '2025-10-05 09:49:42', '2025-10-16 11:27:40'),
(6, 'Sac à Main Cuir Marron', 'sac-a-main-cuir-marron-6', 'Élégant sac à main en cuir véritable marron cognac. Grande capacité avec plusieurs compartiments. Fermeture éclair et bandoulière réglable.', '68.00', 'sac-cuir.jpg', 'Vêtements', 'Bon état', 1, 3, '2025-10-05 09:49:42', '2025-10-16 11:27:40'),
(7, 'Game Cube', 'game-cube-7', 'Le Nintendo Game Cube est une console de jeu vidéo de deuxième génération en forme de cube. Le processeur cadencé à 485 MHz spécialement optimisé, utilisant la technologie high-tech cuivre, est complété par un matériel d\'avant-garde qui élimine toute barrière entre le monde réel et celui du jeu vidéo.', '60.00', '1760614699_gamecube.jpg', 'Électronique', 'Très bon état', 2, 1, '2025-10-16 11:38:19', '2025-10-16 11:38:19'),
(8, 'Raquette de tennis', 'raquette-de-tennis-8', 'Nos concepteurs ont développé cette raquette en graphite pour vous apporter la meilleure maniabilité lors de vos premiers entraînements.\r\n\r\nCette raquette de tennis vous procure une excellente maniabilité grâce à son compromis poids / équilibre. Elle a été développée en collaboration avec nos enseignants diplômés.', '125.00', '1760707452_raquette.jpg', 'Sport', 'Très bon état', 2, 1, '2025-10-17 13:24:12', '2025-10-22 09:01:00'),
(9, 'Billard américain', 'billard-americain-9', 'Billard américain. Avec retour de boules automatique. Set ultra complet avec une multitude d\'accessoires inclus. Dimension de la table : 213 x 122 x 81 cm', '350.00', '1760707579_billard.jpg', 'Sport', 'Comme neuf', 1, 1, '2025-10-17 13:26:19', '2025-11-27 10:39:27'),
(10, 'Table de ping pong', 'table-de-ping-pong-10', 'Conçue par nos pongistes pour jouer au ping pong loisir toutes les semaines. Découvrez le tennis de table avec une table aux coins arrondis & plus compacte.\r\n\r\nRobuste, stable avec ses deux freins, la table de ping pong PPT 530.2 est facile à plier et à déplacer. Elle vous suivra sur tous les terrains, extérieur et intérieur. Votre partenaire de jeu idéal !', '250.00', '1760707676_pingpong.jpg', 'Sport', 'Très bon état', 0, 1, '2025-10-17 13:27:56', '2025-10-17 14:27:12');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `user_id` int NOT NULL,
  `rating` tinyint NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `rating`, `comment`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 4, 'Super merci envoie rapide, marche très bien !', 'approved', '2025-10-16 12:23:31', '2025-10-16 12:24:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `last_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `role` enum('user','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `first_name`, `last_name`, `phone`, `address`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@vinshop.com', '$2y$10$XaSSp13yRMGqRzFmQFqF1eZbFwcElJLyfXTckhYTXZ0NL3N3wWaLa', 'Admin', 'VinShop', '0600000000', '123 Rue Admin, Paris', 'admin', '2025-10-05 09:49:42', '2025-10-05 10:00:55'),
(2, 'john_doe', 'john@example.com', '$2y$10$XaSSp13yRMGqRzFmQFqF1eZbFwcElJLyfXTckhYTXZ0NL3N3wWaLa', 'John', 'Doe', '0612345678', '456 Avenue Client, Lyon', 'user', '2025-10-05 09:49:42', '2025-10-05 10:00:58'),
(3, 'jane_smith', 'jane@example.com', '$2y$10$XaSSp13yRMGqRzFmQFqF1eZbFwcElJLyfXTckhYTXZ0NL3N3wWaLa', 'Jane', 'Smith', '0623456789', '789 Boulevard User, Marseille', 'user', '2025-10-05 09:49:42', '2025-10-05 10:01:00');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`, `created_at`) VALUES
(22, 1, 8, '2025-12-03 18:43:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD UNIQUE KEY `unique_code` (`code`),
  ADD KEY `idx_active` (`active`),
  ADD KEY `idx_code_active` (`code`,`active`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order` (`order_id`),
  ADD KEY `idx_product` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug_unique` (`slug`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_seller` (`seller_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_product_status` (`product_id`,`status`),
  ADD KEY `idx_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_username` (`username`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_product` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_user` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
