-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 12-08-2025 a las 20:52:12
-- Versión del servidor: 8.3.0
-- Versión de PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ssy`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ssy_articles`
--

DROP TABLE IF EXISTS `ssy_articles`;
CREATE TABLE IF NOT EXISTS `ssy_articles` (
  `article_id` int NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `excerpt` text NOT NULL,
  `body` text NOT NULL,
  `meta_title` text NOT NULL,
  `meta_description` text NOT NULL,
  `url_slug` text NOT NULL,
  `category_id` int NOT NULL,
  `publish_date` date NOT NULL,
  PRIMARY KEY (`article_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `ssy_articles`
--

INSERT INTO `ssy_articles` (`article_id`, `title`, `excerpt`, `body`, `meta_title`, `meta_description`, `url_slug`, `category_id`, `publish_date`) VALUES
(1, 'aaaa', 'aaaa', '<p>aaaaaaaa</p>', 'aaaa | Article | Smooth Sailing Yachts', 'aaaa article of Smooth Sailing Yachts', '/aaaa', 1, '2025-08-12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ssy_article_images`
--

DROP TABLE IF EXISTS `ssy_article_images`;
CREATE TABLE IF NOT EXISTS `ssy_article_images` (
  `image_id` int NOT NULL AUTO_INCREMENT,
  `source` text NOT NULL,
  `order_position` text NOT NULL,
  `article_id` int NOT NULL,
  `alt_text` text NOT NULL,
  PRIMARY KEY (`image_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `ssy_article_images`
--

INSERT INTO `ssy_article_images` (`image_id`, `source`, `order_position`, `article_id`, `alt_text`) VALUES
(1, '1755029658-0.png', '0', 1, 'Article Image'),
(2, '1755029658-1.png', '1', 1, 'Article Image');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ssy_article_tags`
--

DROP TABLE IF EXISTS `ssy_article_tags`;
CREATE TABLE IF NOT EXISTS `ssy_article_tags` (
  `article_tag_id` int NOT NULL AUTO_INCREMENT,
  `article_id` int NOT NULL,
  `tag_id` int NOT NULL,
  PRIMARY KEY (`article_tag_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `ssy_article_tags`
--

INSERT INTO `ssy_article_tags` (`article_tag_id`, `article_id`, `tag_id`) VALUES
(1, 1, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ssy_categories`
--

DROP TABLE IF EXISTS `ssy_categories`;
CREATE TABLE IF NOT EXISTS `ssy_categories` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_name` text NOT NULL,
  `meta_title` text NOT NULL,
  `meta_description` text NOT NULL,
  `url_slug` text NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `ssy_categories`
--

INSERT INTO `ssy_categories` (`category_id`, `category_name`, `meta_title`, `meta_description`, `url_slug`) VALUES
(1, 'River Plate', 'River Plate | Category | Smooth Sailing Yachts', 'River Plate category of Smooth Sailing Yachts', '/river-plate');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ssy_tags`
--

DROP TABLE IF EXISTS `ssy_tags`;
CREATE TABLE IF NOT EXISTS `ssy_tags` (
  `tag_id` int NOT NULL AUTO_INCREMENT,
  `tag_name` text NOT NULL,
  `meta_title` text NOT NULL,
  `meta_description` text NOT NULL,
  `url_slug` text NOT NULL,
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `ssy_tags`
--

INSERT INTO `ssy_tags` (`tag_id`, `tag_name`, `meta_title`, `meta_description`, `url_slug`) VALUES
(2, 'tag', 'tag | Tag | Smooth Sailing Yachts', 'tag tag of Smooth Sailing Yachts', '/tag'),
(3, 'tag2', 'tag2 | Tag | Smooth Sailing Yachts', 'tag2 tag of Smooth Sailing Yachts', '/tag2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ssy_users`
--

DROP TABLE IF EXISTS `ssy_users`;
CREATE TABLE IF NOT EXISTS `ssy_users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `ssy_users`
--

INSERT INTO `ssy_users` (`user_id`, `username`, `email`, `password`, `first_name`, `last_name`) VALUES
(1, 'admin', 'lucas.marini@rednodo.com', '$2y$10$FdwUDuBqalZkRCdYNzWCgeO5v0zRYIp01uu0m8aklfXLmnDtug8ya', 'Lucas', 'Marini');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
