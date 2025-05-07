-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-05-2025 a las 12:49:51
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
-- Estructura de tabla para la tabla `cargos`
--

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
-- Estructura de tabla para la tabla `cocineros`
--

CREATE TABLE `cocineros` (
  `id` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  `tipo` enum('entrada','salida') NOT NULL,
  `hora` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cocineros`
--

INSERT INTO `cocineros` (`id`, `id_trabajador`, `tipo`, `hora`) VALUES
(1, 18, 'entrada', '2025-04-19 00:14:00'),
(2, 18, 'salida', '2025-04-19 00:14:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `level_user`
--

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
-- Estructura de tabla para la tabla `maestros`
--

CREATE TABLE `maestros` (
  `id` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  `tipo` enum('entrada','salida') NOT NULL,
  `hora` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `maestros`
--

INSERT INTO `maestros` (`id`, `id_trabajador`, `tipo`, `hora`) VALUES
(1, 21, 'entrada', '2025-04-19 00:10:51'),
(2, 21, 'salida', '2025-04-19 00:10:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medical_rest`
--

CREATE TABLE `medical_rest` (
  `id` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  `e` date NOT NULL,
  `Vence` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `medical_rest`
--

INSERT INTO `medical_rest` (`id`, `id_trabajador`, `e`, `Vence`) VALUES
(3, 20, '2025-05-05', '2025-05-29'),
(4, 19, '2025-04-07', '2025-05-05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `obreros`
--

CREATE TABLE `obreros` (
  `id` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  `tipo` enum('entrada','salida') NOT NULL,
  `hora` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `obreros`
--

INSERT INTO `obreros` (`id`, `id_trabajador`, `tipo`, `hora`) VALUES
(1, 20, 'entrada', '2025-04-19 00:06:44'),
(2, 20, 'salida', '2025-04-19 00:06:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajadores`
--

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
(18, 'Pedro', 'Diaz', '25647980', '04242222222', '1'),
(19, 'Juan', 'Pérez', '12345678', '04240000000', '3'),
(20, 'Angeli', 'Mari', '0000000', '04249152713', '4'),
(21, 'Maria', 'Suarez', '6574321', '04242222222', '2'),
(22, 'Maria', 'Suarez', '25647980', '04242222222', '3'),
(23, '1', '1', '52399411', '04242222222', '3'),
(24, '2', '2', '3658446', '04259631269', '4'),
(25, '3', '3', '36985471', '25136987452', '2'),
(26, '4', '4', '32145698', '25478963221', '1'),
(27, '5', '5', '15695423', '25647895643', '3'),
(28, '6', '6', '36521477', '25413336986', '4');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

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
(1, 'Kervin Días ', 'kervindiaz2017@gmail.com', 'Craft', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vigilantes`
--

CREATE TABLE `vigilantes` (
  `id` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  `tipo` enum('entrada','salida') NOT NULL,
  `hora` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vigilantes`
--

INSERT INTO `vigilantes` (`id`, `id_trabajador`, `tipo`, `hora`) VALUES
(1, 19, 'entrada', '2025-04-18 21:09:00'),
(2, 19, 'salida', '2025-04-19 00:02:00'),
(3, 19, 'entrada', '2025-04-19 00:27:00'),
(4, 19, 'salida', '2025-04-19 00:27:00'),
(5, 19, 'entrada', '2025-05-05 12:39:00'),
(6, 19, 'salida', '2025-05-05 12:39:00');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cargos`
--
ALTER TABLE `cargos`
  ADD PRIMARY KEY (`id_cargo`);

--
-- Indices de la tabla `cocineros`
--
ALTER TABLE `cocineros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_trabajador` (`id_trabajador`);

--
-- Indices de la tabla `level_user`
--
ALTER TABLE `level_user`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `maestros`
--
ALTER TABLE `maestros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_trabajador` (`id_trabajador`);

--
-- Indices de la tabla `medical_rest`
--
ALTER TABLE `medical_rest`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_medical_rest_trabajadores` (`id_trabajador`);

--
-- Indices de la tabla `obreros`
--
ALTER TABLE `obreros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_trabajador` (`id_trabajador`);

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
-- Indices de la tabla `vigilantes`
--
ALTER TABLE `vigilantes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_trabajador` (`id_trabajador`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cargos`
--
ALTER TABLE `cargos`
  MODIFY `id_cargo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `cocineros`
--
ALTER TABLE `cocineros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `level_user`
--
ALTER TABLE `level_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `maestros`
--
ALTER TABLE `maestros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `medical_rest`
--
ALTER TABLE `medical_rest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `obreros`
--
ALTER TABLE `obreros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `trabajadores`
--
ALTER TABLE `trabajadores`
  MODIFY `id_trabajador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `vigilantes`
--
ALTER TABLE `vigilantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cocineros`
--
ALTER TABLE `cocineros`
  ADD CONSTRAINT `cocineros_ibfk_1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`);

--
-- Filtros para la tabla `maestros`
--
ALTER TABLE `maestros`
  ADD CONSTRAINT `maestros_ibfk_1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`);

--
-- Filtros para la tabla `medical_rest`
--
ALTER TABLE `medical_rest`
  ADD CONSTRAINT `fk_medical_rest_trabajadores` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `obreros`
--
ALTER TABLE `obreros`
  ADD CONSTRAINT `obreros_ibfk_1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`);

--
-- Filtros para la tabla `vigilantes`
--
ALTER TABLE `vigilantes`
  ADD CONSTRAINT `vigilantes_ibfk_1` FOREIGN KEY (`id_trabajador`) REFERENCES `trabajadores` (`id_trabajador`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
