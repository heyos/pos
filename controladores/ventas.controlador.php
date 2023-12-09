<?php

require_once "controller.php";

class ControladorVentas extends Controller{

	/*=============================================
	MOSTRAR VENTAS
	=============================================*/

	static public function ctrMostrarVentas($item, $valor){

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlMostrarVentas($tabla, $item, $valor);

		return $respuesta;

	}

	/*=============================================
	CREAR VENTA
	=============================================*/

	static public function ctrCrearVenta(){

		if(isset($_POST["nuevaVenta"])){

			/*=============================================
			ACTUALIZAR LAS COMPRAS DEL CLIENTE Y REDUCIR EL STOCK Y AUMENTAR LAS VENTAS DE LOS PRODUCTOS
			=============================================*/

			if($_POST["listaProductos"] == ""){

					echo'<script>

				swal({
					  type: "error",
					  title: "La venta no se ha ejecuta si no hay productos",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then(function(result){
								if (result.value) {

								window.location = "ventas";

								}
							})

				</script>';

				return;
			}


			$listaProductos = json_decode($_POST["listaProductos"], true);

			$totalProductosComprados = array();

			foreach ($listaProductos as $key => $value) {

			   array_push($totalProductosComprados, $value["cantidad"]);
				
			   $tablaProductos = "productos";

			    $item = "id";
			    $valor = $value["id"];
			    $orden = "id";

			    $traerProducto = ModeloProductos::mdlMostrarProductos($tablaProductos, $item, $valor, $orden);

				$item1a = "ventas";
				$valor1a = $value["cantidad"] + $traerProducto["ventas"];

			    $nuevasVentas = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1a, $valor1a, $valor);

				$item1b = "stock";
				$valor1b = $value["stock"];

				$nuevoStock = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1b, $valor1b, $valor);

			}

			$tablaClientes = "clientes";

			$item = "id";
			$valor = $_POST["seleccionarCliente"];

			$traerCliente = ModeloClientes::mdlMostrarClientes($tablaClientes, $item, $valor);

			$item1a = "compras";
			$valor1a = array_sum($totalProductosComprados) + $traerCliente["compras"];

			$comprasCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item1a, $valor1a, $valor);

			$item1b = "ultima_compra";

			date_default_timezone_set('America/Lima');

			$fecha = date('Y-m-d');
			$hora = date('H:i:s');
			$valor1b = $fecha.' '.$hora;

			$fechaCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item1b, $valor1b, $valor);

			/*=============================================
			GUARDAR LA COMPRA
			=============================================*/	

			$fechaV = ($_POST["nuevaFecha"] < date("Y-m-d")) ? $_POST["nuevaFecha"]:$_POST["nuevaFecha"]." ".date("H:i:s") ;

			$tabla = "ventas";

			$datos = array("id_vendedor"=>$_POST["idVendedor"],
						   "id_cliente"=>$_POST["seleccionarCliente"],
						   "codigo"=>$_POST["nuevaVenta"],
						   "productos"=>$_POST["listaProductos"],
						   "impuesto"=>$_POST["nuevoPrecioImpuesto"],
						   "neto"=>$_POST["nuevoPrecioNeto"],
						   "total"=>$_POST["totalVenta"],
						   "metodo_pago"=>$_POST["nuevoMetodoPago"],
							"fecha"=>$fechaV);

			$arrMetodo = ['Cortesia','Tarjeta','Efectivo'];

			if( in_array($_POST["nuevoMetodoPago"], $arrMetodo)){
				$datos['fecha_pago'] = $fechaV;
			}else{
				$datos['codigo_pago'] = $_POST["codigo_pago"];
			}

			$respuesta = ModeloVentas::create($tabla, $datos);



