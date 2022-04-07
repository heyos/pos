<?php

require_once "../controladores/clientes.controlador.php";
require_once "../modelos/clientes.modelo.php";

class AjaxClientes{

	/*=============================================
	EDITAR CLIENTE
	=============================================*/	

	public $idCliente;
	public $params;

	public function addCliente(){

		$params = $this->params;
		unset($params['accion']);
		unset($params['id']);

		$respuesta = ControladorClientes::crearCliente($params);

		echo json_encode($respuesta);
	}

	public function getCliente(){
		$response = false;
		$message = "No se puede ejecutar la aplicacion";
		$data = [];

		$item = "id";
		$params = $this->params;
		$valor = $params['id'];

		$respuesta = ControladorClientes::ctrMostrarClientes($item, $valor);

		if(!empty($respuesta)){
			$response = true;
			$respuesta['accion'] = 'editar';
			$data = $respuesta;
			$message = "success";
		}else{
			$message = "Error en el proceso";
		}

		$salida = array(
			'response' => $response,
			'data' => $data
		);

		echo json_encode($salida);

	}

	public function updateCliente(){
		$params = $this->params;

		unset($params['accion']);

		$respuesta = ControladorClientes::updateCliente($params);

		echo json_encode($respuesta);
	}

}

/*=============================================
EDITAR CLIENTE
=============================================*/	

if(isset($_POST["accion"])){

	$a = new AjaxClientes();
	$a -> params = $_POST;

	switch ($_POST['accion']) {
		case 'add':
			$a -> addCliente();
			break;
		case 'data':
			$a -> getCliente();
			break;
		case 'editar':
			$a -> updateCliente();
			break;
		default:
			echo json_encode(array(
				'response' => false,
				'data' => [],
				'message' => 'accion no disponible'
			));
			break;
	}

}else{
	echo json_encode(array(
		'response' => false,
		'data' => [],
		'message' => 'error en peticion'
	));
}