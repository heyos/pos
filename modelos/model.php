<?php

require 'conexion.php';

class Model {

    static public function all($params){

        $where = "";
        $str = "";
        $column = "";
        $value = "";
        $signo = "";

        if(array_key_exists('where', $params)){

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

            $query = Conexion::conectar()->prepare("SELECT * FROM $params['tabla'] WHERE $where");

        }else{
            $query = Conexion::conectar()->prepare("SELECT * FROM $params['tabla']");
        }

        $query -> execute();

        return $query -> fetchAll();
       
        $query -> close();
    }

    static public function filtered($params){

    }
}