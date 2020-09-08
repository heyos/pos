<?php

require_once "../controladores/proveedor.controlador.php";
require_once "../modelos/proveedor.modelo.php";

class AjaxProveedor{

    /*=============================================
    EDITAR CLIENTE
    =============================================*/ 

    public $idCliente;

    public function ajaxEditarCliente(){

        $item = "id";
        $valor = $this->idCliente;

        $respuesta = ControladorClientes::ctrMostrarClientes($item, $valor);

        echo json_encode($respuesta);


    }

}

/*=============================================
EDITAR CLIENTE
=============================================*/ 

if(isset($_POST["idCliente"])){

    $cliente = new AjaxClientes();
    $cliente -> idCliente = $_POST["idCliente"];
    $cliente -> ajaxEditarCliente();

}