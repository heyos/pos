
var arr = ['administrar-deudas'];

if(arr.includes($('#ruta').val())){

  var host = $('#url').val();

  var table = $('.tabla-deudas').DataTable( {
    "ajax": {
        url:"ajax/datatable-administrar_deudas.ajax.php",
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
        {data: 'telefono', name: 'telefono', className:'text-center',orderable: false,searchable: false},
        {data: 'deuda_total', name: 'deuda_total', className:'text-center',orderable: false,searchable: false},
        {data: 'ultimo_pago', name: 'ultimo_pago', className:'text-center',orderable: false,searchable: false},
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
  $(".tabla-deudas").on("click", ".btn-openRegistro", function(){

    var idCliente = $(this).attr("id");
    let nombre = $(this).attr('nombre');

    $('#cliente_id').val(idCliente);
    $('#nombre').html('<b>'+nombre+'</b>');

    getPagosList(idCliente);

  });

  $('#importe').keyup(function(){
    let importe = parseFloat($(this).val());
    let deuda = parseFloat($('#deuda').val());
    let newDeuda = deuda - importe;

    if(importe > deuda){
      swal('Importe no puede superar la suma de '+deuda.toFixed(2),'','warning');
      $('#deuda_total').html('$'+deuda.toFixed(2))
      $(this).val('')
      return;
    }

    $('#deuda_total').html('$'+newDeuda.toFixed(2))
  })

  $('#agregar-btn').click(function(){
    let importe = parseFloat($('#importe').val());
    let deuda = parseFloat($('#deuda').val());
    idCliente = $('#cliente_id').val();
    
    if(importe > deuda){
      swal('Importe no puede superar la suma de '+deuda.toFixed(2),'','warning');
      $('#deuda_total').html('$'+deuda.toFixed(2))
      return;
    }

    swal({
      title: '¿Está seguro de registrar el pago?',
      text: "¡Una vez guardado no se podrá modificar!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'Cancelar',
      confirmButtonText: 'Si, guardar pago!'
    }).then(function(result){
      if (result.value) {
        
        let request = {
          cliente_id : idCliente,
          importe: importe,
          accion: 'add'
        }

        let params = formDataParams(request);
        let url = host+'ajax/pago_deuda.ajax.php';

        actionFormData(url,params,function(status,response){
          if(status){
            swal(response.message,'','success');
            getPagosList(idCliente,false)
          }else{
            swal(response.message,'','warning');
          }

          table.draw();
        });
        
      }

    })


  });

  /*=============================================
  ELIMINAR CLIENTE
  =============================================*/
  $(".tabla-deudas").on("click", ".btnEliminarCliente", function(){

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

  function getPagosList(cliente_id,open=true){

    let request = {
      term : cliente_id,
      accion: 'pagosList'
    }

    let params = formDataParams(request);
    let url = host+'ajax/pago_deuda.ajax.php';
    
    actionFormData(url,params,function(status,response){

      if(status){
        $('#tbody_0').hide();
        $('#tbody_data').show().html(response.tbody)
      }else{
        $('#tbody_0').show();
        $('#tbody_data').hide().html('');
      }

      $('#importe').val('')
      
      $('#deuda').val(response.deuda_total)
      $('#deuda_total').html('$'+response.deuda_total.toFixed(2))
      
      if(open){
        $('#modalPago').modal('show');
      }
      
    });

  }

}
  