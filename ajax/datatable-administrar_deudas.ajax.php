<?php

require_once "../controladores/ventas.controlador.php";
require_once "../controladores/pago_deuda.controlador.php";
require_once "../controladores/detalle_pago_deuda.controlador.php";
require_once "../modelos/ventas.modelo.php";
require_once "../modelos/pago_deuda.modelo.php";
require_once "../modelos/detalle_pago_deuda.modelo.php";

class DatatableAjax{

  public $request;

  public function showDataTable(){

    session_start();

    $columns = '
    v.id AS idVenta, 
    v.id_cliente AS idCliente , 
    c.nombre AS nombre,
    c.telefono AS telefono,
    SUM(v.total) AS total'; //columnas

    $join = array(
      array('clientes c','c.id','v.id_cliente')
    );

    $searchColumns = ['nombre','telefono']; //columnas donde generar la busqueda
    $orderColumns = [
      0 => 'idVenta',
      1 => 'nombre',
      2 => 'telefono',
    ]; //columnas para ordenar

    $where = array(
      ['fecha_pago is NULL']
    );

    $params = array(
      "table"=>"ventas v",
      "columns"=>$columns,
      "searchColumns"=>$searchColumns,
      "orderColumns" => $orderColumns,
      "join" => $join,
      "where" => $where,
      "group" => 'v.id_cliente',
      "order" => 'nombre',
      "dir" => 'ASC'
    );

    $options = ControladorVentas::dataTable($this->request,$params,'options');
    $records = ControladorVentas::dataTable($this->request,$params,'data');
    // $records = array();
    // $test = Controller::dataTable($this->request,$params,'');
    $data = [];

    
    $lista = [];
    $class = '';
    
    
    if(count($records) > 0){

      $i =0;
      $idCliente = 0;
      $deuda = 0;
      $detallePago = [];
      $ultimo = [];

      // procesando la data para mostrarla en el front
      foreach ($records as $row) {

        $idCliente = $row[1];
        $deuda = 0;
        //obtener ventas con deuda
        $argsVentas = array(
          'columns' => '*',
          'table'=>'ventas',
          'where' => array(
            ['id_cliente',$idCliente],
            ['fecha_pago is NULL']
          )
        );

        $arrVentas = ModeloVentas::all($argsVentas);

        if(count($arrVentas) > 0){

          foreach ($arrVentas as $venta) {
            $args = array(
              'tabla'=>'detalle_pago_deuda',
              'where' => array(
                ['venta_id',$venta['id']],
                ['ultimo','1']
              )
            );

            $detalle = DetallePagoDeudaController::itemDetail($args);

            if($detalle['respuesta']){
              $detallePago = $detalle['contenido'];
              $deuda += $detallePago['saldo'];

            }else{
              $deuda += $venta['total'];
            }
          }

          $deuda = number_format($deuda,2,'.','');
        }

        $argsUltimo = array(
          'columns' => '*',
          'table'=>'pago_deuda',
          'where' => array(
            ['cliente_id',$idCliente],
          ),
          'start' => '0',
          'length' => '1',
          'order' => 'id',
          'dir' => 'DESC'
        );

        $ultimo_pago = '---';
        $arrUltimo = PagoDeudaModel::all($argsUltimo);

        if(count($arrUltimo) > 0){
          $ultimo = $arrUltimo[0];
          $ultimo_pago = 'Fecha: '.date('d/m/Y',strtotime($ultimo['fecha_pago'])).' | importe: '.number_format($ultimo['importe'],2,'.','');
        }

        $i++;

        $button = '
          <div class="btn-group">
            
        ';

        if($_SESSION["perfil"] == "Administrador"){

            $button .= '
            <button class="btn btn-success btn-sm btn-openRegistro" id="'.$row[1].'" nombre="'.$row[2].'">
              <i class="fa fa-file-text-o"></i>
            </button>';

        }

        $button .= '
          </div>
        ';

        $data[] = array(
          "DT_RowIndex" => $i,
          "nombre" => $row[2],
          "telefono" => $row[3],
          "deuda_total" => $deuda,
          "ultimo_pago" => $ultimo_pago,
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

