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
      
      Administrar proveedores
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar proveedores</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
  
        <button class="btn btn-primary" id="openModalProveedor" >
          
          Agregar proveedor

        </button>

      </div>

      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tablaProveedor" width="100%">
         
        <thead>
         
          <tr>
           
            <th style="width:10px">#</th>
            <th>Razon Social / RUC</th>
            <th>Descripcion</th>
            <th>Dia visita</th>
            <th>Pedido Minimo (S/)</th>
            <th>Representante</th>
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

<!--=====================================
MODAL AGREGAR PROVEEDOR
======================================-->

<div id="modalAgregarProveedor" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" id="formProveedor">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Agregar proveedor</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">
            <input type="hidden" name="id" id="id" value="0">
            <input type="hidden" name="accion" id="accion" value="add">
            <!-- ENTRADA PARA EL NOMBRE -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-address-card"></i></span> 

                <input type="text" class="form-control input-lg number" name="ruc" placeholder="Ingresa RUC">

              </div>

            </div>

            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 
                <input type="text" class="form-control input-lg required" name="razon_social" id="razon_social" placeholder="Ingresa razon social" required>
              </div>
            </div>

            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-comment-o"></i></span> 
                <textarea type="text" name="descripcion" id="descripcion" placeholder="Ingresar descripcion" class="form-control input-lg"></textarea>
              </div>
            </div>

            <div class="form-group">
              <div class="row">

                <div class="col-sm-7">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-map-marker"></i></span> 
                    <select name="dia_visita" id="dia_visita" class="form-control input-lg required" placeholder="Seleccione dia de visita" required>
                      <option value="">SELECCIONE DIA VISITA</option>
                      <option value="8">Todos</option>
                      <option value="1">Lunes</option>
                      <option value="2">Martes</option>
                      <option value="3">Miercoles</option>
                      <option value="4">Jueves</option>
                      <option value="5">Viernes</option>
                      <option value="6">Sabado</option>
                    </select>
                  </div>
                </div>

                <div class="col-sm-5">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-money"></i></span> 
                    <input type="text" class="form-control input-lg required" name="pedido_minimo" id="pedido_minimo" placeholder="Pedido minimo" required>
                  </div>
                </div>
                
              </div>
                              
            </div>

            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control input-lg" id="representante" name="representante" placeholder="Representante" required>

              </div>

            </div>

            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-phone"></i></span> 

                <input type="text" class="form-control input-lg" id="telefono" name="telefono" placeholder="Telefono" required>

              </div>

            </div>

          </div>
        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="button" id="guardarProveedor" class="btn btn-primary">Guardar proveedor</button>

        </div>

      </form>

    </div>

  </div>

</div>



