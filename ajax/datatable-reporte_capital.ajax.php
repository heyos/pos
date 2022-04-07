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
      "orderColumns" => $orderColumns,
      "order" => 'activo',
      "dir" => 'ASC'
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

      $arrActivo = array(
        '0' => array(
          'icon' => 'fa fa-times',
          'color' => 'label-danger'
        ),
        '1' => array(
          'icon' => 'fa fa-check',
          'color' => 'label-success'
        )
      );

      $labelActivo = "";

      // procesando la data para mostrarla en el front
      foreach ($records as $row) {

        $i++;

        $activo = array_key_exists($row['activo'],$arrActivo) ? $arrActivo[$row['activo']] : $arrActivo['0'] ;
        $labelActivo = '<span class="label '.$activo['color'].'"><i class="'.$activo['icon'].'"></i></span>';
        
        $button = '
          <div class="btn-group">
            
        ';

        if($_SESSION["perfil"] == "Administrador"){

            $button .= '
            <button class="btn btn-success btn-sm btn-openRegistro" id="'.$row['id'].'" 
            data-type="detalle" idCliente="'.$row["id"].'">
              <i class="fa fa-file-text-o"></i>
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
          "activo" => $labelActivo,
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

