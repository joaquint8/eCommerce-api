-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: mysql:3306
-- Tiempo de generación: 06-08-2025 a las 03:41:38
-- Versión del servidor: 5.7.44
-- Versión de PHP: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `eCommerce`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Category`
--

CREATE TABLE `Category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `creation_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Category`
--

INSERT INTO `Category` (`id`, `name`, `description`, `creation_date`, `deleted`) VALUES
(1, 'pantalonesEDITADO', 'pantalonesEDITADO', '2025-07-28 20:21:19', 0),
(2, 'remeras', 'prenda de ropa remeras', '2025-07-28 20:21:19', 0),
(5, 'ejemplo', 'ejemplo', '2025-07-31 15:15:47', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Product`
--

CREATE TABLE `Product` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT '0',
  `state` enum('active','inactive','out_of_stock','hidden') NOT NULL DEFAULT 'active',
  `imageUrl` varchar(255) NOT NULL,
  `creation_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `categoryId` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Product`
--

INSERT INTO `Product` (`id`, `name`, `description`, `price`, `stock`, `state`, `imageUrl`, `creation_date`, `categoryId`, `deleted`) VALUES
(1, 'remera levis L', 'remera talle L over', 55000, 7, 'active', '', '2025-07-28 20:34:30', 2, 0),
(2, 'remera2', 'remera xl', 52777, 3, 'active', '', '2025-07-28 20:39:08', 2, 0),
(3, 'remera boxy fit', 'no se', 88000, 8, 'active', '', '2025-07-28 20:39:08', 2, 0),
(4, 'pantapalon1', 'alto lompa', 50000, 20, 'active', '', '2025-07-28 20:40:00', 1, 0),
(5, 'lompa2', 'otro lompa', 40000, 2, 'active', '', '2025-07-28 20:40:22', 1, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Category`
--
ALTER TABLE `Category`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `Product`
--
ALTER TABLE `Product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_category` (`categoryId`) USING BTREE;

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Category`
--
ALTER TABLE `Category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `Product`
--
ALTER TABLE `Product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
