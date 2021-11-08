<?php

require_once "../controladores/reporte_capital.controlador.php";
require_once "../modelos/reporte_capital.modelo.php";

class DatatableAjax{

  public $request;

  public function showDataTable(){

    session_start();

    $columns = 'id,capital,f_inicio,f_fin,activo'; //columnas
    $searchColumns = []; //columnas donde generar la busqueda
    $orderColumns = [
      0 => 'id',
      4 => 'activo',
    ]; //columnas para ordenar

    $params = array(
            "table"=>"reporte_capital",
            "columns"=>$columns,
            "searchColumns"=>$searchColumns,
            "orderColumns" => $orderColumns
    );

    $options = ReporteCapitalController::dataTable($this->request,$params,'options');
    $records = ReporteCapitalController::dataTable($this->request,$params,'data');
    // $records = array();
    // $test = Controller::dataTable($this->request,$params,'');
    $data = [];

    
    $lista = [];
    $class = '';
    
    
    if(count($records) > 0){

      $i =0;

      // procesando la data para mostrarla en el front
      foreach ($records as $row) {

        $i++;
        
        $button = '
          <div class="btn-group">
            <button class="btn btn-warning btnEditarCliente"  id="'.$row["id"].'">
              <i class="fa fa-pencil"></i>
            </button>
        ';

        if($_SESSION["perfil"] == "Administrador"){

            $button .= '
            <button class="btn btn-danger btnEliminarCliente" idCliente="'.$row["id"].'">
              <i class="fa fa-pencil"></i>
            </button>';

        }

        $button .= '
          </div>
        ';

        $data[] = array(
          "DT_RowIndex" => $i,
          "capital" => $row['capital'],
          "f_inicio" => $row['f_inicio'],
          "f_fin" => $row['f_fin'],
          "activo" => $row['activo'],
          "action" => $button
        );

      }

    }else{
      $data[] = array(
          "DT_RowIndex" => 1,
          "codigo" => '',
          "imagen" => '',
          "descripcion" =>$test,
          "stock" => '',
          "action" => ''
      );
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

