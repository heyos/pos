<?php

require_once "controller.php";

class ComprasController extends Controller {
    
    static public function nuevaCompra($params){

        $mensajeError = "";
        $respuestaOk = false;
        $contenidoOk = "";

        $detalle = $params['listaProductos'];
        $arrDetalle = json_decode($detalle,true);
        //$contenidoOk = $arrDetalle;
        $test = [];

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
                    $nuevoStock = 0;
                    $nuevoPrecioVenta = 0;
                    $oldPrecio = 0;

                    foreach ($arrDetalle as $item) {

                        $item['compra_id'] = $idCompra; //AGREGAMOS SU COMPRA_ID
                        unset($item['descripcion']);//PARAMETRO QUE NO ESTA EN COMPRA DETALLE
                        
                        $idProducto = $item['producto_id'];
                        $cantidad = $item['cantidad'];
                        $precioCompra = $item['precio_compra'];

                        //VERIFICAMOS QUE PRODUCTO EXISTA
                        $producto = ModeloProductos::mdlMostrarProductos('productos', 'id', $idProducto, '');
                        
                        if(!empty($producto)){

                            //obtenemos datos del producto
                            $margen = $producto['margen'];
                            $stock = $producto['stock'];
                            $nuevoPrecioVenta = $precioCompra*($margen+1);
                            $oldPrecio = $producto['precio_compra'];

                            //GUARDAMOS DETALLE
                            //$detalle = CompraDetalle::create('compras_detalle',$item);
                            $detalle=1;

                            if($detalle != 0){

                                $nuevoStock = $stock+$cantidad;
                                $producto['precio_venta'] = $nuevoPrecioVenta;
                                $producto['precio_compra'] = $precioCompra;
                                $producto['stock'] = $nuevoStock;

                                //ACTUALIZAMOS PRODUCTO CON NUEVO STOCK
                                //$resPro = ModeloProductos::mdlEditarProducto('productos',$producto);
                                $resPro = 'ok';

                                if($resPro == 'ok'){

                                    


                                }else{
                                    $mensajeError .= "Error al guardar este producto <b>".$item['descripcion']."<b> en el detalle<br>";
                                }
                                
                                $test[] = $item;

                            }else{
                                $mensajeError .= "Error al guardar este producto <b>".$item['descripcion']."<b><br>";
                            }
                            
                        }else{
                            $mensajeError .= "Error al guardar producto <b>".$item['descripcion']."<b> no existe<br>";
                        }

                    }
                    $contenidoOk = $test;
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