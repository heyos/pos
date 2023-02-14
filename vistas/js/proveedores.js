var url = $('#url').val();
var form = 'formProveedor';

var arr = ['proveedores'];
if(arr.includes($('#ruta').val())){

var table = $('.tablaProveedor').DataTable( {
    "ajax": {
      url: url+"ajax/datatable-proveedor.ajax.php",
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
      {data: 'razon_social', name: 'razon_social', className:'text-center'},
      {data: 'descripcion', name: 'ruc',className:'text-center'},
      {data: 'dia_visita', name: 'dia_visita', className:'text-center',searchable: false},
      {data: 'pedido_minimo', name: 'pedido_minimo', className:'text-center',orderable: false, searchable: false},
      {data: 'representante_detalle', name: 'representante_detalle', className:'text-center'},
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
EDITAR PROVEEDOR
=============================================*/
$(".tablaProveedor").on("click", ".btnEditarProveedor", function(){

	var id = $(this).attr("id");
  var ruta = url+'ajax/proveedor.ajax.php';
  var str = 'accion=data&term='+id;

  resetForm(form);

	loadData(ruta,'',str,form,function(result,data){

    if(result){
      $('#accion').val('edit');
      $('#modalAgregarProveedor').modal('show');
    }
  });

});

/*=============================================
ELIMINAR PROVEEDOR
=============================================*/
$(".tablaProveedor").on("click", ".btnEliminarProveedor", function(){

	var id = $(this).attr("id");
  var ruta = url+'ajax/proveedor.ajax.php';
  var str = 'accion=delete&term='+id;
  var type = 'POST';
	
	deleteRow(ruta,str,type);

});

$('#openModalProveedor').click(function(){

  resetForm(form);

  $('#id').val('0');
  $('#accion').val('add');
  $('#modalAgregarProveedor').modal('show');

});

$('#guardarProveedor').click(function(){

  validateForm(form);

  var datos = $('#'+form).serialize();

  $.ajax({
    url:"ajax/proveedor.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    dataType:"json",
    success:function(result){
    
      if(result.response == false){
        swal(result.message,'','warning');
      }else{
        swal('Exito..!',result.message,'success');
        $('#modalAgregarProveedor').modal('hide');
        table.draw();
      }
    },
    error: function(e){
      console.log(e)
    }

  });

});

}