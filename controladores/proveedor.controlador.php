<?php

require_once "controller.php";

class ProveedorController extends Controller {

    static public function nuevoProveedor($params){

    }

    static public function ctrMostrarProveedor($params){

        $tabla = "proveedor";

        if(!array_key_exists('where',$params)){
            $params['where'] = [
                array('estado','1')
            ];
        }

        $data = ProveedorModel::firstOrAll($tabla,$params,$params['data']);

        if($params['show'] == 'ajax'){
            // return ;
        }else{
            return $data;
        }

    }
}