<?php

require_once __DIR__ .'/../extensiones/tcpdf_v2/pdf/tcpdf_include.php';
require_once __DIR__ .'/../extensiones/tcpdf_v2/tcpdf.php';
$data = isset($_REQUEST['data']) ? json_decode($_REQUEST['data'],true) : [];

if(count($data) > 0){
	// $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf = new TCPDF();

	foreach ($data as $item) {
		$pdf->AddPage('L',[50,30]);
				
		$texto = ucfirst($item['descripcion']).' : '.number_format($item['precio_venta'],0,2,'.');
		$src = "../ajax/".$item['barcode'];
		$html = '<div>
				<img style="width: 450px" src="'.$src.'">
				<span style="font-size:2px">'.$texto.'</span>
			</div>';
		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->lastPage();
	}

	$pdf->Output("documento.pdf","I");

	foreach ($data as $item) {
		//unlink("../ajax/".$item['barcode']);
	}

	
}else{
	echo "parametros incorrectos";
}