-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-12-2021 a las 23:56:05
-- Versión del servidor: 10.4.19-MariaDB
-- Versión de PHP: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `psicoapp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas`
--

CREATE TABLE `preguntas` (
  `id_pregunta` int(20) NOT NULL,
  `texto_pregunta` varchar(90) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_romanian_ci;

--
-- Volcado de datos para la tabla `preguntas`
--

INSERT INTO `preguntas` (`id_pregunta`, `texto_pregunta`) VALUES
(1, '¿Cuál es la diferencia entre vivir y existir?'),
(2, ' ¿Qué es lo que más detestas de una persona? ¿Por que?'),
(3, '¿Qué harías de otra manera si supieras que nadie te juzgará?'),
(4, '¿Cuál es la promesa más importante que te has hecho?'),
(5, '¿Cómo podemos tener relaciones saludables?'),
(6, '¿Cuál es el significado de la vida?'),
(7, '¿Cómo mides la vida?'),
(8, '¿Estás en control de tu vida?'),
(9, '¿Por qué a veces te comportas así?'),
(10, '¿Cómo puedes cambiar tu vida?');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `Code_Product` varchar(25) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `Product` varchar(25) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
  `Status_Product` varchar(15) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
  `Price` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
  `Amount` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
  `Description` varchar(15) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_romanian_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`Code_Product`, `Product`, `Status_Product`, `Price`, `Amount`, `Description`) VALUES
('1', '1', 'Existente', '2', '1', '1'),
('11', '11', 'Existente', '1', '1', '11'),
('123', '123', 'Existente', '312', '312', '1322');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas_preguntas`
--

CREATE TABLE `respuestas_preguntas` (
  `correo` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `id_pregunta` int(50) DEFAULT NULL,
  `respuesta` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_romanian_ci;

--
-- Volcado de datos para la tabla `respuestas_preguntas`
--

INSERT INTO `respuestas_preguntas` (`correo`, `id_pregunta`, `respuesta`) VALUES
('prueba@hotmail.com', 2, '7'),
('prueba@hotmail.com', 3, '77'),
('prueba@hotmail.com', 4, '7'),
('prueba@hotmail.com', 5, '7'),
('prueba@hotmail.com', 6, '7'),
('prueba@hotmail.com', 7, '7'),
('prueba@hotmail.com', 8, '7'),
('prueba@hotmail.com', 9, '7'),
('prueba@hotmail.com', 10, '7'),
('cristhian@hotmail.com', 1, 'que vivir es disfrutar de lo que se tiene, y existir es saber que uno no es finito y por lo tanto tiene que ser realizta'),
('cristhian@hotmail.com', 2, 'la envidia por querer tener lo que el otro, ya que esta no es la mejor forma'),
('cristhian@hotmail.com', 3, 'nada pues ya vivo como si no pasara lo que hago es segun mis valores eticos'),
('cristhian@hotmail.com', 4, 'niguna'),
('cristhian@hotmail.com', 5, 'comiendo saludable'),
('cristhian@hotmail.com', 6, 'amar al projimo'),
('cristhian@hotmail.com', 7, 'exclente'),
('cristhian@hotmail.com', 8, 'se puede decir que si'),
('cristhian@hotmail.com', 9, 'porque quiero jajajajaj'),
('cristhian@hotmail.com', 10, 'haciendo cosas que nunca he hecho'),
('toreto@hotmail.com', 1, 'tTT'),
('toreto@hotmail.com', 2, 'T'),
('toreto@hotmail.com', 3, 'TT'),
('toreto@hotmail.com', 4, 'T'),
('toreto@hotmail.com', 5, 'T'),
('toreto@hotmail.com', 6, 'T'),
('toreto@hotmail.com', 7, 'JJJJJ'),
('toreto@hotmail.com', 8, 'T'),
('toreto@hotmail.com', 9, 'T'),
('toreto@hotmail.com', 10, 'TT'),
('toreto@hotmail.com', 1, 'que vivir es disfrutar de lo que se tiene, y existir es saber que uno no es finito y por lo tanto tiene que ser realizta'),
('toreto@hotmail.com', 2, '13245'),
('toreto@hotmail.com', 3, '1'),
('toreto@hotmail.com', 4, 'niguna'),
('toreto@hotmail.com', 5, 'comiendo saludable'),
('toreto@hotmail.com', 6, 'amar al projimo'),
('toreto@hotmail.com', 7, 'exclente'),
('toreto@hotmail.com', 8, 'werty'),
('toreto@hotmail.com', 9, 'porque quiero jajajajaj'),
('toreto@hotmail.com', 10, 'haciendo cosas que nunca he hecho'),
('furiosoandres@hotmail.com', 1, 'que vivir es disfrutar de lo que se tiene, y existir es saber que uno no es finito y por lo tanto tiene que ser realizta'),
('furiosoandres@hotmail.com', 2, '1'),
('furiosoandres@hotmail.com', 3, 'nada pues ya vivo como si no pasara lo que hago es segun mis valores eticos'),
('furiosoandres@hotmail.com', 4, 'niguna'),
('furiosoandres@hotmail.com', 5, 'comiendo saludable'),
('furiosoandres@hotmail.com', 6, 'amar al projimo'),
('furiosoandres@hotmail.com', 7, 'exclente'),
('furiosoandres@hotmail.com', 8, 'se puede decir que si'),
('furiosoandres@hotmail.com', 9, 'porque quiero '),
('furiosoandres@hotmail.com', 10, 'haciendo cosas que nunca he hecho'),
('juan@hotmail.com', 1, 'oiuytr'),
('juan@hotmail.com', 2, 'i'),
('juan@hotmail.com', 3, 'pp'),
('juan@hotmail.com', 4, 'ooo'),
('juan@hotmail.com', 5, 'ooo'),
('juan@hotmail.com', 6, 'ii'),
('juan@hotmail.com', 7, 'i'),
('juan@hotmail.com', 8, 'ii'),
('juan@hotmail.com', 9, 'oo'),
('juan@hotmail.com', 10, 'o');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reunion`
--

