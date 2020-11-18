<?php

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
        $params['order'] = $orderColumn;
        $params['dir'] = $orderDir;

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
                            'id'=>$id);

        return $return;

    }

    public static function itemDetail($datos){

        $respuestaOk = false;
        $mensajeError = "No se puede ejecutar la aplicacion";
        $contenidoOk = '';

        if(array_key_exists('id',$datos) && array_key_exists('tabla',$datos)){

            $id = Globales::sanearData($datos['id']);
            $tabla = Globales::sanearData($datos['tabla']);

            $datos = Model::detalleDatosMdl($tabla,'id',$id);

            if(count($datos) > 0){
                if(array_key_exists('dFecNac', $datos[0])){
                    $fecha = $datos[0]['dFecNac'];
                    $fecha = date('d-m-Y',strtotime($fecha));
                    $datos[0]['dFecNac'] = $fecha;
                }
                $respuestaOk = true;
                $contenidoOk = $datos[0];
            }else{
                $mensajeError = "Parametros incorrectos.";
            }


        }else{
            $mensajeError = "Parametros incorrectos.";
        }

        $salidaJson = array('respuesta'=>$respuestaOk,
                            'mensaje'=>$mensajeError,
                            'contenido'=>$contenidoOk);

        echo json_encode($salidaJson);

    }

    public static function updateItem($datos){

        $respuestaOk = false;
        $mensajeError = "No se puede ejecutar la aplicacion";
        $contenidoOk = '';

        unset($datos['accion']);
        
        if(count($datos) > 0){

            $columns = '';
            $values = '';
            $table = '';

            foreach ($datos as $key => $item) {

                if($key == 'tabla'){
                    $table = $item;
                }elseif ($key == 'id') {
                    $id = $item;
                }
                elseif ($key == 'dFecNac') {

                    if($item == ''){
                        $fecha = 'null';
                       
                    }else{
                        $fecha = date('Y-m-d',strtotime($datos['dFecNac']));
                        $fecha = sprintf(" '%s' ",$fecha) ;
                    }

                    $columns .= sprintf(" %s =  %s, ",$key,$fecha);
                    
                }else{
                    $columns .= sprintf(" %s = '%s', ",$key,$item);
                }
            }

            $columns = substr($columns, 0,-2);
            
            if($table != ''){

                $error = 0;
                $oldrut = '';
                $columnId = 'id';

                switch ($table) {
                    case 'persona':

                        if($_POST['nRutPer'] != ''){

                            if(strlen($_POST['nRutPer']) > 1){
                                $old = Model::detalleDatosMdl($table,'id',$id);
                                if(count($old) > 0){

                                    $validarRut = Globales::valida_rut($_POST['nRutPer']);
                                    
                                    if($validarRut==false){
                                        $mensajeError = "RUT invalido";
                                        $error++;
                                    }else{
                                        $oldrut = $old[0]['nRutPer'];
                                        if($oldrut != $_POST['nRutPer']){
                                            
                                            // $verificar = Model::detalleDatosMdl($table,'nRutPer',$_POST['nRutPer']);
                                            $where = array('nRutPer'=>$_POST['nRutPer'],'xTipoPer'=>$_POST['xTipoPer']);
                                            $verificar = Model::detalleDatosCustomMdl($table,$where);
                                            if(count($verificar) > 0){
                                                $mensajeError = "RUT ya se encuentra registrado";
                                                $error++;
                                            }
                                        }
                                    }
                                }else{
                                    $error++;
                                    $mensaje = 'Parametros invalidos';
                                }
                            }else{
                                $error++;
                                $mensajeError = "RUT invalido";
                            }

                        }else{
                            $error++;
                            $mensajeError = "Rut no puede estar vacio.";
                        }

                        $columnId = 'id';
                        
                        break;
                    case 'direccion':

                        if($_POST['xEmail'] != ''){
                            $valid_email = Globales::is_valid_email($_POST['xEmail']);

                            if($valid_email == false){
                                $error++;
                                if($mensajeError != ''){
                                    $mensajeError.='<br>';
                                }
                                $mensajeError = "Email invalido";
                            }
                        }
                        
                        break;
                    default:
                        
                        break;
                }

                if($error == 0){
                    $respuesta = Model::actualizarDatosMdl($table,$columns,$columnId,$id);

                    if($respuesta ==  true){
                        $respuestaOk = true;
                        $mensajeError = "Se actualizo exitosamente.";
                    }else{
                        $mensajeError = "No se actualizo el registro";
                    }
                }
                                  
            }else{
                $mensajeError = "Parametros incorrectos";
            }
            

        }else{
            $mensajeError = "No contiene parametros validos";
        }

        $salidaJson = array('respuesta'=>$respuestaOk,
                            'mensaje'=>$mensajeError,
                            'contenido'=>$contenidoOk);

        echo json_encode($salidaJson);


    }

    public static function deleteItem($datos){

        $respuestaOk = false;
        $mensajeError = "No se puede ejecutar la aplicacion";
        $contenidoOk = '';

        if($datos['tabla'] !=''){
            $table = $datos['tabla'] ;
            if($datos['id']!=''){

                $id = $datos['id'];
                
                switch ($table) {
                    case 'persona':
                        $columnId = 'id';
                        break;
                    
                    default:
                        $columnId = 'id';
                        break;
                }

                $respuesta = Model::eliminarDatoMdl($table,$columnId,$id);

                if($respuesta ==  true){
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
                            'mensaje'=>$mensajeError,
                            'contenido'=>$contenidoOk);

        echo json_encode($salidaJson);
    }
}