-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 22-08-2024 a las 15:50:16
-- Versión del servidor: 8.0.31
-- Versión de PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `fsc`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fsc_countries`
--

DROP TABLE IF EXISTS `fsc_countries`;
CREATE TABLE IF NOT EXISTS `fsc_countries` (
  `country_id` int NOT NULL AUTO_INCREMENT,
  `country` varchar(80) NOT NULL,
  PRIMARY KEY (`country_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `fsc_countries`
--

INSERT INTO `fsc_countries` (`country_id`, `country`) VALUES
(1, 'Argentina'),
(2, 'Estados Unidos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fsc_users`
--

DROP TABLE IF EXISTS `fsc_users`;
CREATE TABLE IF NOT EXISTS `fsc_users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `user_role_id` int NOT NULL,
  `name` varchar(20) NOT NULL,
  `surname` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` text NOT NULL,
  `registration_date` date NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `fsc_users`
--

INSERT INTO `fsc_users` (`user_id`, `user_role_id`, `name`, `surname`, `email`, `password`, `registration_date`) VALUES
(1, 1, 'Lucas', 'Marini', 'lucas.marini@rednodo.com', '$2y$10$FdwUDuBqalZkRCdYNzWCgeO5v0zRYIp01uu0m8aklfXLmnDtug8ya', '2024-08-20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fsc_user_roles`
--

DROP TABLE IF EXISTS `fsc_user_roles`;
CREATE TABLE IF NOT EXISTS `fsc_user_roles` (
  `user_role_id` int NOT NULL AUTO_INCREMENT,
  `user_role` varchar(20) NOT NULL,
  PRIMARY KEY (`user_role_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `fsc_user_roles`
--

INSERT INTO `fsc_user_roles` (`user_role_id`, `user_role`) VALUES
(1, 'Administrator'),
(2, 'Client');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
