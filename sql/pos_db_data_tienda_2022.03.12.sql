/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : pos_db

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2023-03-13 01:46:37
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `categorias`
-- ----------------------------
DROP TABLE IF EXISTS `categorias`;
CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` enum('1','0') COLLATE utf8_spanish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of categorias
-- ----------------------------
INSERT INTO `categorias` VALUES ('20', 'Abarrotes', '2020-08-25 02:58:19', '0');
INSERT INTO `categorias` VALUES ('21', 'Bebidas', '2021-11-03 22:26:52', '0');
INSERT INTO `categorias` VALUES ('22', 'Lacteos', '2021-11-03 22:27:15', '0');
INSERT INTO `categorias` VALUES ('23', 'Licores', '2021-11-03 22:29:18', '0');
INSERT INTO `categorias` VALUES ('24', 'Golosinas', '2021-11-03 22:29:41', '0');
INSERT INTO `categorias` VALUES ('25', 'Higiene personal', '2021-11-03 22:32:30', '0');
INSERT INTO `categorias` VALUES ('26', 'Libreria', '2023-02-15 03:36:10', '0');
INSERT INTO `categorias` VALUES ('27', 'Limpieza', '2023-02-24 12:55:56', '0');

-- ----------------------------
-- Table structure for `clientes`
-- ----------------------------
DROP TABLE IF EXISTS `clientes`;
CREATE TABLE `clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `documento` int(11) NOT NULL,
  `email` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `telefono` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `direccion` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `compras` int(11) NOT NULL,
  `ultima_compra` datetime NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` enum('1','0') COLLATE utf8_spanish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of clientes
-- ----------------------------
INSERT INTO `clientes` VALUES ('3', 'Publico General', '11111111', 'juan@hotmail.com', '999 999 999', 'Calle 23 # 45 - 56', '1980-11-02', '1887', '2023-03-13 01:42:37', '2023-03-13 01:42:37', '0');
INSERT INTO `clientes` VALUES ('4', 'Heyller Reyes', '46414802', 'heyller.ra@gmail.com', '943 194 241', 'chimbote', '1990-02-17', '11', '2022-09-03 23:38:13', '2022-09-03 23:38:13', '0');
INSERT INTO `clientes` VALUES ('5', 'Martin Pulache', '46414805', 'heyller@gmail.com', '943 194 242', null, null, '2', '2022-07-16 23:28:15', '2022-07-16 23:28:15', '0');
INSERT INTO `clientes` VALUES ('6', 'Lady Cruz', '44444444', 'test@gmail.com', '999 999 995', null, null, '1', '2023-02-15 03:29:10', '2023-02-15 03:29:10', '0');

-- ----------------------------
-- Table structure for `compra`
-- ----------------------------
DROP TABLE IF EXISTS `compra`;
CREATE TABLE `compra` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `codigo` text NOT NULL,
  `proveedor_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `fecha_pago` date DEFAULT NULL,
  `total` double NOT NULL,
  `metodo_pago` enum('contado','credito') NOT NULL DEFAULT 'contado',
  `usuario_id` bigint(11) NOT NULL COMMENT 'usuario que registra',
  `usuario_u_id` bigint(20) DEFAULT NULL COMMENT 'usuario que actualiza el registro',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `proveedor_id` (`proveedor_id`) USING BTREE,
  CONSTRAINT `compra_ibfk_1` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedor` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of compra
-- ----------------------------

-- ----------------------------
-- Table structure for `compra_detalle`
-- ----------------------------
DROP TABLE IF EXISTS `compra_detalle`;
CREATE TABLE `compra_detalle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `compra_id` bigint(20) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `precio_compra` double NOT NULL,
  `cantidad` double NOT NULL,
  `sub_total` double NOT NULL,
  `old_precio` double NOT NULL,
  `deleted` enum('1','0') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `producto_id` (`producto_id`),
  KEY `compra_id` (`compra_id`),
  CONSTRAINT `compra_detalle_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `compra_detalle_ibfk_2` FOREIGN KEY (`compra_id`) REFERENCES `compra` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of compra_detalle
-- ----------------------------

-- ----------------------------
-- Table structure for `detalle_pago_deuda`
-- ----------------------------
DROP TABLE IF EXISTS `detalle_pago_deuda`;
CREATE TABLE `detalle_pago_deuda` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pago_deuda_id` bigint(20) NOT NULL,
  `venta_id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `importe_deuda` double NOT NULL,
  `importe_pagado` double NOT NULL,
  `saldo` double NOT NULL,
  `ultimo` enum('1','0') NOT NULL DEFAULT '1',
  `old_ultimo_id` bigint(20) DEFAULT 0,
  `deleted` enum('1','0') NOT NULL DEFAULT '0',
  `usuario_crea` varchar(50) DEFAULT NULL,
  `usuario_u` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of detalle_pago_deuda
-- ----------------------------
INSERT INTO `detalle_pago_deuda` VALUES ('23', '7', '40', '6', '2.8', '2.8', '0', '1', '0', '0', 'admin', null);

-- ----------------------------
-- Table structure for `gastos`
-- ----------------------------
DROP TABLE IF EXISTS `gastos`;
CREATE TABLE `gastos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text NOT NULL,
  `tipo_gasto` enum('general','ganancia') NOT NULL,
  `detalle_gasto` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `monto` double NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of gastos
-- ----------------------------

