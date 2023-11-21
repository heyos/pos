<?php

$response = ReporteCapitalController::showReporteCapital();
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
                                <td>'.number_format($data['total'],2).'</td>
                            </tr>
                        ';

                        $total += floatval($data['total']);
                    }

                    
                ?>

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Total</td>
                    <td><?php echo number_format($total,2); ?></td>
                </tr>
                
            </tbody>
            
        </table>

                

    </div>

</div>