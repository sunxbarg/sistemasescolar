-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-06-2025 a las 02:56:58
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
-- Base de datos: `mi_proyecto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos`
--

CREATE TABLE `articulos` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `contenido` text NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `autor_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `destacado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `articulos`
--

INSERT INTO `articulos` (`id`, `titulo`, `contenido`, `imagen`, `autor_id`, `created_at`, `destacado`) VALUES
(25, 'Día del Padre 2023', 'Las celebraciones por el Día del Padre son un momento especial para millones en el mundo, ya que se celebra a uno de los miembros más queridos del hogar, que, junto a la madre, mantienen y dan soporte dentro de la familia. Desde la E.T.C. felicitamos a los Padres en su día, elevamos nuestra oración para que sean ejemplo de vida para sus hijos y esperamos que hayan disfrutado del homenaje que con alegría y entusiasmo preparamos desde la Coordinación de Cultura, a cargo de la profesora Claritza Sánchez para los profesores papás de la institución.', 'a4.jpg', NULL, '2024-06-26 01:29:42', 1),
(26, 'Búsqueda del Niño', 'La ETC Madre Rafols se une a la Búsqueda del niño.  Este viernes 02/02/2024 junto a nuestros estudiantes y en compañía del colegio Hmas. María Sorrosal, hicimos nuestro recorrido como todos los años en la tradicional Búsqueda del niño.  El robo del niño Jesús es una tradición popular basado en un episodio de la infancia de Jesús, el cual es relatado en el Evangelion de San Lucas.  Damos las gracias a todas las personas que participaron en esta actividad cultural, a los estudiantes, profesores, obreros y representantes que caminaron junto a nosotros y nos apoyan en cada momento.', 'pic01.jpg', NULL, '2024-06-26 01:30:13', 1),
(27, 'Cierre de Proyecto 2023-2024', 'Este 25 de Junio fue el cierre de proyecto de todos los años específicamente en las áreas de Orientación y Educación Religiosa.', 'pic04.jpg', NULL, '2024-06-26 14:31:06', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `id_articulo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `texto` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('visible','oculto') DEFAULT 'visible',
  `motivo_oculto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `comentarios`
--

INSERT INTO `comentarios` (`id`, `id_articulo`, `id_usuario`, `texto`, `fecha`, `estado`, `motivo_oculto`) VALUES
(3, 25, 3, 'Muy bien!', '2025-06-18 12:08:15', 'visible', NULL),
(4, 26, 4, 'Bien!!', '2025-06-18 15:05:44', 'oculto', 'No valido');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes`
--

CREATE TABLE `docentes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `destacado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `docentes`
--

INSERT INTO `docentes` (`id`, `nombre`, `apellido`, `email`, `telefono`, `created_at`, `destacado`) VALUES
(19, 'Claritza', 'Sanchéz', 'csanchez@gmail.com', '', '2024-06-26 14:06:55', 1),
(20, 'Roger', 'Rivero', 'rrivero@gmail.com', '', '2024-06-26 14:07:27', 0),
(21, 'Liana', 'Silva', 'lsilva@gmail.com', '', '2024-06-26 14:08:23', 0),
(22, '55555555555555', '55555555555555555', '55555555555555555@gmail.com', '555555555555555', '2025-06-18 18:31:49', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `rol` enum('admin','usuario') NOT NULL DEFAULT 'usuario',
  `email` varchar(100) NOT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `ultimo_comentario` timestamp NULL DEFAULT NULL,
  `pregunta_seguridad` varchar(255) NOT NULL,
  `respuesta_seguridad` varchar(255) NOT NULL,
  `intentos_fallidos` int(11) DEFAULT 0,
  `bloqueado_hasta` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `password`, `created_at`, `rol`, `email`, `foto_perfil`, `ultimo_comentario`, `pregunta_seguridad`, `respuesta_seguridad`, `intentos_fallidos`, `bloqueado_hasta`) VALUES
(1, 'admin', '$2y$10$P4bX7yUv7xj22aWpaNQyf.t/VQ4/myAPCp19ZzU6HGqI7TB/7iigS', '2014-12-11 04:00:00', 'admin', '', NULL, NULL, '', '', 0, NULL),
(3, 'robertos', '$2y$10$CiyLPtWtytJUzNztb68kwe1i6/NReRvSIK2X6HiZ3VdhR4IdcozEa', '2025-06-18 06:09:35', 'usuario', 'rorasaher@gmail.com', 'uploads/perfiles/perfil_3_1750263457.jpg', '2025-06-18 12:08:15', '¿En qué ciudad naciste?', '$2y$10$w8HmLx0FDPk9eN3pKsezi.fXcBTGLDQu7wZbW2Nqp0TGnQ2D5RCVi', 0, NULL),
(4, 'adrianm', '$2y$10$CCbmm7o/QfnlgUyx3zXxbucf2E72Jd9uPhw2a5YqmYSlOLJgVxXx.', '2025-06-18 15:05:02', 'usuario', 'aamg19203432@gmail.com', NULL, '2025-06-18 15:05:44', '¿En qué ciudad naciste?', '$2y$10$GR.k2SCrqGbUx0iZjTNyMul54y0sHTf2ggINi9YQVjUGtqc/LpsJS', 0, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `articulos`
--
ALTER TABLE `articulos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `autor_id` (`autor_id`),
  ADD KEY `created_at` (`created_at`);

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_articulo` (`id_articulo`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `fecha` (`fecha`);

--
-- Indices de la tabla `docentes`
--
ALTER TABLE `docentes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `articulos`
--
ALTER TABLE `articulos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `docentes`
--
ALTER TABLE `docentes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `articulos`
--
ALTER TABLE `articulos`
  ADD CONSTRAINT `articulos_ibfk_1` FOREIGN KEY (`autor_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`id_articulo`) REFERENCES `articulos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
