<?php

require_once "../controladores/compras.controlador.php";
require_once "../modelos/compras.modelo.php";

class AjaxCompras{

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