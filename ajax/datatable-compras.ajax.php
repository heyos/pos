<?php

require_once "../controladores/productos.controlador.php";
require_once "../modelos/productos.modelo.php";
require_once "../controladores/controller.php";
require_once "../modelos/model.php";


class TablaProductosCompras{

  public $request;

  public function showDataTable(){

    $columns = ['id','imagen','codigo','descripcion','stock']; //columnas
    $searchColumns = ['codigo','descripcion']; //columnas donde generar la busqueda
    $orderColumns = [
      0 => 'id',
      2 => 'codigo',
      3 => 'descripcion',
      4 => 'stock'
    ]; //columnas para ordenar

    $params = array(
            "table"=>"productos",
            "columns"=>$columns,
            "searchColumns"=>$searchColumns,
            "orderColumns" => $orderColumns
    );

    $options = Controller::dataTable($this->request,$params,'options');
    $records = Controller::dataTable($this->request,$params,'data');
    // $records = array();
    $test = Controller::dataTable($this->request,$params,'');
    $data = [];

    if(count($records) > 0){

      $i =0;

      // procesando la data para mostrarla en el front
      foreach ($records as $row) {

        $i++;
        $imagen = "<img src='".$row["imagen"]."' width='40px'>";

        if($row["stock"] <= 10){

          $stock = "<button class='btn btn-danger'>".$row["stock"]."</button>";

        }else if($row["stock"] > 11 && $row["stock"] <= 15){

          $stock = "<button class='btn btn-warning'>".$row["stock"]."</button>";

        }else{

          $stock = "<button class='btn btn-success'>".$row["stock"]."</button>";

        }

        $button =  "<div class='btn-group'><button class='btn btn-primary agregarProducto recuperarBoton' idProducto='".$row["id"]."'>Agregar</button></div>";

        $data[] = array(
          "DT_RowIndex" => $i,
          "codigo" => $row['codigo'],
          "imagen" => $imagen,
          "descripcion" =>$row['descripcion'],
          "stock" => $stock,
          "action" => $button
        );

      }

    }else{
      // $data[] = array(
      //     "DT_RowIndex" => 1,
      //     "codigo" => '',
      //     "imagen" => '',
      //     "descripcion" =>$test,
      //     "stock" => '',
      //     "action" => ''
      // );
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
