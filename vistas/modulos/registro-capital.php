<?php

if($_SESSION["perfil"] == "Especial"){

  echo '<script>

    window.location = "inicio";

  </script>';

  return;

}

?>
<div class="content-wrapper">

  <?php echo "<input type='hidden' value='".$_GET["ruta"]."' id='ruta'>"; ?>

  <section class="content-header">
    
    <h1>
      
      Registro de capital
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Registro de capital</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
  
        <button type="button" id="btn-openRegistro" data-type="new" class="btn btn-primary btn-openRegistro">
          Generar registro
        </button>

      </div>

      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tablaRegistro" width="100%">
         
        <thead>
          <tr>
            <th style="width:10px">#</th>
            <th>Capital</th>
            <th>F. Inicio</th>
            <th>F. Fin</th> 
            <th>Activo</th>
            <th>Acciones</th>
          </tr> 
        </thead>

        <tbody>

        </tbody>

       </table>

      </div>

    </div>

  </section>

</div>

<div id="modalRegistro" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" id="formRegistro" class="form-horizontal">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Agregar Registro</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <div class="form-group row">
              <label class="control-label col-sm-3 col-xs-12">Capital Total</label>
              <div class="col-sm-4 col-xs-12">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-money"></i></span> 
                  <input type="text" class="form-control capital ingresar" id="capital" name="capital" placeholder="Ingresar monto" required>
                  <input type="hidden" class="form-control capital ingresar" id="oldCapital"  placeholder="Ingresar monto" required>
                </div>
              </div>
              <div class="col-sm-2 col-xs-12">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" id="aumentar" value="1"> Aumentar?
                  </label>
                </div>
              </div>
              
              <div class="col-sm-3 col-xs-12">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-money"></i></span> 
                  <input type="text" class="form-control ingresar" id="aumentar_monto" placeholder="Ingresar monto" disabled="">
                </div>
              </div>
            </div>

            <div id="body-inputs">
            </div>

            <input type="hidden" name="detalle" id="detalle">
            <input type="hidden" name="f_inicio" id="f_inicio">
            <input type="hidden" name="f_fin" id="f_fin">

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="button" id="btnGuardar" class="btn btn-primary">Guardar</button>

        </div>

      </form>

    </div>

  </div>

</div>



