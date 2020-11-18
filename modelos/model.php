<?php

require_once 'conexion.php';

class Model {

    static public function all($params){

        $where = "";
        $join = "";
        $str = "";
        $column = "";
        $table = "";
        $value = "";
        $signo = "";
        $limit = "";
        $orderBy = "";

        //JOIN
        if(array_key_exists('join',$params)){

            if(is_array($params['join']) && count($params['join']) > 0){

                foreach ($params['join'] as $val) {

                    $str = "";
                    
                    switch (count($val)) {
                        case 3:
                            
                            $str = sprintf(" %s ON %s = %s ",$val[0],$val[1],$val[2]);
                            
                            break;
                            
                        default:
                            $str = $val[0];
                            break;
                    }
                    
                    $join .= sprintf(" %s ",$str);
                    
                }

            }elseif (!empty($params['join'])) {
                $join = $params['join'];
            }

            if(!empty($join)){
                $join = ' JOIN '.$join;
            }

        }

        //--------------------------------------------------

        //WHERE
        if(array_key_exists('where', $params)){

            if(is_array($params['where']) && count($params['where']) > 0){
                foreach ($params['where'] as $val) {

                    $str = "";
                    $column = "";
                    $value = "";
                    $signo = "";

                    switch (count($val)) {
                        case 3:
                            $column = $val[0];
                            $signo = $val[1];
                            $value = $val[2];

                            $str = sprintf(" %s %s '%s'",$column,$signo,$value);
                            
                            break;
                            
                        case 2:
                            $column = $val[0];
                            $value = $val[1];
                            $str = sprintf(" %s = '%s'",$column,$value);
                            break;

                        default:
                            $str = $val[0];
                            break;
                    }
                    
                    $where .= sprintf(" %s AND",$str);
                }

                $where = substr($where, 0,-3);
                $where = sprintf(" %s ",$where);

                if(array_key_exists("search",$params)){
                    $where .= " AND ";
                }

            }elseif (!empty($params['where'])) {
                $where = $params['where'];
            }

        }

        if(array_key_exists("search",$params)){

            if(!empty($where)){
                $where .= sprintf(" %s ",$params['search']);
            }else{
                $where .= sprintf(" %s ",$params['search']);
            }
            
        }

        if(!empty($where)){
            $where = ' AND '.$where;
        }
        //-------------------------------------------------------------------
        //FIN WHERE
        //-------------------------------------------------------------------

        //ORDER BY
        if(array_key_exists("order",$params) && array_key_exists("dir",$params)){
            if (!empty($params['order']) && !empty($params['dir'])) {
                $orderBy = sprintf(" ORDER BY %s %s",$params['order'],$params['dir']);
            }
        }
        //----------------------------------------------------

        //LIMIT
        if(array_key_exists("start",$params) && array_key_exists("length",$params)){
            $limit = sprintf(" LIMIT %d,%d ",$params['start'],$params['length']);
        }
        //----------------------------------------------------

        $columns = array_key_exists('columns',$params) ? $params['columns']:'*';

        $deletedParam = 'deleted';

        if(!empty($join)){
            $arr = explode(" ", $params['table']);
            $deletedParam = trim($arr[1]).'.deleted'; //REFENCIA AL "AS" DE LA TABLA
        }

        $sql = sprintf("SELECT %s FROM %s %s WHERE %s = '0' %s %s %s",
                        $columns,$params['table'],$join,$deletedParam,$where,$orderBy,$limit);

        $query = Conexion::conectar()->prepare($sql);

        $data = [];
        //echo $sql;
        
        if($query -> execute()){

            $data = $query -> fetchAll();

        }

        $query = null;

        return $data;       
        
    }

    static public function firstOrAll($table, $params, $data){

        $where = "";

        if(array_key_exists("where",$params)){

            if(is_array($params['where'])){

                if(count($params['where']) > 0){
                    
                    foreach ($params['where'] as $val) {

                        $str = "";
                        $column = "";
                        $signo = "";
                        $value = "";

                        switch (count($val)) {
                            case 3:
                                $column = $val[0];
                                $signo = $val[1];
                                $value = $val[2];
                                
                                $str = sprintf(" %s %s '%s'",$column,$signo,$value);
                                
                                break;
                                
                            case 2:
                                $column = $val[0];
                                $value = $val[1];
                                
                                $str = sprintf(" %s = '%s'",$column,$value);
                                break;
                        }
                        
                        $where .= sprintf(" %s AND",$str);
                    }

                    $where = substr($where, 0,-3);
                                      
                }

                if(!empty($where)){
                    $where = ' AND '.$where;
                }

            }elseif($data=='first'){

                $where = " LIMIT 1";
            }

        }
        
        $sql = sprintf("SELECT * FROM %s WHERE deleted = '0' %s",$table,$where);

        if(array_key_exists('order',$params) && array_key_exists('dir',$params)){
            $sql .= sprintf(" ORDER BY %s %s ",$params['order'],$params['dir']);
        }

        $stmt = Conexion::conectar()->prepare($sql);

        if($stmt -> execute()){
            
            switch ($data) {
                case 'first':
                    return $stmt -> fetch();
                    break;
                
                default://all
                    return $stmt -> fetchAll();
                    break;
            }
            
            
        }else{
            echo $stmt->errorInfo()[2];
        }

        $stmt = null;

    }

