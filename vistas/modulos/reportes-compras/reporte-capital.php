<?php

$response = ReporteCapitalController::showReporteCapital(['viewReport' => true]);
$desde = date('d/m/Y',strtotime($response['desde']));
$hasta = date('d/m/Y',strtotime($response['hasta']));
$mostrar = 'desde '.$desde.' hasta '.$hasta;

?>

<div class="box box-success ">
    
    <div class="box-header with-border">
        
        <i class="fa fa-th"></i>

        <h3 class="box-title">Capital <?php echo $mostrar; ?></h3>

    </div>

    <div class="box-body border-radius-none">

        <table class="table table-bordered table-condensed">
            <thead>
                <tr>
                    <th>Categoria</th>
                    <th>Saldo Anterior</th>
                    <th>Capital Acumulado</th>
                    <th>Capital Gastado</th>
                    <th>SubTotal</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    
                    $total = 0;
                    
                    foreach ($response['data'] as $categoria => $data) {
                        echo '
                            <tr>
                                <td>'.$categoria.'</td>
                                <td>'.$data['inicio'].'</td>
                                <td>'.$data['acumulado'].'</td>
                                <td>'.$data['gastado'].'</td>
                                <td>'.number_format($data['total'],1).'</td>
                            </tr>
                        ';
                        //$tot = number_format($data['total'],1);
                        $total += round(doubleval($data['total']),1);
                    }

                    
                ?>

                <tr>
                    <td colspan="4">
                        <strong>Total</strong>
                    </td>
                    <td><?php echo number_format($total,2); ?></td>
                </tr>
                <?php
                $fechaInicial = date("Y-m")."-01";
                $fechaFinal = ControladorVentas::getLastDayMonth($fechaInicial);
                $ventas = ControladorVentas::ctrGananciaVentas($fechaInicial, $fechaFinal);
                $ganancia = $ventas['saldo'];
                ?>
                <tr>
                    <td colspan="4">
                        <strong>Ganancia [<?php echo $fechaInicial." hasta ".$fechaFinal; ?>]</strong>
                    </td>
                    <td><?php echo number_format($ganancia,1); ?></td>
                </tr>
                <?php
                $efectivo = $total+$ganancia;
                ?>
                <tr>
                    <td colspan="4">
                        <strong>Efectivo en caja</strong>
                    </td>
                    <td><?php echo number_format($efectivo,1); ?></td>
                </tr>
            </tbody>
            
        </table>

                

    </div>

</div>