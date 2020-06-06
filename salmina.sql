-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  ven. 29 mai 2020 à 23:33
-- Version du serveur :  10.4.10-MariaDB
-- Version de PHP :  7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `salmina`
--

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `current_price` decimal(10,0) DEFAULT NULL,
  `alcohol_grams` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `product_name`, `current_price`, `alcohol_grams`) VALUES
(1, 'Bière', '2', 24),
(2, 'Pastis', '2', 18);

-- --------------------------------------------------------

--
-- Structure de la table `sales`
--

DROP TABLE IF EXISTS `sales`;
CREATE TABLE IF NOT EXISTS `sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `buyer_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `sale_datetime` datetime DEFAULT NULL,
  `product_price` decimal(10,2) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `alcoholblood_permil` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `buyer_id` (`buyer_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `weight` int(11) DEFAULT NULL,
  `sex_male` tinyint(1) DEFAULT NULL,
  `alcohol_coef` float NOT NULL COMMENT '1000/(weight*1000*0.68)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `full_name`, `created_at`, `weight`, `sex_male`, `alcohol_coef`) VALUES
(1, 'Gabor', '2020-05-29 21:22:26', 63, 1, 0.0233),
(2, 'Jérémie', '2020-05-29 21:21:55', 70, 1, 0.021),
(3, 'Loris', '2020-05-25 14:17:39', 58, 1, 0.0253),
(4, 'Luca', '2020-05-25 14:33:30', 63, 1, 0.0233),
(5, 'Alex', '2020-05-25 11:08:05', 65, 1, 0.0226244),
(6, 'Nico', '2020-05-25 11:08:05', 76, 1, 0.0193498),
(7, 'Charlotte', '2020-05-25 14:34:15', 52, 0, 0.0349),
(9, 'Laura', '2020-05-25 14:16:30', 55, 0, 0.033),
(10, 'Jan', '2020-05-25 14:15:50', 72, 1, 0.0204),
(11, 'Pablo', '2020-05-25 14:27:42', 80, 1, 0.0183),
(12, 'Olivier', '2020-05-25 19:23:12', 83, 1, 0.019);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
