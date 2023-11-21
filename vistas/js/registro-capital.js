
var arr = ['registro-capital'];

if(arr.includes($('#ruta').val())){

  var url = $('#url').val();

  var table = $('.tablaRegistro').DataTable( {
    "ajax": {
        url:"ajax/datatable-reporte_capital.ajax.php",
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
        {data: 'capital', name: 'capital',className:'text-center',orderable: false,searchable: false},
        {data: 'f_inicio', name: 'f_inicio',className:'text-center',orderable: false,searchable: false},
        {data: 'f_fin', name: 'f_fin', className:'text-center',orderable: false,searchable: false},
        {data: 'activo', name: 'activo', className:'text-center'},
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

  $('body').on('click','.btn-openRegistro',function(){

    var ruta = url+'ajax/registro-capital.ajax.php';
    var str = 'accion=data';

    var type = $(this).attr('data-type');
    var id = $(this).attr('id') ? $(this).attr('id') : 0;

    str += '&type='+type+'&id='+id;

    loadData(ruta,'',str,'',function(response,data){

      if(response){
        resetForm('formRegistro');

        $('.capital').prop('disabled',false);
        $('.capital').val(data.capital);
        $('#body-inputs').html(data.html);

        if(type=="detalle"){
          $('#formRegistro input').prop('disabled',true);
          $('#btnGuardar').hide();
        }else{
          $('#btnGuardar').show();
          $('#formRegistro input').prop('disabled',false);
        }

        $('#aumentar_monto').prop('disabled',true);
        $('#modalRegistro').modal('show');
        $('#f_inicio').val(data.fecha.f_inicio);
        $('#f_fin').val(data.fecha.f_fin);

      }

      //console.log(data);

    });

    
  });

  $('.ingresar').on('keyup',function(e){

    var campos = $('.original').length;
    var capital = $(this).val();
    var old = $('#oldCapital').val();
    var montoCategoria = capital/campos;
    montoCategoria = montoCategoria.toFixed(2);

    calcular(montoCategoria);
    var input = $('#aumentar').is(':checked') ? 'nuevo':'original'
    almacenar(input);

  });

  $('#aumentar').click(function(){

    var div = $('.aumentar');

    if($(this).is(':checked')){
      div.show();
      $('#aumentar_monto').prop('disabled',false);
      $('#original').prop('disabled',true);
      $('.original').prop('disabled',true);
      $('#capital').prop('disabled',true);
    }else{
      div.hide();
      $('#aumentar_monto').prop('disabled',true);
      $('#aumentar_monto').val('0');
      $('#original').prop('disabled',false);
      $('.original').prop('disabled',false);
      $('#capital').prop('disabled',false);
    }
  });

  $('body').on('keyup','.input',function(){

    var input = $(this).attr('input');
    var orden = $(this).attr('orden');
    var val = $(this).val();

    $('.nuevo_'+orden).val(val);
    var aumentar_monto = $('#aumentar_monto');
    var aumentar = $('#aumentar').is(':checked') ? 0 : aumentar_monto.val();

    aumentar_monto.val(aumentar)

    almacenar(input);

  });

  $('#btnGuardar').click(function(){

    validateForm('formRegistro');

    var condicion = $('#aumentar').is(':checked') ? true : false;
    var input = condicion ? 'nuevo':'original';
    almacenar(input);

    var str = $('#formRegistro').serialize();
    str += '&accion=add';

    if(condicion){
      str += '&capital='+$('#capital').val();
    }

    //console.log(str);

    // return;

    var ruta = url+'ajax/registro-capital.ajax.php'

    actionData(ruta, str, function(status,data){

      if(status){
        $('#modalRegistro').modal('hide');
        table.draw();
        swal('Exito..!', data.message,'success');
      }else{
        swal('Advertencia..!', data.message,'warning');
      }
    });

  });

  
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

  function calcular(monto){

    var condicion = $('#aumentar').is(':checked') ? true : false;
    
    $('.original').each(function(){
      
      var val =  parseFloat($(this).val());
      var newMonto = parseFloat(monto);
      var categoria = $(this).attr('categoria');
      var orden = $(this).attr('orden');
      
      if(condicion){
        newMonto = parseFloat(monto) + val;
      }else{
        $(this).val(newMonto);
      }

      $('.nuevo_'+orden).val(newMonto.toFixed(2));

    });

    
  }

  function almacenar(input){
    var total = 0;
    var lista = {};
    
    $('.'+input).each(function(){
      var categoria = $(this).attr('categoria');
      var monto = $(this).val() != '' ? parseFloat($(this).val()) : 0;

      lista[categoria] = monto;
      total += monto;
    });

    $('#capital').val(total.toFixed(2));
    $('#detalle').val(JSON.stringify(lista));
    
    
  }

}
  