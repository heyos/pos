<?php

if($_SESSION["perfil"] == "Especial"){

  echo '<script>

    window.location = "inicio";

  </script>';

  return;

}

?>

<div class="content-wrapper">

  <section class="content-header">
    
    <h1>
      
      Administrar deudas
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar deudas</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tabla-deudas" width="100%">
         
        <thead>
         
         <tr>
           
           <th style="width:10px">#</th>
           <th>Nombre</th>
           <th>Teléfono</th>
           <th>Deuda Total</th>
           <th>Último pago</th>
           <th>Acciones</th>

         </tr> 

        </thead>

        <tbody></tbody>

       </table>

      </div>

    </div>

  </section>

</div>

<!--=====================================
MODAL AGREGAR CLIENTE
======================================-->

<div id="modalPago" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <!--=====================================
      CABEZA DEL MODAL
      ======================================-->

      <div class="modal-header" style="background:#3c8dbc; color:white">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title">Registrar Pago - [<span id="nombre"></span>]</h4>

      </div>

      <!--=====================================
      CUERPO DEL MODAL
      ======================================-->

      <div class="modal-body">
        
        <div class="row">
          <div class="col-md-2"></div>
          <div class="col-md-8 col-sm-12">
            <div class="small-box bg-yellow">
        
              <div class="inner">
                <h3 id="deuda_total">$489.50</h3>
                <p>Deuda Total</p>
              </div>
              
              <div class="icon">
                <i class="ion ion-social-usd"></i>
              </div>
              
            </div>
          </div>
          <div class="col-md-2"></div>
        </div>
            
        <form role="form" method="post">
          <div class="box-body">
            <div class="form-group">
              <label class="col-sm-3 control-label">
                Ingresar importe
              </label>
              <div class="col-sm-8">
                <div class="input-group input-group-sm">
                  <input type="text" id="importe" class="form-control decimal">
                  <input type="hidden" id="deuda" class="form-control">
                  <input type="hidden" id="cliente_id" class="form-control">
                  <span class="input-group-btn">
                    <button type="button" id="agregar-btn" class="btn btn-success btn-flat">Agregar</button>
                  </span>
                </div>

              </div>

            </div>

          </div>
        </form>

        <div class="box box-warning">
          <div class="box-header with-border">
            <h3 class="box-title">Ultimos pagos registrados</h3>
          </div>
          <div class="box-body">
            <table class="table table-condensed table-striped" width="100%">
              <thead>
                <tr>
                  <th width="15%">#</th>
                  <th width="45%">Importe</th>
                  <th width="40%">Fecha Pago</th>
                  <!-- <th width="20%">Accion</th> -->
                </tr>
              </thead>
              <tbody id="tbody_data">
                
              </tbody>
              <tbody id="tbody_0">
                <tr>
                  <td colspan="4" class="text-center"> <label class="label label-warning">0 pagos registrados</label></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>

      <!--=====================================
      PIE DEL MODAL
      ======================================-->

      <div class="modal-footer">

        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

        <!-- <button type="button" class="btn btn-primary">Guardar cliente</button> -->

      </div>

      

    </div>

  </div>

</div>



