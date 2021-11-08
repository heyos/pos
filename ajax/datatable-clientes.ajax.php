<?php

require_once "../controladores/clientes.controlador.php";
require_once "../modelos/clientes.modelo.php";

class TablaProductosCompras{

  public $request;

  public function showDataTable(){

    session_start();

    $columns = 'id,nombre,documento,email, telefono, compras, ultima_compra,direccion'; //columnas
    $searchColumns = ['nombre','documento','telefono','email']; //columnas donde generar la busqueda
    $orderColumns = [
      0 => 'id',
      1 => 'nombre',
      2 => 'documento',
    ]; //columnas para ordenar

    $params = array(
            "table"=>"clientes",
            "columns"=>$columns,
            "searchColumns"=>$searchColumns,
            "orderColumns" => $orderColumns
    );

    $options = ControladorClientes::dataTable($this->request,$params,'options');
    $records = ControladorClientes::dataTable($this->request,$params,'data');
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
            <button class="btn btn-warning btnEditarCliente" data-toggle="modal" data-target="#modalEditarCliente" idCliente="'.$row["id"].'">
              <i class="fa fa-pencil"></i>
            </button>
        ';

        if($_SESSION["perfil"] == "Administrador"){

            $button .= '
            <button class="btn btn-danger btnEliminarCliente" idCliente="'.$row["id"].'">
              <i class="fa fa-times"></i>
            </button>';

        }

        $button .= '
          </div>
        ';

        $data[] = array(
          "DT_RowIndex" => $i,
          "nombre" => $row['nombre'],
          "documento" => $row['documento'],
          "email" => $row['email'],
          "telefono" => $row['telefono'],
          "direccion" => $row['direccion'],
          "compras" => $row['compras'],
          "ultima_compra" => $row['ultima_compra'],
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
    $data = new TablaProductosCompras();
    $data -> request = $_GET;
    $data -> showDataTable();
}

