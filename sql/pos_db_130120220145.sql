/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : pos_db

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2022-01-13 01:47:00
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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of categorias
-- ----------------------------
INSERT INTO `categorias` VALUES ('20', 'Abarrotes', '2020-08-25 02:58:19', '0');
INSERT INTO `categorias` VALUES ('21', 'Bebidas', '2021-11-03 22:26:52', '0');
INSERT INTO `categorias` VALUES ('22', 'Lacteos', '2021-11-03 22:27:15', '0');
INSERT INTO `categorias` VALUES ('23', 'Licores', '2021-11-03 22:29:18', '0');
INSERT INTO `categorias` VALUES ('24', 'Golosinas', '2021-11-03 22:29:41', '0');
INSERT INTO `categorias` VALUES ('25', 'Higiene personal', '2021-11-03 22:32:30', '0');

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
  `deleted` enum('1','0') COLLATE utf8_spanish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of clientes
-- ----------------------------
INSERT INTO `clientes` VALUES ('3', 'Publico General', '11111111', 'juan@hotmail.com', '(300) 341-2345', 'Calle 23 # 45 - 56', '1980-11-02', '1012', '2022-01-03 05:57:24', '2022-01-03 05:57:24', '0');

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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of compra
-- ----------------------------
INSERT INTO `compra` VALUES ('1', '10002', '1', '2020-09-17', '02:14:48', null, '20', '', '1', null, '1');
INSERT INTO `compra` VALUES ('2', '10002', '1', '2020-09-17', '02:14:48', null, '20', '', '1', null, '1');
INSERT INTO `compra` VALUES ('3', '10002', '1', '2020-09-17', '02:14:48', null, '20', '', '1', null, '1');
INSERT INTO `compra` VALUES ('4', '10002', '1', '2020-09-17', '02:14:48', null, '20', '', '1', null, '1');
INSERT INTO `compra` VALUES ('5', '10002', '1', '2020-10-13', '19:33:19', null, '200', 'contado', '1', null, '1');
INSERT INTO `compra` VALUES ('6', '10002', '1', '2020-10-13', '20:06:00', null, '200', 'contado', '1', null, '1');
INSERT INTO `compra` VALUES ('7', '10002', '1', '2020-10-13', '20:06:15', null, '200', 'contado', '1', null, '1');
INSERT INTO `compra` VALUES ('8', '10002', '1', '2020-10-13', '20:07:12', null, '200', 'contado', '1', null, '1');
INSERT INTO `compra` VALUES ('9', '10002', '1', '2020-10-13', '20:08:46', null, '300', 'contado', '1', null, '1');
INSERT INTO `compra` VALUES ('10', '10002', '1', '2020-10-13', '20:08:46', null, '300', 'contado', '1', null, '1');
INSERT INTO `compra` VALUES ('11', '10002', '1', '2020-10-13', '20:08:46', null, '300', 'contado', '1', null, '1');
INSERT INTO `compra` VALUES ('12', '10002', '1', '2020-10-13', '20:08:46', null, '300', 'contado', '1', null, '1');
INSERT INTO `compra` VALUES ('13', '10002', '1', '2020-10-30', '22:10:04', null, '250', 'contado', '1', '1', '1');
INSERT INTO `compra` VALUES ('14', '10002', '1', '2020-10-13', '20:08:46', null, '300', 'contado', '1', null, '1');
INSERT INTO `compra` VALUES ('15', '10002', '1', '2020-10-13', '20:08:46', null, '300', 'contado', '1', null, '1');
INSERT INTO `compra` VALUES ('16', '10002', '1', '2020-10-13', '20:08:46', null, '300', 'contado', '1', null, '1');
INSERT INTO `compra` VALUES ('17', '10002', '1', '2020-10-13', '21:05:12', null, '200', 'contado', '1', null, '1');
INSERT INTO `compra` VALUES ('18', '10003', '1', '2020-11-28', '22:49:48', null, '625', 'contado', '1', null, '1');
INSERT INTO `compra` VALUES ('19', '10004', '1', '2020-12-13', '01:51:26', null, '25', 'contado', '1', '1', '0');
INSERT INTO `compra` VALUES ('20', '10005', '14', '2021-09-24', '18:01:43', '2021-09-24', '25', 'contado', '1', null, '0');
INSERT INTO `compra` VALUES ('21', '10006', '14', '2021-10-09', '04:07:21', '2021-10-09', '50', 'contado', '1', null, '0');
INSERT INTO `compra` VALUES ('22', '10007', '1', '2021-10-09', '04:21:06', '2021-10-09', '200', 'contado', '1', null, '0');

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
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of compra_detalle
-- ----------------------------
INSERT INTO `compra_detalle` VALUES ('1', '6', '172', '10', '10', '100', '10', '1');
INSERT INTO `compra_detalle` VALUES ('2', '6', '173', '10', '10', '100', '10', '1');
INSERT INTO `compra_detalle` VALUES ('3', '7', '172', '10', '10', '100', '10', '1');
INSERT INTO `compra_detalle` VALUES ('4', '7', '173', '10', '10', '100', '10', '1');
INSERT INTO `compra_detalle` VALUES ('5', '8', '172', '10', '10', '100', '10', '1');
INSERT INTO `compra_detalle` VALUES ('6', '8', '173', '10', '10', '100', '10', '1');
INSERT INTO `compra_detalle` VALUES ('7', '9', '172', '10', '10', '100', '10', '1');
INSERT INTO `compra_detalle` VALUES ('8', '9', '173', '10', '20', '200', '10', '1');
INSERT INTO `compra_detalle` VALUES ('9', '10', '172', '10', '10', '100', '10', '1');
INSERT INTO `compra_detalle` VALUES ('10', '10', '173', '10', '20', '200', '10', '1');
INSERT INTO `compra_detalle` VALUES ('11', '11', '172', '10', '10', '100', '10', '1');
INSERT INTO `compra_detalle` VALUES ('12', '11', '173', '10', '20', '200', '10', '1');
INSERT INTO `compra_detalle` VALUES ('13', '13', '172', '10', '10', '100', '10', '1');
INSERT INTO `compra_detalle` VALUES ('14', '12', '173', '10', '20', '200', '10', '1');
INSERT INTO `compra_detalle` VALUES ('15', '13', '172', '10', '10', '100', '10', '1');
INSERT INTO `compra_detalle` VALUES ('16', '13', '173', '10', '20', '200', '10', '1');
INSERT INTO `compra_detalle` VALUES ('17', '14', '172', '10', '10', '100', '10', '1');
INSERT INTO `compra_detalle` VALUES ('18', '14', '173', '10', '20', '200', '10', '1');
INSERT INTO `compra_detalle` VALUES ('19', '15', '172', '10', '10', '100', '10', '1');
INSERT INTO `compra_detalle` VALUES ('20', '15', '173', '10', '20', '200', '10', '1');
INSERT INTO `compra_detalle` VALUES ('21', '16', '172', '10', '10', '100', '10', '1');
INSERT INTO `compra_detalle` VALUES ('22', '16', '173', '10', '20', '200', '10', '1');
INSERT INTO `compra_detalle` VALUES ('23', '17', '172', '10', '10', '100', '10', '1');
INSERT INTO `compra_detalle` VALUES ('24', '17', '173', '10', '10', '100', '10', '1');
INSERT INTO `compra_detalle` VALUES ('25', '13', '173', '10', '1', '10', '10', '1');
INSERT INTO `compra_detalle` VALUES ('26', '13', '173', '10', '10', '100', '10', '1');
INSERT INTO `compra_detalle` VALUES ('27', '13', '173', '10', '10', '100', '10', '1');
INSERT INTO `compra_detalle` VALUES ('28', '13', '173', '10', '10', '100', '10', '1');
INSERT INTO `compra_detalle` VALUES ('29', '13', '173', '10', '10', '100', '10', '1');
INSERT INTO `compra_detalle` VALUES ('30', '13', '173', '10', '1', '10', '10', '1');
INSERT INTO `compra_detalle` VALUES ('31', '13', '172', '10', '10', '100', '10', '1');
INSERT INTO `compra_detalle` VALUES ('32', '13', '174', '5', '10', '50', '5', '1');
INSERT INTO `compra_detalle` VALUES ('33', '13', '173', '10', '10', '100', '10', '1');
INSERT INTO `compra_detalle` VALUES ('34', '18', '172', '10', '25', '250', '10', '1');
INSERT INTO `compra_detalle` VALUES ('35', '18', '173', '10', '25', '250', '10', '1');
INSERT INTO `compra_detalle` VALUES ('36', '18', '174', '5', '25', '125', '5', '1');
INSERT INTO `compra_detalle` VALUES ('37', '19', '174', '5', '5', '25', '5', '0');
INSERT INTO `compra_detalle` VALUES ('38', '20', '174', '5', '5', '25', '5', '0');
INSERT INTO `compra_detalle` VALUES ('39', '21', '174', '5', '10', '50', '5', '0');
INSERT INTO `compra_detalle` VALUES ('40', '22', '172', '10', '10', '100', '10', '0');
INSERT INTO `compra_detalle` VALUES ('41', '22', '173', '10', '10', '100', '10', '0');

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
) ENGINE=InnoDB AUTO_INCREMENT=175 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of productos
-- ----------------------------
INSERT INTO `productos` VALUES ('172', '20', '2001', 'Test', 'vistas/img/productos/default/anonymous.png', '1', '10', '0.5', '15', null, null, null, '10', '[{\"id\":\"1\",\"razon_social\":\"Alvarez Bolt - Nestle\"}]', '2021-10-11 22:24:09', '0');
INSERT INTO `productos` VALUES ('173', '20', '2002', 'Test 2', 'vistas/img/productos/default/anonymous.png', '0', '10', '0.5', '15', null, null, null, '10', '[{\"id\":\"2\",\"razon_social\":\"Despensa Peruana\"}]', '2022-01-03 05:57:24', '0');
INSERT INTO `productos` VALUES ('174', '20', '2003', 'Aceite belili 1L', 'vistas/img/productos/default/anonymous.png', '1', '5', '0.1', '5.5', null, null, null, '25', '', '2021-10-11 02:04:14', '0');

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of producto_proveedor
-- ----------------------------
INSERT INTO `producto_proveedor` VALUES ('1', '172', '1', '10', '10', '2021-10-10', '10', '0');
INSERT INTO `producto_proveedor` VALUES ('2', '173', '1', '10', '10', '2021-10-10', '10', '0');
INSERT INTO `producto_proveedor` VALUES ('3', '174', '1', '5', '5', '2020-12-13', '5', '0');
INSERT INTO `producto_proveedor` VALUES ('4', '174', '14', '5', '5', '2021-10-10', '10', '0');

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of reporte_capital
-- ----------------------------
INSERT INTO `reporte_capital` VALUES ('1', '100', '{\"Abarrotes\":100}', '2021-08', '2021-08-01', '2021-08-31', '0', '0');
INSERT INTO `reporte_capital` VALUES ('5', '420', '{\"Abarrotes\":120,\"Bebidas\":0,\"Lacteos\":0,\"Licores\":200,\"Golosinas\":0,\"Higiene personal\":100}', '', '2021-09-01', '2021-12-12', '0', '0');
INSERT INTO `reporte_capital` VALUES ('6', '0', '{\"Abarrotes\":170,\"Bebidas\":50,\"Lacteos\":50,\"Licores\":250,\"Golosinas\":50,\"Higiene personal\":150}', '', '2021-12-13', '2021-12-26', '0', '0');
INSERT INTO `reporte_capital` VALUES ('7', '0', '{\"Abarrotes\":170,\"Bebidas\":50,\"Lacteos\":50,\"Licores\":250,\"Golosinas\":50,\"Higiene personal\":150}', '', '2021-12-27', '2021-12-26', '0', '0');
INSERT INTO `reporte_capital` VALUES ('8', '0', '{\"Abarrotes\":220,\"Bebidas\":100,\"Lacteos\":100,\"Licores\":300,\"Golosinas\":150,\"Higiene personal\":200}', '', '2021-12-27', '2021-12-26', '0', '0');
INSERT INTO `reporte_capital` VALUES ('9', '1070', '{\"Abarrotes\":220,\"Bebidas\":100,\"Lacteos\":100,\"Licores\":300,\"Golosinas\":150,\"Higiene personal\":200}', '', '2021-12-27', '2021-12-26', '0', '0');
INSERT INTO `reporte_capital` VALUES ('10', '1070', '{\"Abarrotes\":220,\"Bebidas\":100,\"Lacteos\":100,\"Licores\":300,\"Golosinas\":150,\"Higiene personal\":200}', '', '2021-12-27', '2021-12-27', '1', '0');

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
INSERT INTO `usuarios` VALUES ('1', 'Administrador', 'admin', '$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG', 'Administrador', 'vistas/img/usuarios/admin/157.jpg', '1', '2022-01-13 01:25:45', '2022-01-13 01:25:45', '1');

