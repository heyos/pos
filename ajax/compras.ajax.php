
<?php


require_once "../controladores/globales.php";
require_once "../controladores/compras.controlador.php";

require_once "../modelos/compras.modelo.php";
require_once "../modelos/productos.modelo.php";
require_once "../modelos/compra_detalle.modelo.php";
require_once "../modelos/producto_proveedor.php";

class AjaxCompras{

    public $params;
    

    public function ajaxAddEditCompras(){

        $id = "id";
        $params = $this->params;
        $accion = $params['accion'];
        
        unset($params['accion']);
        unset($params['agregarProducto']);

        if($accion=='add'){
            unset($params['listaId']);
            $respuesta = ComprasController::nuevaCompra($params);
        }else{
            $respuesta = ComprasController::editarCompra($params);
        }

        echo json_encode($respuesta);


    }

}

/*=============================================
EDITAR CLIENTE
=============================================*/ 

if(isset($_POST["accion"])){

    $compras = new AjaxCompras();
    $compras -> params = $_POST;
    $compras -> ajaxAddEditCompras();

}