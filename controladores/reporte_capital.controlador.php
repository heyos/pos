<?php

require_once "controller.php";

class ReporteCapitalController extends Controller {

	public static function showReporteCapital($params = null) {

		$table = 'reporte_capital';

		$args = array(
			['activo','1']
		);

		$type = 'new';

		if($params){

			$type = $params['type'];

			if($type == 'detalle'){
				$args = array(
					['id',$params['id']]
				);
			}
		}

		$where = array(
			'where' => $args
		);

		$arrInicio = [];
		$arrAcumulado = [];
		$arrGastado = [];
		$inicio = 0;
		$acumulado = 0;
		$gastado = 0;
		$total = 0;
		$f_inicio = '';
		$hoy = date('Y-m-d');
		$f_fin = date('Y-m-d',strtotime('-1day',strtotime($hoy)));

		$reporte = ReporteCapitalModel::firstOrAll($table,$where,'first');
		$detalle = [];

		if(!empty($reporte)){

			$arrInicio = $reporte['capital'];
			$detalle = json_decode($reporte['detalle'],true);
			$f_inicio = $reporte['f_fin'];
			$f_inicio = date('Y-m-d',strtotime('+1day',strtotime($f_inicio)));

			if($type == 'new'){
				$arrAcumulado = ControladorVentas::capitalAcumulado($f_inicio, $f_fin);
				$arrGastado = ComprasController::capitalGastado($f_inicio, $f_fin);
			}

		}else{
			$f_inicio = $f_fin;
			$arrAcumulado = ControladorVentas::capitalAcumulado('','',true);
			$arrGastado = ComprasController::capitalGastado('','',true);
		}

		
		$categorias = ControladorCategorias::ctrMostrarCategorias('', '');
		$arrTotal = [];

		$response = false;

		if(count($categorias) > 0){

			foreach ($categorias as $categoria) {

				$name = $categoria['categoria'];
				$inicio = array_key_exists($name,$detalle) ? $detalle[$name] : 0;
				$acumulado = array_key_exists($name,$arrAcumulado) ? $arrAcumulado[$name] : 0;
				$gastado = array_key_exists($name,$arrGastado) ? $arrGastado[$name] : 0;

				$total = $inicio + $acumulado - $gastado;
				
				$arrTotal[$name] = array(
					'inicio' => number_format($inicio,2),
					'acumulado' => number_format($acumulado,2),
					'gastado' => number_format($gastado,2),
					'total' => $total
				);
			}

			$response = true;
		}

		$data = array(
			'data' => $arrTotal,
			'desde' => $f_inicio,
			'hasta' => $f_fin,
			'response' => $response
		);

		return $data;
		

	}

	public static function showResumenInput($params){

		$html = '';
		$message = 'No se puede ejecutar la aplicacion';
		$response = false;

		$reporte = self::showReporteCapital($params);
		$capital = 0;

		$out = $reporte['desde'] > $reporte['hasta'] ? false : true;

		if($reporte['response'] && $out){

			$i = 0;

			foreach ($reporte['data'] as $categoria => $data) {

				$html .= '
					<div class="form-group">
						<label class="control-label col-sm-3 col-xs-12">'.$categoria.'</label>
						<div class="col-sm-4 col-xs-12">
							<div class="input-group">
                				<span class="input-group-addon"><i class="fa fa-money"></i></span>
                				<input type="text" class="form-control input original number" input="original"
                				orden="'.$i.'" categoria="'.$categoria.'" value="'.$data['total'].'">
                			</div>
						</div>
						<div class="col-sm-4 col-xs-12 aumentar" style="display:none;">
							<div class="input-group">
                				<span class="input-group-addon"><i class="fa fa-money"></i></span>
                				<input type="text" class="form-control number input nuevo nuevo_'.$i.'" input="nuevo"
                				orden="'.$i.'" categoria="'.$categoria.'" value="'.$data['total'].'">
                			</div>
						</div>
	              	</div>
				';

				$capital += $data['total'];

				$i++;
				
			}

			$response = true;

		}else{
			$message = $out ? 'Debe registrar almenos una categoria para empezar.' : 'Es muy pronto para crear un regitro nuevo.';
		}


		return array(
			'response' => $response,
			'message' => $message,
			'html' => $html,
			'capital' => number_format($capital,2,'.',''),
			'fecha' => array(
				'f_inicio' => $reporte['desde'],
				'f_fin' => $reporte['hasta']
			)
		);

	}

	public static function addRegistro($params){

		$response = false;
		$message = "Error en el proceso.";
		
		$params['tabla'] = "reporte_capital";

		$respuesta = self::newItem($params);

		if($respuesta['respuesta']){

			$message = $respuesta['message'];

			$where = array(
				'activo' => '0',
				'where' => array(
					['activo','1']
				)
			);

			$update = ReporteCapitalModel::update('reporte_capital',$where);

			if($update == 1){

				$where = array(
					'activo' => '1',
					'id' => $respuesta['id']
				);

				$update = ReporteCapitalModel::update('reporte_capital',$where);

				if($update == 1){
					$response = true;
				}else{
					$message = "Se guardo, pero no se pudo activar el registro.";
				}
				
			}else{
				$message = "Se guardo, pero hubo un error al completar el proceso.";
			}
		}

		$salida = array(
			'response' => $response,
			'message' => $message
		);

		return $salida;
		
	}
		

}