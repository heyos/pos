<?php

require_once "../controladores/gastos.controller.php";
require_once "../modelos/gastos.model.php";

class DatatableAjax{

  public $request;

  public function showDataTable(){

    session_start();

    $columns = '
    id,
    descripcion, 
    tipo_gasto, 
    detalle_gasto,
    fecha,
    importe'; //columnas

    $searchColumns = ['tipo_gasto','descripcion','fecha']; //columnas donde generar la busqueda
    $orderColumns = [
      0 => 'id',
      1 => 'tipo_gasto',
      2 => 'descripcion',
      4 => 'fecha',
    ]; //columnas para ordenar

    $params = array(
      "table"=>"gastos",
      "columns"=>$columns,
      "searchColumns"=>$searchColumns,
      "orderColumns" => $orderColumns,
      "order" => 'fecha',
      "dir" => 'DESC'
    );

    $options = GastosController::dataTable($this->request,$params,'options');
    $records = GastosController::dataTable($this->request,$params,'data');
    // $records = array();
    // $test = Controller::dataTable($this->request,$params,'');
    $data = [];

    
    $lista = [];
    $class = '';
    
    
    if(count($records) > 0){

      $i =0;
      $id = 0;
      $deuda = 0;
      $detallePago = [];
      $ultimo = [];

      // procesando la data para mostrarla en el front
      foreach ($records as $row) {

        $id = $row['id'];
        $deuda = 0;
        
        $i++;

        $button = '
          <div class="btn-group">
            
        ';

        if($_SESSION["perfil"] == "Administrador"){

            $button .= '
            <button class="btn btn-warning btn-sm btn-editar-gasto" id="'.$id.'">
              <i class="fa fa-pencil"></i>
            </button>
            <button class="btn btn-success btn-sm btn-detalle-gasto" id="'.$id.'" detalle="'.$row['detalle_gasto'].'">
              <i class="fa fa-file-text-o"></i>
            </button>
            <button class="btn btn-danger btn-sm btn-eliminar-gasto" id="'.$id.'">
              <i class="fa fa-times"></i>
            </button>';

        }

        $button .= '
          </div>
        ';

        $data[] = array(
          "DT_RowIndex" => $i,
          "tipo_gasto" => $row['tipo_gasto'],
          "descripcion" => $row['descripcion'],
          "importe" => $row['importe'],
          "fecha" => $row['fecha'],
          "action" => $button
        );

      }

    }else{
      // $data[] = array(
      //     "DT_RowIndex" => 1,
      //     "nombre" => '',
      //     "telefono" => '',
      //     "deuda_total" =>$test,
      //     "ultimo_pago" => '',
      //     "action" => ''
      // );
    }

    $options['data'] = $data;

    echo json_encode($options);

  }

}

if(isset($_GET)){
    $data = new DatatableAjax();
    $data -> request = $_GET;
    $data -> showDataTable();
}

