
var arr = ['registrar-gastos'];

if(arr.includes($('#ruta').val())){

  let action = "";

  $(".fecha").datepicker({ 
    dateFormat: 'yy-mm-dd',
    maxDate: "+0D"
  });

  var host = $('#url').val();

  var table = $('.tabla-gastos').DataTable( {
    "ajax": {
        url:"ajax/datatable-registrar_gastos.ajax.php",
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

        {data: 'DT_RowIndex', name: 'DT_RowIndex',className:'text-center',orderable: false,searchable: false},
        {data: 'tipo_gasto', name: 'tipo_gasto',className:'text-center'},
        {data: 'descripcion', name: 'descripcion', className:'text-center'},
        {data: 'importe', name: 'importe', className:'text-center',orderable: false,searchable: false},
        {data: 'fecha', name: 'fecha', className:'text-center'},
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

  $('#btn-add-gasto').click(function(){
    resetForm('formGasto');

    $('#id').val('0');
    action = 'add';
  });

  /*=============================================
  EDITAR CLIENTE
  =============================================*/
  $(".tabla-gastos").on("click", ".btn-editar-gasto", function(){

    var id = $(this).attr("id");
    action = 'edit';
    getDataPago(id)
    
  });


  $('#saveGasto').click(function(){

    validateForm('formGasto',function(status,message){

      if(!status){

        swal({
          title: 'Un momento ..!',
          html: message,
          type: 'warning'
        });

      }else{
        let request = formSerializeToObject('formGasto');
        request['action'] = action;

        actionGasto(request);

      }

    });

    

  });


  /*=============================================
  ELIMINAR CLIENTE
  =============================================*/
  $(".tabla-deudas").on("click", ".btn-eliminar-gasto", function(){

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

  function getDataPago(id){

    let request = {
      id : id,
      action: 'data'
    }

    let params = formDataParams(request);
    let url = host+'ajax/registrar-gastos.ajax.php';
    
    actionFormData(url,params,function(status,response){

      if(status){

        let form = "#formGasto";
        let data = response.data;

        $.each(data,(e) => {
          console.log(e)
          $(form+' #'+e).val(data[e])
        });

        $('#modalAgregarGasto').modal('show');
        
      }else{
        swal(response.message,'','warning')
      }

    });

  }

  function actionGasto(request){

    let params = formDataParams(request);
    let url = host+'ajax/registrar-gastos.ajax.php';
    
    actionFormData(url,params,function(status,response){

      if(status){

        swal(response.message,'','success');

        $('#modalAgregarGasto').modal('hide');
        table.draw();
        resetForm('formGasto');
        
      }else{
        swal(response.message,'','warning');
      }

    });

  }

}

  


  