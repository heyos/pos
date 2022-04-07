<?php

require_once "controller.php";

class ControladorClientes extends Controller{

	/*=============================================
	CREAR CLIENTES
	=============================================*/

	static public function crearCliente($params){

		$response = false;
		$message = "No se puede ejecutar la aplicacion";

		$columsValid = array(
			'data' => ['nombre','documento','email','telefono'],
			'dataOut' => [
				'nombre' => 'Nombre'
			]
		);

		$table = 'clientes';

		$respuesta = self::paramsValid($table,$columsValid,$params);

		if($respuesta['response']){

			$res = ModeloClientes::create($table,$params);

			if($res > 0){
				$response = true;
				$message = "Se actualizo el registro exitosamente.";
			}else{
				$message = "No se guardo el registro";
			}

		}else{
			$message = $respuesta['message'];
		}

		$salidaJson = array(
			'response' => $response,
			'message' => $message
		);

		return $salidaJson;

	}


	/*=============================================
	MOSTRAR CLIENTES
	=============================================*/

	static public function ctrMostrarClientes($item, $valor){

		$tabla = "clientes";

		$respuesta = ModeloClientes::mdlMostrarClientes($tabla, $item, $valor);

		return $respuesta;

	}

	
	static public function updateCliente($params){

		$response = false;
		$message = "No se puede ejecutar la aplicacion";
		$data = '';

		$columsValid = array(
			'data' => ['nombre','documento','email','telefono'],
			'diff' => 'id',
			'dataOut' => [
				'nombre' => 'Nombre'
			]
		);

		$table = 'clientes';

		$respuesta = self::paramsValid($table,$columsValid,$params);

		if($respuesta['response']){

			$update = ModeloClientes::update($table,$params);

			if($update > 0){
				$response = true;
				$message = "Se actualizo el registro exitosamente.";
			}else{
				$message = "No se actualizo el registro";
			}

		}else{
			$message = $respuesta['message'];
		}

		
		$salidaJson = array(
			'response' => $response,
			'message' => $message
		);

		return $salidaJson;
	}

	/*=============================================
	ELIMINAR CLIENTE
	=============================================*/

	static public function ctrEliminarCliente(){

		if(isset($_GET["idCliente"])){

			$tabla ="clientes";
			$datos = $_GET["idCliente"];

			$respuesta = ModeloClientes::mdlEliminarCliente($tabla, $datos);

			if($respuesta == "ok"){

				echo'<script>

				swal({
					  type: "success",
					  title: "El cliente ha sido borrado correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar",
					  closeOnConfirm: false
					  }).then(function(result){
								if (result.value) {

								window.location = "clientes";

								}
							})

				</script>';

			}		

		}

	}

}

