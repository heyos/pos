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

                //CREAMOS UN REGISTRO DE COMPRA
                $tabla = 'compra';
                $idCompra = ComprasModel::create($tabla,$params);
                
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
                    $descripcion = "";

                    foreach ($arrDetalle as $item) {

                        $item['compra_id'] = $idCompra; //AGREGAMOS SU COMPRA_ID
                        $descripcion = $item['descripcion'];
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
                            $detalle = CompraDetalle::create('compra_detalle',$item);
                            
                            if($detalle != 0){

                                $nuevoStock = $stock+$cantidad;
                                $producto['precio_venta'] = $nuevoPrecioVenta;
                                $producto['precio_compra'] = $precioCompra;
                                $producto['stock'] = $nuevoStock;

                                //ACTUALIZAMOS PRODUCTO CON NUEVO STOCK
                                $resPro = ModeloProductos::mdlEditarProducto('productos',$producto);
                                
                                if($resPro == 'ok'){

                                    $mensajeError = "Se guardo la compra exitosamente";
                                    $respuestaOk = true;

                                    $productoProveedor = [];

                                    $productoProveedor = array(
                                        'producto_id' => $idProducto,
                                        'proveedor_id' => $params['proveedor_id'],
                                        'old_precio' => $oldPrecio,
                                        'ultimo_precio' => $precioCompra,
                                        'ultima_compra' => $params['fecha'],
                                        'compras' => $cantidad,
                                        'where' => array(
                                            array('producto_id',$idProducto),
                                            array('proveedor_id',$params['proveedor_id'])
                                        )
                                    );

                                    $productoProveedorResponse = ProductoProveedor::createOrUpdate('producto_proveedor',$productoProveedor);

                                    if($productoProveedorResponse == 0){
                                        $mensajeError .='<br> Error en el almacenamiento de producto_proveedor';
                                    }
                                
                                    
                                }else{
                                    $mensajeError .= "Error al guardar este producto <b>".$descripcion."<b> en el detalle<br>";
                                }
                                $contenidoOk = $producto;
                                
                            }else{
                                $mensajeError .= "Error al guardar este producto <b>".$descripcion."<b><br>";
                            }
                            
                        }else{
                            $mensajeError .= "Error al guardar producto <b>".$descripcion."<b> no existe<br>";
                        }

                    }

                } else {
                    $mensajeError = "Error al crear la compra";
                }

            }else{
                $mensajeError = "Debe registrar almenos un producto.";
            }

        } else{
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