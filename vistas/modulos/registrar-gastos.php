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
      
      Registrar Gastos
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Registrar Gastos</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
  
        <button class="btn btn-primary" id="btn-add-gasto" data-toggle="modal" data-target="#modalAgregarGasto">
          
          Agregar gasto

        </button>

      </div>

      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tabla-gastos" width="100%">
         
        <thead>
         
         <tr>
           
           <th style="width:10px">#</th>
           <th>Tipo</th>
           <th>Descripcion</th>
           <th>Importe</th>
           <th>Fecha</th>
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
MODAL AGREGAR GASTO
======================================-->

<div id="modalAgregarGasto" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" id="formGasto" >

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Registrar Gasto</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">


            <!-- ENTRADA PARA SELECCIONAR CATEGORÍA -->

            <div class="form-group row">
              <div class="col-md-6 col-sm-12">
                <div class="input-group">
                
                  <span class="input-group-addon"><i class="fa fa-th"></i></span> 

                  <select class="form-control input-lg required" id="tipo_gasto" name="tipo_gasto" placeholder="Tipo gasto" required>
                    <option value="">Selecionar tipo de gasto</option>
                    <option value="capital">Capital</option>
                    <option value="ganancia">Ganancia</option>
                  </select>

                </div>
              </div>
              <div class="col-md-6 col-sm-12">
                <div class="input-group">
              
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 

                  <input type="text" class="form-control input-lg fecha required" id="fecha" name="fecha" placeholder="Ingresar fecha" required>

                </div>
              </div>
                

            </div>

            <!-- ENTRADA PARA LA DESCRIPCIÓN -->
            <input type="text" class="form-control input-lg" id="id" name="id" value="0" required>
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span> 

                <input type="text" class="form-control input-lg required" 
                id="descripcion" name="descripcion" placeholder="Ingresar descripción" required>

              </div>

            </div>

            <!-- ENTRADA PARA STOCK -->

            

             <!-- ENTRADA PARA PRECIO COMPRA -->

            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-arrow-up"></i></span> 
                <input type="number" class="form-control input-lg required" id="importe" 
                name="importe" step="0.1" min="0" placeholder="Importe" required>
              </div>
            </div>

            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-th"></i></span> 
                <textarea type="text" class="form-control" id="detalle_gasto" name="detalle_gasto" required></textarea>

              </div>

            </div>
            

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="button" id="saveGasto" class="btn btn-primary">Guardar gasto</button>

        </div>

      </form>

    </div>

  </div>

</div>


