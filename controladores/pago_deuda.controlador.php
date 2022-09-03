<?php

require_once "controller.php";

class PagoDeudaController extends Controller {

	public static function getPagosList($cliente_id){

		$response = false;
		$message = "";

		$args = array(
          'columns' => 'id,importe,DATE_FORMAT(fecha_pago,"%d/%m/%Y") as fecha_pago',
          'table'=>'pago_deuda',
          'where' => array(
            ['cliente_id',$cliente_id],
          ),
          'start' => '0',
          'length' => '5',
          'order' => 'id',
          'dir' => 'DESC'
        );

        $data = PagoDeudaModel::all($args);

        if(count($data) > 0){
        	$response = true;

        	$arr = [];
        	foreach ($data as $item) {
        		$item['importe'] = number_format($item['importe'],2,'.','');
        		$arr[] = $item;
        	}

        	$data = $arr;
        }

        $salida = array(
        	'message' => $message,
        	'response' => $response,
        	'data' => $data
        );

        return $salida;
	}

	public static function allVentasPendientes($cliente_id){

		$response = false;
		$message = "";

		$argsVentas = array(
          'columns' => '*',
          'table'=>'ventas',
          'where' => array(
            ['id_cliente',$cliente_id],
            ['fecha_pago is NULL']
          )
        );

        $arrVentas = ModeloVentas::all($argsVentas);

        if(count($arrVentas) > 0){
        	$response = true;
        }

        $salida = array(
        	'message' => $message,
        	'response' => $response,
        	'data' => $arrVentas
        );

        return $salida;
	}

	public static function getDeudaTotal($cliente_id){
		
		//obtener ventas con deuda
		$ventas = self::allVentasPendientes($cliente_id);

        $deuda = 0;

        if($ventas['response']){
          
          foreach ($ventas['data'] as $venta) {
            $args = array(
              'tabla'=>'detalle_pago_deuda',
              'where' => array(
                ['venta_id',$venta['id']],
                ['ultimo','1']
              )
            );

            $detalle = DetallePagoDeudaController::itemDetail($args);

            if($detalle['respuesta']){
              $detallePago = $detalle['contenido'];
              $deuda += $detallePago['saldo'];

            }else{
              $deuda += $venta['total'];
            }
          }

        }

        return $deuda;
	}

	public static function guardarPago($params){

		session_start();

		$user = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'TEST';

		$response = false;
		$message = "";

		$tabla = "pago_deuda";
		$params['tabla'] = $tabla;
		$params['fecha_pago'] = date('Y-m-d');
		$cliente_id = $params['cliente_id'];

		$importe = is_numeric($params['importe']) ? $params['importe'] : 0;

		if($importe > 0){
			
			$pago = self::newItem($params);

			$idPago = $pago['id'];
			$saldoSave = 0;

			if($idPago != 0){

				$ventas = self::allVentasPendientes($cliente_id);
				$old_ultimo_id = 0;

				foreach ($ventas['data'] as $venta) {
					$venta_id = $venta['id'];
					$ultimo = DetallePagoDeudaController::ultimoPagoEnVenta($venta_id);
					
					$saldo = $venta['total'];

					if($ultimo['respuesta']){
						$saldo = $ultimo['data']['saldo'];
						$old_ultimo_id = $ultimo['data']['id'];

					}else{
						$old_ultimo_id = 0;
					}

					$ventaUpdate = 0;

					$argsDet = array(
						'usuario_crea' => $user,
						'pago_deuda_id' => $idPago,
						'old_ultimo_id' => $old_ultimo_id,
						'cliente_id' => $cliente_id,
						'ultimo' => '1',
						'venta_id' => $venta_id
					);

					if($importe >= $saldo){

						$argsDet['importe_deuda'] = $saldo;
						$argsDet['importe_pagado'] = $saldo;
						$argsDet['saldo'] = 0;

						$importe = $importe - $saldo;
						$saldo = 0;

						$argsVenta = array(
							'usuario_u' => $user,
							'fecha_pago' => date('Y-m-d'),
							'id' => $venta_id
						);
						$ventaUpdate = ModeloVentas::update('ventas',$argsVenta);

					}else{

						$argsDet['importe_deuda'] = $saldo;
						$argsDet['importe_pagado'] = $importe;
						
						$saldo = $saldo - $importe;
						$argsDet['saldo'] = $saldo;

						$importe = 0;
					}

					if($ultimo['respuesta']){
						
						$args = array(
							'ultimo' => '0',
							'id' => $old_ultimo_id,
							'usuario_u' => $user
						);

						DetallePagoDeudaModel::update('detalle_pago_deuda',$args);
					}

					
					DetallePagoDeudaModel::create('detalle_pago_deuda',$argsDet);
					
				}

				$message = "Pago guardado exitosamente.";
				$response = true;

			}else{
				$message = "Error al guardar el pago.";
			}

		}else{
			$message = "Importe tiene que ser mayor a 0";
		}			


		$salida = array(
        	'message' => $message,
        	'response' => $response,
        );

        return $salida;

	}
	
}