    static public function createOrUpdate($table,$params){

        if(array_key_exists('where',$params)){

            $response = self::firstOrAll($table,$params,'first');

            unset($params['where']);

            if(!empty($response)){
                $id = $response['id'];
                $params['id'] = $id;

                return self::update($table,$params);

            }elseif(array_key_exists('id',$params)){
                
                unset($params['id']);
                
                return self::create($table,$params);
                 
            }else{
                return self::create($table,$params);
            }

        }elseif(array_key_exists('id',$params)){

            if($params['id'] == 0){
                unset($params['id']);
                return self::create($table,$params);
            }else{
                return self::update($table,$params);
            }

        }else{
            return self::create($table,$params);
        }

    }

    static public function create($table,$params){

        $columns = '';
        $values = '';
        $id = 0;
        
        if(count($params) > 0){
            
            foreach ($params as $key => $item) {

                if($item == ''){
                    $item = 'null';
                    $values .= sprintf(" %s, ",$item);
                }else{
                    $item = Globales::sanearData($item);
                    $values .= sprintf(" '%s', ",$item);
                }

                $columns .= $key.', ';
                
            }

            $columns = substr($columns, 0,-2);
            $values = substr($values, 0,-2);

            $con = Conexion::conectar();

            $sql = sprintf("INSERT INTO %s (%s) VALUES (%s) ",$table,$columns,$values);
            $query = $con -> prepare($sql);

            if($query->execute()) {

                $id = $con->lastInsertId();

            }else {
                echo $query -> errorInfo()[2];
            }
        
        }else{
            echo "DB :>> Parametros invalidos";
        }


        $con = null;
        $query = null;

        return $id;

    }

    static public function update($table,$params){

        $columns = '';
        $values = '';
       
        $id = 0;

        $response = 0;

        if(array_key_exists('id',$params)){
            
            $id = $params['id'];

            unset($params['id']);

            if($id != 0){

                if(count($params) > 0){
                    foreach ($params as $key => $item) {

                        if($item == ''){
                            $item = 'null';
                            $values .= sprintf(" %s=%s, ",$key,$item);
                        }else{
                            $item = Globales::sanearData($item);
                            $values .= sprintf(" %s='%s', ",$key,$item);
                        }

                    }

                    $values = substr($values, 0,-2);

                    $con = Conexion::conectar();

                    $sql = sprintf("UPDATE %s SET %s WHERE id = '%d' ",$table,$values,$id);
                    $query = $con -> prepare($sql);

                    if($query->execute()) {

                        $response = $id;

                    }else {
                        echo $query -> errorInfo()[2];
                    }
                }

            }else{
                echo "DB >> ID no puede ser 0";
            }
        
        }else{
            echo "DB >> no existe un ID";
        }
        
        
        $con = null;
        $query = null;

        return $response;
    }

    static public function delete($table,$id,$type){

        $sql = "";
        $response = 0;

        switch ($type) {
            case 'logic':
                $sql = sprintf("UPDATE %s SET deleted = '1' WHERE id = :id ",$table);
                break;
            case 'force':
                $sql = sprintf("DELETE FROM %s WHERE id = :id ",$table);
                break;
            default:
                
                break;
        }

        $con = Conexion::conectar();
        $query = $con->prepare($sql);

        $query -> bindParam(':id',$id,PDO::PARAM_INT);

        if($query->execute()){
            $response = 1;
        }else{
            echo $query -> errorInfo()[2];
        }

        $sql = null;
        $query = null;
        $con = null;

        return $response;

    }

    static public function lastRow($table){

        $salida = [];
        $con = Conexion::conectar();
        $sql = sprintf("SELECT * FROM %s ORDER BY id DESC LIMIT 1",$table);
        $query = $con->prepare($sql);

        if($query->execute()){

            $salida = $query->fetch(PDO::FETCH_ASSOC);

        }else{
            echo $query -> errorInfo()[2];
        }

        $con = null;
        $query = null;
        $sql = null;

        return $salida;

    }

}