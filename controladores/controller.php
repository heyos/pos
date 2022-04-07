<?php

date_default_timezone_set('America/Lima');

class Controller {

    static public function dataTable($req,$params,$action){

        $like = "";
        $concat = "";
        $val = "";
        $start = $req['start'];
        $length = $req['length'];
        $order = $req['order'][0]['column'];
        $orderDir = $req['order'][0]['dir'];
        $orderColumn = $params['orderColumns'][$order];
        

        if(!empty($req['search']['value'])){

            if(array_key_exists("searchColumns", $params)){
                if(count($params['searchColumns']) > 0){
                    
                    foreach ($params['searchColumns'] as $column) {
                        $concat .= $column.",' ',";
                    }
                    $concat =  substr($concat, 0,-5);

                    $arr = explode(' ',$req['search']['value']);

                    foreach ($arr as $value) {

                        if(count($arr) == 1){
                            $val .= $value;
                        }else{
                            $val .= $value.' ';
                        }
                        
                        $like .= " CONCAT(".$concat.") LIKE '%".$val."%' OR";
                    }

                    $like =  substr($like, 0,-2);
                    
                    $params['search'] = sprintf(" (%s) ",$like);
                    
                }
            }
                
        }

        $qTotal = Model::all($params);
        $totalRecords = count($qTotal);

        $params['start'] = $start;
        $params['length'] = $length;
        $params['order'] = !isset($params['order']) ? $orderColumn : $params['order'];
        $params['dir'] = !isset($params['dir']) ? $orderDir : $params['dir'];

        $qRecords = Model::all($params);

        switch ($action) {
            case 'data':

                return $qRecords;
                
                break;
            
            case 'options':

                $options = [
                    "draw" => intval( $req['draw'] ),   
                    "recordsTotal" => intval( $totalRecords ),  
                    "recordsFiltered" => intval($totalRecords)
                ];

                return $options;
                
                break;
            default:
                return $qRecords;
                break;
        }
    }

    public static function newItem($datos){

        $respuestaOk = false;
        $mensajeError = "No se puede ejecutar la aplicacion";
        $contenidoOk = '';

        $id = 0;
        $table = "";

        if(array_key_exists('id',$datos)){
            unset($datos['id']);
        }

        if(count($datos) > 1){

            if(array_key_exists('tabla',$datos)){

                $table = $datos['tabla'];
            
                unset($datos['tabla']);

                $salida = Model::create($table,$datos);

                if($salida != 0){
                    $respuestaOk = true;
                    $mensajeError = "Registro creado exitosamente";
                    $id = $salida;
                }else{
                    $mensajeError = $salida;
                }
                                  
            }else{
                $mensajeError = "Parametros incorrectos";
            }
            
        }else{
            $mensajeError = "No contiene parametros validos para terminar la consulta";
        }

        $return = array('respuesta'=>$respuestaOk,
                            'mensaje'=>$mensajeError,
                            'contenido'=>$contenidoOk,
                            'message' => $mensajeError,
                            'id'=>$id);

        return $return;

    }

    public static function itemDetail($params){

        $respuestaOk = false;
        $mensajeError = "No se puede ejecutar la aplicacion";
        $contenidoOk = [];

        if((array_key_exists('id',$params) || array_key_exists('where',$params))  && array_key_exists('tabla',$params)){

            $tabla = $params['tabla'];

            $datos = Model::firstOrAll($tabla,$params,'first');

            if(!empty($datos)){
                
                $respuestaOk = true;
                $contenidoOk = $datos;
            }else{
                $mensajeError = "Parametros incorrectos.";
            }


        }else{
            $mensajeError = "Parametros incorrectos.";
        }

        $salidaJson = array('respuesta'=>$respuestaOk,
                            'mensaje'=>$mensajeError,
                            'contenido'=>$contenidoOk);

        return $salidaJson;

    }

    public static function updateItem($datos){

        $respuestaOk = false;
        $mensajeError = "No se puede ejecutar la aplicacion";
        $contenidoOk = '';

        unset($datos['accion']);
        
        if(count($datos) > 0){

            
            

        }else{
            $mensajeError = "No contiene parametros validos";
        }

        $salidaJson = array('respuesta'=>$respuestaOk,
                            'mensaje'=>$mensajeError,
                            'contenido'=>$contenidoOk);

        echo json_encode($salidaJson);

    }

    public static function deleteItem($params){

        $respuestaOk = false;
        $mensajeError = "No se puede ejecutar la aplicacion";
        
        if($params['table'] !=''){

            $table = $params['table'];

            if($params['id']!=''){

                $id = $params['id'];
                $type = $params['type'];
                
                $respuesta = Model::delete($table,$id,$type);

                if($respuesta !=  0){
                    $respuestaOk = true;
                    $mensajeError = "Se elimino exitosamente.";
                }else{
                    $mensajeError = "No se elimino el registro";
                }

            }else{
                $mensajeError = "Parametros inccorrectos";
            }

        }else{
            $mensajeError = "Parametros inccorrectos";
        }

        $salidaJson = array('respuesta'=>$respuestaOk,
                            'message'=>$mensajeError);

        return $salidaJson;
    }

    public static function paramsValid($table,$arrKey,$arrData){

        $response = false;
        $data = '';
        $error = 0;
        $message = "";
        
        if(array_key_exists('data',$arrKey)){
            if(count($arrKey['data']) > 0 && count($arrData) > 0){
                
                $params = [];
                $respuesta = [];
                $out = '';

                foreach ($arrKey['data'] as $key) {

                    $out = '';
                    
                    if(array_key_exists($key,$arrData)){
                        
                        $data = sprintf(" %s = '%s' ",$key,$arrData[$key]);

                        if(array_key_exists('diff',$arrKey)){
                            $diff = $arrKey['diff'];
                            if(array_key_exists($diff,$arrData)){
                                $data .= sprintf(" AND %s <> '%s' ",$diff,$arrData[$diff]);
                            }
                        }

                        $params = array(
                            'where' => [[$data]]
                        );

                        $respuesta = Model::firstOrAll($table,$params,'all');

                        if(count($respuesta) > 0){

                            $error ++;
                            $out = $key;
                            if(array_key_exists('dataOut',$arrKey)){
                                $out = array_key_exists($key, $arrKey['dataOut']) ? $arrKey['dataOut'][$key] : $key;
                            }

                            $message .= $out.' : '.$arrData[$key].' ya esta registrado. <br>';
                        }

                        $data = '';
                    }

                }

                if($error > 0) {
                    $message = substr($message, 0,-4);
                }else {
                    $response = true;
                }
                    
            }
        }
            
        $salida = array(
            'response' => $response,
            'message' => $message
        );

        return $salida;
    }
}