<?php

require_once __DIR__ ."/../extensiones/barcode/vendor/autoload.php";
use BarcodeBakery\Common\BCGColor;
use BarcodeBakery\Common\BCGDrawing;
use BarcodeBakery\Common\BCGFontFile;
use BarcodeBakery\Common\BCGLabel;
use BarcodeBakery\Barcode\BCGean13;
use BarcodeBakery\Barcode\BCGcode128;

$status = false;
$data = [];

if(isset($_REQUEST['data'])){
	
	$colorFront = new BCGColor(0, 0, 0);
	$colorBack = new BCGColor(255, 255, 255);

	$font = new BCGFontFile(__DIR__ . '/../extensiones/barcode/vendor/font/Arial.ttf', 10);
	
	$code = new BCGcode128();
	$code->setScale(8);
	$code->setThickness(10); // modifica el alto
	$code->setForegroundColor($colorFront); // color de las barras
	$code->setBackgroundColor($colorBack); // color de fondo
	$code->setFont($font);
	
	$data = json_decode($_REQUEST['data'],true);

	$count_ok = 0;
	
	foreach ($data as $key => $item) {
		$codigo = $item['codigo'];
		$code->parse($codigo);
		$barcode = $code;
		$drawing = new BCGDrawing($code, $colorBack);

		$file = "ajax/barcode/".$codigo.".png";
		$drawing->finish(BCGDrawing::IMG_FORMAT_PNG,"../".$file);
		
		if(is_file("../".$file)){

			$data[$key]['barcode'] = "barcode/".$codigo.".png";
			$count_ok ++;

		}
		
	}

	$status = count($data) == $count_ok ? true : false;
	$message = $status ? "" : "No se procesaron todos los codigos";

}else{
	$message = "Parametros incorrectos";
}	

echo json_encode(
	array(
		'status' => $status,
		'message' => $message,
		'data' => $data
	)
);