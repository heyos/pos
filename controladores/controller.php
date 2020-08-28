<?php

class Controller {

    static public function dataTable($req,$columns,$table,$join,$where,$action){

        $params = [
            'table'=>$table
        ];

        if($where !=''){
            $params['where'] = $where;
        }



        switch ($action) {
            case 'data':
                
                break;
            
            case 'options':
                
                break;
            default:
                # code...
                break;
        }
    }
}