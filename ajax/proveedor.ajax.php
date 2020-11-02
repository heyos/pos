<?php

require_once "../controladores/proveedor.controlador.php";
require_once "../modelos/proveedor.modelo.php";

class AjaxProveedor{

    public $params;
    
    public function ajaxAddEditProveedor(){

        $params = $this->params;
        $accion = $params['accion'];

        if($accion == 'add'){

        }elseif ($accion == 'edit') {
           
        }

        $respuesta = ControladorClientes::ctrMostrarClientes($item, $valor);

        echo json_encode($respuesta);


    }

}

/*=============================================
EDITAR CLIENTE
=============================================*/ 

if(isset($_POST["accion"])){

    $proveedor = new AjaxProveedor();
    $cliente -> params = $_POST;
    $cliente -> ajaxAddEditProveedor();

}