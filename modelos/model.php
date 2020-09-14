<?php

require_once 'conexion.php';

class Model {

    static public function all($params){

        $where = "";
        $join = "";
        $str = "";
        $column = "";
        $value = "";
        $signo = "";
        $limit = "";
        $orderBy = "";

        if(array_key_exists('where', $params)){

            if(count($params['where']) > 0){
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
                $where = sprintf(" WHERE %s ",$where);

                if(array_key_exists("search",$params)){
                    $where .= " AND ";
                }
            }

        }

        if(array_key_exists("search",$params)){

            if(!empty($where)){
                $where .= sprintf(" %s ",$params['search']);
            }else{
                $where .= sprintf(" WHERE %s ",$params['search']);
            }
            
        }

        if(array_key_exists("order",$params) && array_key_exists("dir",$params)){
            if (!empty($params['order']) && !empty($params['dir'])) {
                $orderBy = sprintf(" ORDER BY %s %s",$params['order'],$params['dir']);
            }
        }

        if(array_key_exists("start",$params) && array_key_exists("length",$params)){
            $limit = sprintf(" LIMIT %d,%d ",$params['start'],$params['length']);
        }

        $sql = sprintf("SELECT * FROM %s %s %s %s",
                        $params['table'],$where,$orderBy,$limit);

        $query = Conexion::conectar()->prepare($sql);

        $query -> execute();

        return $query -> fetchAll();
       
        $query -> close();
    }

    static public function firstOrAll($table, $params){

        $where = "";

        if(array_key_exists("where",$params)){

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
                $where = sprintf(" WHERE %s ",$where);

            }

        }

        $sql = sprintf("SELECT * FROM %s %s",$table,$where);

        if(array_key_exists('order',$params) && array_key_exists('dir',$params)){
            $sql .= sprintf(" ORDER BY %s %s ",$params['order'],$params['dir']);
        }

        $stmt = Conexion::conectar()->prepare($sql);

        if($stmt -> execute()){
            
            switch ($params['data']) {
                case 'first':
                    return $stmt -> fetch();
                    break;
                
                default://all
                    return $stmt -> fetchAll();
                    break;
            }
            
            
        }else{
            echo Conexion::conectar()->errorInfo();
        }

        $stmt -> close();

        $stmt = null;

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
        }

        $con = Conexion::conectar();

        $sql = sprintf("INSERT INTO %s (%s) VALUES (%s) ",$table,$columns,$values);
        $query = $con -> prepare($sql);

        if($query->execute()) {

            $id = $con->lastInsertId();

        }else {
            return $query -> errorInfo()[2];
        }

        $con->close();

        return $id;

    }

}