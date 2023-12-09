<?php

require_once "controller.php";

class ControladorProductos extends Controller{

	/*=============================================
	MOSTRAR PRODUCTOS
	=============================================*/

	static public function ctrMostrarProductos($item, $valor, $orden){

		$tabla = "productos";

		$respuesta = ModeloProductos::mdlMostrarProductos($tabla, $item, $valor, $orden);

		return $respuesta;

	}

	static public function ctrShowProducto($item, $valor, $orden){

		$out = "";
		
		$tabla = "productos";
		$producto = ModeloProductos::mdlMostrarProductos($tabla, $item, $valor, $orden);

		$idProducto = 0;
		$descripcion = "";
		$stock = 0;
		$precioCompra = 0;
		$params = [];

		if(!empty($producto)){

			$idProducto = $producto['id'];
			$descripcion = $producto['descripcion'];
			$cantidad = 1;
			$precioCompra = $producto['precio_compra'];

			$params = [
					'id' => '0',
					'producto_id' => $idProducto,
					'descripcion' => $descripcion,
					'cantidad' => 1,
					'precio_compra' => $precioCompra,
					'old_precio' => $precioCompra,
					'sub_total' => $precioCompra
			];

			$out = self::ctrViewProducto($params);
			
		}

		$respuesta = [
			"stock"=> $stock,
			"contenido"=>$out,
			"idProducto"=>$idProducto
		] ;

		return $respuesta;

	}

	public static function ctrViewProducto($proDetalle){

		$idDetalle = $proDetalle['id'];
		$idProducto = $proDetalle['producto_id'];
		$cantidad = $proDetalle['cantidad'];
		$precioCompra = $proDetalle['precio_compra'];
		$oldPrecio = $proDetalle['old_precio'];
		$subTotal = $proDetalle['sub_total'];

		if(!array_key_exists('descripcion',$proDetalle)){

			$tabla = "productos";
			$item = 'id';
			$valor = $idProducto;
			$orden = 'id';
			$producto = ModeloProductos::mdlMostrarProductos($tabla, $item, $valor, $orden);
			$descripcion = $producto['descripcion'];

		}else{
			$descripcion = $proDetalle['descripcion'];
		}

		$respuesta = '
			<div class="row rowProducto" id="'.$idProducto.'" style="padding:5px 15px">
				<div class="col-xs-5" style="padding-right:0px">
	          		<div class="input-group">
		              	<span class="input-group-addon">
		              	<button type="button" class="btn btn-danger btn-xs quitarProducto" idProducto="'.$idProducto.'"><i class="fa fa-times"></i></button>
		              	</span>
		              	<input type="text" class="form-control nuevaDescripcionProducto" idProducto="'.$idProducto.'" idDetalle="'.$idDetalle.'"
		              	 value="'.$descripcion.'" readonly required>
		            </div>
		        </div>

	          	<div class="col-xs-2">
	            	<input type="number" class="form-control nuevaCantidadProducto" step="any" idProducto = "'.$idProducto.'"
	             	value="'.$cantidad.'" required>
	            </div>

	            <div class="col-xs-2" style="padding-left:0px">
	            	<input type="text" class="form-control nuevoPrecioProducto" oldPrecio="'.$oldPrecio.'"  idProducto = "'.$idProducto.'"
	             	value="'.$precioCompra.'" required>
	            </div>

	        	<div class="col-xs-3 ingresoPrecio" style="padding-left:0px">
	        		<div class="input-group">
	        			<span class="input-group-addon"><i class="ion ion-social-usd"></i></span>
		                <input type="text" idProducto="'.$idProducto.'" class="form-control  nuevoTotalProducto" value="'.$subTotal.'" required>
		 			</div>
	            </div>
	        </div>
		';

		return $respuesta;
	}

	static public function getProducto($item, $valor, $orden){

		$status = false;
		$message = "";
		
		$tabla = "productos";
		$producto = ModeloProductos::mdlMostrarProductos($tabla, $item, $valor, $orden);

		$idProducto = 0;
		$descripcion = "";
		$stock = 0;
		$precioCompra = 0;
		$params = [];

		if(!empty($producto)){

			
			$idProducto = $producto['id'];
			$descripcion = $producto['descripcion'];
			$cantidad = 1;
			$precioCompra = $producto['precio_compra'];
			$stock = $producto['stock'];

			if($stock > 0){
				$status = true;
				$params = [
					'id' => $idProducto,
					'descripcion' => $descripcion,
					'cantidad' => 1, //cantidad vendida
					'stock' => $stock,
					'precio' => $producto['precio_venta'],
					'precioCompra' => $precioCompra,
					'total' => $producto['precio_venta']
				];
			}else{
				$message = $descripcion.": Stock insuficiente";
			}

		}

		$respuesta = [
			"status" => $status,
			"data"=>$params,
			"idProducto"=>$idProducto,
			"message" => $message
		] ;

		return $respuesta;

	}

