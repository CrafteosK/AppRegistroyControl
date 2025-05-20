-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-05-2025 a las 04:41:05
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `registro`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias`
--
DROP TABLE IF EXISTS `asistencias`;
CREATE TABLE `asistencias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `tipo_trabajador` varchar(50) NOT NULL,
  `tipo` enum('entrada','salida') NOT NULL,
  `hora` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asistencias`
--

INSERT INTO `asistencias` (`id`, `nombre`, `apellido`, `cedula`, `tipo_trabajador`, `tipo`, `hora`) VALUES
(1, 'Juan', 'Pérez', '12345678', 'Vigilante', 'entrada', '2025-05-13 06:11:33'),
(2, 'Juan', 'Pérez', '12345678', 'Vigilante', 'salida', '2025-05-13 06:11:38'),
(3, 'variedades', 'Yose', '28688249', 'Vigilante', 'entrada', '2025-05-13 06:15:09'),
(4, 'kervin', 'diaz', '30993371', 'Cocinero', 'entrada', '2025-05-13 06:15:14'),
(5, 'variedades', 'Yose', '28688249', 'Vigilante', 'salida', '2025-05-13 06:15:19'),
(6, 'kervin', 'diaz', '30993371', 'Cocinero', 'entrada', '2025-05-13 06:15:23'),
(7, 'kervin', 'diaz', '30993371', 'Cocinero', 'salida', '2025-05-13 06:15:29'),
(8, 'susu ledys', 'jkjkj', '12761541', 'Obrero', 'entrada', '2025-05-13 06:51:49'),
(9, 'susu ledys', 'jkjkj', '12761541', 'Obrero', 'salida', '2025-05-13 06:51:53'),
(10, 'variedades', 'Yose', '28688249', 'Vigilante', 'entrada', '2025-05-17 06:57:42'),
(11, 'variedades', 'Yose', '28688249', 'Vigilante', 'salida', '2025-05-17 06:57:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargos`
--
DROP TABLE IF EXISTS `cargos`;
CREATE TABLE `cargos` (
  `id_cargo` int(11) NOT NULL,
  `cargo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cargos`
--

INSERT INTO `cargos` (`id_cargo`, `cargo`) VALUES
(1, 'Cocinero'),
(2, 'Maestro'),
(3, 'Vigilante'),
(4, 'Obrero');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `level_user`
--
DROP TABLE IF EXISTS `level_user`;
CREATE TABLE `level_user` (
  `id` int(11) NOT NULL,
  `roles` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `level_user`
--

INSERT INTO `level_user` (`id`, `roles`) VALUES
(1, 'Administrador'),
(2, 'Moderador'),
(3, 'Usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medical_rest`
--
DROP TABLE IF EXISTS `medical_rest`;
CREATE TABLE `medical_rest` (
  `id` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  `e` date NOT NULL,
  `Vence` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajadores`
--
DROP TABLE IF EXISTS `trabajadores`;
CREATE TABLE `trabajadores` (
  `id_trabajador` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `cargos` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `trabajadores`
--

INSERT INTO `trabajadores` (`id_trabajador`, `nombre`, `apellido`, `cedula`, `telefono`, `cargos`) VALUES
(30, 'variedades', 'Yose', '28688249', '04148503709', '3'),
(31, 'susu ledys', 'jkjkj', '12761541', '04148503709', '4');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `ID` int(11) NOT NULL,
  `nombre_completo` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `user` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol_id` int(11) NOT NULL DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`ID`, `nombre_completo`, `email`, `user`, `password`, `rol_id`) VALUES
(1, 'Kervin Días ', 'kervindiaz2017@gmail.com', 'Craft', 'd404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db', 1),
(18, 'maria gh', 'siontvsports@gmail.com', 'guy58', '40883b2b2bed501ba260864d6ad8821ccb8dda7ef84d21e1e884b3cc0bea04d88135089bd576ce1a5d828f6c1338963380afff2ef4623c63793d6439853da03b', 3),
(19, 'maria d', 'kervindiaz2021@gmail.com', 'guy', '450932b36461918a013b8d6cdf7491c9c601f49e71902d502daf8cdc8734cef65ed8e102cfa321b3441c87650751724fb3c15d32fa86bb1b5e6d2dc60ba86228', 2),
(20, 'maria f', 'elieljuliansanchez@gmail.com', 'kervin', '106e5684fad4d2adfebfd5e0225b337ca21a786ab3fb076e78119bd002913f524536235d6d029cdef99399138e22db7d7bdbbf94538e2692be21bc03af0694f9', 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cargos`
--
ALTER TABLE `cargos`
  ADD PRIMARY KEY (`id_cargo`);

--
-- Indices de la tabla `level_user`
--
ALTER TABLE `level_user`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `medical_rest`
--
ALTER TABLE `medical_rest`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_medical_rest_trabajadores` (`id_trabajador`);

--
-- Indices de la tabla `trabajadores`
--
ALTER TABLE `trabajadores`
  ADD PRIMARY KEY (`id_trabajador`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `cargos`
--
ALTER TABLE `cargos`
  MODIFY `id_cargo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `level_user`
--
ALTER TABLE `level_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `medical_rest`
--
ALTER TABLE `medical_rest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `trabajadores`
--
ALTER TABLE `trabajadores`
  MODIFY `id_trabajador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `medical_rest`
--
ALTER TABLE `medical_rest`
  ADD CONSTRAINT `fk_medical_rest_trabajadores` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