-- ----------------------------
-- Table structure for `ventas`
-- ----------------------------
DROP TABLE IF EXISTS `ventas`;
CREATE TABLE `ventas` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `codigo` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_vendedor` bigint(20) NOT NULL,
  `usuario_u_id` bigint(20) DEFAULT NULL COMMENT 'usuario que modifica el registro',
  `productos` text COLLATE utf8_spanish_ci NOT NULL,
  `impuesto` float NOT NULL,
  `neto` float NOT NULL,
  `total` float NOT NULL,
  `metodo_pago` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` datetime NOT NULL,
  `fecha_pago` date DEFAULT NULL,
  `deleted` enum('1','0') COLLATE utf8_spanish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of ventas
-- ----------------------------
INSERT INTO `ventas` VALUES ('30', '10001', '3', '1', null, '[{\"id\":\"172\",\"descripcion\":\"Test\",\"cantidad\":\"1\",\"stock\":\"24\",\"precio\":\"15\",\"precioCompra\":\"10\",\"total\":\"15\"}]', '0', '15', '15', 'Efectivo', '2020-08-25 03:44:46', '2020-08-25', '0');
INSERT INTO `ventas` VALUES ('31', '10002', '3', '1', null, '[{\"id\":\"174\",\"descripcion\":\"Aceite belili 1L\",\"cantidad\":\"25\",\"stock\":\"1\",\"precio\":\"5.5\",\"precioCompra\":\"5\",\"total\":\"137.5\"}]', '0', '137.5', '137.5', 'Efectivo', '2021-10-10 00:00:00', '2021-10-10', '0');
INSERT INTO `ventas` VALUES ('32', '10003', '3', '1', null, '[{\"id\":\"173\",\"descripcion\":\"Test 2\",\"cantidad\":\"3\",\"stock\":\"7\",\"precio\":\"15\",\"precioCompra\":\"10\",\"total\":\"45\"},{\"id\":\"172\",\"descripcion\":\"Test\",\"cantidad\":\"5\",\"stock\":\"5\",\"precio\":\"15\",\"precioCompra\":\"10\",\"total\":\"75\"}]', '0', '120', '120', 'Efectivo', '2021-10-11 02:16:44', '2021-10-11', '0');
INSERT INTO `ventas` VALUES ('33', '10004', '3', '1', null, '[{\"id\":\"173\",\"descripcion\":\"Test 2\",\"cantidad\":\"5\",\"stock\":\"2\",\"precio\":\"15\",\"precioCompra\":\"10\",\"total\":\"75\"},{\"id\":\"172\",\"descripcion\":\"Test\",\"cantidad\":\"4\",\"stock\":\"1\",\"precio\":\"15\",\"precioCompra\":\"10\",\"total\":\"60\"}]', '0', '135', '135', 'Efectivo', '2021-10-11 22:24:09', '2021-10-11', '0');
INSERT INTO `ventas` VALUES ('34', '10005', '3', '1', null, '[{\"id\":\"173\",\"descripcion\":\"Test 2\",\"cantidad\":\"2\",\"stock\":\"0\",\"precio\":\"15\",\"precioCompra\":\"10\",\"total\":\"30\"}]', '0', '30', '30', 'Efectivo', '2022-01-01 05:57:24', '2022-01-01', '0');