-- ----------------------------
-- Table structure for `pago_deuda`
-- ----------------------------
DROP TABLE IF EXISTS `pago_deuda`;
CREATE TABLE `pago_deuda` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `importe` float NOT NULL,
  `fecha_pago` date DEFAULT NULL,
  `deleted` enum('1','0') NOT NULL DEFAULT '0',
  `old_pago_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`),
  CONSTRAINT `pago_deuda_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pago_deuda
-- ----------------------------
INSERT INTO `pago_deuda` VALUES ('7', '6', '2.8', '2023-03-05', '0', null);

-- ----------------------------
-- Table structure for `productos`
-- ----------------------------
DROP TABLE IF EXISTS `productos`;
CREATE TABLE `productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_categoria` int(11) NOT NULL,
  `codigo` text COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  `imagen` text COLLATE utf8_spanish_ci NOT NULL,
  `stock` float NOT NULL,
  `precio_compra` double NOT NULL,
  `margen` double NOT NULL,
  `precio_venta` double NOT NULL,
  `unidad_medida_entrada_id` int(11) DEFAULT NULL,
  `unidad_medida_salida_id` int(11) DEFAULT NULL,
  `factor` double DEFAULT NULL,
  `ventas` int(11) NOT NULL,
  `proveedor_detalle` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` enum('1','0') COLLATE utf8_spanish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_categoria` (`id_categoria`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=276 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of productos
-- ----------------------------
INSERT INTO `productos` VALUES ('175', '24', '2401', 'Chocosoda', 'vistas/img/productos/default/anonymous.png', '41', '0.95', '0', '1.2', null, null, null, '9', '', '2023-03-10 02:13:39', '0');
INSERT INTO `productos` VALUES ('176', '24', '2402', 'Hals', 'vistas/img/productos/default/anonymous.png', '82', '0.11', '0', '0.2', null, null, null, '18', '', '2023-03-13 01:36:59', '0');
INSERT INTO `productos` VALUES ('177', '24', '2403', 'Oreo xl', 'vistas/img/productos/default/anonymous.png', '0', '0.95', '0', '1.2', null, null, null, '0', '', '2022-09-08 21:58:50', '0');
INSERT INTO `productos` VALUES ('178', '21', '2101', 'Inka cola 1L', 'vistas/img/productos/default/anonymous.png', '93', '3.15', '0', '3.8', null, null, null, '7', '', '2023-03-10 02:22:53', '0');
INSERT INTO `productos` VALUES ('179', '21', '2102', 'Coca cola 1L', 'vistas/img/productos/default/anonymous.png', '89', '3.15', '0', '3.8', null, null, null, '11', '', '2023-03-13 01:36:59', '0');
INSERT INTO `productos` VALUES ('180', '21', '2103', 'Coca cola 2L', 'vistas/img/productos/default/anonymous.png', '93', '5.3', '0', '6.5', null, null, null, '7', '', '2023-03-10 02:22:53', '0');
INSERT INTO `productos` VALUES ('181', '21', '2104', 'Inka cola 2L', 'vistas/img/productos/default/anonymous.png', '6', '5.3', '0', '6.5', null, null, null, '3', '', '2023-03-10 02:15:57', '0');
INSERT INTO `productos` VALUES ('182', '21', '2105', 'Inka cola 600 ml', 'vistas/img/productos/default/anonymous.png', '93', '2.35', '0', '2.8', null, null, null, '7', '', '2023-03-13 01:36:59', '0');
INSERT INTO `productos` VALUES ('183', '21', '2106', 'Coca cola 600 ml', 'vistas/img/productos/default/anonymous.png', '92', '2.35', '0', '2.8', null, null, null, '8', '', '2023-03-13 01:42:37', '0');
INSERT INTO `productos` VALUES ('184', '22', '2201', 'Yogurt yofresh 1L', 'vistas/img/productos/default/anonymous.png', '1', '6', '0', '6.8', null, null, null, '2', '', '2023-02-27 11:32:41', '0');
INSERT INTO `productos` VALUES ('185', '22', '2202', 'Leche gloria entera 1L', 'vistas/img/productos/default/anonymous.png', '0', '4.37', '0', '4.8', null, null, null, '0', '', '2022-09-08 22:49:42', '0');
INSERT INTO `productos` VALUES ('186', '22', '2203', 'Leche gloria azul 400 gr', 'vistas/img/productos/default/anonymous.png', '0', '3.5', '0', '4', null, null, null, '10', '', '2023-03-10 02:13:40', '0');
INSERT INTO `productos` VALUES ('187', '22', '2204', 'Leche gloria sin lactosa 400 gr', 'vistas/img/productos/default/anonymous.png', '0', '4', '0', '4.5', null, null, null, '0', '', '2023-02-14 02:10:18', '0');
INSERT INTO `productos` VALUES ('188', '22', '2205', 'leche gloria light 400 gr', 'vistas/img/productos/default/anonymous.png', '8', '3.7', '0', '4.2', null, null, null, '2', '', '2023-03-05 02:49:28', '0');
INSERT INTO `productos` VALUES ('189', '24', '2404', 'Wafer nick', 'vistas/img/productos/default/anonymous.png', '0', '1.2', '0', '1.5', null, null, null, '0', '', '2023-02-15 03:22:41', '0');
INSERT INTO `productos` VALUES ('190', '20', '2001', 'Mermelada fanny sachet', 'vistas/img/productos/default/anonymous.png', '0', '1.23', '0', '1.5', null, null, null, '0', '', '2022-09-09 10:37:07', '0');
INSERT INTO `productos` VALUES ('191', '24', '2405', 'Vicio', 'vistas/img/productos/default/anonymous.png', '20', '1.07', '0', '1.2', null, null, null, '0', '', '2022-09-10 22:57:12', '0');
INSERT INTO `productos` VALUES ('192', '24', '2406', 'Caramelo de limon', 'vistas/img/productos/default/anonymous.png', '100', '0.05', '0', '0.1', null, null, null, '0', '', '2022-09-10 23:01:55', '0');
INSERT INTO `productos` VALUES ('193', '24', '2407', 'Chupete pin pon', 'vistas/img/productos/default/anonymous.png', '23', '0.28', '0', '0.5', null, null, null, '1', '', '2023-02-27 11:32:41', '0');
INSERT INTO `productos` VALUES ('194', '24', '2408', 'Galleta soda gn', 'vistas/img/productos/default/anonymous.png', '20', '0.26', '0', '0.4', null, null, null, '20', '', '2023-03-13 01:36:59', '0');
INSERT INTO `productos` VALUES ('195', '21', '2107', 'Kr', 'vistas/img/productos/default/anonymous.png', '11', '0.86', '0', '1', null, null, null, '231', '', '2023-03-13 01:42:37', '0');
INSERT INTO `productos` VALUES ('196', '25', '2501', 'Detergente marsella 450gr', 'vistas/img/productos/default/anonymous.png', '4', '4.93', '0', '5.7', null, null, null, '2', '', '2023-03-07 01:41:40', '0');
INSERT INTO `productos` VALUES ('197', '24', '2409', 'Galleta ritz taco', 'vistas/img/productos/default/anonymous.png', '2', '1.1', '0', '1.3', null, null, null, '10', '', '2023-03-10 02:13:39', '0');
INSERT INTO `productos` VALUES ('198', '21', '2108', 'Agua cielo', 'vistas/img/productos/default/anonymous.png', '84', '0.75', '0', '1', null, null, null, '212', '', '2023-03-13 01:42:37', '0');
INSERT INTO `productos` VALUES ('199', '24', '2410', 'Galleta picara', 'vistas/img/productos/default/anonymous.png', '1', '0.83', '0', '1', null, null, null, '11', '', '2023-03-13 01:36:59', '0');
INSERT INTO `productos` VALUES ('200', '24', '2411', 'Galleta trikis', 'vistas/img/productos/default/anonymous.png', '4', '0.59', '0', '0.8', null, null, null, '14', '', '2023-03-13 01:42:37', '0');
INSERT INTO `productos` VALUES ('201', '21', '2109', 'Guarana 450ml', 'vistas/img/productos/default/anonymous.png', '12', '1.4', '0', '2', null, null, null, '3', '', '2023-03-07 01:47:43', '0');
INSERT INTO `productos` VALUES ('202', '25', '2502', 'Papel elite mellizo', 'vistas/img/productos/default/anonymous.png', '4', '2.01', '0', '2.3', null, null, null, '6', '', '2023-03-13 01:36:59', '0');
INSERT INTO `productos` VALUES ('203', '25', '2503', 'Nosotras noche paq', 'vistas/img/productos/default/anonymous.png', '3', '6.83', '0', '8', null, null, null, '0', '', '2023-02-15 02:24:47', '0');
INSERT INTO `productos` VALUES ('204', '25', '2504', 'Nosotras dia paq', 'vistas/img/productos/default/anonymous.png', '6', '3.97', '0', '4.6', null, null, null, '0', '', '2023-02-15 02:25:38', '0');
INSERT INTO `productos` VALUES ('205', '25', '2505', 'Detergente patito', 'vistas/img/productos/default/anonymous.png', '16', '1.09', '0', '1.3', null, null, null, '4', '', '2023-03-02 02:11:56', '0');
INSERT INTO `productos` VALUES ('206', '25', '2506', 'Nutribela', 'vistas/img/productos/default/anonymous.png', '11', '1.21', '0', '1.5', null, null, null, '1', '', '2023-03-02 02:12:01', '0');
INSERT INTO `productos` VALUES ('207', '25', '2507', 'Suavitel sachet', 'vistas/img/productos/default/anonymous.png', '19', '0.93', '0', '1.2', null, null, null, '11', '', '2023-03-13 01:42:37', '0');
INSERT INTO `productos` VALUES ('208', '24', '2412', 'Cereal grande', 'vistas/img/productos/default/anonymous.png', '5', '2.65', '0', '3.2', null, null, null, '1', '', '2023-02-27 11:32:41', '0');
INSERT INTO `productos` VALUES ('209', '24', '2413', 'Cereal chico', 'vistas/img/productos/default/anonymous.png', '22', '0.62', '0', '0.8', null, null, null, '2', '', '2023-03-07 01:47:43', '0');
INSERT INTO `productos` VALUES ('210', '25', '2508', 'Jabon palmolive 120gr', 'vistas/img/productos/default/anonymous.png', '6', '3.54', '0', '4', null, null, null, '0', '', '2023-02-15 02:33:46', '0');
INSERT INTO `productos` VALUES ('211', '25', '2509', 'Jabon protex 110gr', 'vistas/img/productos/default/anonymous.png', '7', '3.65', '0', '4', null, null, null, '1', '', '2023-02-24 13:18:27', '0');
INSERT INTO `productos` VALUES ('212', '24', '2414', 'Chiclets adams chico', 'vistas/img/productos/default/anonymous.png', '84', '0.14', '0', '0.2', null, null, null, '16', '', '2023-03-10 02:22:53', '0');
INSERT INTO `productos` VALUES ('213', '24', '2415', 'Galleta oreo chica', 'vistas/img/productos/default/anonymous.png', '5', '0.75', '0', '1', null, null, null, '7', '', '2023-03-05 02:59:30', '0');
INSERT INTO `productos` VALUES ('214', '25', '2510', 'Papel noble mellizo', 'vistas/img/productos/default/anonymous.png', '9.5', '1.49', '0', '2', null, null, null, '1', '', '2023-03-13 01:42:37', '0');
INSERT INTO `productos` VALUES ('215', '24', '2416', 'Galleta vainilla field', 'vistas/img/productos/default/anonymous.png', '7', '0.79', '0', '1', null, null, null, '5', '', '2023-03-07 01:41:40', '0');
INSERT INTO `productos` VALUES ('216', '24', '2417', 'Galleta soda field', 'vistas/img/productos/default/anonymous.png', '9', '0.48', '0', '0.7', null, null, null, '3', '', '2023-03-07 01:47:44', '0');
INSERT INTO `productos` VALUES ('217', '20', '2002', 'Ajinomen', 'vistas/img/productos/default/anonymous.png', '10', '1.28', '0', '1.5', null, null, null, '2', '', '2023-03-05 02:59:30', '0');
INSERT INTO `productos` VALUES ('218', '20', '2003', 'Ajinomix crocante', 'vistas/img/productos/default/anonymous.png', '5', '1.58', '0', '2', null, null, null, '1', '', '2023-02-24 13:16:01', '0');
INSERT INTO `productos` VALUES ('219', '25', '2511', 'Shampo ballerina', 'vistas/img/productos/default/anonymous.png', '7', '0.7', '0', '1', null, null, null, '5', '', '2023-03-10 02:13:39', '0');
INSERT INTO `productos` VALUES ('220', '25', '2512', 'Lavavajilla Orion 1Lt', 'vistas/img/productos/default/anonymous.png', '3', '6.83', '0', '7.8', null, null, null, '0', '', '2023-02-15 02:44:12', '0');
INSERT INTO `productos` VALUES ('221', '24', '2418', 'Chicle bubaloo', 'vistas/img/productos/default/anonymous.png', '6', '0.12', '0', '0.2', null, null, null, '24', '', '2023-03-13 01:42:37', '0');
INSERT INTO `productos` VALUES ('222', '24', '2419', 'Chifle', 'vistas/img/productos/default/anonymous.png', '7', '0.79', '0', '1', null, null, null, '16', '', '2023-03-13 01:38:35', '0');
INSERT INTO `productos` VALUES ('223', '26', '2601', 'Pegamento tris', 'vistas/img/productos/default/anonymous.png', '12', '0.5', '0', '0.8', null, null, null, '3', '', '2023-03-05 02:59:30', '0');
INSERT INTO `productos` VALUES ('224', '20', '2004', 'Fosforo', 'vistas/img/productos/default/anonymous.png', '42', '0.18', '0', '0.3', null, null, null, '8', '', '2023-03-13 01:36:59', '0');
INSERT INTO `productos` VALUES ('225', '21', '2110', 'Pepsi litro y medio', 'vistas/img/productos/default/anonymous.png', '3', '3.05', '0', '3.5', null, null, null, '7', '', '2023-03-13 01:36:59', '0');
INSERT INTO `productos` VALUES ('226', '21', '2111', 'Pepsi jumbo', 'vistas/img/productos/default/anonymous.png', '4', '2.25', '0', '2.7', null, null, null, '6', '', '2023-03-10 02:22:53', '0');
INSERT INTO `productos` VALUES ('227', '25', '2513', 'Jabon popeye', 'vistas/img/productos/default/anonymous.png', '3', '1.5', '0', '2', null, null, null, '1', '', '2023-02-15 03:43:20', '0');
INSERT INTO `productos` VALUES ('228', '25', '2514', 'Lejia clorox ropa color grande', 'vistas/img/productos/default/anonymous.png', '4', '3.42', '0', '4', null, null, null, '2', '', '2023-03-05 03:11:17', '0');
INSERT INTO `productos` VALUES ('229', '20', '2005', 'Sal', 'vistas/img/productos/default/anonymous.png', '17', '1.2', '0', '1.5', null, null, null, '3', '', '2023-03-05 16:56:14', '0');
INSERT INTO `productos` VALUES ('230', '27', '2701', 'Poet 350ml', 'vistas/img/productos/default/anonymous.png', '9', '1.9', '0', '2.3', null, null, null, '1', '', '2023-02-27 11:00:38', '0');
INSERT INTO `productos` VALUES ('231', '24', '2420', 'Cheese tris 16gr', 'vistas/img/productos/default/anonymous.png', '2', '0.52', '0', '0.6', null, null, null, '23', '', '2023-03-13 01:42:37', '0');
INSERT INTO `productos` VALUES ('232', '24', '2421', 'Piqueo 23gr', 'vistas/img/productos/default/anonymous.png', '7', '0.83', '0', '1', null, null, null, '5', '', '2023-03-02 02:10:29', '0');
INSERT INTO `productos` VALUES ('233', '24', '2422', 'Cuate 26gr', 'vistas/img/productos/default/anonymous.png', '10', '0.5', '0', '0.6', null, null, null, '2', '', '2023-03-07 01:41:40', '0');
INSERT INTO `productos` VALUES ('234', '25', '2515', 'jabon marsella', 'vistas/img/productos/default/anonymous.png', '3', '2.4', '0', '3', null, null, null, '0', '', '2023-02-24 13:04:12', '0');
INSERT INTO `productos` VALUES ('235', '25', '2516', 'jabon bolivar', 'vistas/img/productos/default/anonymous.png', '5', '2.75', '0', '3.3', null, null, null, '1', '', '2023-03-05 02:59:29', '0');
INSERT INTO `productos` VALUES ('236', '25', '2517', 'Jabon camay 125gr', 'vistas/img/productos/default/anonymous.png', '7', '3.24', '0', '4', null, null, null, '0', '', '2023-02-24 13:05:50', '0');
INSERT INTO `productos` VALUES ('237', '24', '2423', 'Galleta rellenita', 'vistas/img/productos/default/anonymous.png', '64', '0.37', '0', '0.5', null, null, null, '16', '', '2023-03-13 01:42:37', '0');
INSERT INTO `productos` VALUES ('238', '25', '2518', 'Detergente marsella 330gr', 'vistas/img/productos/default/anonymous.png', '3', '4.15', '0', '4.7', null, null, null, '3', '', '2023-03-13 01:42:37', '0');
INSERT INTO `productos` VALUES ('239', '20', '2006', 'Maizena duryea 100gr', 'vistas/img/productos/default/anonymous.png', '7', '2', '0', '2.5', null, null, null, '0', '', '2023-02-24 13:09:41', '0');
INSERT INTO `productos` VALUES ('240', '25', '2519', 'Detergente bolivar 450gr', 'vistas/img/productos/default/anonymous.png', '4', '6.38', '0', '7.2', null, null, null, '2', '', '2023-03-10 02:03:33', '0');
INSERT INTO `productos` VALUES ('241', '25', '2520', 'Detergente opal 450gr', 'vistas/img/productos/default/anonymous.png', '5', '5.67', '0', '6.3', null, null, null, '1', '', '2023-02-24 13:28:09', '0');
INSERT INTO `productos` VALUES ('242', '27', '2702', 'Lejia color clorox sachet', 'vistas/img/productos/default/anonymous.png', '12', '0.89', '0', '1.2', null, null, null, '0', '', '2023-02-28 00:45:00', '0');
INSERT INTO `productos` VALUES ('243', '27', '2703', 'Lavavajilla crema ñapancha', 'vistas/img/productos/default/anonymous.png', '0', '3.8', '0', '4.5', null, null, null, '1', '', '2023-02-24 13:16:01', '0');
INSERT INTO `productos` VALUES ('244', '21', '2112', 'Kris', 'vistas/img/productos/default/anonymous.png', '5', '1', '0', '1.2', null, null, null, '5', '', '2023-03-10 02:03:33', '0');
INSERT INTO `productos` VALUES ('245', '26', '2602', 'Pilas panasonic 2A', 'vistas/img/productos/default/anonymous.png', '7', '1.3', '0', '2', null, null, null, '3', '', '2023-02-28 01:31:42', '0');
INSERT INTO `productos` VALUES ('246', '20', '2007', 'Sibarita', 'vistas/img/productos/default/anonymous.png', '48', '0.2', '0', '0.4', null, null, null, '2', '', '2023-03-05 16:56:14', '0');
INSERT INTO `productos` VALUES ('247', '21', '2113', 'Cool 1500Ml', 'vistas/img/productos/default/anonymous.png', '6', '3', '0', '3.5', null, null, null, '1', '', '2023-02-27 11:32:41', '0');
INSERT INTO `productos` VALUES ('248', '21', '2114', 'Oro 1500Ml', 'vistas/img/productos/default/anonymous.png', '6', '2.6', '0', '3.5', null, null, null, '1', '', '2023-03-05 02:59:30', '0');
INSERT INTO `productos` VALUES ('249', '21', '2115', 'Kr 1500Ml', 'vistas/img/productos/default/anonymous.png', '7', '2.6', '0', '3.5', null, null, null, '0', '', '2023-02-25 02:57:02', '0');
INSERT INTO `productos` VALUES ('250', '21', '2116', 'Sprite 2L', 'vistas/img/productos/default/anonymous.png', '3', '3.84', '0', '4.5', null, null, null, '1', '', '2023-02-27 11:32:41', '0');
INSERT INTO `productos` VALUES ('251', '21', '2117', 'Fanta 2L', 'vistas/img/productos/default/anonymous.png', '3', '3.84', '0', '4.5', null, null, null, '1', '', '2023-03-05 03:11:17', '0');
INSERT INTO `productos` VALUES ('252', '21', '2118', 'Jugo caja 1L', 'vistas/img/productos/default/anonymous.png', '6', '3.7', '0', '4.5', null, null, null, '0', '', '2023-02-25 03:06:53', '0');
INSERT INTO `productos` VALUES ('253', '21', '2119', 'Inka cola 3L', 'vistas/img/productos/default/anonymous.png', '2', '10.08', '0', '12', null, null, null, '0', '', '2023-02-25 03:08:35', '0');
INSERT INTO `productos` VALUES ('254', '21', '2120', 'Coca cola 3L', 'vistas/img/productos/default/anonymous.png', '2', '10.08', '0', '12', null, null, null, '0', '', '2023-02-25 03:09:17', '0');
INSERT INTO `productos` VALUES ('255', '21', '2121', 'Agua benedictino 600ml', 'vistas/img/productos/default/anonymous.png', '0', '0.82', '0', '1', null, null, null, '8', '', '2023-03-02 01:54:42', '0');
INSERT INTO `productos` VALUES ('256', '25', '2521', 'Shampo hys', 'vistas/img/productos/default/anonymous.png', '27', '0.75', '0', '1', null, null, null, '3', '', '2023-03-10 02:13:40', '0');
INSERT INTO `productos` VALUES ('257', '24', '2424', 'Chupete globo pop', 'vistas/img/productos/default/anonymous.png', '13', '0.28', '0', '0.5', null, null, null, '2', '', '2023-03-13 01:36:59', '0');
INSERT INTO `productos` VALUES ('258', '25', '2522', 'Papel toalla nova megarrollo', 'vistas/img/productos/default/anonymous.png', '10', '2', '0', '2.4', null, null, null, '2', '', '2023-02-27 11:32:42', '0');
INSERT INTO `productos` VALUES ('259', '21', '2122', 'Jugo caja chico', 'vistas/img/productos/default/anonymous.png', '3', '1.2', '0', '1.5', null, null, null, '7', '', '2023-03-07 01:41:40', '0');
INSERT INTO `productos` VALUES ('260', '27', '2704', 'Lejia clorox grande', 'vistas/img/productos/default/anonymous.png', '4', '1.65', '0', '2', null, null, null, '2', '', '2023-02-28 01:31:42', '0');
INSERT INTO `productos` VALUES ('261', '25', '2523', 'Shampo sedal', 'vistas/img/productos/default/anonymous.png', '9', '1', '0', '1.3', null, null, null, '1', '', '2023-03-07 01:41:40', '0');
INSERT INTO `productos` VALUES ('262', '21', '2123', 'Guarana 2L', 'vistas/img/productos/default/anonymous.png', '6', '4.37', '0', '5.5', null, null, null, '0', '', '2023-03-02 01:41:39', '0');
INSERT INTO `productos` VALUES ('263', '21', '2124', 'Guarana 3L', 'vistas/img/productos/default/anonymous.png', '3', '6.73', '0', '8.5', null, null, null, '1', '', '2023-03-02 02:10:29', '0');
INSERT INTO `productos` VALUES ('264', '24', '2425', 'Mani granuts oriental', 'vistas/img/productos/default/anonymous.png', '7', '0.82', '0', '1.2', null, null, null, '5', '', '2023-03-10 02:13:40', '0');
INSERT INTO `productos` VALUES ('265', '27', '2705', 'Lavavajilla crema sapolio', 'vistas/img/productos/default/anonymous.png', '3', '5.2', '0', '6', null, null, null, '0', '', '2023-03-02 01:46:36', '0');
INSERT INTO `productos` VALUES ('266', '25', '2524', 'Servilleta nova', 'vistas/img/productos/default/anonymous.png', '11', '1.47', '0', '1.8', null, null, null, '1', '', '2023-03-13 01:42:37', '0');
INSERT INTO `productos` VALUES ('267', '25', '2525', 'Papel noble plus x4', 'vistas/img/productos/default/anonymous.png', '5', '4.2', '0', '5', null, null, null, '1', '', '2023-03-13 01:36:59', '0');
INSERT INTO `productos` VALUES ('268', '20', '2008', 'Cocoa winter', 'vistas/img/productos/default/anonymous.png', '46', '0.28', '0', '0.5', null, null, null, '4', '', '2023-03-10 02:03:33', '0');
INSERT INTO `productos` VALUES ('269', '27', '2706', 'Poet 750ml', 'vistas/img/productos/default/anonymous.png', '4', '2.3', '0', '3', null, null, null, '1', '', '2023-03-05 02:49:28', '0');
INSERT INTO `productos` VALUES ('270', '20', '2009', 'Huevos', 'vistas/img/productos/default/anonymous.png', '35', '0.51', '0', '0.6', null, null, null, '25', '', '2023-03-13 01:42:37', '0');
INSERT INTO `productos` VALUES ('271', '21', '2125', 'Agua cielo 2L', 'vistas/img/productos/default/anonymous.png', '0', '2.8', '0', '3.5', null, null, null, '6', '', '2023-03-10 02:22:53', '0');
INSERT INTO `productos` VALUES ('272', '24', '2426', 'Chicle trident', 'vistas/img/productos/default/anonymous.png', '8', '0.9', '0', '1.2', null, null, null, '2', '', '2023-03-07 01:41:41', '0');
INSERT INTO `productos` VALUES ('273', '22', '2206', 'Leche condensada gloria', 'vistas/img/productos/default/anonymous.png', '3', '5.06', '0', '5.8', null, null, null, '0', '', '2023-03-07 01:37:03', '0');
INSERT INTO `productos` VALUES ('274', '22', '2207', 'Leche gloria ninios', 'vistas/img/productos/default/anonymous.png', '5', '3.7', '0', '4.2', null, null, null, '1', '', '2023-03-10 02:13:40', '0');
INSERT INTO `productos` VALUES ('275', '24', '2427', 'Chizito chico', 'vistas/img/productos/default/anonymous.png', '3', '0.5', '0', '0.6', null, null, null, '9', '', '2023-03-13 01:36:59', '0');

-- ----------------------------
-- Table structure for `producto_proveedor`
-- ----------------------------
DROP TABLE IF EXISTS `producto_proveedor`;
CREATE TABLE `producto_proveedor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `producto_id` int(11) NOT NULL,
  `proveedor_id` int(11) NOT NULL,
  `old_precio` double NOT NULL,
  `ultimo_precio` double NOT NULL,
  `ultima_compra` date NOT NULL,
  `compras` double NOT NULL,
  `deleted` enum('1','0') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `producto_id` (`producto_id`),
  KEY `proveedor_id` (`proveedor_id`),
  CONSTRAINT `producto_proveedor_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `producto_proveedor_ibfk_2` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedor` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of producto_proveedor
