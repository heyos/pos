<?php
session_start();

require_once "../controladores/compras.controlador.php";
require_once "../modelos/compras.modelo.php";

class TablaProductosCompras{

  public $request;

  public function showDataTable(){

    $columns = "c.id AS id,
                c.codigo AS codigo,
                c.proveedor_id AS proveedor_id,
                p.razon_social AS proveedor_name,
                c.total AS total,
                CONCAT(c.fecha,' ',c.hora) AS fecha_hora"; //columnas

    if(array_key_exists('fechas',$this->request)){ 


    }

    $join = array(
      array('proveedor p','c.proveedor_id','p.id')
    );

    $searchColumns = ['codigo','descripcion']; //columnas donde generar la busqueda
    $orderColumns = [
      0 => 'id',
      1 => 'codigo',
      2 => 'proveedor_name',
      4 => 'fecha_hora'
    ]; //columnas para ordenar

    $params = array(
            "table"=>"compra c",
            "columns"=>$columns,
            "searchColumns"=>$searchColumns,
            "orderColumns" => $orderColumns,
            "join"=>$join
    );

    $options = ComprasController::dataTable($this->request,$params,'options');
    $records = ComprasController::dataTable($this->request,$params,'data');
    //$records = array();
    // $test = Controller::dataTable($this->request,$params,'');
    $data = [];

    
    //-------------------------------------------------

    if(count($records) > 0){

      $i =0;

      // procesando la data para mostrarla en el front
      $id = 0;
      $proveedor_id = 0;
      $proveedor_name = '';
      $codigo = '';
      $fecha_hora = '';
      $total = 0;

      foreach ($records as $row) {

        $i++;
        $id = $row[0];
        $codigo = $row[1];
        $proveedor_name = $row[3];
        $total = $row[4];
        $fecha_hora = $row[5];

        if($_SESSION['perfil'] == 'Administrador'){
          
          $button =  "
                <div class='btn-group'>
                  <button class='btn btn-warning btnEditarCompra btn-sm' idCompra='".$row["id"]."'><i class='fa fa-pencil'></i></button>
                  <button class='btn btn-danger btnEliminarCompra btn-sm' idCompra='".$row["id"]."'><i class='fa fa-times'></i></button>
                </div>
          ";

        }
        
        

        $data[] = array(
          "DT_RowIndex" => $i,
          "codigo" => $codigo,
          "proveedor_name" => $proveedor_name,
          "total" =>$total,
          "fecha_hora" => $fecha_hora,
          "action" => $button
        );

      }

    }else{
      // $var = '';
      
      // $data[] = array(
      //     "DT_RowIndex" => '',
      //     "codigo" => '',
      //     "proveedor_name" => $var,
      //     "total" =>'',
      //     "fecha_hora" => '',
      //     "action" => ''
      //   );
    }

    $options['data'] = $data;

    echo json_encode($options);

  }

}

/*=============================================
ACTIVAR TABLA DE PRODUCTOS
=============================================*/ 
// $activarProductosVentas = new TablaProductosCompras();
// $activarProductosVentas -> mostrarTablaProductosCompras();


if(isset($_GET)){
    $data = new TablaProductosCompras();
    $data -> request = $_GET;
    $data -> showDataTable();
}
// $request = $_GET;

// echo json_encode($request);
