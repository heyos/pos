<?php

error_reporting(1);

$mostrar = "";

if(isset($_GET["fechaInicial"])){

    $fechaInicial = $_GET["fechaInicial"];
    $fechaFinal = $_GET["fechaFinal"];

}else{

$fechaInicial = date("Y-m-d");
$fechaFinal = date("Y-m-d");

}

if($fechaInicial == $fechaFinal){
    $mostrar = " - [".$fechaInicial."]";
}else{
    $mostrar = " - [".$fechaInicial." y ".$fechaFinal."]";
}

?>

<!--=====================================
GRÁFICO DE VENTAS
======================================-->


<div class="box box-solid ">
    
    <div class="box-header">
        
        <i class="fa fa-th"></i>

        <h3 class="box-title">Ganancia de Ventas <?php echo $mostrar; ?></h3>

    </div>

    <div class="box-body border-radius-none">

        <table class="table table-bordered table-condensed">
            <thead>
                <tr>
                    <th>Categoria</th>
                    <th>Capital Compra</th>
                    <th>Total Vendido</th>
                    <th>Ganancia</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    ControladorVentas::ctrGananciaVentas($fechaInicial, $fechaFinal);
                ?>
                
            </tbody>
            
        </table>

                

    </div>

</div>