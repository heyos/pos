<?php

class Controller {

    static public function dataTable($req,$params,$action){

        $like = "";
        $where = array_key_exists("where", $params) ? $params['where']:"";

        if(!empty($req['search']['value'])){

            if(array_key_exists("searchColumns", $params)){
                if(count($params['searchColumns']) > 0){
                    
                    foreach ($params['searchColumns'] as $column) {
                        $like .= $column." LIKE '%".$req['search']['value']."%' OR";
                    }
                    $like =  substr($like, 0,-2);
                    
                    $params['search'] = sprintf(" (%s) ",$like);
                    
                }
            }
                
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