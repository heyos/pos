<?php

require_once "controller.php";

class ReporteCapitalController extends Controller {

	public static function showReporteCapital() {

		$table = 'reporte_capital';

		$where = array(
			'where' => [
				['activo','1']
			]
		);

		$arrInicio = [];
		$arrAcumulado = [];
		$arrGastado = [];
		$inicio = 0;
		$acumulado = 0;
		$gastado = 0;
		$total = 0;
		$f_inicio = '';
		$f_fin = date('Y-m-d');
		$f_fin = date('Y-m-d',strtotime('-1day',strtotime($f_fin)));

		$reporte = ReporteCapitalModel::firstOrAll($table,$where,'first');
		$detalle = [];

		if(!empty($reporte)){

			$arrInicio = $reporte['capital'];
			$detalle = json_decode($reporte['detalle'],true);
			$f_inicio = $reporte['f_fin'];
			$f_inicio = date('Y-m-d',strtotime('+1day',strtotime($f_inicio)));

			$arrAcumulado = ControladorVentas::capitalAcumulado($f_inicio, $f_fin);
			$arrGastado = ComprasController::capitalGastado($f_inicio, $f_fin);

		}else{
			$f_inicio = $f_fin;
			$arrInicio = ControladorVentas::capitalAcumulado('','',true);
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
					'inicio' => $inicio,
					'acumulado' => $acumulado,
					'gastado' => $gastado,
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

	public static function showResumenInput(){

		$html = '';
		$message = 'No se puede ejecutar la aplicacion';

		$reporte = self::showReporteCapital();
		$capital = 0;

		if($reporte['response']){

			$i = 0;

			foreach ($reporte['data'] as $categoria => $data) {

				$html .= '
					<div class="form-group">
						<label class="control-label col-sm-3 col-xs-12">'.$categoria.'</label>
						<div class="col-sm-4 col-xs-12">
							<div class="input-group">
                				<span class="input-group-addon"><i class="fa fa-money"></i></span>
                				<input type="text" class="form-control input original" input="original"
                				orden="'.$i.'" categoria="'.$categoria.'" value="'.$data['total'].'">
                			</div>
						</div>
						<div class="col-sm-4 col-xs-12 aumentar" style="display:none;">
							<div class="input-group">
                				<span class="input-group-addon"><i class="fa fa-money"></i></span>
                				<input type="text" class="form-control input nuevo nuevo_'.$i.'" input="nuevo"
                				orden="'.$i.'" categoria="'.$categoria.'" value="'.$data['total'].'">
                			</div>
						</div>
	              	</div>
				';

				$capital += $data['total'];

				$i++;
				
			}

		}else{
			$message = 'Debe registrar almenos una categoria para empezar.';
		}


		return array(
			'response' => $reporte['response'],
			'message' => $message,
			'html' => $html,
			'capital' => $capital
		);

	}
		

}