			if($respuesta != 0){

				echo'<script>

				localStorage.removeItem("rango");

				swal({
					  type: "success",
					  title: "La venta ha sido guardada correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then(function(result){
								if (result.value) {

								window.location = "ventas";

								}
							})

				</script>';

			}
			

		}

	}

	static public function apiCreateVenta(){

		$status = false;

		if($_POST["listaProductos"] == ""){

			$message = "Debe agregar almenos un producto";
			
		}else{
			$listaProductos = json_decode($_POST["listaProductos"], true);

			$totalProductosComprados = array();

			foreach ($listaProductos as $key => $value) {

			   	array_push($totalProductosComprados, $value["cantidad"]);
				
			   	$tablaProductos = "productos";

			    $item = "id";
			    $valor = $value["id"];
			    $orden = "id";

			    $traerProducto = ModeloProductos::mdlMostrarProductos($tablaProductos, $item, $valor, $orden);

				$item1a = "ventas";
				$valor1a = $value["cantidad"] + $traerProducto["ventas"];

			    $nuevasVentas = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1a, $valor1a, $valor);

				$item1b = "stock";
				$valor1b = $traerProducto["stock"] - $value['cantidad'];

				$nuevoStock = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1b, $valor1b, $valor);

			}

			$tablaClientes = "clientes";

			$item = "id";
			$valor = $_POST["seleccionarCliente"];

			$traerCliente = ModeloClientes::mdlMostrarClientes($tablaClientes, $item, $valor);

			$item1a = "compras";
			$valor1a = array_sum($totalProductosComprados) + $traerCliente["compras"];

			$comprasCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item1a, $valor1a, $valor);

			$item1b = "ultima_compra";

			date_default_timezone_set('America/Lima');

			$fecha = date('Y-m-d');
			$hora = date('H:i:s');
			$valor1b = $fecha.' '.$hora;

			$fechaCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item1b, $valor1b, $valor);

			/*=============================================
			GUARDAR LA VENTA
			=============================================*/	

			$fechaV = ($_POST["nuevaFecha"] < date("Y-m-d")) ? $_POST["nuevaFecha"]:$_POST["nuevaFecha"]." ".date("H:i:s") ;
			$fecha_sin_hora = $_POST["nuevaFecha"];

			$tabla = "ventas";

			$datos = array(
				"id_vendedor" => $_POST["idVendedor"],
				"id_cliente" => $_POST["seleccionarCliente"],
				"codigo" => $_POST["codigo"],
				"productos" => $_POST["listaProductos"],
				"impuesto" => 0,
				"neto" => $_POST["totalVenta"],
				"total" => $_POST["totalVenta"],
				"metodo_pago" => $_POST["nuevoMetodoPago"],
				"fecha" => $fechaV
			);

			$arrMetodo = ['Cortesia','Tarjeta','Efectivo'];

			if( in_array($_POST["nuevoMetodoPago"], $arrMetodo)){
				$datos['fecha_pago'] = $fecha_sin_hora;
			}else{
				$datos['codigo_pago'] = $_POST["codigo_pago"];
			}

			$respuesta = ModeloVentas::create($tabla, $datos);

			if($respuesta != 0){

				/*
				echo '
				<script>

				localStorage.removeItem("rango");

				swal({
					type: "success",
					title: "La venta ha sido guardada correctamente",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
				}).then(function(result){
					if (result.value) {
						window.location = "ventas";
					}
				})

				</script>';
				*/

				$status = true;
				$message = "La venta ha sido guardada correctamente";

			}
		
		}

		return array(
			'status' => $status,
			'message' => $message
		);

	}


	/*=============================================
	EDITAR VENTA
	=============================================*/

	static public function ctrEditarVenta(){

		if(isset($_POST["editarVenta"])){

			/*=============================================
			FORMATEAR TABLA DE PRODUCTOS Y LA DE CLIENTES
			=============================================*/
			$tabla = "ventas";

			$item = "codigo";
			$valor = $_POST["editarVenta"];

			$traerVenta = ModeloVentas::mdlMostrarVentas($tabla, $item, $valor);

			/*=============================================
			REVISAR SI VIENE PRODUCTOS EDITADOS
			=============================================*/

			if($_POST["listaProductos"] == ""){

				$listaProductos = $traerVenta["productos"];
				$cambioProducto = false;


			}else{

				$listaProductos = $_POST["listaProductos"];
				$cambioProducto = true;
			}

			if($cambioProducto){

				$productos =  json_decode($traerVenta["productos"], true);

				$totalProductosComprados = array();

				foreach ($productos as $key => $value) {

					array_push($totalProductosComprados, $value["cantidad"]);
					
					$tablaProductos = "productos";

					$item = "id";
					$valor = $value["id"];
					$orden = "id";

					$traerProducto = ModeloProductos::mdlMostrarProductos($tablaProductos, $item, $valor, $orden);

					$item1a = "ventas";
					$valor1a = $traerProducto["ventas"] - $value["cantidad"];

					$nuevasVentas = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1a, $valor1a, $valor);

					$item1b = "stock";
					$valor1b = $value["cantidad"] + $traerProducto["stock"];

					$nuevoStock = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1b, $valor1b, $valor);

				}

				$tablaClientes = "clientes";

				$itemCliente = "id";
				$valorCliente = $_POST["seleccionarCliente"];

				$traerCliente = ModeloClientes::mdlMostrarClientes($tablaClientes, $itemCliente, $valorCliente);

				$item1a = "compras";
				$valor1a = $traerCliente["compras"] - array_sum($totalProductosComprados);

				$comprasCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item1a, $valor1a, $valorCliente);

				/*=============================================
				ACTUALIZAR LAS COMPRAS DEL CLIENTE Y REDUCIR EL STOCK Y AUMENTAR LAS VENTAS DE LOS PRODUCTOS
				=============================================*/

				$listaProductos_2 = json_decode($listaProductos, true);

				$totalProductosComprados_2 = array();

				foreach ($listaProductos_2 as $key => $value) {

					array_push($totalProductosComprados_2, $value["cantidad"]);
					
					$tablaProductos_2 = "productos";

					$item_2 = "id";
					$valor_2 = $value["id"];
					$orden = "id";

					$traerProducto_2 = ModeloProductos::mdlMostrarProductos($tablaProductos_2, $item_2, $valor_2, $orden);

					$item1a_2 = "ventas";
					$valor1a_2 = $value["cantidad"] + $traerProducto_2["ventas"];

					$nuevasVentas_2 = ModeloProductos::mdlActualizarProducto($tablaProductos_2, $item1a_2, $valor1a_2, $valor_2);

					$item1b_2 = "stock";
					$valor1b_2 = $value["stock"];

					$nuevoStock_2 = ModeloProductos::mdlActualizarProducto($tablaProductos_2, $item1b_2, $valor1b_2, $valor_2);

				}

				$tablaClientes_2 = "clientes";

				$item_2 = "id";
				$valor_2 = $_POST["seleccionarCliente"];

				$traerCliente_2 = ModeloClientes::mdlMostrarClientes($tablaClientes_2, $item_2, $valor_2);

				$item1a_2 = "compras";
				$valor1a_2 = array_sum($totalProductosComprados_2) + $traerCliente_2["compras"];

				$comprasCliente_2 = ModeloClientes::mdlActualizarCliente($tablaClientes_2, $item1a_2, $valor1a_2, $valor_2);

				$item1b_2 = "ultima_compra";

				date_default_timezone_set('America/Bogota');

				$fecha = date('Y-m-d');
				$hora = date('H:i:s');
				$valor1b_2 = $fecha.' '.$hora;

				$fechaCliente_2 = ModeloClientes::mdlActualizarCliente($tablaClientes_2, $item1b_2, $valor1b_2, $valor_2);

			}

			/*=============================================
			GUARDAR CAMBIOS DE LA COMPRA
			=============================================*/	
			//$fechaV = ($_POST["nuevaFecha"] < date("Y-m-d")) ? $_POST["nuevaFecha"]:$_POST["nuevaFecha"]." ".date("H:i:s") ;

			$datos = array("id_vendedor"=>$_POST["idVendedor"],
						   "id_cliente"=>$_POST["seleccionarCliente"],
						   //"codigo"=>$_POST["editarVenta"],
						   "productos"=>$listaProductos,
						   "impuesto"=>$_POST["nuevoPrecioImpuesto"],
						   "neto"=>$_POST["nuevoPrecioNeto"],
						   "total"=>$_POST["totalVenta"],
						   "metodo_pago"=>$_POST["nuevoMetodoPago"]);

			$arrMetodo = ['Cortesia','Tarjeta','Efectivo'];

			if( in_array($_POST["nuevoMetodoPago"], $arrMetodo)){
				$datos['fecha_pago'] = date("Y-m-d");
			}else{
				$datos['fecha_pago'] = 'null';
				$datos['codigo_pago'] = isset($_POST["codigo_pago"]) ? $_POST["codigo_pago"] : '';
			}

			$datos['where'] = array(
				['codigo',$_POST["editarVenta"]]
			);


			$respuesta = ModeloVentas::update($tabla, $datos);

			if($respuesta == 1){

				echo'<script>

				localStorage.removeItem("rango");

				swal({
					  type: "success",
					  title: "La venta ha sido editada correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then((result) => {
								if (result.value) {

								window.location = "ventas";

								}
							})

				</script>';

			}

		}

	}


	/*=============================================
	ELIMINAR VENTA
	=============================================*/

	static public function ctrEliminarVenta(){

		if(isset($_GET["idVenta"])){

			$tabla = "ventas";

			$item = "id";
			$valor = $_GET["idVenta"];

			$traerVenta = ModeloVentas::mdlMostrarVentas($tabla, $item, $valor);

			/*=============================================
			ACTUALIZAR FECHA ÚLTIMA COMPRA
			=============================================*/

			$tablaClientes = "clientes";

			$itemVentas = null;
			$valorVentas = null;

			$traerVentas = ModeloVentas::mdlMostrarVentas($tabla, $itemVentas, $valorVentas);

			$guardarFechas = array();

			foreach ($traerVentas as $key => $value) {
				
				if($value["id_cliente"] == $traerVenta["id_cliente"]){

					array_push($guardarFechas, $value["fecha"]);

				}

			}

			if(count($guardarFechas) > 1){

				if($traerVenta["fecha"] > $guardarFechas[count($guardarFechas)-2]){

					$item = "ultima_compra";
					$valor = $guardarFechas[count($guardarFechas)-2];
					$valorIdCliente = $traerVenta["id_cliente"];

					$comprasCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item, $valor, $valorIdCliente);

				}else{

					$item = "ultima_compra";
					$valor = $guardarFechas[count($guardarFechas)-1];
					$valorIdCliente = $traerVenta["id_cliente"];

					$comprasCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item, $valor, $valorIdCliente);

				}


			}else{

				$item = "ultima_compra";
				$valor = "0000-00-00 00:00:00";
				$valorIdCliente = $traerVenta["id_cliente"];

				$comprasCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item, $valor, $valorIdCliente);

			}

			/*=============================================
			FORMATEAR TABLA DE PRODUCTOS Y LA DE CLIENTES
			=============================================*/

			$productos =  json_decode($traerVenta["productos"], true);

			$totalProductosComprados = array();

			foreach ($productos as $key => $value) {

				array_push($totalProductosComprados, $value["cantidad"]);
				
				$tablaProductos = "productos";

				$item = "id";
				$valor = $value["id"];
				$orden = "id";

				$traerProducto = ModeloProductos::mdlMostrarProductos($tablaProductos, $item, $valor, $orden);

				$item1a = "ventas";
				$valor1a = $traerProducto["ventas"] - $value["cantidad"];

				$nuevasVentas = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1a, $valor1a, $valor);

				$item1b = "stock";
				$valor1b = $value["cantidad"] + $traerProducto["stock"];

				$nuevoStock = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1b, $valor1b, $valor);

			}

			$tablaClientes = "clientes";

			$itemCliente = "id";
			$valorCliente = $traerVenta["id_cliente"];

			$traerCliente = ModeloClientes::mdlMostrarClientes($tablaClientes, $itemCliente, $valorCliente);

			$item1a = "compras";
			$valor1a = $traerCliente["compras"] - array_sum($totalProductosComprados);

			$comprasCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item1a, $valor1a, $valorCliente);

			/*=============================================
			ELIMINAR VENTA
			=============================================*/

			$respuesta = ModeloVentas::mdlEliminarVenta($tabla, $_GET["idVenta"]);

			if($respuesta == "ok"){

				echo'<script>

				swal({
					  type: "success",
					  title: "La venta ha sido borrada correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then(function(result){
								if (result.value) {

								window.location = "ventas";

								}
							})

				</script>';

			}		
		}

	}

	/*=============================================
	RANGO FECHAS
	=============================================*/	

	static public function ctrRangoFechasVentas($fechaInicial, $fechaFinal){

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlRangoFechasVentas($tabla, $fechaInicial, $fechaFinal);

		return $respuesta;
		
	}

	/*=============================================
	DESCARGAR EXCEL
	=============================================*/

	public function ctrDescargarReporte(){

		if(isset($_GET["reporte"])){

			$tabla = "ventas";

			if(isset($_GET["fechaInicial"]) && isset($_GET["fechaFinal"])){

				$ventas = ModeloVentas::mdlRangoFechasVentas($tabla, $_GET["fechaInicial"], $_GET["fechaFinal"]);

			}else{

				$item = null;
				$valor = null;

				$ventas = ModeloVentas::mdlMostrarVentas($tabla, $item, $valor);

			}


			/*=============================================
			CREAMOS EL ARCHIVO DE EXCEL
			=============================================*/

			$Name = $_GET["reporte"].'.xls';

			header('Expires: 0');
			header('Cache-control: private');
			header("Content-type: application/vnd.ms-excel"); // Archivo de Excel
			header("Cache-Control: cache, must-revalidate"); 
			header('Content-Description: File Transfer');
			header('Last-Modified: '.date('D, d M Y H:i:s'));
			header("Pragma: public"); 
			header('Content-Disposition:; filename="'.$Name.'"');
			header("Content-Transfer-Encoding: binary");

			echo utf8_decode("<table border='0'> 

					<tr> 
					<td style='font-weight:bold; border:1px solid #eee;'>CÓDIGO</td> 
					<td style='font-weight:bold; border:1px solid #eee;'>CLIENTE</td>
					<td style='font-weight:bold; border:1px solid #eee;'>VENDEDOR</td>
					<td style='font-weight:bold; border:1px solid #eee;'>CANTIDAD</td>
					<td style='font-weight:bold; border:1px solid #eee;'>PRODUCTOS</td>
					<td style='font-weight:bold; border:1px solid #eee;'>IMPUESTO</td>
					<td style='font-weight:bold; border:1px solid #eee;'>NETO</td>		
					<td style='font-weight:bold; border:1px solid #eee;'>TOTAL</td>		
					<td style='font-weight:bold; border:1px solid #eee;'>METODO DE PAGO</td	
					<td style='font-weight:bold; border:1px solid #eee;'>FECHA</td>		
					</tr>");

			foreach ($ventas as $row => $item){

				$cliente = ControladorClientes::ctrMostrarClientes("id", $item["id_cliente"]);
				$vendedor = ControladorUsuarios::ctrMostrarUsuarios("id", $item["id_vendedor"]);

			 echo utf8_decode("<tr>
			 			<td style='border:1px solid #eee;'>".$item["codigo"]."</td> 
			 			<td style='border:1px solid #eee;'>".$cliente["nombre"]."</td>
			 			<td style='border:1px solid #eee;'>".$vendedor["nombre"]."</td>
			 			<td style='border:1px solid #eee;'>");

			 	$productos =  json_decode($item["productos"], true);

			 	foreach ($productos as $key => $valueProductos) {
			 			
			 			echo utf8_decode($valueProductos["cantidad"]."<br>");
			 		}

			 	echo utf8_decode("</td><td style='border:1px solid #eee;'>");	

		 		foreach ($productos as $key => $valueProductos) {
			 			
		 			echo utf8_decode($valueProductos["descripcion"]."<br>");
		 		
		 		}

		 		echo utf8_decode("</td>
					<td style='border:1px solid #eee;'>$ ".number_format($item["impuesto"],2)."</td>
					<td style='border:1px solid #eee;'>$ ".number_format($item["neto"],2)."</td>	
					<td style='border:1px solid #eee;'>$ ".number_format($item["total"],2)."</td>
					<td style='border:1px solid #eee;'>".$item["metodo_pago"]."</td>
					<td style='border:1px solid #eee;'>".substr($item["fecha"],0,10)."</td>		
		 			</tr>");


			}


			echo "</table>";

		}

	}


	/*=============================================
	SUMA TOTAL VENTAS
	=============================================*/

	static public function ctrSumaTotalVentas(){

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlSumaTotalVentas($tabla);

		return $respuesta;

	}

	/*=============================================
	GANANCIA VENTAS
	=============================================*/

	public static function ctrGananciaVentas($fechaInicial, $fechaFinal){
		
		$salida = "";

		$tabla = "ventas";

		$totalVendidoCategoria = array();
		$totalPrecioCompraCategoria = array();
		$gananciaCategoria = array();
		$arrayKey = array();

		//$respuesta = ModeloVentas::mdlRangoFechasVentas($tabla, $fechaInicial, $fechaFinal);

		$params = array(
			'table' => $tabla,
			'columns' => '*',
			'where' => array(
				["fecha_pago BETWEEN '".$fechaInicial."' AND '".$fechaFinal."'"]
			)
		);

		$respuesta = ModeloVentas::all($params);
		$data = $respuesta['data'];

		if(count($data) > 0){
			
			foreach ($data as $row) {
				
				$productos = json_decode($row["productos"],true);
				$metodo_pago = $row['metodo_pago'];

				foreach ($productos as $value) {

					$totalPrecioCompra = 0;

					$id = $value["id"];
					$cantidadVendida = $value["cantidad"];
					$totalVendido = $value["total"];
					
					$datosProd = ModeloProductos::mdlMostrarProductosDetalle("productos AS p, categorias AS c", $id);

					if(!empty($datosProd[1])){

						$categoria =  $datosProd[1];

						$arrayKey[] = $categoria;

						$precioCompra = isset($value["precioCompra"]) ? $value["precioCompra"] : $datosProd[2];
						$totalPrecioCompra = $precioCompra*$cantidadVendida;
						$totalVendido = $metodo_pago == 'Cortesia' ? 0 : $totalVendido;
						$ganancia = $totalVendido - $totalPrecioCompra;

						$totalVendidoCategoria[] = array($categoria=>$totalVendido);
						$totalPrecioCompraCategoria[] = array($categoria=>$totalPrecioCompra);
						$gananciaCategoria[] = array($categoria=>$ganancia);

						$precioCompra = 0;

					}
				}

			}

			$arrayUniqueKey = array_unique($arrayKey);

			

			$totalPC = 0;
			$totalVendido = 0;
			$ganancia = 0;

			foreach ($arrayUniqueKey as $value) {

				$salida .='
					<tr>
						<td>'.$value.'</td>
						<td align="right">'.array_sum(array_column($totalPrecioCompraCategoria, $value)).'</td>
						<td align="right">'.array_sum(array_column($totalVendidoCategoria, $value)).'</td>
						<td align="right">'.array_sum(array_column($gananciaCategoria, $value)).'</td>
					</tr>
				';

				$totalPC += array_sum(array_column($totalPrecioCompraCategoria, $value));
				$totalVendido += array_sum(array_column($totalVendidoCategoria, $value));
				$ganancia += array_sum(array_column($gananciaCategoria, $value));
				
			}
					

			$salida .='
					<tr>
						<td><strong>Total</strong></td>
						<td align="right">'.$totalPC.'</td>
						<td align="right">'.$totalVendido.'</td>
						<td align="right">'.number_format($ganancia,2).'</td>
					</tr>
			';

			$gastado = GastosController::getTotalGasto($fechaInicial,$fechaFinal, 'ganancia');

			$salida .='
					<tr>
						<td><strong>Ganancia consumida</strong></td>
						<td align="right"></td>
						<td align="right"></td>
						<td align="right">'.number_format($gastado,2).'</td>
					</tr>
			';

			$saldo = $ganancia - $gastado;

			$salida .='
					<tr>
						<td><strong>Saldo</strong></td>
						<td align="right"></td>
						<td align="right"></td>
						<td align="right">'.number_format($saldo,2).'</td>
					</tr>
			';

		}else{
			$salida = '
				<tr>
                    <td colspan="4">Sin registros</td>
                </tr>
			';
		}


		echo $salida;
	}

	public static function capitalAcumulado($fechaInicial, $fechaFinal,$all = false){

		$tabla = "ventas";

		//$fechaInicial = date('Y-m-').'01';
		//$fechaFinal = date('Y-m-d');
		$totalPrecioCompra = 0;
		$arrayTotalCategoria = array();
		$categoria = "";

		$term = '';

		if($all){
			$fechaFinal = date('Y-m-d');
			$fechaFinal = date('Y-m-d',strtotime('-1day',strtotime($fechaFinal)));
			$term = sprintf("fecha_pago <= '%s'",$fechaFinal);
		}else{
			$term = sprintf("fecha_pago BETWEEN '%s' AND '%s'",$fechaInicial,$fechaFinal);
		}

		$params = array(
			'table' => $tabla,
			'columns' => '*',
			'where' => array(
				[$term]
			)
		);

		$respuesta = ModeloVentas::all($params);

		if(count($respuesta) > 0){

			foreach ($respuesta['data'] as $key => $row) {
				
				$productos = json_decode($row["productos"],true);

				foreach ($productos as $key => $value) {

					$precioCompra = 0;

					$id = $value["id"];
					$cantidadVendida = $value["cantidad"];
										
					$datosProd = ModeloProductos::mdlMostrarProductosDetalle("productos AS p, categorias AS c", $id);

					if(!empty($datosProd[1])){

						$precioCompra = (isset($value["precioCompra"]))?$value["precioCompra"]:$datosProd[2];
						$totalPrecioCompra += $precioCompra*$cantidadVendida;
						$total = $precioCompra*$cantidadVendida;

						$categoria = $datosProd[1];
						if(array_key_exists($categoria,$arrayTotalCategoria)){
							$arrayTotalCategoria[$categoria] = $arrayTotalCategoria[$categoria] + $total;
						}else{
							$arrayTotalCategoria[$categoria] = $total;
						}
					
					}
				}

			}

		}

		return $arrayTotalCategoria;

	}

	public static function metodosPago($metodo){

		$arr = [
			'Efectivo','Credito','Cortesia','Tarjeta'
		];

		$contenido = '
			<option value="">Seleccionar Metodo</option>
		';

		foreach ($arr as $item) {
			$selected = $metodo == $item ? 'selected' : '';

			$contenido .= '
				<option value="'.$item.'" '.$selected.'>'.$item.'</option>
			';
		}

		return $contenido;
	}

	static public function codigo(){

        $compra = ModeloVentas::lastRow('ventas');
        $codigo = 1000;
        $band = true;

        if(!empty($compra)){
            $codigo = $compra['codigo'] + 1;

            while ($band) {

                $params = array(
                    'where'=> array(['codigo',$codigo])
                );

                $datos = ModeloVentas::firstOrAll('ventas',$params,'first');

                if(!empty($datos)){
                    $codigo ++;
                }else{
                    $band = false;
                }
            }
        }

        return $codigo;
    }

}