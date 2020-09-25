/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : pos_db

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2020-09-25 03:38:30
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of categorias
-- ----------------------------
INSERT INTO `categorias` VALUES ('20', 'Abarrotes', '2020-08-25 02:58:19');

-- ----------------------------
-- Table structure for `clientes`
-- ----------------------------
DROP TABLE IF EXISTS `clientes`;
CREATE TABLE `clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `documento` int(11) NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `telefono` text COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `compras` int(11) NOT NULL,
  `ultima_compra` datetime NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of clientes
-- ----------------------------
INSERT INTO `clientes` VALUES ('3', 'Publico General', '11111111', 'juan@hotmail.com', '(300) 341-2345', 'Calle 23 # 45 - 56', '1980-11-02', '968', '2020-08-25 03:44:46', '2020-08-25 03:44:46');

-- ----------------------------
-- Table structure for `compra`
-- ----------------------------
DROP TABLE IF EXISTS `compra`;
CREATE TABLE `compra` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `proveedor_id` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `hora` time NOT NULL,
  `total` double NOT NULL,
  `metodo_pago` enum('contado','credito') NOT NULL DEFAULT 'contado',
  `delete` enum('0','1') NOT NULL DEFAULT '0',
  `usuario_id` int(11) NOT NULL,
  `codigo` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `proveedor_id` (`proveedor_id`) USING BTREE,
  CONSTRAINT `compra_ibfk_1` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedor` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of compra
-- ----------------------------
INSERT INTO `compra` VALUES ('1', '1', '2020-09-17 00:00:00', '02:14:48', '20', '', '0', '1', '10002');
INSERT INTO `compra` VALUES ('2', '1', '2020-09-17 00:00:00', '02:14:48', '20', '', '0', '1', '10002');
INSERT INTO `compra` VALUES ('3', '1', '2020-09-17 00:00:00', '02:14:48', '20', '', '0', '1', '10002');
INSERT INTO `compra` VALUES ('4', '1', '2020-09-17 00:00:00', '02:14:48', '20', '', '0', '1', '10002');

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
  `delete` enum('1','0') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `producto_id` (`producto_id`),
  KEY `compra_id` (`compra_id`),
  CONSTRAINT `compra_detalle_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `compra_detalle_ibfk_2` FOREIGN KEY (`compra_id`) REFERENCES `compra` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of compra_detalle
-- ----------------------------

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
  `borrado` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of gastos
-- ----------------------------

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
  `precio_compra` float NOT NULL,
  `margen` double NOT NULL,
  `precio_venta` float NOT NULL,
  `ventas` int(11) NOT NULL,
  `proveedor_detalle` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_categoria` (`id_categoria`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=174 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of productos
-- ----------------------------
INSERT INTO `productos` VALUES ('172', '20', '2001', 'Test', 'vistas/img/productos/default/anonymous.png', '24', '10', '0.5', '15', '1', '[{\"id\":\"1\",\"razon_social\":\"Alvarez Bolt - Nestle\"}]', '2020-09-05 04:20:17');
INSERT INTO `productos` VALUES ('173', '20', '2002', 'Test 2', 'vistas/img/productos/default/anonymous.png', '10', '10', '0.5', '15', '0', '[{\"id\":\"2\",\"razon_social\":\"Despensa Peruana\"}]', '2020-09-05 04:21:06');

-- ----------------------------
-- Table structure for `producto_proveedor`
-- ----------------------------
DROP TABLE IF EXISTS `producto_proveedor`;
CREATE TABLE `producto_proveedor` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `proveedor_id` int(11) NOT NULL,
  `old_precio` double NOT NULL,
  `ultimo_precio` double NOT NULL,
  `ultima_compra` date NOT NULL,
  `compras` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `producto_id` (`producto_id`),
  KEY `proveedor_id` (`proveedor_id`),
  CONSTRAINT `producto_proveedor_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `producto_proveedor_ibfk_2` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedor` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `representante_detalle` text NOT NULL,
  `estado` enum('1','0') NOT NULL DEFAULT '1',
  `deleted` enum('1','0') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of proveedor
-- ----------------------------
INSERT INTO `proveedor` VALUES ('1', 'Alvarez Bolt - Nestle', '[{\"id\":\"1\",\"nombre\":\"Clever\",\"telefono\":\"914680398\"}]', '1', '0');
INSERT INTO `proveedor` VALUES ('2', 'Despensa Peruana', '[{\"id\":\"2\",\"nombre\":\"Dispensa Gloria\",\"telefono\":\"992283090\"},{\"id\":\"3\",\"nombre\":\"Iveth\",\"telefono\":\"925904597\"}]', '1', '0');

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of usuarios
-- ----------------------------
INSERT INTO `usuarios` VALUES ('1', 'Administrador', 'admin', '$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG', 'Administrador', 'vistas/img/usuarios/admin/157.jpg', '1', '2020-09-24 18:05:56', '2020-09-24 18:05:56');

-- ----------------------------
-- Table structure for `ventas`
-- ----------------------------
DROP TABLE IF EXISTS `ventas`;
CREATE TABLE `ventas` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `codigo` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_vendedor` int(11) NOT NULL,
  `productos` text COLLATE utf8_spanish_ci NOT NULL,
  `impuesto` float NOT NULL,
  `neto` float NOT NULL,
  `total` float NOT NULL,
  `metodo_pago` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of ventas
-- ----------------------------
INSERT INTO `ventas` VALUES ('30', '10001', '3', '1', '[{\"id\":\"172\",\"descripcion\":\"Test\",\"cantidad\":\"1\",\"stock\":\"24\",\"precio\":\"15\",\"precioCompra\":\"10\",\"total\":\"15\"}]', '0', '15', '15', 'Efectivo', '2020-08-25 03:44:46');
