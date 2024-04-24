-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 24-04-2024 a las 13:35:34
-- Versión del servidor: 8.2.0
-- Versión de PHP: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bd_todo_list`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tareas`
--

DROP TABLE IF EXISTS `tareas`;
CREATE TABLE IF NOT EXISTS `tareas` (
  `id_tarea` int NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `hora` time(6) NOT NULL,
  `titulo` varchar(35) NOT NULL,
  `imagen` varchar(45) NOT NULL,
  `descripcion` varchar(128) NOT NULL,
  `prioridad` varchar(25) NOT NULL,
  `lugar` varchar(25) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '0',
  `id_cat` int NOT NULL,
  PRIMARY KEY (`id_tarea`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `tareas`
--

INSERT INTO `tareas` (`id_tarea`, `fecha`, `hora`, `titulo`, `imagen`, `descripcion`, `prioridad`, `lugar`, `estado`, `id_cat`) VALUES
(8, '2024-04-25', '16:10:00.000000', 'Examen dia 25', '1713963989-Sin título-1.png', '<p>Examen Cliente</p>\r\n', 'alta', 'Clase', 1, 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
