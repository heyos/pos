<?php

require_once "../controladores/proveedor.controlador.php";
require_once "../modelos/proveedor.modelo.php";

class AjaxProveedor{

    public $params;
    
    public function ajaxAddEditProveedor(){

        $params = $this->params;
        $accion = $params['accion'];

        unset($params['accion']);

        if($accion == 'add'){

            $respuesta = ProveedorController::nuevoProveedor($params);

        }elseif ($accion == 'edit') {
           $respuesta = ProveedorController::updateProveedor($params);
        }        

        echo json_encode($respuesta);

    }

    public function getProveedorAjax(){
        $params = $this->params;

        $params['tabla'] = 'proveedor';
        $params['id'] = $params['term'];

        $data = ProveedorController::itemDetail($params);

        $respuesta = array(
            'response' => $data['respuesta'],
            'message' => $data['mensaje'],
            'data' => $data['contenido']
        );


        echo json_encode($respuesta);

    }

    public function deleteProveedorAjax(){

        $params = $this->params;

        $params['id'] = $params['term'];

        $respuesta = ProveedorController::deleteProveedor($params);

        echo json_encode($respuesta);

    }

}

/*=============================================
EDITAR CLIENTE
=============================================*/ 

if(isset($_POST["accion"])){

    $a = new AjaxProveedor();
    $a -> params = $_POST;

    switch ($_POST["accion"]) {
        case 'edit':
        case 'add':
            $a -> ajaxAddEditProveedor();
            break;
        case 'data':
            $a -> getProveedorAjax();
            break;
        case 'delete':
            $a -> deleteProveedorAjax();
            break;
        default:
            echo json_encode([
                'response' => false,
                'message' => "Accion no disponible",
                'data' => []
            ]);
            break;
    }
   

}