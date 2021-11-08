<?php
session_start();

require_once "../controladores/globales.php";
require_once "../controladores/proveedor.controlador.php";
require_once "../modelos/proveedor.modelo.php";

class TablaProveedor{

  public $request;

  public function showDataTable(){

    $columns = "id,
                razon_social,
                ruc,
                CONCAT(representante,' - ',telefono) AS representante_detalle,
                dia_visita,
                pedido_minimo,
                descripcion"; //columnas

    
    $searchColumns = ['razon_social','ruc']; //columnas donde generar la busqueda
    $orderColumns = [
      0 => 'id',
      1 => 'razon_social',
      2 => 'ruc',
      3 => 'dia_visita',
      4 => 'representante_detalle'
    ]; //columnas para ordenar

    $params = array(
      "table"=>"proveedor",
      "columns"=>$columns,
      "searchColumns"=>$searchColumns,
      "orderColumns" => $orderColumns,
      "order" => 'dia_visita',
      "dir" => "ASC"
            
    );

    $options = ProveedorController::dataTable($this->request,$params,'options');
    $records = ProveedorController::dataTable($this->request,$params,'data');
    //$records = array();
    // $test = Controller::dataTable($this->request,$params,'');
    $data = [];

    
    //-------------------------------------------------

    if(count($records) > 0){

      $i = 0;

      // procesando la data para mostrarla en el front
      $id = 0;
      $ruc = '-';
      $razon_social = '';
      $dia_visita = '';
      $dia_visita_des = '';
      $representante = '';
      $descripcion = '';
      $pedido_minimo = '';
      
      foreach ($records as $row) {

        $i++;
        $id = $row['id'];
        $ruc = $row['ruc'] ? $row['ruc'] : '';
        $razon_social = $row['razon_social'];
        $razon_social = $ruc != '' ? $razon_social.' / '.$ruc : $razon_social;
        $dia_visita = $row['dia_visita'];
        $dia_visita_des = Globales::nombre_dia($dia_visita);
        $representante = $row['representante_detalle'];
        $descripcion = $row['descripcion'];
        $pedido_minimo = $row['pedido_minimo'];
        $button = '';

        if($_SESSION['perfil'] == 'Administrador'){
          
          $button =  "
                <div class='btn-group'>
                  <button class='btn btn-warning btnEditarProveedor btn-sm' id='".$id."'><i class='fa fa-pencil'></i></button>
                  <button class='btn btn-danger btnEliminarProveedor btn-sm' id='".$id."'><i class='fa fa-times'></i></button>
                </div>
          ";

        }
        
        

        $data[] = array(
          "DT_RowIndex" => $i,
          "razon_social" => $razon_social,
          "descripcion" => $descripcion,
          "dia_visita" =>$dia_visita_des,
          "representante_detalle" =>$representante,
          "pedido_minimo" => $pedido_minimo,
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


if(isset($_GET)){
    $data = new TablaProveedor();
    $data -> request = $_GET;
    $data -> showDataTable();
}
// $request = $_GET;

// echo json_encode($request);
