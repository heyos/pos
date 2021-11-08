
var arr = ['clientes'];

if(arr.includes($('#ruta').val())){

  var table = $('.tabla-cliente').DataTable( {
    "ajax": {
        url:"ajax/datatable-clientes.ajax.php",
        data: function(d){
          //d.productos = $('#listaId').val(); //enviar parametros personalizados
        },
        complete: function(res){
            // console.log(res);
        }
    },
    "deferRender": true,
    "retrieve": true,
    "processing": true,
    "serverSide":true,
    columns: [

        {data: 'DT_RowIndex', name: 'DT_RowIndex',className:'text-center'},
        {data: 'nombre', name: 'nombre',className:'text-center'},
        {data: 'documento', name: 'documento',className:'text-center'},
        {data: 'email', name: 'email', className:'text-center'},
        {data: 'telefono', name: 'telefono', className:'text-center',orderable: false,searchable: false},
        {data: 'direccion', name: 'direccion', className:'text-center',orderable: false,searchable: false},
        {data: 'compras', name: 'compras', className:'text-center',orderable: false,searchable: false},
        {data: 'ultima_compra', name: 'ultima_compra', className:'text-center',orderable: false,searchable: false},
        {data: 'action', name: 'action', className:'text-center',orderable: false, searchable: false},

    ],
    "language": {

        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":    "",
        "sSearch":         "Buscar:",
        "sUrl":            "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
        "sFirst":    "Primero",
        "sLast":     "Último",
        "sNext":     "Siguiente",
        "sPrevious": "Anterior"
        },
        "oAria": {
          "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
          "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }

    }

  });

  /*=============================================
  EDITAR CLIENTE
  =============================================*/
  $(".tabla-cliente").on("click", ".btnEditarCliente", function(){

    var idCliente = $(this).attr("idCliente");

    var datos = new FormData();
      datos.append("idCliente", idCliente);

      $.ajax({

        url:"ajax/clientes.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType:"json",
        success:function(respuesta){
        
             $("#idCliente").val(respuesta["id"]);
           $("#editarCliente").val(respuesta["nombre"]);
           $("#editarDocumentoId").val(respuesta["documento"]);
           $("#editarEmail").val(respuesta["email"]);
           $("#editarTelefono").val(respuesta["telefono"]);
           $("#editarDireccion").val(respuesta["direccion"]);
             $("#editarFechaNacimiento").val(respuesta["fecha_nacimiento"]);
      }

      })

  })

  /*=============================================
  ELIMINAR CLIENTE
  =============================================*/
  $(".tabla-cliente").on("click", ".btnEliminarCliente", function(){

    var idCliente = $(this).attr("idCliente");
    
    swal({
          title: '¿Está seguro de borrar el cliente?',
          text: "¡Si no lo está puede cancelar la acción!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          cancelButtonText: 'Cancelar',
          confirmButtonText: 'Si, borrar cliente!'
        }).then(function(result){
          if (result.value) {
            
            window.location = "index.php?ruta=clientes&idCliente="+idCliente;
          }

    })

  });

}
  