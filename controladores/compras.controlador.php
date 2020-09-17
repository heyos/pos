<?php

require_once "controller.php";

class ComprasController extends Controller {
    
    static public function nuevaCompra($params){

        $mensajeError = "No se puede ejecutar la aplicacion";
        $respuestaOk = false;
        $contenidoOk = "";

        $detalle = $params['listaProductos'];
        $arrDetalle = json_decode($detalle,true);
        $contenidoOk = $arrDetalle;

        unset($params['listaProductos']);

        if(is_array($arrDetalle)){

            if(count($arrDetalle) > 0){

                $contenidoOk = $params;
                //CREAMOS UN REGISTRO DE COMPRA
                $tabla = 'compra';
                $idCompra = ComprasModel::create($tabla,$params);

                if($idCompra != 0){
                    $contenidoOk = $idCompra;

                    //GUARDAR DETALLE
                }else{
                    $mensajeError = "Error al crear la compra";
                }


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