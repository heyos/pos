<?php

require_once "../controladores/globales.php";
require_once "../controladores/compras.controlador.php";
require_once "../modelos/compras.modelo.php";

class AjaxCompras{

    public $params;
    

    public function ajaxAddEditCompras(){

        $id = "id";
        $params = $this->params;
        $accion = $params['accion'];
        
        unset($params['accion']);
        unset($params['listaId']);
        unset($params['agregarProducto']);

        if($accion=='add'){
            $respuesta = ComprasController::nuevaCompra($params);
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