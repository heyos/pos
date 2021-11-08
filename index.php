<?php

ini_set("display_errors",1);
ini_set("log_errors", 1); // con esta línea estamos diciendo que queremos crear un nuevo archivo de errores
ini_set("error_log", __DIR__.'/php_error_log');

require_once "controladores/plantilla.controlador.php";
require_once "controladores/usuarios.controlador.php";
require_once "controladores/categorias.controlador.php";
require_once "controladores/productos.controlador.php";
require_once "controladores/clientes.controlador.php";
require_once "controladores/ventas.controlador.php";
require_once "controladores/proveedor.controlador.php";
require_once "controladores/compras.controlador.php";
require_once "controladores/reporte_capital.controlador.php";

require_once "modelos/usuarios.modelo.php";
require_once "modelos/categorias.modelo.php";
require_once "modelos/productos.modelo.php";
require_once "modelos/clientes.modelo.php";
require_once "modelos/ventas.modelo.php";
require_once "modelos/proveedor.modelo.php";
require_once "modelos/compras.modelo.php";
require_once "modelos/compra_detalle.modelo.php";
require_once "modelos/reporte_capital.modelo.php";

ControladorPlantilla::baseUrl();

$plantilla = new ControladorPlantilla();
$plantilla -> ctrPlantilla();



// $fechaInicial = date('Y-m-').'01';
// $fechaFinal = date('Y-m-d');

// $gastado = ReporteCapitalController::showReporteCapital();

// print_r($gastado);

// echo $fechaInicial.' - '.$fechaFinal.'<br>';
// echo "".$gastado;