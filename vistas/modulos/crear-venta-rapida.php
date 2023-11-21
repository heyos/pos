<?php

date_default_timezone_set("America/Lima");

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
      
      Crear compra
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Crear compra</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="row">

      <!--=====================================
      EL FORMULARIO
      ======================================-->
      
      <div class="col-lg-5 col-xs-12">
        
        <div class="box box-success">
          
          <div class="box-header with-border"></div>

          <form role="form" method="post" class="formularioCompra">

              <div class="box-body">
    
                <div class="box">

                  <div class="form-group">

                    <div class="row">

                      <div class="col-sm-4">

                        <div class="input-group">
                          
                          <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 

                          <input type="text" class="form-control nuevaFecha" id="fecha" name="fecha" value="<?php echo date("Y-m-d"); ?>" required>

                        </div>

                      </div>

                      <div class="col-sm-4">
                        
                        <div class="input-group">
                          
                          <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 

                          <input type="text" class="form-control" id="hora" name="hora" value="<?php echo date("H:i:s"); ?>" readonly>

                        </div>

                      </div>

                      <div class="col-sm-4">

                        <div class="input-group">
                      
                          <span class="input-group-addon"><i class="fa fa-key"></i></span>

                          <?php

                          $compras = ComprasController::codigo();
                          $codigo = $compras['contenido'];
                          $accion = "add";

                          echo '<input type="text" class="form-control" id="codigo" name="codigo" value="'.$codigo.'" readonly>';

                          ?>
                        
                        </div>
                        
                      </div>

                    </div>
                  
                  </div> 

                  <input type="hidden" name="accion" value="<?php echo $accion; ?>">
                  <input type="hidden" name="usuario_id" value="<?php echo $_SESSION["id"]; ?>">
                  <!--=====================================
                  ENTRADA DEL CLIENTE
                  ======================================--> 

                  <div class="form-group">
                    
                    <div class="input-group">
                      
                      <span class="input-group-addon"><i class="fa fa-users"></i></span>
                      
                      <select class="form-control" id="seleccionarProveedor" name="proveedor_id" required>

                      <?php

                        $params = [
                          'show' => 'any',
                          'data' =>'all'
                        ];

                        $proveedores = ProveedorController::ctrMostrarProveedor($params);
                        foreach ($proveedores as $value) {

                         echo '<option value="'.$value["id"].'">'.$value["razon_social"].'</option>';

                        }

                      ?>

                      </select>
                                          
                      <span class="input-group-addon"><button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modalAgregarProveedor" data-dismiss="modal">Agregar proveedor</button></span>
                    
                    </div>
                  
                  </div>

                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-search"></i></span>
                      <input type="text" class="form-control search" placeholder="Buscar productos ingresados en la lista">
                    </div>
                  </div>

                  <!--=====================================
                  ENTRADA PARA AGREGAR PRODUCTO
                  ======================================-->
                  <hr>
                  <div class="form-group row text-center">
                    <div class="col-sm-5">
                      <strong>Descripcion</strong>
                    </div>
                    <div class="col-sm-2">
                      <strong>Cantidad</strong>
                    </div>
                    <div class="col-sm-2">
                      <strong>P.U</strong>
                    </div>
                    <div class="col-sm-3">
                      <strong>P. Total</strong>
                    </div>
                  </div>

                  <div class="form-group row nuevoProducto slimscroll">

                  </div>

                  <input type="hidden" id="listaProductos" name="listaProductos" required>
                  <input type="hidden" id="listaId" name="listaId" required>
                  <!--=====================================
                  BOTÓN PARA AGREGAR PRODUCTO
                  ======================================-->

                  <button type="button" class="btn btn-default hidden-lg btnAgregarProducto">Agregar producto</button>

                  <br>
                  <div class="row">
                    <div class="col-xs-6">
                      <button class="btn btn-default btn-block totalItems" disabled></button>
                    </div>
                    <div class="col-xs-6">
                      <button type="button" class="btn btn-danger btn-block clearLista">Limpiar lista</button>
                    </div>
                  </div>

                  <br>

                  <div class="form-horizontal row">
                      
                    <div class="col-sm-6">
                      <select class="form-control" id="nuevoMetodoPago" name="metodo_pago" required>
                        <option value="contado">Contado</option>
                        <option value="credito">Credito</option>
                      </select>    
                    </div>

                    <div class="col-sm-6">
                      <div class="control-label col-sm-4 ">
                        <strong>TOTAL</strong>
                      </div>
                      <div class="col-sm-8">
                        <div class="input-group">
                          <span class="input-group-addon"><i class="ion ion-social-usd"></i></span>
                          <input type="text" class="form-control input-lg" id="nuevoTotalCompra"  total="" placeholder="00000" readonly>
                          <input type="hidden" name="total" class="totalCompra" id="totalCompra">
                        </div>
                      </div>
                    </div>

                  </div><br>

                </div>

                <div class="form-group">
                  <button type="submit" class="btn btn-primary btn-block">Guardar Compra</button>
                </div>

              </div>

          </form>

        </div>
      </div>

      <!--=====================================
      LA TABLA DE PRODUCTOS
      ======================================-->

      <div class="col-lg-7 hidden-md hidden-sm hidden-xs">

        <div class="box box-warning">
          <div class="box-header with-border"></div>
          <div class="box-body">
            <div class="form-group has-success">
              <label>Ingrese codigo de barras</label>
              <div class="input-group">
                <input type="text" class="codigoProducto form-control">
                <span class="input-group-addon">
                  <i class="fa fa-barcode"></i>
                </span>
              </div>
              
            </div>
            
          </div>
        </div>
        
        <div class="box box-warning">

          <div class="box-header with-border"></div>

          <div class="box-body">
            
            <table class="table table-bordered table-striped dt-responsive tablaCompras">
              
               <thead>

                 <tr>
                  <th style="width: 10px">#</th>
                  <th>Imagen</th>
                  <th>Código</th>
                  <th>Descripcion</th>
                  <th>Stock</th>
                  <th>Acciones</th>
                  
                </tr>

              </thead>

            </table>

          </div>

        </div>


      </div>

    </div>
   
  </section>

