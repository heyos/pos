<?php

require_once "controller.php";

class ComprasController extends Controller {
    
    static public function nuevaCompra($params){

        $mensajeError = "";
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
                //$idCompra = ComprasModel::create($tabla,$params);
                $idCompra = 4;

                if($idCompra != 0){
                    
                    //GUARDAR DETALLE
                    $tabla = "compra_detalle";
                    $cantidad = 0;
                    $precioCompra = 0;
                    
                    //variables del producto
                    $margen = 0;
                    $stock = 0;
                    $nuevoPrecioVenta = 0;
                    $oldPrecio = 0;


                    foreach ($arrDetalle as $item) {
                        //Model::create()
                        $idProducto = $item['producto_id'];
                        $cantidad = $item['cantidad'];
                        $precioCompra = $item['precio_compra'];

                        //primero guardar el detalle luego actualizar stock y otros parametros

                        //obtenemos datos del producto
                        $producto = ModeloProductos::mdlMostrarProductos('productos', 'id', $idProducto, '');

                        if(!empty($producto)){

                            $margen = $producto['margen'];
                            $stock = $producto['stock'];
                            $nuevoPrecioVenta = $precioCompra*($margen+1);
                            $oldPrecio = $producto['precio_compra'];

                        }else{
                            $mensajeError .= "Error al guardar este producto <b>".$item['descripcion']."<b><br>";
                        }
                    }

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