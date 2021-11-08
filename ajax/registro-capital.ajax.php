<?php

require_once "../controladores/categorias.controlador.php";
require_once "../controladores/compras.controlador.php";
require_once "../controladores/ventas.controlador.php";
require_once "../controladores/reporte_capital.controlador.php";

require_once "../modelos/compras.modelo.php";
require_once "../modelos/ventas.modelo.php";
require_once "../modelos/reporte_capital.modelo.php";
require_once "../modelos/productos.modelo.php";
require_once "../modelos/categorias.modelo.php";

class ReporteCapitalAjax{

    public $params;
    
    public function addRegistro(){

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

    public function getResumen(){
        $params = $this->params;

        $respuesta = ReporteCapitalController::showResumenInput();

        echo json_encode($respuesta);

    }

    public function deleteRegistro(){

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

    $a = new ReporteCapitalAjax();
    $a -> params = $_POST;

    switch ($_POST["accion"]) {
        case 'add':
            $a -> addRegistro();
            break;
        case 'data':
            $a -> getResumen();
            break;
        case 'delete':
            $a -> deleteRegistro();
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