	/*=============================================
	CREAR PRODUCTO
	=============================================*/

	static public function ctrCrearProducto(){

		if(isset($_POST["nuevaDescripcion"])){

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevaDescripcion"]) &&
			   preg_match('/^[0-9.]+$/', $_POST["nuevoPrecioCompra"]) &&
			   preg_match('/^[0-9.]+$/', $_POST["nuevoPrecioVenta"])){

		   		/*=============================================
				VALIDAR IMAGEN
				=============================================*/

			   	$ruta = "vistas/img/productos/default/anonymous.png";

			   	if(isset($_FILES["nuevaImagen"]["tmp_name"])){

					list($ancho, $alto) = getimagesize($_FILES["nuevaImagen"]["tmp_name"]);

					$nuevoAncho = 500;
					$nuevoAlto = 500;

					/*=============================================
					CREAMOS EL DIRECTORIO DONDE VAMOS A GUARDAR LA FOTO DEL USUARIO
					=============================================*/

					$directorio = "vistas/img/productos/".$_POST["nuevoCodigo"];

					mkdir($directorio, 0755);

					/*=============================================
					DE ACUERDO AL TIPO DE IMAGEN APLICAMOS LAS FUNCIONES POR DEFECTO DE PHP
					=============================================*/

					if($_FILES["nuevaImagen"]["type"] == "image/jpeg"){

						/*=============================================
						GUARDAMOS LA IMAGEN EN EL DIRECTORIO
						=============================================*/

						$aleatorio = mt_rand(100,999);

						$ruta = "vistas/img/productos/".$_POST["nuevoCodigo"]."/".$aleatorio.".jpg";

						$origen = imagecreatefromjpeg($_FILES["nuevaImagen"]["tmp_name"]);						

						$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

						imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

						imagejpeg($destino, $ruta);

					}

					if($_FILES["nuevaImagen"]["type"] == "image/png"){

						/*=============================================
						GUARDAMOS LA IMAGEN EN EL DIRECTORIO
						=============================================*/

						$aleatorio = mt_rand(100,999);

						$ruta = "vistas/img/productos/".$_POST["nuevoCodigo"]."/".$aleatorio.".png";

						$origen = imagecreatefrompng($_FILES["nuevaImagen"]["tmp_name"]);						

						$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

						imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

						imagepng($destino, $ruta);

					}

				}

				$tabla = "productos";

				$datos = array("id_categoria" => $_POST["nuevaCategoria"],
							   "codigo" => $_POST["nuevoCodigo"],
							   "descripcion" => $_POST["nuevaDescripcion"],
							   "stock" => $_POST["nuevoStock"],
							   "precio_compra" => $_POST["nuevoPrecioCompra"],
							   "precio_venta" => $_POST["nuevoPrecioVenta"],
							   "imagen" => $ruta);

				$respuesta = ModeloProductos::mdlIngresarProducto($tabla, $datos);

				if($respuesta == "ok"){

					echo'<script>

						swal({
							  type: "success",
							  title: "El producto ha sido guardado correctamente",
							  showConfirmButton: true,
							  confirmButtonText: "Cerrar"
							  }).then(function(result){
										if (result.value) {

										window.location = "productos";

										}
									})

						</script>';

				}


			}else{

				echo'<script>

					swal({
						  type: "error",
						  title: "¡El producto no puede ir con los campos vacíos o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {

							window.location = "productos";

							}
						})

			  	</script>';
			}
		}

	}

	/*=============================================
	EDITAR PRODUCTO
	=============================================*/

	static public function ctrEditarProducto(){

		if(isset($_POST["editarDescripcion"])){

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarDescripcion"]) &&
			   preg_match('/^[0-9.]+$/', $_POST["editarStock"]) &&	
			   preg_match('/^[0-9.]+$/', $_POST["editarPrecioCompra"]) &&
			   preg_match('/^[0-9.]+$/', $_POST["editarPrecioVenta"])){

		   		/*=============================================
				VALIDAR IMAGEN
				=============================================*/

			   	$ruta = $_POST["imagenActual"];

			   	if(isset($_FILES["editarImagen"]["tmp_name"]) && !empty($_FILES["editarImagen"]["tmp_name"])){

					list($ancho, $alto) = getimagesize($_FILES["editarImagen"]["tmp_name"]);

					$nuevoAncho = 500;
					$nuevoAlto = 500;

					/*=============================================
					CREAMOS EL DIRECTORIO DONDE VAMOS A GUARDAR LA FOTO DEL USUARIO
					=============================================*/

					$directorio = "vistas/img/productos/".$_POST["editarCodigo"];

					/*=============================================
					PRIMERO PREGUNTAMOS SI EXISTE OTRA IMAGEN EN LA BD
					=============================================*/

					if(!empty($_POST["imagenActual"]) && $_POST["imagenActual"] != "vistas/img/productos/default/anonymous.png"){

						unlink($_POST["imagenActual"]);

					}else{

						mkdir($directorio, 0755);	
					
					}
					
					/*=============================================
					DE ACUERDO AL TIPO DE IMAGEN APLICAMOS LAS FUNCIONES POR DEFECTO DE PHP
					=============================================*/

					if($_FILES["editarImagen"]["type"] == "image/jpeg"){

						/*=============================================
						GUARDAMOS LA IMAGEN EN EL DIRECTORIO
						=============================================*/

						$aleatorio = mt_rand(100,999);

						$ruta = "vistas/img/productos/".$_POST["editarCodigo"]."/".$aleatorio.".jpg";

						$origen = imagecreatefromjpeg($_FILES["editarImagen"]["tmp_name"]);						

						$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

						imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

						imagejpeg($destino, $ruta);

					}

					if($_FILES["editarImagen"]["type"] == "image/png"){

						/*=============================================
						GUARDAMOS LA IMAGEN EN EL DIRECTORIO
						=============================================*/

						$aleatorio = mt_rand(100,999);

						$ruta = "vistas/img/productos/".$_POST["editarCodigo"]."/".$aleatorio.".png";

						$origen = imagecreatefrompng($_FILES["editarImagen"]["tmp_name"]);						

						$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

						imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

						imagepng($destino, $ruta);

					}

				}

				$tabla = "productos";

				$datos = array("id_categoria" => $_POST["editarCategoria"],
							   "codigo" => $_POST["editarCodigo"],
							   "descripcion" => $_POST["editarDescripcion"],
							   "stock" => $_POST["editarStock"],
							   "precio_compra" => $_POST["editarPrecioCompra"],
							   "precio_venta" => $_POST["editarPrecioVenta"],
							   "imagen" => $ruta,
							   "id" => $_POST['editarId']
							);

				$respuesta = ModeloProductos::mdlEditarProducto($tabla, $datos);

				if($respuesta == "ok"){

					echo'<script>

						swal({
							  type: "success",
							  title: "El producto ha sido editado correctamente",
							  showConfirmButton: true,
							  confirmButtonText: "Cerrar"
							  }).then(function(result){
										if (result.value) {

										window.location = "productos";

										}
									})

						</script>';

				}


			}else{

				echo'<script>

					swal({
						  type: "error",
						  title: "¡El producto no puede ir con los campos vacíos o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {

							window.location = "productos";

							}
						})

			  	</script>';
			}
		}

	}

	/*=============================================
	BORRAR PRODUCTO
	=============================================*/
	static public function ctrEliminarProducto(){

		if(isset($_GET["idProducto"])){

			$tabla ="productos";
			$datos = $_GET["idProducto"];

			if($_GET["imagen"] != "" && $_GET["imagen"] != "vistas/img/productos/default/anonymous.png"){

				unlink($_GET["imagen"]);
				rmdir('vistas/img/productos/'.$_GET["codigo"]);

			}

			$respuesta = ModeloProductos::mdlEliminarProducto($tabla, $datos);

			if($respuesta == "ok"){

				echo'<script>

				swal({
					  type: "success",
					  title: "El producto ha sido borrado correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then(function(result){
								if (result.value) {

								window.location = "productos";

								}
							})

				</script>';

			}		
		}


	}

	/*=============================================
	MOSTRAR SUMA VENTAS
	=============================================*/

	static public function ctrMostrarSumaVentas(){

		$tabla = "productos";

		$respuesta = ModeloProductos::mdlMostrarSumaVentas($tabla);

		return $respuesta;

	}


	static public function barcodePdf($data){
		
		require '../extensiones/fpdf/fpdf.php';

		if(count($data) > 0){
			$pdf = new FPDF('L','mm',array(50,30));
			$pdf->SetAutoPageBreak(false);

			foreach ($data as $item) {
				$pdf->AddPage();
				
				$pdf->Image($item['barcode'], 4, 7, 42, 9);

				$pdf->SetFont('Arial', '', 6);

				$pdf->SetXY(3, 18);
				$texto = ucfirst($item['descripcion']).' : S/ '.number_format($item['precio_venta'],2,'.','');
				// $pdf->Cell(44, 2, $texto, 0, 0, 'C');
				$pdf->MultiCell(44, 2, $texto, 0, 'C');
			}

			$pdf->Output("I","documento.pdf");

			foreach ($data as $item) {
				unlink($item['barcode']);
			}
		}
			
	}


}