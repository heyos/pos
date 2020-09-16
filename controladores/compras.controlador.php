<?php

require_once "controller.php";

class ComprasController extends Controller {
    
    static public function nuevaCompra($params){

        $mensajeError = "No se puede ejecutar la aplicacion";
        $respuestaOk = false;
        $contenidoOk = "";

        $tabla = 'compras';
        $params['tabla'] = $tabla;

        $detalle = $params['listaProductos'];
        $arrDetalle = json_decode($detalle,true);
        $contenidoOk = $arrDetalle;

        if(is_array($arrDetalle)){

            if(count($arrDetalle) > 0){

                $contenidoOk = $arrDetalle;

            }else{
                $mensajeError = "Debe registrar almenos un producto.";
            }

        }else{
            $mensajeError = 'Detalle de productos invalido.';
        }

        $salidaJson = [
            'mensaje'=>$mensajeError,
            'respuesta'=>$respuestaOk,
            'contenido'=>$contenidoOk
        ];


        return $salidaJson;
        
    }
}