<?php

require_once "../controladores/gastos.controller.php";
require_once "../modelos/gastos.model.php";

class RegistrarGastoAjax {
	
	public $request;
	
	public function addGasto(){

		$params = $this->request;

		unset($params['action']);
		unset($params['id']);

		$params['tabla'] = 'gastos';

		$response = GastosController::newItem($params);

		echo json_encode(
			array(
				'status' => $response['respuesta'],
				'message' => $response['message']
			)
		);

	}

	public function editGasto(){

		$params = $this->request;

		unset($params['action']);

		$params['tabla'] = 'gastos';
		
		$response = GastosController::updateItem($params);

		echo json_encode(
			array(
				'status' => $response['respuesta'],
				'message' => $response['mensaje']
			)
		);

	}

	public function deleteGasto(){

		$params = $this->request;

		$params['table'] = 'gastos';

		$response = GastosController::deleteItem($params);

		echo json_encode(
			array(
				'status' => $response['respuesta'],
				'message' => $response['message']
			)
		);

	}

	public function getDetalle(){

		$params = $this->request;

		unset($params['action']);

		$params['tabla'] = 'gastos';

		$response = GastosController::itemDetail($params);

		echo json_encode(
			array(
				'status' => $response['respuesta'],
				'message' => $response['mensaje'],
				'data' => $response['data']
			)
		);

	}

}

if($_POST){

	$a = new RegistrarGastoAjax();
	$a -> request = $_POST;

	if($_POST['action']){

		switch ($_POST['action']) {

			case 'add':
				$a -> addGasto();
				break;

			case 'edit':
				$a -> editGasto();
				break;

			case 'del':
				$a -> deleteGasto();
				break;
			
			case "data":
				$a -> getDetalle();
				break;
			default:
				# code...
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