</div>

<!--=====================================
MODAL AGREGAR CLIENTE
======================================-->

<div id="modalAgregarProveedor" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Agregar cliente</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL NOMBRE -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevoCliente" placeholder="Ingresar nombre" required>

              </div>

            </div>

            <!-- ENTRADA PARA EL DOCUMENTO ID -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-key"></i></span> 

                <input type="number" min="0" class="form-control input-lg" name="nuevoDocumentoId" placeholder="Ingresar documento" required>

              </div>

            </div>

            <!-- ENTRADA PARA EL EMAIL -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-envelope"></i></span> 

                <input type="email" class="form-control input-lg" name="nuevoEmail" placeholder="Ingresar email" required>

              </div>

            </div>

            <!-- ENTRADA PARA EL TELÉFONO -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-phone"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevoTelefono" placeholder="Ingresar teléfono" data-inputmask="'mask':'(999) 999-9999'" data-mask required>

              </div>

            </div>

            <!-- ENTRADA PARA LA DIRECCIÓN -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-map-marker"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevaDireccion" placeholder="Ingresar dirección" required>

              </div>

            </div>

             <!-- ENTRADA PARA LA FECHA DE NACIMIENTO -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevaFechaNacimiento" placeholder="Ingresar fecha nacimiento" data-inputmask="'alias': 'yyyy/mm/dd'" data-mask required>

              </div>

            </div>
  
          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Guardar cliente</button>

        </div>

      </form>

      <?php

        // $crearCliente = new ControladorClientes();
        // $crearCliente -> ctrCrearCliente();

      ?>

    </div>

  </div>

</div>


