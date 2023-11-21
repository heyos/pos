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
require_once "controladores/gastos.controller.php";

require_once "modelos/usuarios.modelo.php";
require_once "modelos/categorias.modelo.php";
require_once "modelos/productos.modelo.php";
require_once "modelos/clientes.modelo.php";
require_once "modelos/ventas.modelo.php";
require_once "modelos/proveedor.modelo.php";
require_once "modelos/compras.modelo.php";
require_once "modelos/compra_detalle.modelo.php";
require_once "modelos/reporte_capital.modelo.php";
require_once "modelos/gastos.model.php";

ControladorPlantilla::baseUrl();

$plantilla = new ControladorPlantilla();
$plantilla -> ctrPlantilla();

//echo date('Y-m-d');

// $fechaInicial = date('Y-m-').'01';
// $fechaFinal = date('Y-m-d');

// $gastado = ReporteCapitalController::showReporteCapital();
// $acumulado = ControladorVentas::capitalAcumulado('','',true);
// print_r($acumulado);
// echo '<br>';

// $reporte = ReporteCapitalController::showReporteCapital();

// echo '<pre>';
// print_r($reporte);

// echo '</pre>';
// echo $fechaInicial.' - '.$fechaFinal.'<br>';
// echo "".$gastado;

exit();

$data = array('heyller'=>11,'juan'=>12,'miguel'=>15);
arsort($data);
var_export($data);

// echo $data[0];



exit();
$total = 41;

$arr = array(
	array(
		'total' => 20.5,
		'pagado' => 0,
		'pago' => 0,
		'saldo' => 20.5
	),
	array(
		'total' => 20.5,
		'pagado' => 0,
		'pago' => 0,
		'saldo' => 20.5
	)
);

echo '<pre>';
print_r($arr);
echo '</pre>';

$pago = 10;
$totalDeuda = 0;
$arr2 = [];

foreach ($arr as $deuda) {
	
	if($pago >= $deuda['total']){

		$deuda['pago'] = $deuda['total'];
		$deuda['saldo'] = 0;
		$deuda['pagado'] = 1;

		$pago = $pago - $deuda['total'];

	}else{

		$deuda['pago'] = $pago;
		$deuda['saldo'] = $deuda['total'] - $pago;
		
		$pago = 0;
	}

	$arr2[] = $deuda;
}

echo '<pre>';
print_r($arr2);
echo '</pre>';