-- ----------------------------

-- ----------------------------
-- Table structure for `proveedor`
-- ----------------------------
DROP TABLE IF EXISTS `proveedor`;
CREATE TABLE `proveedor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `razon_social` varchar(100) NOT NULL,
  `ruc` varchar(11) DEFAULT '',
  `representante` text DEFAULT NULL,
  `telefono` varchar(9) DEFAULT NULL,
  `dia_visita` int(1) NOT NULL,
  `pedido_minimo` double NOT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` enum('1','0') NOT NULL DEFAULT '1',
  `deleted` enum('1','0') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of proveedor
-- ----------------------------
INSERT INTO `proveedor` VALUES ('1', 'Alvarez Bolt - Nestle', null, null, null, '4', '31', null, '1', '0');
INSERT INTO `proveedor` VALUES ('2', 'Despensa Peruana - surtido', null, null, null, '6', '30', null, '1', '0');
INSERT INTO `proveedor` VALUES ('3', 'Codijisa', null, null, null, '1', '30', 'colgate, protex, nosotras, sapolio, patito', '1', '0');
INSERT INTO `proveedor` VALUES ('4', 'Mariza - surtido', null, null, null, '2', '21', null, '1', '0');
INSERT INTO `proveedor` VALUES ('5', 'Mariza - Field', null, null, null, '6', '21', null, '1', '0');
INSERT INTO `proveedor` VALUES ('7', 'Linares', null, null, null, '2', '33', null, '1', '0');
INSERT INTO `proveedor` VALUES ('8', 'Linares - Costa', null, null, null, '4', '33', null, '1', '0');
INSERT INTO `proveedor` VALUES ('9', 'Winter - mks', null, null, null, '1', '22', null, '1', '0');
INSERT INTO `proveedor` VALUES ('10', 'Winter - otro', null, null, null, '6', '22', null, '1', '0');
INSERT INTO `proveedor` VALUES ('11', 'Inkapesca', null, null, null, '1', '22', null, '1', '0');
INSERT INTO `proveedor` VALUES ('12', 'Kr - Martes', null, null, null, '2', '10.2', 'Gaseosa kr, agua loa', '1', '0');
INSERT INTO `proveedor` VALUES ('13', 'Kr - Martes', null, null, null, '2', '10.2', 'Gaseosa kr, agua loa', '1', '1');
INSERT INTO `proveedor` VALUES ('14', 'Distribuidora Junior', null, null, null, '8', '10', 'Detergentes,abarrotes en general', '1', '0');
INSERT INTO `proveedor` VALUES ('15', 'Pepsi', null, null, null, '6', '10', null, '1', '0');
INSERT INTO `proveedor` VALUES ('16', 'Despensa - gloria', null, null, null, '3', '50', 'productos de la marca gloria', '1', '0');

-- ----------------------------
-- Table structure for `reporte_capital`
-- ----------------------------
DROP TABLE IF EXISTS `reporte_capital`;
CREATE TABLE `reporte_capital` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `capital` double NOT NULL,
  `detalle` longtext NOT NULL,
  `anio_mes` varchar(7) NOT NULL,
  `f_inicio` date DEFAULT NULL,
  `f_fin` date DEFAULT NULL,
  `activo` enum('1','0') DEFAULT '0',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of reporte_capital
-- ----------------------------

-- ----------------------------
-- Table structure for `unidad_medida`
-- ----------------------------
DROP TABLE IF EXISTS `unidad_medida`;
CREATE TABLE `unidad_medida` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion_larga` varchar(100) NOT NULL,
  `descripcion_corta` varchar(30) DEFAULT NULL,
  `deleted` enum('1','0') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of unidad_medida
-- ----------------------------
INSERT INTO `unidad_medida` VALUES ('1', 'Paquete', 'Paq', '0');
INSERT INTO `unidad_medida` VALUES ('2', 'Kilogramo', 'Kg', '0');
INSERT INTO `unidad_medida` VALUES ('3', 'Unidad', 'UN', '0');

-- ----------------------------
-- Table structure for `usuarios`
-- ----------------------------
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `usuario` text COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL,
  `perfil` text COLLATE utf8_spanish_ci NOT NULL,
  `foto` text COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(11) NOT NULL,
  `ultimo_login` datetime NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` enum('1','0') COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of usuarios
-- ----------------------------
INSERT INTO `usuarios` VALUES ('1', 'Administrador', 'admin', '$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG', 'Administrador', 'vistas/img/usuarios/admin/157.jpg', '1', '2023-03-13 01:29:31', '2023-03-13 01:29:31', '1');

-- ----------------------------
-- Table structure for `ventas`
-- ----------------------------
DROP TABLE IF EXISTS `ventas`;
CREATE TABLE `ventas` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `codigo` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_vendedor` bigint(20) NOT NULL,
  `usuario_u` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `usuario_u_id` bigint(20) DEFAULT NULL COMMENT 'usuario que modifica el registro',
  `productos` text COLLATE utf8_spanish_ci NOT NULL,
  `impuesto` float NOT NULL,
  `neto` float NOT NULL,
  `total` float NOT NULL,
  `metodo_pago` text COLLATE utf8_spanish_ci NOT NULL,
  `codigo_pago` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fecha` datetime NOT NULL,
  `fecha_pago` date DEFAULT NULL,
  `deleted` enum('1','0') COLLATE utf8_spanish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of ventas
