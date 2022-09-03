<?php

require_once "../controladores/ventas.controlador.php";
require_once "../controladores/pago_deuda.controlador.php";
require_once "../controladores/detalle_pago_deuda.controlador.php";
require_once "../modelos/ventas.modelo.php";
require_once "../modelos/pago_deuda.modelo.php";
require_once "../modelos/detalle_pago_deuda.modelo.php";

class PagoDeudaAjax {

	public $params;

	public function addPagoAjax(){

		$params = $this->params;

		unset($params['accion']);

		$response = PagoDeudaController::guardarPago($params);

		echo json_encode($response);
	}

	public function getPagosListAjax(){

		$params = $this->params;
		$cliente_id = $params['term'];

		$response = PagoDeudaController::getPagosList($cliente_id);

		$tbody= "";

		if($response['response']){
			$i = 0;
			foreach ($response['data'] as $item) {
				$i++;

				$tbody .= '
					<tr>
						<td>'.$i.'</td>
						<td>'.$item["importe"].'</td>
						<td>'.$item["fecha_pago"].'</td>
					</tr>
				';
			}
		}

		$response['tbody'] = $tbody;
		$response['deuda_total'] = PagoDeudaController::getDeudaTotal($cliente_id);

		echo json_encode($response);

	}

}

if(isset($_REQUEST["accion"])){

	$a = new PagoDeudaAjax();
	$a -> params = $_REQUEST;

	switch ($_REQUEST['accion']) {
		case 'add':
			$a -> addPagoAjax();
			break;
		case 'pagosList':
			$a -> getPagosListAjax();
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