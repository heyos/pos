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
                return $qTotal;
                break;
        }
    }
}