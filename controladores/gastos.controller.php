<?php

require_once "controller.php";

class GastosController extends Controller {

	public static function getTotalGasto($fechaInicial,$fechaFinal, $tipo,$all = false){

        $tabla = "gastos";

        $total = 0;

        $arrayTotalCategoria = array();
        $categoria = "";

        $term = '';

        if($all){
            $fechaFinal = date('Y-m-d');
            $fechaFinal = date('Y-m-d',strtotime('-1day',strtotime($fechaFinal)));
            $term = sprintf("fecha <= '%s'",$fechaFinal);
        }else{
            $term = sprintf("fecha BETWEEN '%s' AND '%s'",$fechaInicial,$fechaFinal);
        }

        $columns = '
            SUM(importe) importe_capital
        ';

        $params = array(
            'table' => $tabla,
            'columns' => $columns,
            'where' => array(
                [$term],
                ['tipo_gasto',$tipo]
            )
        );

        if($all && $tipo == 'capital' ){
        	$params['where'][] = array(
        		"utilizado = '0' "
        	);
        }

        $respuesta = Gastos::all($params);

        if(count($respuesta) > 0){

            foreach ($respuesta['data'] as $value) {
                $total += $value['importe_capital'];
            }

        }

        return $total;
    }

    public static function updateGastoUtilizado(){

    	$status = false;

    	$tabla = "gastos";
    	$fechaFinal = date('Y-m-d');
        $fechaFinal = date('Y-m-d',strtotime('-1day',strtotime($fechaFinal)));
        // $term = sprintf("fecha <= '%s'",$fechaFinal);

        $params = array(
            'utilizado' => '1',
            'where' => array(
                ['fecha','<=',$fechaFinal],
                ['tipo_gasto','capital']
            )
        );

        $respuesta = Gastos::update($tabla,$params);

        return array(
        	'status' => $status
        );

    }

}