<?php

require_once "../controladores/productos.controlador.php";
require_once "../controladores/ventas.controlador.php";
require_once "../modelos/clientes.modelo.php";
require_once "../modelos/productos.modelo.php";
require_once "../modelos/ventas.modelo.php";

class VentasAjax {
	
	public $request;
	
	public function newVenta(){

		$response = ControladorVentas::apiCreateVenta() ;

		echo json_encode($response);

	}

	

}

if($_POST){

	$a = new VentasAjax();
	$a -> request = $_POST;

	if($_POST['accion']){

		switch ($_POST['accion']) {

			case 'add':
				$a -> newVenta();
				break;

			
			default:
				echo json_encode(array(
					'status' => false,
					'message' => "Accion no disponible"
				));
				break;
		}

	}else{
		echo json_encode(array(
			'status' => false,
			'message' => "Accion no disponible"
		));
	}

}else{
	echo json_encode(array(
		'status' => false,
		'message' => "Error en la peticion"
	));
}