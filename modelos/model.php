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

        if(array_key_exists('where', $params) || array_key_exists('join', $params)){

            if(count($params['where']) > 0){
                foreach ($params['where'] as $str => $val) {

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

        // if(array_key_exists("order",$params) && array_key_exists("dir",$params)){
        if (!empty($params['order']) && !empty($params['dir'])) {
            $orderBy = sprintf(" ORDER BY %s %s",$params['order'],$params['dir']);
        }
        // }

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

    static public function filtered($params){

    }
}