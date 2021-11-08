/*=============================================
SideBar Menu
=============================================*/

$('.sidebar-menu').tree()

/*=============================================
Data Table
=============================================*/

$(".tablas").DataTable({

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
 //iCheck for checkbox and radio inputs
=============================================*/

$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
  checkboxClass: 'icheckbox_minimal-blue',
  radioClass   : 'iradio_minimal-blue'
})

/*=============================================
 //input Mask
=============================================*/

//Datemask dd/mm/yyyy
$('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
//Datemask2 mm/dd/yyyy
$('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
//Money Euro
$('[data-mask]').inputmask()

$('.number').numeric();

/*=============================================
CORRECCIÓN BOTONERAS OCULTAS BACKEND	
=============================================*/

if(window.matchMedia("(max-width:767px)").matches){
	
	//$("body").removeClass('sidebar-collapse');

}else{

	//$("body").addClass('sidebar-collapse');
}

var ruta = $('#ruta').val();

$('a[href='+ruta+']').parent().addClass('active');
$('a[href='+ruta+']').parent().parent().parent().addClass('active');


/*===================================
//slimscroll
====================================*/

$('.slimscroll').slimscroll({
  height: '200px'
});


function blockPage(){
    
    $.blockUI({
    message: '<div class="semibold">&nbsp; Cargando ...</div>',
    css: {
    		border: 'none',
    		padding: '15px',
    		backgroundColor: '#000',
    		'-webkit-border-radius': '10px',
    		'-moz-border-radius': '10px',
    		opacity: .5,
    		color: '#fff'
	   },
	   baseZ: 2000
    });

}

function unBlockPage(){
    $.unblockUI();
}

function resetForm(form){
    $('#'+form+' input[type=checkbox]').prop('checked',false);
	$('#'+form+' input[type=text]').val('');
    $('#'+form+' input[type=email]').val('');
    $('#'+form+' select').val('');
    $('#'+form+' .select2').val(null).trigger('change');
	$('#'+form+' textarea').val('');
}

function validateForm(form){

	var i = 0;
	var message = '';

	$('#'+form+' .required').each(function(){
		
		var val = $(this).val();
		
		var placeholder = $(this).attr('placeholder') ? $(this).attr('placeholder') : $(this).attr('id')+' es requerido';
		
		if(val == ''){
			i++;
			message += '* '+placeholder+'<br>';
			 
		}
		
	});

	if(i > 0){
		swal({
			title: 'Un momento ..!',
			html: message,
			type: 'warning'
		});
		return false;
	}

}

function loadData(url,type,str,form,callback){

    if(type == ''){
        type = 'POST';
    }

    

    $.ajax({
        beforeSend:function(){
            blockPage();
        },
        url: url,
        cache: false,
        type: type,
        dataType: "json",
        data: str,
        success: function(result){

            unBlockPage();

            var data = result.data;

            if(result.response == true){

                if(form != ''){

                    form = '#'+form;

                    $.each(data,function(e){

                        if($(form+ ' input[type=checkbox][name="'+e+'"]').length > 0){

                            if(data[e] == '1'){
                                $(form+' div.switcher').addClass('checked');
                                $(form+' #'+e).prop('checked',true);
                            }else{
                                $(form+' div.switcher').removeClass('checked');
                                $(form+' #'+e).prop('checked',false);
                            }

                        }else{
                            $(form+' #'+e).val(data[e]);
                        }
                    });

                }                   

            }else{
                swal('Advertencia..!', result.message,'warning');
            }

            callback(result.response,result.data ? data : result);

        },
        error: function(e){
            unBlockPage();
            // msgErrorsForm(e);
			console.log(e);
            
            callback(false,e.status);
            
        }

    });

}

function deleteRow(url,params,type){

    var str = params != '' ? params : 'params=';
	var $type = type != '' ? type : 'DELETE';

    swal({
        title: '¿Está seguro de borrar el registro?',
        text: "¡Si no lo está puede cancelar la acción!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, borrar registro!'
  	}).then(function(result){
        
        if (result.value) {
          
          	$.ajax({
                beforeSend:function(){
                    blockPage();
                },
                url: url,
                cache: false,
                type: $type,
                dataType: "json",
                data: str,
                success: function(data){

                    if(data.response==false){
                        
                        swal('Advertencia..!', data.message,'warning');
                    }else{

                        table.draw();
                        swal('Exito..!', 'Registro eliminado exitosamente','success');

                    }

                    unBlockPage();
					

                },
                error: function(e){
                    unBlockPage();
                    console.log(e);
                }

            });
        }

  	})

}