CREATE TABLE `reunion` (
  `id` int(150) NOT NULL,
  `fecha` datetime DEFAULT NULL,
  `titulo` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
  `descripcion` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
  `correo` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_romanian_ci;

--
-- Volcado de datos para la tabla `reunion`
--

INSERT INTO `reunion` (`id`, `fecha`, `titulo`, `descripcion`, `correo`) VALUES
(113, '2021-04-25 00:00:00', 'AYUDA', 'Me estan maltratando', 'jose.jdgo97@gmail.com'),
(114, '2021-04-25 00:00:00', 'AYUDA', 'Me estan maltratando', 'jose.jdgo97@gmail.com'),
(119, '2021-04-25 00:00:00', 'AYUDA', 'Me estan maltratando', 'ma@gmial.com'),
(120, '2021-04-25 00:00:00', 'AYUDA', 'Me estan maltratando', 'jose.jdgo97@gmail.com'),
(121, '2021-04-25 00:00:00', 'AYUDA', 'Me estan maltratando', 'may@gmail.com'),
(122, '2021-12-03 00:00:00', 'AYUDA', 'Me estan maltratando', 'jose.jdgo97@gmail.com'),
(123, '2021-12-06 00:00:00', 'AYUDA', 'Me estan maltratando', 'uknown@hotmail.com'),
(126, '2021-12-09 00:00:00', 'AYUDA', 'Me estan maltratando', 'spy@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `Id_rol` int(20) NOT NULL,
  `nom_rol` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_romanian_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`Id_rol`, `nom_rol`) VALUES
(1, 'psicologo'),
(2, 'paciente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_persona` int(11) NOT NULL,
  `correo` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `contrasena` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `id_rol` int(20) NOT NULL,
  `nombres` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
  `apellidos` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
  `codigo_recuperacion` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `imagen_usuario` varchar(244) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
  `direccion` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
  `descripcion_persona` varchar(100) COLLATE utf8_romanian_ci DEFAULT NULL,
  `pais` varchar(100) COLLATE utf8_romanian_ci DEFAULT NULL,
  `celular` varchar(100) COLLATE utf8_romanian_ci DEFAULT NULL,
  `cedula` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_romanian_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_persona`, `correo`, `contrasena`, `id_rol`, `nombres`, `apellidos`, `codigo_recuperacion`, `imagen_usuario`, `direccion`, `descripcion_persona`, `pais`, `celular`, `cedula`) VALUES
(1, 'juandavidgo1997@gmail.com', '$2y$10$4a0c169015dc17569872buXTHlASsw3ol7WG5k9XT.6k2Y.pqBlPK', 2, 'Juan', 'Grijalba', NULL, 'JUAN-juandavidgo1997@gmail.com.jpg', 'Cra 7 t bis # 76-12', 'Aficionado por la informatica', 'Estados Unidos', '3147483647', NULL),
(5, 'jose.jdgo97@gmail.com', '$2y$10$20f07a51f388ba0793282eoqWCVsYogEIpxGyynEOzqDKR01sTPOC', 1, 'Jose', 'Grijalba', '64a1000d5c84df18e8cf1c112b973fdc', 'Eren-jose.jdgo97@gmail.com.png', 'Cra 7 t bis # 76-12', 'Aficionado por la informatica', 'Colombia', NULL, NULL),
(7, 'admin@hotmail.com', '$2y$10$20f07a51f388ba0793282eoqWCVsYogEIpxGyynEOzqDKR01sTPOC', 1, 'Cristhian', 'Hernandez', NULL, '3-admin@hotmail.com.jpg', 'Cra 52A #5 Oeste-2 a 5 Oeste-78, Cali, Valle del Cauca', 'Estudiante de la universidad uniminuto de tecnologia en indformatica', 'Colombia', '3147483647', NULL),
(15, 'asd', '$2y$2y$10$21462a7e50cc42c24bfb6e49IwpPO9wA8RW534F7pgXyEvJTgossS', 1, 'Elon', '   Musk', NULL, '2-admin@hotmail.com.jpg', NULL, NULL, 'Espana', NULL, NULL),
(19, 'juan@gmail.com', '$2y$10$21462a7e50cc42c24bfb6e49IwpPO9wA8RW534F7pgXyEvJTgossS', 2, 'Pablo', 'hernandez', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, 'toreto@hotmail.com', '$2y$10$4a0c169015dc17569872buXTHlASsw3ol7WG5k9XT.6k2Y.pqBlPK', 2, 'ivan', 'toreto', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, 'delia@hotmail.com', '$2y$10$4a0c169015dc17569872buXTHlASsw3ol7WG5k9XT.6k2Y.pqBlPK', 2, 'Delia', 'Hernandez', NULL, NULL, NULL, 'vterinarioa', NULL, NULL, NULL),
(23, 'furiosoandres@hotmail.com', '$2y$10$4a0c169015dc17569872buXTHlASsw3ol7WG5k9XT.6k2Y.pqBlPK', 2, 'andres', 'furioso', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, 'victor@hotmail.com', '$2y$10$4a0c169015dc17569872buXTHlASsw3ol7WG5k9XT.6k2Y.pqBlPK', 2, 'victor ', 'andres', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(25, 'user3@hotmail.com', '$2y$10$4a0c169015dc17569872buXTHlASsw3ol7WG5k9XT.6k2Y.pqBlPK', 2, 'mario ', 'bros', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(26, 'alejo@hotmail.com', '$2y$10$4a0c169015dc17569872buXTHlASsw3ol7WG5k9XT.6k2Y.pqBlPK', 2, 'alejandro', 'grijalba', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(30, 'alejo1@hotmail.com', '$2y$10$4a0c169015dc17569872buXTHlASsw3ol7WG5k9XT.6k2Y.pqBlPK', 2, '123', '123', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(31, 'kl@hotmail.com', '$2y$10$4a0c169015dc17569872buXTHlASsw3ol7WG5k9XT.6k2Y.pqBlPK', 2, 'Kleiz', 'Stone', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(35, 'cluston@hotmail.com', '$2y$10$4a0c169015dc17569872buXTHlASsw3ol7WG5k9XT.6k2Y.pqBlPK', 2, 'Cluston', 'Carton', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(37, 'kento@hotmail.com', '$2y$10$4a0c169015dc17569872buXTHlASsw3ol7WG5k9XT.6k2Y.pqBlPK', 2, 'kento', 'hamaka', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(38, 'g@hotmail.com', '$2y$10$4a0c169015dc17569872buXTHlASsw3ol7WG5k9XT.6k2Y.pqBlPK', 2, 'gonde', 'amos', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(39, 'psicologo2@hotmail.com', '$2y$10$c271ef14556eb9b8f9eaae3bXrAp0U5v/zk0PsezSgl1cLe.MhHtK', 2, 'Jose', 'Grijalba', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(47, 'danirela@aramburo.com', '$2y$10$90d98ad53caf1ddfd850eeeiu8074gDFDH/rQxWkvsvIQBVK.sQ3S', 2, 'daniela', 'aramburo', NULL, NULL, 'cra 5 oeste', NULL, 'colombiana', '314756899', 122333),
(48, 'ma@gmial.com', '$2y$10$6f5880afd774642df0682e3uCrobiQ8tczj75bQBn7QKnvu0c3j2W', 2, 'ma', 'ma', NULL, NULL, 'ma', NULL, 'col', '123', 213),
(49, 'may@gmail.com', '$2y$10$d48ed1d3b0d9e7c98d6cduCW0Kt024pIP2vo0VBCGQ0zzEP30oerm', 2, ' mayra', ' aleja', NULL, '6-may@gmail.com.jpg', 'cra nuevo latir', NULL, 'colombiana', '32145676675', 123),
(50, 'mat@hot.com', '$2y$10$84ce44c228bdcec8d15bce4AeuH7zpdn1qRaUeqYjY6y1nL9hxEOu', 2, 'jack', 'mat', NULL, NULL, 'cra50', NULL, 'Colombiano', '9514267', 1594826),
(52, 'jjdd1997@gmail.com', '$2y$10$933dd3a6aac1b4b63c7f7ub18r7eWug/yEH4UeyHcmc0PSznHXKEq', 2, '   Eren', '   kleizer', NULL, 'Eren-jjdd1997@gmail.com.png', 'cra', NULL, 'col', '123123123', 951951),
(53, 'jd@hot.com', '$2y$10$933dd3a6aac1b4b63c7f7ub18r7eWug/yEH4UeyHcmc0PSznHXKEq', 2, '<br /><b>Notice</b>:  Undefined index: nombres in ', '<br /><b>Notice</b>:  Undefined index: apellidos i', NULL, NULL, 'asdasd', NULL, 'col', '951', 951),
(54, 'uknown@hotmail.com', '$2y$10$933dd3a6aac1b4b63c7f7ub18r7eWug/yEH4UeyHcmc0PSznHXKEq', 3, 'uknown', 'Kleiz', NULL, 'Foto_Jose_Daniel_Grijalba_Osorio-psicologo@hotmail.com-uknown@hotmail.com.png', 'Cra 7oeste d50-04', NULL, 'cali', '3147072792', 1594826),
(59, 'spy@gmail.com', '$2y$10$113f8aac83a2479afce40umAtb4INfO0fcZjNg4PwyJPQBkpbb2O6', 2, 'peter', 'parker', NULL, NULL, 'carsdasd', NULL, 'cali', '123465487', 12345678),
(60, '123@hot.com', '$2y$10$5f0b71e7b514013e67947OnCtmRHsiI5Yt1Y..unfES7Ottvcdf3.', 2, 'psicologo@hotmail.com', '123', NULL, NULL, 'sdad', NULL, 'asd', '123123', 13254687);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD PRIMARY KEY (`id_pregunta`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`Code_Product`),
  ADD KEY `Tipo_Producto` (`Status_Product`),
  ADD KEY `Forma_Farmaceutica` (`Description`);

--
-- Indices de la tabla `reunion`
--
ALTER TABLE `reunion`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_persona`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  MODIFY `id_pregunta` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `reunion`
--
ALTER TABLE `reunion`
  MODIFY `id` int(150) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
