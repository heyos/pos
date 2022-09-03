<?php

require_once "controller.php";

class DetallePagoDeudaController extends Controller {

	public static function ultimoPagoEnVenta($venta_id){

		$params = [
			'tabla' => 'detalle_pago_deuda',
			'where' => [['ultimo','1'],['venta_id',$venta_id]]
		];

		$detalle = self::itemDetail($params);

		return $detalle;
	}
	
}