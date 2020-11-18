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
      
      Administrar compras
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar compras</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
  
        <a href="crear-compra">

          <button class="btn btn-primary">
            
            Agregar compra

          </button>

        </a>

         <button type="button" class="btn btn-default pull-right" id="daterange-btn">
           
            <span>
              <i class="fa fa-calendar"></i> Rango de fecha
            </span>

            <i class="fa fa-caret-down"></i>

         </button>

      </div>

      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tablaListaCompras" width="100%">
         
        <thead>
          <tr>
            <th style="width:10px">#</th>
            <th>Código</th>
            <th>Proveedor</th>
            <th>Total</th> 
            <th>Fecha</th>
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