-- ----------------------------
INSERT INTO `ventas` VALUES ('39', '10001', '3', '1', null, null, '[{\"id\":\"198\",\"descripcion\":\"Agua cielo\",\"cantidad\":\"16\",\"stock\":\"34\",\"precio\":\"1\",\"precioCompra\":\"0.8\",\"total\":\"16\"},{\"id\":\"195\",\"descripcion\":\"Kr\",\"cantidad\":\"10\",\"stock\":\"90\",\"precio\":\"1\",\"precioCompra\":\"0.853\",\"total\":\"10\"},{\"id\":\"207\",\"descripcion\":\"Suavitel sachet\",\"cantidad\":\"1\",\"stock\":\"29\",\"precio\":\"1.2\",\"precioCompra\":\"0.93\",\"total\":\"1.2\"},{\"id\":\"197\",\"descripcion\":\"Galleta ritz taco\",\"cantidad\":\"3\",\"stock\":\"9\",\"precio\":\"1.3\",\"precioCompra\":\"1.1\",\"total\":\"3.9000000000000004\"},{\"id\":\"200\",\"descripcion\":\"Galleta trikis\",\"cantidad\":\"3\",\"stock\":\"15\",\"precio\":\"0.8\",\"precioCompra\":\"0.583\",\"total\":\"2.4\"},{\"id\":\"199\",\"descripcion\":\"Galleta picara\",\"cantidad\":\"2\",\"stock\":\"10\",\"precio\":\"1\",\"precioCompra\":\"0.83\",\"total\":\"2\"},{\"id\":\"202\",\"descripcion\":\"Papel elite mellizo\",\"cantidad\":\"1\",\"stock\":\"9\",\"precio\":\"2.3\",\"precioCompra\":\"2.01\",\"total\":\"2.3\"},{\"id\":\"221\",\"descripcion\":\"Chicle bubaloo\",\"cantidad\":\"6\",\"stock\":\"24\",\"precio\":\"0.2\",\"precioCompra\":\"0.12\",\"total\":\"1.2\"},{\"id\":\"212\",\"descripcion\":\"Chiclets adams chico\",\"cantidad\":\"6\",\"stock\":\"94\",\"precio\":\"0.2\",\"precioCompra\":\"0.133\",\"total\":\"1.2\"}]', '0', '40.2', '40.2', 'Efectivo', null, '2023-02-14 00:00:00', '2023-02-14', '0');
INSERT INTO `ventas` VALUES ('40', '10002', '6', '1', 'admin', null, '[{\"id\":\"182\",\"descripcion\":\"Inka cola 600 ml\",\"cantidad\":\"1\",\"stock\":\"99\",\"precio\":\"2.8\",\"precioCompra\":\"2.35\",\"total\":\"2.8\"}]', '0', '2.8', '2.8', 'Credito', '1', '2023-02-14 00:00:00', '2023-03-05', '0');
INSERT INTO `ventas` VALUES ('41', '10003', '3', '1', null, null, '[{\"id\":\"181\",\"descripcion\":\"Inka cola 2L\",\"cantidad\":\"1\",\"stock\":\"99\",\"precio\":\"6\",\"precioCompra\":\"5.25\",\"total\":\"6\"},{\"id\":\"180\",\"descripcion\":\"Coca cola 2L\",\"cantidad\":\"1\",\"stock\":\"99\",\"precio\":\"6\",\"precioCompra\":\"5.25\",\"total\":\"6\"},{\"id\":\"222\",\"descripcion\":\"Chifle\",\"cantidad\":\"2\",\"stock\":\"8\",\"precio\":\"1\",\"precioCompra\":\"0.791\",\"total\":\"2\"},{\"id\":\"186\",\"descripcion\":\"Leche gloria azul 400 gr\",\"cantidad\":\"2\",\"stock\":\"8\",\"precio\":\"4\",\"precioCompra\":\"3.5\",\"total\":\"8\"}]', '0', '22', '22', 'Efectivo', null, '2023-02-15 03:34:34', '2023-02-15', '0');
INSERT INTO `ventas` VALUES ('42', '10004', '3', '1', null, null, '[{\"id\":\"227\",\"descripcion\":\"Jabon popeye\",\"cantidad\":\"1\",\"stock\":\"3\",\"precio\":\"2\",\"precioCompra\":\"1.5\",\"total\":\"2\"},{\"id\":\"226\",\"descripcion\":\"Pepsi jumbo\",\"cantidad\":\"1\",\"stock\":\"9\",\"precio\":\"2.7\",\"precioCompra\":\"2.25\",\"total\":\"2.7\"},{\"id\":\"225\",\"descripcion\":\"Pepsi litro y medio\",\"cantidad\":\"1\",\"stock\":\"9\",\"precio\":\"3.5\",\"precioCompra\":\"3.042\",\"total\":\"3.5\"},{\"id\":\"224\",\"descripcion\":\"Fosforo\",\"cantidad\":\"4\",\"stock\":\"46\",\"precio\":\"0.3\",\"precioCompra\":\"0.18\",\"total\":\"1.2\"}]', '0', '9.4', '9.4', 'Efectivo', null, '2023-02-14 00:00:00', '2023-02-14', '0');
INSERT INTO `ventas` VALUES ('43', '10005', '3', '1', null, null, '[{\"id\":\"243\",\"descripcion\":\"Lavavajilla crema ñapancha\",\"cantidad\":\"1\",\"stock\":\"0\",\"precio\":\"4.5\",\"precioCompra\":\"3.8\",\"total\":\"4.5\"},{\"id\":\"229\",\"descripcion\":\"Sal\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"1.5\",\"precioCompra\":\"1.2\",\"total\":\"1.5\"},{\"id\":\"218\",\"descripcion\":\"Ajinomix crocante\",\"cantidad\":\"1\",\"stock\":\"5\",\"precio\":\"2\",\"precioCompra\":\"1.58\",\"total\":\"2\"},{\"id\":\"199\",\"descripcion\":\"Galleta picara\",\"cantidad\":\"1\",\"stock\":\"9\",\"precio\":\"1\",\"precioCompra\":\"0.83\",\"total\":\"1\"},{\"id\":\"195\",\"descripcion\":\"Kr\",\"cantidad\":\"3\",\"stock\":\"87\",\"precio\":\"1\",\"precioCompra\":\"0.853\",\"total\":\"3\"}]', '0', '12', '12', 'Efectivo', null, '2023-02-15 13:16:01', '2023-02-15', '0');
INSERT INTO `ventas` VALUES ('44', '10006', '3', '1', null, null, '[{\"id\":\"200\",\"descripcion\":\"Galleta trikis\",\"cantidad\":\"2\",\"stock\":\"13\",\"precio\":\"0.8\",\"precioCompra\":\"0.583\",\"total\":\"1.6\"},{\"id\":\"211\",\"descripcion\":\"Jabon protex 110gr\",\"cantidad\":\"1\",\"stock\":\"7\",\"precio\":\"4\",\"precioCompra\":\"3.65\",\"total\":\"4\"},{\"id\":\"221\",\"descripcion\":\"Chicle bubaloo\",\"cantidad\":\"1\",\"stock\":\"23\",\"precio\":\"0.2\",\"precioCompra\":\"0.12\",\"total\":\"0.2\"},{\"id\":\"205\",\"descripcion\":\"Detergente patito\",\"cantidad\":\"2\",\"stock\":\"18\",\"precio\":\"1.3\",\"precioCompra\":\"1.083\",\"total\":\"2.6\"},{\"id\":\"195\",\"descripcion\":\"Kr\",\"cantidad\":\"9\",\"stock\":\"78\",\"precio\":\"1\",\"precioCompra\":\"0.853\",\"total\":\"9\"},{\"id\":\"198\",\"descripcion\":\"Agua cielo\",\"cantidad\":\"4\",\"stock\":\"30\",\"precio\":\"1\",\"precioCompra\":\"0.8\",\"total\":\"4\"}]', '0', '21.4', '21.4', 'Efectivo', null, '2023-02-21 00:00:00', '2023-02-21', '0');
INSERT INTO `ventas` VALUES ('45', '10007', '3', '1', null, null, '[{\"id\":\"241\",\"descripcion\":\"Detergente opal 450gr\",\"cantidad\":\"1\",\"stock\":\"5\",\"precio\":\"6.3\",\"total\":\"6.3\"},{\"id\":\"207\",\"descripcion\":\"Suavitel sachet\",\"cantidad\":\"1\",\"stock\":\"28\",\"precio\":\"1.2\",\"total\":\"1.2\"},{\"id\":\"195\",\"descripcion\":\"Kr\",\"cantidad\":\"6\",\"stock\":\"72\",\"precio\":\"1\",\"total\":\"6\"},{\"id\":\"205\",\"descripcion\":\"Detergente patito\",\"cantidad\":\"1\",\"stock\":\"17\",\"precio\":\"1.3\",\"total\":\"1.3\"},{\"id\":\"198\",\"descripcion\":\"Agua cielo\",\"cantidad\":\"8\",\"stock\":\"22\",\"precio\":\"1\",\"total\":\"8\"},{\"id\":\"209\",\"descripcion\":\"Cereal chico\",\"cantidad\":\"1\",\"stock\":\"11\",\"precio\":\"0.8\",\"total\":\"0.8\"},{\"id\":\"226\",\"descripcion\":\"Pepsi jumbo\",\"cantidad\":\"1\",\"stock\":\"8\",\"precio\":\"2.7\",\"total\":\"2.7\"},{\"id\":\"212\",\"descripcion\":\"Chiclets adams chico\",\"cantidad\":\"1\",\"stock\":\"93\",\"precio\":\"0.2\",\"total\":\"0.2\"},{\"id\":\"176\",\"descripcion\":\"Hals\",\"cantidad\":\"2\",\"stock\":\"98\",\"precio\":\"0.2\",\"precioCompra\":\"0.11\",\"total\":\"0.4\"},{\"id\":\"244\",\"descripcion\":\"Kris\",\"cantidad\":\"1\",\"stock\":\"9\",\"precio\":\"1.2\",\"precioCompra\":\"1\",\"total\":\"1.2\"},{\"id\":\"245\",\"descripcion\":\"Pilas panasonic 2A\",\"cantidad\":\"1\",\"stock\":\"9\",\"precio\":\"2\",\"precioCompra\":\"1.3\",\"total\":\"2\"}]', '0', '30.1', '30.1', 'Efectivo', null, '2023-02-22 00:00:00', '2023-02-22', '0');
INSERT INTO `ventas` VALUES ('46', '10008', '3', '1', null, null, '[{\"id\":\"195\",\"descripcion\":\"Kr\",\"cantidad\":\"12\",\"stock\":\"60\",\"precio\":\"1\",\"precioCompra\":\"0.853\",\"total\":\"12\"},{\"id\":\"198\",\"descripcion\":\"Agua cielo\",\"cantidad\":\"10\",\"stock\":\"12\",\"precio\":\"1\",\"precioCompra\":\"0.8\",\"total\":\"10\"},{\"id\":\"244\",\"descripcion\":\"Kris\",\"cantidad\":\"1\",\"stock\":\"8\",\"precio\":\"1.2\",\"precioCompra\":\"1\",\"total\":\"1.2\"},{\"id\":\"246\",\"descripcion\":\"Sibarita\",\"cantidad\":\"1\",\"stock\":\"49\",\"precio\":\"0.4\",\"precioCompra\":\"0.2\",\"total\":\"0.4\"},{\"id\":\"199\",\"descripcion\":\"Galleta picara\",\"cantidad\":\"2\",\"stock\":\"7\",\"precio\":\"1\",\"precioCompra\":\"0.83\",\"total\":\"2\"},{\"id\":\"223\",\"descripcion\":\"Pegamento tris\",\"cantidad\":\"1\",\"stock\":\"14\",\"precio\":\"0.8\",\"precioCompra\":\"0.5\",\"total\":\"0.8\"},{\"id\":\"176\",\"descripcion\":\"Hals\",\"cantidad\":\"1\",\"stock\":\"97\",\"precio\":\"0.2\",\"precioCompra\":\"0.11\",\"total\":\"0.2\"}]', '0', '26.6', '26.6', 'Efectivo', null, '2023-02-23 00:00:00', '2023-02-23', '0');
INSERT INTO `ventas` VALUES ('47', '10009', '3', '1', null, null, '[{\"id\":\"230\",\"descripcion\":\"Poet 350ml\",\"cantidad\":\"1\",\"stock\":\"9\",\"precio\":\"2.3\",\"precioCompra\":\"1.9\",\"total\":\"2.3\"},{\"id\":\"195\",\"descripcion\":\"Kr\",\"cantidad\":\"6\",\"stock\":\"54\",\"precio\":\"1\",\"precioCompra\":\"0.853\",\"total\":\"6\"},{\"id\":\"198\",\"descripcion\":\"Agua cielo\",\"cantidad\":\"10\",\"stock\":\"2\",\"precio\":\"1\",\"precioCompra\":\"0.8\",\"total\":\"10\"},{\"id\":\"215\",\"descripcion\":\"Galleta vainilla field\",\"cantidad\":\"1\",\"stock\":\"11\",\"precio\":\"1\",\"precioCompra\":\"0.793\",\"total\":\"1\"},{\"id\":\"231\",\"descripcion\":\"Cheese tris 16gr\",\"cantidad\":\"1\",\"stock\":\"12\",\"precio\":\"0.6\",\"precioCompra\":\"0.52\",\"total\":\"0.6\"},{\"id\":\"197\",\"descripcion\":\"Galleta ritz taco\",\"cantidad\":\"1\",\"stock\":\"8\",\"precio\":\"1.3\",\"precioCompra\":\"1.1\",\"total\":\"1.3\"},{\"id\":\"226\",\"descripcion\":\"Pepsi jumbo\",\"cantidad\":\"1\",\"stock\":\"7\",\"precio\":\"2.7\",\"precioCompra\":\"2.25\",\"total\":\"2.7\"},{\"id\":\"207\",\"descripcion\":\"Suavitel sachet\",\"cantidad\":\"2\",\"stock\":\"26\",\"precio\":\"1.2\",\"precioCompra\":\"0.93\",\"total\":\"2.4\"},{\"id\":\"238\",\"descripcion\":\"Detergente marsella 330gr\",\"cantidad\":\"1\",\"stock\":\"5\",\"precio\":\"4.7\",\"precioCompra\":\"4.15\",\"total\":\"4.7\"}]', '0', '31', '31', 'Efectivo', null, '2023-02-24 00:00:00', '2023-02-24', '0');
INSERT INTO `ventas` VALUES ('48', '10010', '3', '1', null, null, '[{\"id\":\"184\",\"descripcion\":\"Yogurt yofresh 1L\",\"cantidad\":\"1\",\"stock\":\"2\",\"precio\":\"6.8\",\"precioCompra\":\"6\",\"total\":\"6.6\"},{\"id\":\"183\",\"descripcion\":\"Coca cola 600 ml\",\"cantidad\":\"1\",\"stock\":\"99\",\"precio\":\"2.8\",\"precioCompra\":\"2.35\",\"total\":\"2.8\"},{\"id\":\"195\",\"descripcion\":\"Kr\",\"cantidad\":\"7\",\"stock\":\"47\",\"precio\":\"1\",\"precioCompra\":\"0.853\",\"total\":\"7\"},{\"id\":\"198\",\"descripcion\":\"Agua cielo\",\"cantidad\":\"2\",\"stock\":\"0\",\"precio\":\"1\",\"precioCompra\":\"0.8\",\"total\":\"2\"},{\"id\":\"255\",\"descripcion\":\"Agua benedictino 600ml\",\"cantidad\":\"5\",\"stock\":\"3\",\"precio\":\"1\",\"precioCompra\":\"0.82\",\"total\":\"5\"},{\"id\":\"179\",\"descripcion\":\"Coca cola 1L\",\"cantidad\":\"1\",\"stock\":\"99\",\"precio\":\"3.8\",\"precioCompra\":\"3.15\",\"total\":\"3.8\"},{\"id\":\"178\",\"descripcion\":\"Inka cola 1L\",\"cantidad\":\"1\",\"stock\":\"99\",\"precio\":\"3.8\",\"precioCompra\":\"3.15\",\"total\":\"3.8\"},{\"id\":\"237\",\"descripcion\":\"Galleta rellenita\",\"cantidad\":\"1\",\"stock\":\"79\",\"precio\":\"0.5\",\"precioCompra\":\"0.37\",\"total\":\"0.5\"}]', '0', '31.5', '31.5', 'Efectivo', null, '2023-02-25 11:05:32', '2023-02-25', '0');
INSERT INTO `ventas` VALUES ('49', '10011', '3', '1', null, null, '[{\"id\":\"224\",\"descripcion\":\"Fosforo\",\"cantidad\":\"2\",\"stock\":\"44\",\"precio\":\"0.3\",\"total\":\"0.6\"},{\"id\":\"195\",\"descripcion\":\"Kr\",\"cantidad\":\"7\",\"stock\":\"40\",\"precio\":\"1\",\"total\":\"7\"},{\"id\":\"215\",\"descripcion\":\"Galleta vainilla field\",\"cantidad\":\"2\",\"stock\":\"9\",\"precio\":\"1\",\"total\":\"2\"},{\"id\":\"250\",\"descripcion\":\"Sprite 2L\",\"cantidad\":\"1\",\"stock\":\"3\",\"precio\":\"4.5\",\"total\":\"4.5\"},{\"id\":\"198\",\"descripcion\":\"Agua cielo\",\"cantidad\":\"6\",\"stock\":\"44\",\"precio\":\"1\",\"total\":\"6\"},{\"id\":\"222\",\"descripcion\":\"Chifle\",\"cantidad\":\"4\",\"stock\":\"4\",\"precio\":\"1\",\"total\":\"4\"},{\"id\":\"193\",\"descripcion\":\"Chupete pin pon\",\"cantidad\":\"1\",\"stock\":\"23\",\"precio\":\"0.5\",\"total\":\"0.5\"},{\"id\":\"247\",\"descripcion\":\"Cool 1500Ml\",\"cantidad\":\"1\",\"stock\":\"6\",\"precio\":\"3.5\",\"total\":\"3.5\"},{\"id\":\"208\",\"descripcion\":\"Cereal grande\",\"cantidad\":\"1\",\"stock\":\"5\",\"precio\":\"3.2\",\"total\":\"3.2\"},{\"id\":\"196\",\"descripcion\":\"Detergente marsella 450gr\",\"cantidad\":\"1\",\"stock\":\"5\",\"precio\":\"5.7\",\"total\":\"5.6\"},{\"id\":\"228\",\"descripcion\":\"Lejia clorox ropa color grande\",\"cantidad\":\"1\",\"stock\":\"5\",\"precio\":\"4\",\"total\":\"4\"},{\"id\":\"186\",\"descripcion\":\"Leche gloria azul 400 gr\",\"cantidad\":\"1\",\"stock\":\"7\",\"precio\":\"4\",\"total\":\"4\"},{\"id\":\"184\",\"descripcion\":\"Yogurt yofresh 1L\",\"cantidad\":\"1\",\"stock\":\"1\",\"precio\":\"6.8\",\"total\":\"6.5\"},{\"id\":\"223\",\"descripcion\":\"Pegamento tris\",\"cantidad\":\"1\",\"stock\":\"13\",\"precio\":\"0.8\",\"total\":\"0.8\"},{\"id\":\"221\",\"descripcion\":\"Chicle bubaloo\",\"cantidad\":\"1\",\"stock\":\"22\",\"precio\":\"0.2\",\"total\":\"0.2\"},{\"id\":\"245\",\"descripcion\":\"Pilas panasonic 2A\",\"cantidad\":\"1\",\"stock\":\"8\",\"precio\":\"2\",\"total\":\"2\"},{\"id\":\"232\",\"descripcion\":\"Piqueo 23gr\",\"cantidad\":\"1\",\"stock\":\"11\",\"precio\":\"1\",\"total\":\"1\"},{\"id\":\"231\",\"descripcion\":\"Cheese tris 16gr\",\"cantidad\":\"3\",\"stock\":\"9\",\"precio\":\"0.6\",\"total\":\"1.8\"},{\"id\":\"255\",\"descripcion\":\"Agua benedictino 600ml\",\"cantidad\":\"1\",\"stock\":\"2\",\"precio\":\"1\",\"total\":\"3\"},{\"id\":\"200\",\"descripcion\":\"Galleta trikis\",\"cantidad\":\"1\",\"stock\":\"12\",\"precio\":\"0.8\",\"total\":\"0.8\"},{\"id\":\"258\",\"descripcion\":\"Papel toalla nova megarrollo\",\"cantidad\":\"2\",\"stock\":\"10\",\"precio\":\"2.4\",\"precioCompra\":\"2\",\"total\":\"4.8\"}]', '0', '65.8', '65.8', 'Efectivo', null, '2023-02-26 00:00:00', '2023-02-26', '0');
INSERT INTO `ventas` VALUES ('50', '10012', '3', '1', null, null, '[{\"id\":\"178\",\"descripcion\":\"Inka cola 1L\",\"cantidad\":\"1\",\"stock\":\"98\",\"precio\":\"3.8\",\"total\":\"3.8\"},{\"id\":\"183\",\"descripcion\":\"Coca cola 600 ml\",\"cantidad\":\"2\",\"stock\":\"97\",\"precio\":\"2.8\",\"total\":\"5.6\"},{\"id\":\"259\",\"descripcion\":\"Jugo caja chico\",\"cantidad\":\"2\",\"stock\":\"8\",\"precio\":\"1.5\",\"total\":\"3\"},{\"id\":\"245\",\"descripcion\":\"Pilas panasonic 2A\",\"cantidad\":\"1\",\"stock\":\"7\",\"precio\":\"2\",\"total\":\"2\"},{\"id\":\"213\",\"descripcion\":\"Galleta oreo chica\",\"cantidad\":\"4\",\"stock\":\"8\",\"precio\":\"1\",\"total\":\"4\"},{\"id\":\"257\",\"descripcion\":\"Chupete globo pop\",\"cantidad\":\"1\",\"stock\":\"14\",\"precio\":\"0.5\",\"total\":\"0.5\"},{\"id\":\"224\",\"descripcion\":\"Fosforo\",\"cantidad\":\"1\",\"stock\":\"43\",\"precio\":\"0.3\",\"total\":\"0.3\"},{\"id\":\"205\",\"descripcion\":\"Detergente patito\",\"cantidad\":\"1\",\"stock\":\"16\",\"precio\":\"1.3\",\"total\":\"1.3\"},{\"id\":\"181\",\"descripcion\":\"Inka cola 2L\",\"cantidad\":\"1\",\"stock\":\"7\",\"precio\":\"6.3\",\"total\":\"6.3\"},{\"id\":\"219\",\"descripcion\":\"Shampo ballerina\",\"cantidad\":\"1\",\"stock\":\"11\",\"precio\":\"1\",\"total\":\"1\"},{\"id\":\"198\",\"descripcion\":\"Agua cielo\",\"cantidad\":\"20\",\"stock\":\"24\",\"precio\":\"1\",\"total\":\"20\"},{\"id\":\"212\",\"descripcion\":\"Chiclets adams chico\",\"cantidad\":\"1\",\"stock\":\"92\",\"precio\":\"0.2\",\"total\":\"0.2\"},{\"id\":\"222\",\"descripcion\":\"Chifle\",\"cantidad\":\"1\",\"stock\":\"3\",\"precio\":\"1\",\"total\":\"1\"},{\"id\":\"179\",\"descripcion\":\"Coca cola 1L\",\"cantidad\":\"1\",\"stock\":\"98\",\"precio\":\"3.8\",\"total\":\"3.8\"},{\"id\":\"256\",\"descripcion\":\"Shampo hys\",\"cantidad\":\"1\",\"stock\":\"29\",\"precio\":\"1\",\"total\":\"1\"},{\"id\":\"202\",\"descripcion\":\"Papel elite mellizo\",\"cantidad\":\"1\",\"stock\":\"8\",\"precio\":\"2.3\",\"total\":\"2.3\"},{\"id\":\"206\",\"descripcion\":\"Nutribela\",\"cantidad\":\"1\",\"stock\":\"11\",\"precio\":\"1.5\",\"total\":\"1.5\"},{\"id\":\"195\",\"descripcion\":\"Kr\",\"cantidad\":\"11\",\"stock\":\"29\",\"precio\":\"1\",\"total\":\"11\"},{\"id\":\"260\",\"descripcion\":\"Lejia clorox grande\",\"cantidad\":\"2\",\"stock\":\"4\",\"precio\":\"2\",\"precioCompra\":\"1.65\",\"total\":\"4\"},{\"id\":\"175\",\"descripcion\":\"Chocosoda\",\"cantidad\":\"2\",\"stock\":\"48\",\"precio\":\"1.2\",\"precioCompra\":\"0.95\",\"total\":\"2.4\"}]', '0', '75', '75', 'Efectivo', null, '2023-02-27 00:00:00', '2023-02-27', '0');
INSERT INTO `ventas` VALUES ('51', '10013', '3', '1', null, null, '[{\"id\":\"195\",\"descripcion\":\"Kr\",\"cantidad\":\"14\",\"stock\":\"15\",\"precio\":\"1\",\"precioCompra\":\"0.853\",\"total\":\"14\"},{\"id\":\"198\",\"descripcion\":\"Agua cielo\",\"cantidad\":\"3\",\"stock\":\"21\",\"precio\":\"1\",\"precioCompra\":\"0.8\",\"total\":\"3\"},{\"id\":\"255\",\"descripcion\":\"Agua benedictino 600ml\",\"cantidad\":\"2\",\"stock\":\"0\",\"precio\":\"1\",\"precioCompra\":\"0.82\",\"total\":\"2\"},{\"id\":\"182\",\"descripcion\":\"Inka cola 600 ml\",\"cantidad\":\"1\",\"stock\":\"98\",\"precio\":\"2.8\",\"precioCompra\":\"2.35\",\"total\":\"2.8\"},{\"id\":\"183\",\"descripcion\":\"Coca cola 600 ml\",\"cantidad\":\"1\",\"stock\":\"96\",\"precio\":\"2.8\",\"precioCompra\":\"2.35\",\"total\":\"2.8\"},{\"id\":\"268\",\"descripcion\":\"Cocoa winter\",\"cantidad\":\"1\",\"stock\":\"49\",\"precio\":\"0.5\",\"precioCompra\":\"0.28\",\"total\":\"0.5\"},{\"id\":\"264\",\"descripcion\":\"Mani granuts oriental\",\"cantidad\":\"1\",\"stock\":\"11\",\"precio\":\"1.2\",\"precioCompra\":\"0.82\",\"total\":\"1.2\"},{\"id\":\"179\",\"descripcion\":\"Coca cola 1L\",\"cantidad\":\"1\",\"stock\":\"97\",\"precio\":\"3.8\",\"precioCompra\":\"3.15\",\"total\":\"3.8\"},{\"id\":\"244\",\"descripcion\":\"Kris\",\"cantidad\":\"2\",\"stock\":\"6\",\"precio\":\"1.2\",\"precioCompra\":\"1\",\"total\":\"2.4\"},{\"id\":\"232\",\"descripcion\":\"Piqueo 23gr\",\"cantidad\":\"2\",\"stock\":\"9\",\"precio\":\"1\",\"precioCompra\":\"0.83\",\"total\":\"2\"}]', '0', '34.5', '34.5', 'Efectivo', null, '2023-02-28 00:00:00', '2023-02-28', '0');
INSERT INTO `ventas` VALUES ('52', '10014', '3', '1', null, null, '[{\"id\":\"201\",\"descripcion\":\"Guarana 450ml\",\"cantidad\":\"1\",\"stock\":\"14\",\"precio\":\"2\",\"precioCompra\":\"1.393\",\"total\":\"2\"},{\"id\":\"197\",\"descripcion\":\"Galleta ritz taco\",\"cantidad\":\"1\",\"stock\":\"7\",\"precio\":\"1.3\",\"precioCompra\":\"1.1\",\"total\":\"1.3\"},{\"id\":\"178\",\"descripcion\":\"Inka cola 1L\",\"cantidad\":\"1\",\"stock\":\"97\",\"precio\":\"3.8\",\"precioCompra\":\"3.15\",\"total\":\"3.8\"},{\"id\":\"198\",\"descripcion\":\"Agua cielo\",\"cantidad\":\"11\",\"stock\":\"10\",\"precio\":\"1\",\"precioCompra\":\"0.8\",\"total\":\"11\"},{\"id\":\"221\",\"descripcion\":\"Chicle bubaloo\",\"cantidad\":\"2\",\"stock\":\"20\",\"precio\":\"0.2\",\"precioCompra\":\"0.12\",\"total\":\"0.4\"},{\"id\":\"232\",\"descripcion\":\"Piqueo 23gr\",\"cantidad\":\"2\",\"stock\":\"7\",\"precio\":\"1\",\"precioCompra\":\"0.83\",\"total\":\"2\"},{\"id\":\"264\",\"descripcion\":\"Mani granuts oriental\",\"cantidad\":\"1\",\"stock\":\"10\",\"precio\":\"1.2\",\"precioCompra\":\"0.82\",\"total\":\"1.2\"},{\"id\":\"222\",\"descripcion\":\"Chifle\",\"cantidad\":\"1\",\"stock\":\"2\",\"precio\":\"1\",\"precioCompra\":\"0.791\",\"total\":\"1\"},{\"id\":\"195\",\"descripcion\":\"Kr\",\"cantidad\":\"2\",\"stock\":\"13\",\"precio\":\"1\",\"precioCompra\":\"0.853\",\"total\":\"2\"},{\"id\":\"182\",\"descripcion\":\"Inka cola 600 ml\",\"cantidad\":\"1\",\"stock\":\"97\",\"precio\":\"2.8\",\"precioCompra\":\"2.35\",\"total\":\"2.8\"},{\"id\":\"183\",\"descripcion\":\"Coca cola 600 ml\",\"cantidad\":\"2\",\"stock\":\"94\",\"precio\":\"2.8\",\"precioCompra\":\"2.35\",\"total\":\"5.6\"},{\"id\":\"263\",\"descripcion\":\"Guarana 3L\",\"cantidad\":\"1\",\"stock\":\"3\",\"precio\":\"8.5\",\"precioCompra\":\"6.73\",\"total\":\"8.5\"},{\"id\":\"194\",\"descripcion\":\"Galleta soda gn\",\"cantidad\":\"5\",\"stock\":\"35\",\"precio\":\"0.4\",\"precioCompra\":\"0.26\",\"total\":\"2\"},{\"id\":\"175\",\"descripcion\":\"Chocosoda\",\"cantidad\":\"1\",\"stock\":\"47\",\"precio\":\"1.2\",\"precioCompra\":\"0.95\",\"total\":\"1.2\"},{\"id\":\"268\",\"descripcion\":\"Cocoa winter\",\"cantidad\":\"1\",\"stock\":\"48\",\"precio\":\"0.5\",\"precioCompra\":\"0.28\",\"total\":\"0.5\"}]', '0', '45.3', '45.3', 'Efectivo', null, '2023-03-01 00:00:00', '2023-03-01', '0');
INSERT INTO `ventas` VALUES ('53', '10015', '3', '1', null, null, '[{\"id\":\"201\",\"descripcion\":\"Guarana 450ml\",\"cantidad\":\"1\",\"stock\":\"13\",\"precio\":\"2\",\"total\":\"2\"},{\"id\":\"198\",\"descripcion\":\"Agua cielo\",\"cantidad\":\"17\",\"stock\":\"13\",\"precio\":\"1\",\"total\":\"17\"},{\"id\":\"222\",\"descripcion\":\"Chifle\",\"cantidad\":\"1\",\"stock\":\"1\",\"precio\":\"1\",\"total\":\"1\"},{\"id\":\"195\",\"descripcion\":\"Kr\",\"cantidad\":\"14\",\"stock\":\"16\",\"precio\":\"1\",\"total\":\"14\"},{\"id\":\"264\",\"descripcion\":\"Mani granuts oriental\",\"cantidad\":\"1\",\"stock\":\"9\",\"precio\":\"1.2\",\"total\":\"1.2\"},{\"id\":\"259\",\"descripcion\":\"Jugo caja chico\",\"cantidad\":\"1\",\"stock\":\"7\",\"precio\":\"1.5\",\"total\":\"1.5\"},{\"id\":\"213\",\"descripcion\":\"Galleta oreo chica\",\"cantidad\":\"1\",\"stock\":\"7\",\"precio\":\"1\",\"total\":\"1\"},{\"id\":\"200\",\"descripcion\":\"Galleta trikis\",\"cantidad\":\"2\",\"stock\":\"10\",\"precio\":\"0.8\",\"total\":\"1.6\"},{\"id\":\"175\",\"descripcion\":\"Chocosoda\",\"cantidad\":\"1\",\"stock\":\"46\",\"precio\":\"1.2\",\"total\":\"1.2\"},{\"id\":\"231\",\"descripcion\":\"Cheese tris 16gr\",\"cantidad\":\"1\",\"stock\":\"8\",\"precio\":\"0.6\",\"total\":\"0.6\"},{\"id\":\"188\",\"descripcion\":\"leche gloria light 400 gr\",\"cantidad\":\"2\",\"stock\":\"8\",\"precio\":\"4.2\",\"total\":\"8.2\"},{\"id\":\"202\",\"descripcion\":\"Papel elite mellizo\",\"cantidad\":\"1\",\"stock\":\"7\",\"precio\":\"2.3\",\"total\":\"2.3\"},{\"id\":\"212\",\"descripcion\":\"Chiclets adams chico\",\"cantidad\":\"2\",\"stock\":\"90\",\"precio\":\"0.2\",\"total\":\"0.4\"},{\"id\":\"269\",\"descripcion\":\"Poet 750ml\",\"cantidad\":\"1\",\"stock\":\"4\",\"precio\":\"3\",\"precioCompra\":\"2.3\",\"total\":\"3\"}]', '0', '55', '55', 'Efectivo', null, '2023-03-02 00:00:00', '2023-03-02', '0');
INSERT INTO `ventas` VALUES ('54', '10016', '3', '1', null, null, '[{\"id\":\"179\",\"descripcion\":\"Coca cola 1L\",\"cantidad\":\"2\",\"stock\":\"95\",\"precio\":\"3.8\",\"total\":\"7.6\"},{\"id\":\"178\",\"descripcion\":\"Inka cola 1L\",\"cantidad\":\"1\",\"stock\":\"96\",\"precio\":\"3.8\",\"total\":\"3.8\"},{\"id\":\"180\",\"descripcion\":\"Coca cola 2L\",\"cantidad\":\"1\",\"stock\":\"98\",\"precio\":\"6.3\",\"total\":\"6.3\"},{\"id\":\"237\",\"descripcion\":\"Galleta rellenita\",\"cantidad\":\"1\",\"stock\":\"78\",\"precio\":\"0.5\",\"total\":\"0.5\"},{\"id\":\"198\",\"descripcion\":\"Agua cielo\",\"cantidad\":\"12\",\"stock\":\"1\",\"precio\":\"1\",\"total\":\"12\"},{\"id\":\"235\",\"descripcion\":\"jabon bolivar\",\"cantidad\":\"1\",\"stock\":\"5\",\"precio\":\"3.3\",\"total\":\"3.3\"},{\"id\":\"225\",\"descripcion\":\"Pepsi litro y medio\",\"cantidad\":\"1\",\"stock\":\"8\",\"precio\":\"3.5\",\"total\":\"3.5\"},{\"id\":\"195\",\"descripcion\":\"Kr\",\"cantidad\":\"16\",\"stock\":\"0\",\"precio\":\"1\",\"total\":\"16\"},{\"id\":\"248\",\"descripcion\":\"Oro 1500Ml\",\"cantidad\":\"1\",\"stock\":\"6\",\"precio\":\"3.5\",\"total\":\"3.5\"},{\"id\":\"213\",\"descripcion\":\"Galleta oreo chica\",\"cantidad\":\"2\",\"stock\":\"5\",\"precio\":\"1\",\"total\":\"2\"},{\"id\":\"223\",\"descripcion\":\"Pegamento tris\",\"cantidad\":\"1\",\"stock\":\"12\",\"precio\":\"0.8\",\"total\":\"0.8\"},{\"id\":\"231\",\"descripcion\":\"Cheese tris 16gr\",\"cantidad\":\"1\",\"stock\":\"7\",\"precio\":\"0.6\",\"total\":\"0.6\"},{\"id\":\"222\",\"descripcion\":\"Chifle\",\"cantidad\":\"1\",\"stock\":\"0\",\"precio\":\"1\",\"total\":\"1\"},{\"id\":\"271\",\"descripcion\":\"Agua cielo 2L\",\"cantidad\":\"1\",\"stock\":\"5\",\"precio\":\"3.5\",\"total\":\"3.5\"},{\"id\":\"217\",\"descripcion\":\"Ajinomen\",\"cantidad\":\"2\",\"stock\":\"10\",\"precio\":\"1.5\",\"precioCompra\":\"1.28\",\"total\":\"3\"},{\"id\":\"270\",\"descripcion\":\"Huevos\",\"cantidad\":\"2\",\"stock\":\"58\",\"precio\":\"0.6\",\"precioCompra\":\"0.51\",\"total\":\"1.2\"}]', '0', '68.6', '68.6', 'Efectivo', null, '2023-03-03 00:00:00', '2023-03-03', '0');
INSERT INTO `ventas` VALUES ('55', '10017', '3', '1', null, null, '[{\"id\":\"238\",\"descripcion\":\"Detergente marsella 330gr\",\"cantidad\":\"1\",\"stock\":\"4\",\"precio\":\"4.7\",\"total\":\"4.7\"},{\"id\":\"207\",\"descripcion\":\"Suavitel sachet\",\"cantidad\":\"3\",\"stock\":\"23\",\"precio\":\"1.2\",\"total\":\"3.6\"},{\"id\":\"259\",\"descripcion\":\"Jugo caja chico\",\"cantidad\":\"2\",\"stock\":\"5\",\"precio\":\"1.5\",\"total\":\"3\"},{\"id\":\"233\",\"descripcion\":\"Cuate 26gr\",\"cantidad\":\"1\",\"stock\":\"11\",\"precio\":\"0.6\",\"total\":\"0.6\"},{\"id\":\"226\",\"descripcion\":\"Pepsi jumbo\",\"cantidad\":\"1\",\"stock\":\"6\",\"precio\":\"2.7\",\"total\":\"2.7\"},{\"id\":\"229\",\"descripcion\":\"Sal\",\"cantidad\":\"1\",\"stock\":\"18\",\"precio\":\"1.5\",\"total\":\"1.5\"},{\"id\":\"183\",\"descripcion\":\"Coca cola 600 ml\",\"cantidad\":\"1\",\"stock\":\"93\",\"precio\":\"2.8\",\"total\":\"2.8\"},{\"id\":\"178\",\"descripcion\":\"Inka cola 1L\",\"cantidad\":\"1\",\"stock\":\"95\",\"precio\":\"3.8\",\"total\":\"3.8\"},{\"id\":\"231\",\"descripcion\":\"Cheese tris 16gr\",\"cantidad\":\"2\",\"stock\":\"5\",\"precio\":\"0.6\",\"total\":\"1.2\"},{\"id\":\"199\",\"descripcion\":\"Galleta picara\",\"cantidad\":\"1\",\"stock\":\"6\",\"precio\":\"1\",\"total\":\"1\"},{\"id\":\"198\",\"descripcion\":\"Agua cielo\",\"cantidad\":\"17\",\"stock\":\"24\",\"precio\":\"1\",\"total\":\"17\"},{\"id\":\"237\",\"descripcion\":\"Galleta rellenita\",\"cantidad\":\"1\",\"stock\":\"77\",\"precio\":\"0.5\",\"total\":\"0.5\"},{\"id\":\"175\",\"descripcion\":\"Chocosoda\",\"cantidad\":\"1\",\"stock\":\"45\",\"precio\":\"1.2\",\"total\":\"1.2\"},{\"id\":\"221\",\"descripcion\":\"Chicle bubaloo\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"0.2\",\"total\":\"0.2\"},{\"id\":\"181\",\"descripcion\":\"Inka cola 2L\",\"cantidad\":\"1\",\"stock\":\"6\",\"precio\":\"6.3\",\"total\":\"6.3\"},{\"id\":\"240\",\"descripcion\":\"Detergente bolivar 450gr\",\"cantidad\":\"1\",\"stock\":\"5\",\"precio\":\"7.2\",\"total\":\"7.2\"},{\"id\":\"228\",\"descripcion\":\"Lejia clorox ropa color grande\",\"cantidad\":\"1\",\"stock\":\"4\",\"precio\":\"4\",\"total\":\"4\"},{\"id\":\"186\",\"descripcion\":\"Leche gloria azul 400 gr\",\"cantidad\":\"2\",\"stock\":\"5\",\"precio\":\"4\",\"total\":\"8\"},{\"id\":\"271\",\"descripcion\":\"Agua cielo 2L\",\"cantidad\":\"1\",\"stock\":\"4\",\"precio\":\"3.5\",\"total\":\"3\"},{\"id\":\"251\",\"descripcion\":\"Fanta 2L\",\"cantidad\":\"1\",\"stock\":\"3\",\"precio\":\"4.5\",\"total\":\"4.5\"},{\"id\":\"194\",\"descripcion\":\"Galleta soda gn\",\"cantidad\":\"2\",\"stock\":\"33\",\"precio\":\"0.4\",\"total\":\"0.8\"},{\"id\":\"202\",\"descripcion\":\"Papel elite mellizo\",\"cantidad\":\"1\",\"stock\":\"6\",\"precio\":\"2.3\",\"total\":\"2.3\"},{\"id\":\"256\",\"descripcion\":\"Shampo hys\",\"cantidad\":\"1\",\"stock\":\"28\",\"precio\":\"1\",\"total\":\"1\"},{\"id\":\"195\",\"descripcion\":\"Kr\",\"cantidad\":\"15\",\"stock\":\"25\",\"precio\":\"1\",\"precioCompra\":\"0.86\",\"total\":\"15\"}]', '0', '95.9', '95.9', 'Efectivo', null, '2023-03-04 00:00:00', '2023-03-04', '0');
INSERT INTO `ventas` VALUES ('56', '10018', '3', '1', null, null, '[{\"id\":\"231\",\"descripcion\":\"Cheese tris 16gr\",\"cantidad\":\"3\",\"stock\":\"2\",\"precio\":\"0.6\",\"precioCompra\":\"0.52\",\"total\":\"1.8\"},{\"id\":\"194\",\"descripcion\":\"Galleta soda gn\",\"cantidad\":\"4\",\"stock\":\"29\",\"precio\":\"0.4\",\"precioCompra\":\"0.26\",\"total\":\"1.6\"},{\"id\":\"186\",\"descripcion\":\"Leche gloria azul 400 gr\",\"cantidad\":\"2\",\"stock\":\"3\",\"precio\":\"4\",\"precioCompra\":\"3.5\",\"total\":\"8\"},{\"id\":\"199\",\"descripcion\":\"Galleta picara\",\"cantidad\":\"1\",\"stock\":\"5\",\"precio\":\"1\",\"precioCompra\":\"0.83\",\"total\":\"1\"},{\"id\":\"229\",\"descripcion\":\"Sal\",\"cantidad\":\"1\",\"stock\":\"17\",\"precio\":\"1.5\",\"precioCompra\":\"1.2\",\"total\":\"1.5\"},{\"id\":\"178\",\"descripcion\":\"Inka cola 1L\",\"cantidad\":\"1\",\"stock\":\"94\",\"precio\":\"3.8\",\"precioCompra\":\"3.15\",\"total\":\"3.8\"},{\"id\":\"179\",\"descripcion\":\"Coca cola 1L\",\"cantidad\":\"1\",\"stock\":\"94\",\"precio\":\"3.8\",\"precioCompra\":\"3.15\",\"total\":\"3.8\"},{\"id\":\"175\",\"descripcion\":\"Chocosoda\",\"cantidad\":\"1\",\"stock\":\"44\",\"precio\":\"1.2\",\"precioCompra\":\"0.95\",\"total\":\"1.2\"},{\"id\":\"246\",\"descripcion\":\"Sibarita\",\"cantidad\":\"1\",\"stock\":\"48\",\"precio\":\"0.4\",\"precioCompra\":\"0.2\",\"total\":\"0.4\"}]', '0', '23.1', '23.1', 'Efectivo', null, '2023-03-04 00:00:00', '2023-03-04', '0');
INSERT INTO `ventas` VALUES ('57', '10019', '3', '1', null, null, '[{\"id\":\"259\",\"descripcion\":\"Jugo caja chico\",\"cantidad\":\"2\",\"stock\":\"3\",\"precio\":\"1.5\",\"total\":\"3\"},{\"id\":\"197\",\"descripcion\":\"Galleta ritz taco\",\"cantidad\":\"3\",\"stock\":\"4\",\"precio\":\"1.3\",\"total\":\"3.9\"},{\"id\":\"215\",\"descripcion\":\"Galleta vainilla field\",\"cantidad\":\"2\",\"stock\":\"7\",\"precio\":\"1\",\"total\":\"2\"},{\"id\":\"271\",\"descripcion\":\"Agua cielo 2L\",\"cantidad\":\"3\",\"stock\":\"1\",\"precio\":\"3.5\",\"total\":\"10.5\"},{\"id\":\"196\",\"descripcion\":\"Detergente marsella 450gr\",\"cantidad\":\"1\",\"stock\":\"4\",\"precio\":\"5.7\",\"total\":\"5.7\"},{\"id\":\"207\",\"descripcion\":\"Suavitel sachet\",\"cantidad\":\"1\",\"stock\":\"22\",\"precio\":\"1.2\",\"total\":\"1.2\"},{\"id\":\"226\",\"descripcion\":\"Pepsi jumbo\",\"cantidad\":\"1\",\"stock\":\"5\",\"precio\":\"2.7\",\"total\":\"2.7\"},{\"id\":\"198\",\"descripcion\":\"Agua cielo\",\"cantidad\":\"8\",\"stock\":\"152\",\"precio\":\"1\",\"total\":\"8\"},{\"id\":\"180\",\"descripcion\":\"Coca cola 2L\",\"cantidad\":\"3\",\"stock\":\"95\",\"precio\":\"6.3\",\"total\":\"18.9\"},{\"id\":\"186\",\"descripcion\":\"Leche gloria azul 400 gr\",\"cantidad\":\"1\",\"stock\":\"2\",\"precio\":\"4\",\"total\":\"4\"},{\"id\":\"233\",\"descripcion\":\"Cuate 26gr\",\"cantidad\":\"1\",\"stock\":\"10\",\"precio\":\"0.6\",\"total\":\"0.6\"},{\"id\":\"231\",\"descripcion\":\"Cheese tris 16gr\",\"cantidad\":\"5\",\"stock\":\"9\",\"precio\":\"0.6\",\"total\":\"3\"},{\"id\":\"179\",\"descripcion\":\"Coca cola 1L\",\"cantidad\":\"1\",\"stock\":\"93\",\"precio\":\"3.8\",\"total\":\"3.8\"},{\"id\":\"261\",\"descripcion\":\"Shampo sedal\",\"cantidad\":\"1\",\"stock\":\"9\",\"precio\":\"1.3\",\"total\":\"1.3\"},{\"id\":\"175\",\"descripcion\":\"Chocosoda\",\"cantidad\":\"2\",\"stock\":\"42\",\"precio\":\"1.2\",\"total\":\"2.4\"},{\"id\":\"194\",\"descripcion\":\"Galleta soda gn\",\"cantidad\":\"3\",\"stock\":\"26\",\"precio\":\"0.4\",\"total\":\"1.2\"},{\"id\":\"195\",\"descripcion\":\"Kr\",\"cantidad\":\"3\",\"stock\":\"22\",\"precio\":\"1\",\"total\":\"3\"},{\"id\":\"225\",\"descripcion\":\"Pepsi litro y medio\",\"cantidad\":\"1\",\"stock\":\"7\",\"precio\":\"3.5\",\"total\":\"3.5\"},{\"id\":\"176\",\"descripcion\":\"Hals\",\"cantidad\":\"2\",\"stock\":\"95\",\"precio\":\"0.2\",\"total\":\"0.4\"},{\"id\":\"222\",\"descripcion\":\"Chifle\",\"cantidad\":\"2\",\"stock\":\"3\",\"precio\":\"1\",\"precioCompra\":\"0.79\",\"total\":\"2\"},{\"id\":\"272\",\"descripcion\":\"Chicle trident\",\"cantidad\":\"2\",\"stock\":\"8\",\"precio\":\"1.2\",\"precioCompra\":\"0.9\",\"total\":\"2.4\"}]', '0', '83.5', '83.5', 'Efectivo', null, '2023-03-05 00:00:00', '2023-03-05', '0');
INSERT INTO `ventas` VALUES ('58', '10020', '3', '1', null, null, '[{\"id\":\"209\",\"descripcion\":\"Cereal chico\",\"cantidad\":\"1\",\"stock\":\"22\",\"precio\":\"0.8\",\"precioCompra\":\"0.62\",\"total\":\"0.8\"},{\"id\":\"195\",\"descripcion\":\"Kr\",\"cantidad\":\"22\",\"stock\":\"0\",\"precio\":\"1\",\"precioCompra\":\"0.86\",\"total\":\"22\"},{\"id\":\"201\",\"descripcion\":\"Guarana 450ml\",\"cantidad\":\"1\",\"stock\":\"12\",\"precio\":\"2\",\"precioCompra\":\"1.4\",\"total\":\"2\"},{\"id\":\"221\",\"descripcion\":\"Chicle bubaloo\",\"cantidad\":\"1\",\"stock\":\"18\",\"precio\":\"0.2\",\"precioCompra\":\"0.12\",\"total\":\"0.2\"},{\"id\":\"198\",\"descripcion\":\"Agua cielo\",\"cantidad\":\"17\",\"stock\":\"135\",\"precio\":\"1\",\"precioCompra\":\"0.75\",\"total\":\"17\"},{\"id\":\"199\",\"descripcion\":\"Galleta picara\",\"cantidad\":\"1\",\"stock\":\"4\",\"precio\":\"1\",\"precioCompra\":\"0.83\",\"total\":\"1\"},{\"id\":\"194\",\"descripcion\":\"Galleta soda gn\",\"cantidad\":\"4\",\"stock\":\"22\",\"precio\":\"0.4\",\"precioCompra\":\"0.26\",\"total\":\"1.6\"},{\"id\":\"225\",\"descripcion\":\"Pepsi litro y medio\",\"cantidad\":\"1\",\"stock\":\"6\",\"precio\":\"3.5\",\"precioCompra\":\"3.05\",\"total\":\"3.5\"},{\"id\":\"200\",\"descripcion\":\"Galleta trikis\",\"cantidad\":\"2\",\"stock\":\"8\",\"precio\":\"0.8\",\"precioCompra\":\"0.59\",\"total\":\"1.6\"},{\"id\":\"176\",\"descripcion\":\"Hals\",\"cantidad\":\"2\",\"stock\":\"93\",\"precio\":\"0.2\",\"precioCompra\":\"0.11\",\"total\":\"0.4\"},{\"id\":\"179\",\"descripcion\":\"Coca cola 1L\",\"cantidad\":\"1\",\"stock\":\"92\",\"precio\":\"3.8\",\"precioCompra\":\"3.15\",\"total\":\"3.8\"},{\"id\":\"216\",\"descripcion\":\"Galleta soda field\",\"cantidad\":\"3\",\"stock\":\"9\",\"precio\":\"0.7\",\"precioCompra\":\"0.48\",\"total\":\"2.0999999999999996\"}]', '0', '56', '56', 'Efectivo', null, '2023-03-06 00:00:00', '2023-03-06', '0');
INSERT INTO `ventas` VALUES ('59', '10021', '3', '1', null, null, '[{\"id\":\"182\",\"descripcion\":\"Inka cola 600 ml\",\"cantidad\":\"1\",\"stock\":\"96\",\"precio\":\"2.8\",\"precioCompra\":\"2.35\",\"total\":\"2.8\"},{\"id\":\"270\",\"descripcion\":\"Huevos\",\"cantidad\":\"12\",\"stock\":\"46\",\"precio\":\"0.6\",\"precioCompra\":\"0.51\",\"total\":\"7.2\"},{\"id\":\"195\",\"descripcion\":\"Kr\",\"cantidad\":\"20\",\"stock\":\"40\",\"precio\":\"1\",\"precioCompra\":\"0.86\",\"total\":\"20\"},{\"id\":\"264\",\"descripcion\":\"Mani granuts oriental\",\"cantidad\":\"1\",\"stock\":\"8\",\"precio\":\"1.2\",\"precioCompra\":\"0.82\",\"total\":\"1.2\"},{\"id\":\"198\",\"descripcion\":\"Agua cielo\",\"cantidad\":\"17\",\"stock\":\"118\",\"precio\":\"1\",\"precioCompra\":\"0.75\",\"total\":\"17\"},{\"id\":\"237\",\"descripcion\":\"Galleta rellenita\",\"cantidad\":\"1\",\"stock\":\"76\",\"precio\":\"0.5\",\"precioCompra\":\"0.37\",\"total\":\"0.5\"},{\"id\":\"268\",\"descripcion\":\"Cocoa winter\",\"cantidad\":\"2\",\"stock\":\"46\",\"precio\":\"0.5\",\"precioCompra\":\"0.28\",\"total\":\"1\"},{\"id\":\"240\",\"descripcion\":\"Detergente bolivar 450gr\",\"cantidad\":\"1\",\"stock\":\"4\",\"precio\":\"7.2\",\"precioCompra\":\"6.38\",\"total\":\"7.2\"},{\"id\":\"207\",\"descripcion\":\"Suavitel sachet\",\"cantidad\":\"1\",\"stock\":\"21\",\"precio\":\"1.2\",\"precioCompra\":\"0.93\",\"total\":\"1.2\"},{\"id\":\"200\",\"descripcion\":\"Galleta trikis\",\"cantidad\":\"2\",\"stock\":\"6\",\"precio\":\"0.8\",\"precioCompra\":\"0.59\",\"total\":\"1.6\"},{\"id\":\"244\",\"descripcion\":\"Kris\",\"cantidad\":\"1\",\"stock\":\"5\",\"precio\":\"1.2\",\"precioCompra\":\"1\",\"total\":\"1.2\"},{\"id\":\"179\",\"descripcion\":\"Coca cola 1L\",\"cantidad\":\"1\",\"stock\":\"91\",\"precio\":\"3.8\",\"precioCompra\":\"3.15\",\"total\":\"3.8\"},{\"id\":\"176\",\"descripcion\":\"Hals\",\"cantidad\":\"5\",\"stock\":\"88\",\"precio\":\"0.2\",\"precioCompra\":\"0.11\",\"total\":\"1\"},{\"id\":\"197\",\"descripcion\":\"Galleta ritz taco\",\"cantidad\":\"1\",\"stock\":\"3\",\"precio\":\"1.3\",\"precioCompra\":\"1.1\",\"total\":\"1.3\"}]', '0', '67', '67', 'Efectivo', null, '2023-03-07 00:00:00', '2023-03-07', '0');
INSERT INTO `ventas` VALUES ('60', '10022', '3', '1', null, null, '[{\"id\":\"219\",\"descripcion\":\"Shampo ballerina\",\"cantidad\":\"4\",\"stock\":\"7\",\"precio\":\"1\",\"precioCompra\":\"0.7\",\"total\":\"4\"},{\"id\":\"195\",\"descripcion\":\"Kr\",\"cantidad\":\"15\",\"stock\":\"25\",\"precio\":\"1\",\"precioCompra\":\"0.86\",\"total\":\"15\"},{\"id\":\"175\",\"descripcion\":\"Chocosoda\",\"cantidad\":\"1\",\"stock\":\"41\",\"precio\":\"1.2\",\"precioCompra\":\"0.95\",\"total\":\"1.2\"},{\"id\":\"197\",\"descripcion\":\"Galleta ritz taco\",\"cantidad\":\"1\",\"stock\":\"2\",\"precio\":\"1.3\",\"precioCompra\":\"1.1\",\"total\":\"1.3\"},{\"id\":\"179\",\"descripcion\":\"Coca cola 1L\",\"cantidad\":\"1\",\"stock\":\"90\",\"precio\":\"3.8\",\"precioCompra\":\"3.15\",\"total\":\"3.8\"},{\"id\":\"221\",\"descripcion\":\"Chicle bubaloo\",\"cantidad\":\"6\",\"stock\":\"12\",\"precio\":\"0.2\",\"precioCompra\":\"0.12\",\"total\":\"1.2\"},{\"id\":\"264\",\"descripcion\":\"Mani granuts oriental\",\"cantidad\":\"1\",\"stock\":\"7\",\"precio\":\"1.2\",\"precioCompra\":\"0.82\",\"total\":\"1.2\"},{\"id\":\"198\",\"descripcion\":\"Agua cielo\",\"cantidad\":\"7\",\"stock\":\"111\",\"precio\":\"1\",\"precioCompra\":\"0.75\",\"total\":\"7\"},{\"id\":\"222\",\"descripcion\":\"Chifle\",\"cantidad\":\"1\",\"stock\":\"2\",\"precio\":\"1\",\"precioCompra\":\"0.79\",\"total\":\"1\"},{\"id\":\"225\",\"descripcion\":\"Pepsi litro y medio\",\"cantidad\":\"2\",\"stock\":\"4\",\"precio\":\"3.5\",\"precioCompra\":\"3.05\",\"total\":\"7\"},{\"id\":\"186\",\"descripcion\":\"Leche gloria azul 400 gr\",\"cantidad\":\"2\",\"stock\":\"0\",\"precio\":\"4\",\"precioCompra\":\"3.5\",\"total\":\"8\"},{\"id\":\"270\",\"descripcion\":\"Huevos\",\"cantidad\":\"4\",\"stock\":\"42\",\"precio\":\"0.6\",\"precioCompra\":\"0.51\",\"total\":\"2.4\"},{\"id\":\"237\",\"descripcion\":\"Galleta rellenita\",\"cantidad\":\"2\",\"stock\":\"74\",\"precio\":\"0.5\",\"precioCompra\":\"0.37\",\"total\":\"1\"},{\"id\":\"202\",\"descripcion\":\"Papel elite mellizo\",\"cantidad\":\"1\",\"stock\":\"5\",\"precio\":\"2.3\",\"precioCompra\":\"2.01\",\"total\":\"2.3\"},{\"id\":\"212\",\"descripcion\":\"Chiclets adams chico\",\"cantidad\":\"4\",\"stock\":\"86\",\"precio\":\"0.2\",\"precioCompra\":\"0.14\",\"total\":\"0.8\"},{\"id\":\"182\",\"descripcion\":\"Inka cola 600 ml\",\"cantidad\":\"2\",\"stock\":\"94\",\"precio\":\"2.8\",\"precioCompra\":\"2.35\",\"total\":\"5.6\"},{\"id\":\"274\",\"descripcion\":\"Leche gloria ninios\",\"cantidad\":\"1\",\"stock\":\"5\",\"precio\":\"4.2\",\"precioCompra\":\"3.7\",\"total\":\"4.2\"},{\"id\":\"256\",\"descripcion\":\"Shampo hys\",\"cantidad\":\"1\",\"stock\":\"27\",\"precio\":\"1\",\"precioCompra\":\"0.75\",\"total\":\"1\"}]', '0', '68', '68', 'Efectivo', null, '2023-03-08 00:00:00', '2023-03-08', '0');
INSERT INTO `ventas` VALUES ('61', '10023', '3', '1', null, null, '[{\"id\":\"195\",\"descripcion\":\"Kr\",\"cantidad\":\"15\",\"stock\":\"10\",\"precio\":\"1\",\"precioCompra\":\"0.86\",\"total\":\"15\"},{\"id\":\"226\",\"descripcion\":\"Pepsi jumbo\",\"cantidad\":\"1\",\"stock\":\"4\",\"precio\":\"2.7\",\"precioCompra\":\"2.25\",\"total\":\"2.7\"},{\"id\":\"176\",\"descripcion\":\"Hals\",\"cantidad\":\"1\",\"stock\":\"87\",\"precio\":\"0.2\",\"precioCompra\":\"0.11\",\"total\":\"0.2\"},{\"id\":\"180\",\"descripcion\":\"Coca cola 2L\",\"cantidad\":\"2\",\"stock\":\"93\",\"precio\":\"6.5\",\"precioCompra\":\"5.3\",\"total\":\"13\"},{\"id\":\"198\",\"descripcion\":\"Agua cielo\",\"cantidad\":\"18\",\"stock\":\"93\",\"precio\":\"1\",\"precioCompra\":\"0.75\",\"total\":\"18\"},{\"id\":\"271\",\"descripcion\":\"Agua cielo 2L\",\"cantidad\":\"1\",\"stock\":\"0\",\"precio\":\"3.5\",\"precioCompra\":\"2.8\",\"total\":\"3.5\"},{\"id\":\"178\",\"descripcion\":\"Inka cola 1L\",\"cantidad\":\"1\",\"stock\":\"93\",\"precio\":\"3.8\",\"precioCompra\":\"3.15\",\"total\":\"3.8\"},{\"id\":\"221\",\"descripcion\":\"Chicle bubaloo\",\"cantidad\":\"3\",\"stock\":\"9\",\"precio\":\"0.2\",\"precioCompra\":\"0.12\",\"total\":\"0.6\"},{\"id\":\"231\",\"descripcion\":\"Cheese tris 16gr\",\"cantidad\":\"3\",\"stock\":\"6\",\"precio\":\"0.6\",\"precioCompra\":\"0.52\",\"total\":\"1.8\"},{\"id\":\"275\",\"descripcion\":\"Chizito chico\",\"cantidad\":\"6\",\"stock\":\"6\",\"precio\":\"0.6\",\"precioCompra\":\"0.5\",\"total\":\"3.6\"},{\"id\":\"222\",\"descripcion\":\"Chifle\",\"cantidad\":\"2\",\"stock\":\"0\",\"precio\":\"1\",\"precioCompra\":\"0.79\",\"total\":\"2\"},{\"id\":\"212\",\"descripcion\":\"Chiclets adams chico\",\"cantidad\":\"2\",\"stock\":\"84\",\"precio\":\"0.2\",\"precioCompra\":\"0.14\",\"total\":\"0.4\"},{\"id\":\"237\",\"descripcion\":\"Galleta rellenita\",\"cantidad\":\"3\",\"stock\":\"71\",\"precio\":\"0.5\",\"precioCompra\":\"0.37\",\"total\":\"1.5\"},{\"id\":\"199\",\"descripcion\":\"Galleta picara\",\"cantidad\":\"2\",\"stock\":\"2\",\"precio\":\"1\",\"precioCompra\":\"0.83\",\"total\":\"2\"}]', '0', '68.1', '68.1', 'Efectivo', null, '2023-03-09 00:00:00', '2023-03-09', '0');
INSERT INTO `ventas` VALUES ('62', '10024', '3', '1', null, null, '[{\"id\":\"237\",\"descripcion\":\"Galleta rellenita\",\"cantidad\":\"5\",\"stock\":\"66\",\"precio\":\"0.5\",\"precioCompra\":\"0.37\",\"total\":\"2.5\"},{\"id\":\"221\",\"descripcion\":\"Chicle bubaloo\",\"cantidad\":\"1\",\"stock\":\"8\",\"precio\":\"0.2\",\"precioCompra\":\"0.12\",\"total\":\"0.2\"},{\"id\":\"270\",\"descripcion\":\"Huevos\",\"cantidad\":\"5\",\"stock\":\"37\",\"precio\":\"0.6\",\"precioCompra\":\"0.51\",\"total\":\"3\"},{\"id\":\"195\",\"descripcion\":\"Kr\",\"cantidad\":\"10\",\"stock\":\"0\",\"precio\":\"1\",\"precioCompra\":\"0.86\",\"total\":\"10\"},{\"id\":\"257\",\"descripcion\":\"Chupete globo pop\",\"cantidad\":\"1\",\"stock\":\"13\",\"precio\":\"0.5\",\"precioCompra\":\"0.28\",\"total\":\"0.5\"},{\"id\":\"224\",\"descripcion\":\"Fosforo\",\"cantidad\":\"1\",\"stock\":\"42\",\"precio\":\"0.3\",\"precioCompra\":\"0.18\",\"total\":\"0.3\"},{\"id\":\"176\",\"descripcion\":\"Hals\",\"cantidad\":\"5\",\"stock\":\"82\",\"precio\":\"0.2\",\"precioCompra\":\"0.11\",\"total\":\"1\"},{\"id\":\"267\",\"descripcion\":\"Papel noble plus x4\",\"cantidad\":\"1\",\"stock\":\"5\",\"precio\":\"5\",\"precioCompra\":\"4.2\",\"total\":\"5\"},{\"id\":\"198\",\"descripcion\":\"Agua cielo\",\"cantidad\":\"5\",\"stock\":\"88\",\"precio\":\"1\",\"precioCompra\":\"0.75\",\"total\":\"5\"},{\"id\":\"199\",\"descripcion\":\"Galleta picara\",\"cantidad\":\"1\",\"stock\":\"1\",\"precio\":\"1\",\"precioCompra\":\"0.83\",\"total\":\"1\"},{\"id\":\"194\",\"descripcion\":\"Galleta soda gn\",\"cantidad\":\"2\",\"stock\":\"20\",\"precio\":\"0.4\",\"precioCompra\":\"0.26\",\"total\":\"0.8\"},{\"id\":\"182\",\"descripcion\":\"Inka cola 600 ml\",\"cantidad\":\"1\",\"stock\":\"93\",\"precio\":\"2.8\",\"precioCompra\":\"2.35\",\"total\":\"2.8\"},{\"id\":\"179\",\"descripcion\":\"Coca cola 1L\",\"cantidad\":\"1\",\"stock\":\"89\",\"precio\":\"3.8\",\"precioCompra\":\"3.15\",\"total\":\"3.7\"},{\"id\":\"202\",\"descripcion\":\"Papel elite mellizo\",\"cantidad\":\"1\",\"stock\":\"4\",\"precio\":\"2.3\",\"precioCompra\":\"2.01\",\"total\":\"2.3\"},{\"id\":\"225\",\"descripcion\":\"Pepsi litro y medio\",\"cantidad\":\"1\",\"stock\":\"3\",\"precio\":\"3.5\",\"precioCompra\":\"3.05\",\"total\":\"3.5\"},{\"id\":\"275\",\"descripcion\":\"Chizito chico\",\"cantidad\":\"3\",\"stock\":\"3\",\"precio\":\"0.6\",\"precioCompra\":\"0.5\",\"total\":\"1.8\"},{\"id\":\"231\",\"descripcion\":\"Cheese tris 16gr\",\"cantidad\":\"2\",\"stock\":\"4\",\"precio\":\"0.6\",\"precioCompra\":\"0.52\",\"total\":\"1.2\"}]', '0', '44.6', '44.6', 'Efectivo', null, '2023-03-10 00:00:00', '2023-03-10', '0');
INSERT INTO `ventas` VALUES ('63', '10025', '3', '1', null, null, '[{\"id\":\"195\",\"descripcion\":\"Kr\",\"cantidad\":\"8\",\"stock\":\"17\",\"precio\":\"1\",\"precioCompra\":\"0.86\",\"total\":\"8\"},{\"id\":\"222\",\"descripcion\":\"Chifle\",\"cantidad\":\"1\",\"stock\":\"7\",\"precio\":\"1\",\"precioCompra\":\"0.79\",\"total\":\"1\"}]', '0', '9', '9', 'Efectivo', null, '2023-03-10 00:00:00', '2023-03-10', '0');
INSERT INTO `ventas` VALUES ('64', '10026', '3', '1', null, null, '[{\"id\":\"238\",\"descripcion\":\"Detergente marsella 330gr\",\"cantidad\":\"1\",\"stock\":\"3\",\"precio\":\"4.7\",\"precioCompra\":\"4.15\",\"total\":\"4.7\"},{\"id\":\"207\",\"descripcion\":\"Suavitel sachet\",\"cantidad\":\"2\",\"stock\":\"19\",\"precio\":\"1.2\",\"precioCompra\":\"0.93\",\"total\":\"2.4\"},{\"id\":\"266\",\"descripcion\":\"Servilleta nova\",\"cantidad\":\"1\",\"stock\":\"11\",\"precio\":\"1.8\",\"precioCompra\":\"1.47\",\"total\":\"1.8\"},{\"id\":\"195\",\"descripcion\":\"Kr\",\"cantidad\":\"6\",\"stock\":\"11\",\"precio\":\"1\",\"precioCompra\":\"0.86\",\"total\":\"6\"},{\"id\":\"221\",\"descripcion\":\"Chicle bubaloo\",\"cantidad\":\"2\",\"stock\":\"6\",\"precio\":\"0.2\",\"precioCompra\":\"0.12\",\"total\":\"0.4\"},{\"id\":\"183\",\"descripcion\":\"Coca cola 600 ml\",\"cantidad\":\"1\",\"stock\":\"92\",\"precio\":\"2.8\",\"precioCompra\":\"2.35\",\"total\":\"2.8\"},{\"id\":\"200\",\"descripcion\":\"Galleta trikis\",\"cantidad\":\"2\",\"stock\":\"4\",\"precio\":\"0.8\",\"precioCompra\":\"0.59\",\"total\":\"1.6\"},{\"id\":\"231\",\"descripcion\":\"Cheese tris 16gr\",\"cantidad\":\"2\",\"stock\":\"2\",\"precio\":\"0.6\",\"precioCompra\":\"0.52\",\"total\":\"1.2\"},{\"id\":\"198\",\"descripcion\":\"Agua cielo\",\"cantidad\":\"4\",\"stock\":\"84\",\"precio\":\"1\",\"precioCompra\":\"0.75\",\"total\":\"4\"},{\"id\":\"214\",\"descripcion\":\"Papel noble mellizo\",\"cantidad\":\"0.5\",\"stock\":\"9.5\",\"precio\":\"2\",\"precioCompra\":\"1.49\",\"total\":\"1\"},{\"id\":\"237\",\"descripcion\":\"Galleta rellenita\",\"cantidad\":\"2\",\"stock\":\"64\",\"precio\":\"0.5\",\"precioCompra\":\"0.37\",\"total\":\"1\"},{\"id\":\"270\",\"descripcion\":\"Huevos\",\"cantidad\":\"2\",\"stock\":\"35\",\"precio\":\"0.6\",\"precioCompra\":\"0.51\",\"total\":\"1.2\"}]', '0', '28.1', '28.1', 'Efectivo', null, '2023-03-11 00:00:00', '2023-03-11', '0');
