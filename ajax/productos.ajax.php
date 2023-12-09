<?php

require_once "../controladores/productos.controlador.php";
require_once "../modelos/productos.modelo.php";

require_once "../controladores/categorias.controlador.php";
require_once "../modelos/categorias.modelo.php";



class AjaxProductos{

  /*=============================================
  GENERAR CÓDIGO A PARTIR DE ID CATEGORIA
  =============================================*/
  public $idCategoria;

  public function ajaxCrearCodigoProducto(){

  	$item = "id_categoria";
  	$valor = $this->idCategoria;
    $orden = "id";

  	$respuesta = ControladorProductos::ctrMostrarProductos($item, $valor, $orden);

  	echo json_encode($respuesta);

  }


  /*=============================================
  EDITAR PRODUCTO
  =============================================*/ 

  public $idProducto;
  public $traerProductos;
  public $nombreProducto;
  public $params;
  public $accion;

  public function ajaxEditarProducto(){

    if($this->traerProductos == "ok"){

      $item = null;
      $valor = null;
      $orden = "id";

      $respuesta = ControladorProductos::ctrMostrarProductos($item, $valor,$orden);

      echo json_encode($respuesta);


    }else if($this->nombreProducto != ""){

      $item = "descripcion";
      $valor = $this->nombreProducto;
      $orden = "id";

      $respuesta = ControladorProductos::ctrMostrarProductos($item, $valor,$orden);

      echo json_encode($respuesta);

    }else{
      $item = "id";
      $valor = $this->idProducto;
      $orden = "id";

      $respuesta = ControladorProductos::ctrMostrarProductos($item, $valor,$orden);

      echo json_encode($respuesta);
    }

  }

  public function addList(){

    switch ($this->accion) {
      case 'data':

        if(array_key_exists('codigo',$this->params)){
          //agregar desde codigo de barra
          $item = "codigo";
          $valor = $this->params['codigo'];
          $orden = "codigo";

        }else{
          //agregar desde lista de productos
          $item = "id";
          $valor = $this->params['id'];
          $orden = "id";
        }                

        $respuesta = ControladorProductos::ctrShowProducto($item, $valor,$orden);

        break;

      case 'data_producto':

        if(array_key_exists('codigo',$this->params)){
          //agregar desde codigo de barra
          $item = "codigo";
          $valor = $this->params['codigo'];
          $orden = "codigo";

        }else{
          //agregar desde lista de productos
          $item = "id";
          $valor = $this->params['id'];
          $orden = "id";
        }                

        $respuesta = ControladorProductos::getProducto($item, $valor,$orden);

      break;

      case 'filterCompra':

        $lista = json_decode($this->params['lista'],true);
        $contenido = '';

        foreach ($lista as $detalle) {
          $contenido .= ControladorProductos::ctrViewProducto($detalle);
        }

        $respuesta = array('data' => $contenido,$lista );
        
        break;
      
      default:
        $respuesta = [];
        break;
    }

    

    echo json_encode($respuesta);

    

  }

  public function getBarcodeList(){
    $params = $this->params;

    $data = json_decode($params['data'],true);
    ControladorProductos::barcodePdf($data);

  }

  public function getListProductos(){

    $item = null;
    $valor = null;
    $orden = "id";

    $productos = ControladorProductos::ctrMostrarProductos($item, $valor, $orden);
    $status = count($productos) > 0 ? true : false;

    echo json_encode(
      array(
        'status' => $status,
        'data' => $productos
      )
    );

  }

}


/*=============================================
GENERAR CÓDIGO A PARTIR DE ID CATEGORIA
=============================================*/	

if(isset($_POST["idCategoria"])){

	$codigoProducto = new AjaxProductos();
	$codigoProducto -> idCategoria = $_POST["idCategoria"];
	$codigoProducto -> ajaxCrearCodigoProducto();

}
/*=============================================
EDITAR PRODUCTO
=============================================*/ 

if(isset($_POST["idProducto"])){

  $editarProducto = new AjaxProductos();
  $editarProducto -> idProducto = $_POST["idProducto"];
  $editarProducto -> ajaxEditarProducto();

}

/*=============================================
TRAER PRODUCTO
=============================================*/ 

if(isset($_POST["traerProductos"])){

  $traerProductos = new AjaxProductos();
  $traerProductos -> traerProductos = $_POST["traerProductos"];
  $traerProductos -> ajaxEditarProducto();

}

/*=============================================
TRAER PRODUCTO
=============================================*/ 

if(isset($_POST["nombreProducto"])){

  $traerProductos = new AjaxProductos();
  $traerProductos -> nombreProducto = $_POST["nombreProducto"];
  $traerProductos -> ajaxEditarProducto();

}

/*=============================================
TRAER PRODUCTO
=============================================*/ 

if(isset($_POST["accion"])){

  $traerProductos = new AjaxProductos();
  $traerProductos -> params = $_POST;
  $traerProductos -> accion = $_POST["accion"];

  switch ($_POST["accion"]) {
    case 'barcode':
      $traerProductos -> getBarcodeList();
      break;
    case 'data_productos':
      $traerProductos -> getListProductos();
      break;
    default:
      $traerProductos -> addList();
      break;
  }
  

}






