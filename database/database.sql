-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-05-2026 a las 01:02:45
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE DATABASE IF NOT EXISTS sistema_envios;

USE sistema_envios;

--
-- Base de datos: `sistema_envios`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `envio`
--

CREATE TABLE `envio` (
  `id_envio` int(11) NOT NULL,
  `codigo_guia` varchar(30) NOT NULL,
  `id_usuario_remitente` int(11) NOT NULL,
  `nombre_destinatario` varchar(100) NOT NULL,
  `telefono_destinatario` varchar(20) NOT NULL,
  `direccion_destinatario` varchar(255) NOT NULL,
  `descripcion_paquete` varchar(255) NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `estado_actual_id` int(11) NOT NULL,
  `observaciones` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `envio`
--

INSERT INTO `envio` (`id_envio`, `codigo_guia`, `id_usuario_remitente`, `nombre_destinatario`, `telefono_destinatario`, `direccion_destinatario`, `descripcion_paquete`, `fecha_registro`, `estado_actual_id`, `observaciones`) VALUES
(1, 'ENV-20260519-264974', 1, 'Luis Fernando', '46588636', 'Calzada Roosevelth 13-46', 'Caja pequeña', '2026-05-18 18:19:14', 1, 'Caja pequeña'),
(2, 'ENV-20260519-A6E436', 1, 'Luis Fernando', '46588636', 'Calzada Roosevelth 13-46', 'Caja pequeña', '2026-05-18 18:20:42', 1, 'Caja pequeña'),
(3, 'ENV-20260519-C34A10', 1, 'Luis Fernando', '46588636', 'Calzada Roosevelth 13-46', 'Caja pequeña', '2026-05-18 18:20:44', 3, 'Caja pequeña'),
(4, 'ENV-20260519-44CEB4', 1, 'adasd', '46886699', 'casa 1', 'Paquete fragil', '2026-05-18 18:25:24', 2, ''),
(5, 'ENV-20260519-C4FD99', 1, 'Fernando Colindres', '58586969', 'Calzada Roosevelth 13-46 zona 7 ciudad de Guatemala', 'Caja pequeña fragil', '2026-05-18 19:25:13', 1, 'Entregar en horario de  8-4');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `id_estado` int(11) NOT NULL,
  `nombre_estado` varchar(50) NOT NULL,
  `descripcion` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`id_estado`, `nombre_estado`, `descripcion`) VALUES
(1, 'Registrado', 'El envío fue registrado en el sistema'),
(2, 'En proceso', 'El envío está siendo procesado'),
(3, 'En ruta', 'El envío está en camino'),
(4, 'Entregado', 'El envío fue entregado'),
(5, 'Cancelado', 'El envío fue cancelado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historia_estado`
--

CREATE TABLE `historia_estado` (
  `id_historial` int(11) NOT NULL,
  `id_envio` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL DEFAULT current_timestamp(),
  `comentario` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `historia_estado`
--

INSERT INTO `historia_estado` (`id_historial`, `id_envio`, `id_estado`, `id_usuario`, `fecha_hora`, `comentario`) VALUES
(1, 1, 1, 1, '2026-05-18 18:19:14', 'Envío registrado en el sistema'),
(2, 2, 1, 1, '2026-05-18 18:20:42', 'Envío registrado en el sistema'),
(3, 3, 1, 1, '2026-05-18 18:20:44', 'Envío registrado en el sistema'),
(4, 4, 1, 1, '2026-05-18 18:25:24', 'Envío registrado en el sistema'),
(5, 3, 3, 1, '2026-05-18 18:32:29', 'Paquete en ruta de entrega #2'),
(6, 4, 3, 1, '2026-05-18 18:42:18', 'En ruta #2'),
(7, 4, 2, 1, '2026-05-18 18:43:09', 'En proceso de recolección por parte de usuario'),
(8, 4, 2, 1, '2026-05-18 18:48:26', 'En proceso de recolección por parte de usuario'),
(9, 5, 1, 1, '2026-05-18 19:25:13', 'Envío registrado en el sistema');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nombre`) VALUES
(1, 'Administrador'),
(2, 'Operador'),
(3, 'Usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `correo` varchar(120) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `id_rol`, `nombres`, `apellidos`, `correo`, `password_hash`, `telefono`, `direccion`, `estado`) VALUES
(1, 1, 'Admin', 'Sistema', 'admin@sistema.com', '123456', '00000000', 'Sistema', 'activo');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `envio`
--
ALTER TABLE `envio`
  ADD PRIMARY KEY (`id_envio`),
  ADD UNIQUE KEY `codigo_guia` (`codigo_guia`),
  ADD KEY `fk_envio_usuario` (`id_usuario_remitente`),
  ADD KEY `fk_envio_estado_actual` (`estado_actual_id`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`id_estado`),
  ADD UNIQUE KEY `nombre_estado` (`nombre_estado`);

--
-- Indices de la tabla `historia_estado`
--
ALTER TABLE `historia_estado`
  ADD PRIMARY KEY (`id_historial`),
  ADD KEY `fk_historial_envio` (`id_envio`),
  ADD KEY `fk_historial_estado` (`id_estado`),
  ADD KEY `fk_historial_usuario` (`id_usuario`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `fk_usuario_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `envio`
--
ALTER TABLE `envio`
  MODIFY `id_envio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `historia_estado`
--
ALTER TABLE `historia_estado`
  MODIFY `id_historial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `envio`
--
ALTER TABLE `envio`
  ADD CONSTRAINT `fk_envio_estado_actual` FOREIGN KEY (`estado_actual_id`) REFERENCES `estado` (`id_estado`),
  ADD CONSTRAINT `fk_envio_usuario` FOREIGN KEY (`id_usuario_remitente`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `historia_estado`
--
ALTER TABLE `historia_estado`
  ADD CONSTRAINT `fk_historial_envio` FOREIGN KEY (`id_envio`) REFERENCES `envio` (`id_envio`),
  ADD CONSTRAINT `fk_historial_estado` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`),
  ADD CONSTRAINT `fk_historial_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_rol` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
