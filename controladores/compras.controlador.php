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

                if($params['metodo_pago'] == 'contado'){
                    $params['fecha_pago'] = $params['fecha'];
                }

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

    static public function editarCompra($params){

        $mensajeError = "";
        $respuestaOk = false;
        $contenidoOk = "";

        $datosCompra = self::mostrarCompra($params['id']);
        $arrOldDetalle = $datosCompra['detalle'];
        $detalle = $params['listaProductos'];
        $validar = $params['listaId'];
        $arrDetalle = json_decode($detalle,true);
        $arrValidar = json_decode($validar,true);
        $test = [];

        unset($params['listaProductos']);
        unset($params['listaId']);
        
        if(is_array($arrDetalle)){
        
            if(count($arrDetalle) > 0){

                //ACTUALIZMOS REGISTRO DE COMPRA
                $idCompra = $params['id'];
                $tabla = 'compra';

                if($params['metodo_pago'] == 'contado'){
                    $params['fecha_pago'] = $params['fecha'];
                }

                $response = ComprasModel::update($tabla,$params);
                
                if($response != 0){
                    
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

                    //OLD
                    $oldCantidad = 0;

                    //PROCESO PARA QUITAR EL STOCK ANTERIORMENTE GUARDADO
                    foreach ($arrOldDetalle as $oldItem) {

                        $oldCantidad = $oldItem['cantidad'];
                        $idProducto = $oldItem['producto_id'];
                        //VERIFICAMOS QUE PRODUCTO EXISTA
                        $producto = ModeloProductos::mdlMostrarProductos('productos', 'id', $idProducto, '');

                        if(!empty($producto)){
                            $descripcion = $producto['descripcion'];
                            $stock = $producto['stock'];
                            $stock -= $oldCantidad;
                            $producto['stock'] = $stock;
                            $resPro = ModeloProductos::mdlEditarProducto('productos',$producto);

                            if($resPro == 'ok'){

                                if(!in_array($idProducto, $arrValidar)){

                                    $idDetalle = $oldItem['id'];
                                    $delete = CompraDetalle::delete('compra_detalle',$idDetalle,'logic');
                                    
                                    if($delete == 0){
                                        $mensajeError .= "Error al eliminar este producto <b>".$descripcion."<b> del detalle<br>";
                                    }
                                }

                            }else{
                                $mensajeError .= "Error al actualizar este producto <b>".$descripcion."<b><br>";
                            }
                        }

                        $stock = 0;
                        $descripcion ='';
                        
                    }
                
                    //PROCESO PARA ACTUALIZAR CON EL NUEVO STOCK EDITADO
                    foreach ($arrDetalle as $item) {

                        $item['compra_id'] = $idCompra;
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

                            //GUARDAMOS O ACTUALIZAMOS DETALLE
                            $detalle = CompraDetalle::createOrUpdate('compra_detalle',$item);
                           
                            if($detalle != 0){

                                $nuevoStock = $stock+$cantidad;
                                $producto['precio_venta'] = $nuevoPrecioVenta;
                                $producto['precio_compra'] = $precioCompra;
                                $producto['stock'] = $nuevoStock;

                                //ACTUALIZAMOS PRODUCTO CON NUEVO STOCK
                                $resPro = ModeloProductos::mdlEditarProducto('productos',$producto);
                                
                                if($resPro == 'ok'){

                                    $mensajeError = "Se actualizo la compra exitosamente";
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

    static public function eliminarCompra($params){

        $mensajeError = "";
        $respuestaOk = false;
        $contenidoOk = "";

        $idCompra = $params['id'];
        $datosCompra = self::mostrarCompra($idCompra);
        $arrDetalle = $datosCompra['detalle'];
        $i = 0;

        if(count($arrDetalle) > 0){

            //PROCESO PARA QUITAR EL STOCK ANTERIORMENTE GUARDADO
            foreach ($arrDetalle as $oldItem) {

                $oldCantidad = $oldItem['cantidad'];
                $idProducto = $oldItem['producto_id'];
                //VERIFICAMOS QUE PRODUCTO EXISTA
                $producto = ModeloProductos::mdlMostrarProductos('productos', 'id', $idProducto, '');

                if(!empty($producto)){
                    $descripcion = $producto['descripcion'];
                    $stock = $producto['stock'];
                    $old = $stock;
                    $stock -= $oldCantidad;
                    $producto['stock'] = $stock;
                    
                    $resPro = ModeloProductos::mdlEditarProducto('productos',$producto);

                    if($resPro == 'ok'){

                        $idDetalle = $oldItem['id'];
                        $delete = CompraDetalle::delete('compra_detalle',$idDetalle,'logic');
                        
                        if($delete == 0){
                            $mensajeError .= "Error al eliminar este producto <b>".$descripcion."<b> del detalle<br>";
                        }else{
                            $i++;
                            $mensajeError .= "<b>".$descripcion."<b><br>";
                        }
                        
                    }else{
                        $mensajeError .= "Error al eliminar este producto <b>".$descripcion."<b><br>";
                    }
                    
                    
                }

                $stock = 0;
                $descripcion ='';
                
            }
        }

        
        $deleteCompra = ComprasModel::delete('compra',$idCompra,'logic');

        if($deleteCompra != 0){
            $respuestaOk = true;
            
            $mensajeErrorPro = '';
            // if($i > 0){
            //     $mensajeErrorPro = '<br>'.$mensajeError;
            // }
            $mensajeError = "Se elimino la compra exitosamente".$mensajeErrorPro;

        }else{
            $mensajeError = "Error al eliminar la compra";
        }


        $salidaJson = [
            'mensaje'=>$mensajeError,
            'respuesta'=>$respuestaOk,
            'contenido'=>$contenidoOk
        ];

        return $salidaJson;

    }

    static public function codigo(){

        $response = false;
        $mensajeError = "";
        $contenidoOk = "";

        $compra = ComprasModel::lastRow('compra');
        $codigo = 1000;
        $band = true;

        if(!empty($compra)){
            $codigo = $compra['codigo'] + 1;

            while ($band) {

                $params = array(
                    'where'=> array(['codigo',$codigo])
                );

                $datos = ComprasModel::firstOrAll('compra',$params,'first');

                if(!empty($datos)){
                    $codigo ++;
                }else{
                    $band = false;
                }
            }
        }

        $salida = array(
            'respuesta'=>$response,
            'mensaje'=>$mensajeError,
            'contenido'=>$codigo
        );

        return $salida;
    }

    static public function mostrarCompra($id){

        $response = [];
        $compra = [];
        $detalle = [];
        $compra_id = 0;

        if(is_numeric($id)){

            $params = array(
                'where'=> array(['id',$id])
            );
            $datos = ComprasModel::firstOrAll('compra',$params,'first');
            
            if(!empty($datos)){
                $compra = $datos;
                $compra_id = $datos['id'];
                $params_det = array(
                    'table' => 'compra_detalle',
                    'where' => array(['compra_id',$compra_id])
                );

                $detalle = CompraDetalle::all($params_det);
            }
        }

        $response = array(
            'compra'=> $compra,
            'detalle' => $detalle
        );
            

        return $response;
    }

    public static function capitalGastado($fechaInicial,$fechaFinal,$all = false){

        $tabla = "compra_detalle dt";

        $total = 0;

        $arrayTotalCategoria = array();
        $categoria = "";

        $term = '';

        if($all){
            $fechaFinal = date('Y-m-d');
            $fechaFinal = date('Y-m-d',strtotime('-1day',strtotime($fechaFinal)));
            $term = sprintf("c.fecha_pago <= '%s'",$fechaFinal);
        }else{
            $term = sprintf("c.fecha_pago BETWEEN '%s' AND '%s'",$fechaInicial,$fechaFinal);
        }

        $columns = '
            SUM(dt.sub_total),
            ca.categoria
        ';

        $params = array(
            'table' => $tabla,
            'columns' => $columns,
            'where' => array(
                [$term]
            ),
            'join' => array(
                ['compra c','dt.compra_id','c.id'],
                ['productos p','dt.producto_id','p.id'],
                ['categorias ca','p.id_categoria','ca.id']
            ),
            'group' => 'ca.categoria'
        );

        $respuesta = ComprasModel::all($params);
        
        if(count($respuesta) > 0){

            foreach ($respuesta['data'] as $value) {
                $categoria = $value[1];
                $total = $value[0];

                if(array_key_exists($categoria,$arrayTotalCategoria)){
                    $arrayTotalCategoria[$categoria] = $arrayTotalCategoria[$categoria] + $total;
                }else{
                    $arrayTotalCategoria[$categoria] = $total;
                }
            }

            $totalGastos = GastosController::getTotalGasto($fechaInicial,$fechaFinal,'capital',true);
            $numCate = count($arrayTotalCategoria);

            if($numCate == 0){
                $categoriasArr = ControladorCategorias::ctrMostrarCategorias(null,null);
                $numCate = count($categoriasArr);
                $subPorCate = $totalGastos/$numCate;

                foreach ($categoriasArr as $item) {
                    $arrayTotalCategoria[$item['categoria']] = $subPorCate;
                }

            }else{
                $subPorCate = $totalGastos/$numCate;
                foreach ($arrayTotalCategoria as $key => $total) {
                    $arrayTotalCategoria[$key] = $total + $subPorCate;
                }
            }
                
        }

        return $arrayTotalCategoria;
    }
}