<?php

require_once "controller.php";

class ProveedorController extends Controller {

    static public function nuevoProveedor($params){

        $message = "No se puede ejecutar la aplicacion";
        $response = false;
        $data = "";

        $table = 'proveedor';

        if(array_key_exists('id',$params)){
            unset($params['id']);
        }
        $where = array(
            'where' => [
                //['ruc',$params['ruc']],
                ['razon_social',$params['razon_social']]
            ]
        );

        $proveedor = ProveedorModel::firstOrAll($table,$where,'first');

        if(!empty($proveedor)){

            $message = "Proveedor existente.";

        }else{

            $data = ProveedorModel::create($table,$params);
            
            if($data != 0){
                $response = true;
                $message = "Se creo exitosamente el registro.";
            }else{
                $message = "Error al guardar el registro";
            }

        }


        $salida = array(
            'response' => $response,
            'message' => $message,
            'data' => ''
        );

        return $salida;
    }

    static public function updateProveedor($params){

        $response = false;
        $message = "No se puede ejecutar la aplicacion";

        $table = 'proveedor';
        $where = array(
            'tabla' => $table,
            'id' => $params['id']
        );

        $search = self::itemDetail($where);
        $duplicado = false;

        if($search['respuesta']){

            if($params['razon_social'] != $search['contenido']['razon_social']){
                $where['where'] = array(
                    ['razon_social',$params['razon_social']]
                );

                $search = self::itemDetail($where);
                $duplicado = $search['respuesta'];
            }
        }

        if($duplicado){
            $message = "Razon social duplicado";
        }else{
            //update
            $respuesta = ProveedorModel::update($table,$params);

            if($respuesta == 1){
                $message = "Se actualizo exitosamente.";
                $response = true;
            }else{
                $message = "Hubo un error en el proceso.";
            }
        }

        $salida = array(
            'response' => $response,
            'message' => $message,
            'data' => ''
        );

        return $salida;

    }

    static public function deleteProveedor($params){

        $tabla = 'proveedor';

        $response = false;
        $message = "No se puede ejecutar la aplicacion";

        if(array_key_exists('id',$params)){

            $params['table'] = $tabla;
            $params['type'] = 'logic';

            $data = self::deleteItem($params);

            $response = $data['respuesta'];
            $message = $data['message'];

        }else{
            $message = "Parametros incorrectos.";
        }


        $salida = array(
            'response' => $response,
            'message' => $message,
            'data' => ''
        );

        return $salida;       

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