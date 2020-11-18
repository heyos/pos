/*=============================================
CARGAR LA TABLA DINÁMICA DE VENTAS
=============================================*/

sumarTotalPrecios();
listarProductos();

$(".nuevaFecha").datepicker({ 
    dateFormat: 'yy-mm-dd',
    maxDate: "+0D"
});

//$("#seleccionarCliente").select2();

var table = $('.tablaCompras').DataTable( {
    "ajax": {
        url:"ajax/datatable-productos-v2.ajax.php",
        data: function(d){
            d.productos = $('#listaId').val(); //enviar parametros personalizados
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
        {data: 'imagen', name: 'imagen',className:'text-center',orderable: false, searchable: false},
        {data: 'codigo', name: 'codigo',className:'text-center'},
        {data: 'descripcion', name: 'descripcion', className:'text-center'},
        {data: 'stock', name: 'stock', className:'text-center',searchable: false},
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
AGREGANDO PRODUCTOS A LA COMPRA DESDE LA TABLA
=============================================*/

$(".tablaCompras tbody").on("click", "button.agregarProducto", function(){

	var idProducto = $(this).attr("idProducto");

	$(this).removeClass("btn-primary agregarProducto");
    $(this).addClass("btn-default");

	var datos = new FormData();
    datos.append("id", idProducto);
    datos.append("accion","data");

    $.ajax({
        url:"ajax/productos.ajax.php",
      	method: "POST",
      	data: datos,
      	cache: false,
      	contentType: false,
      	processData: false,
      	dataType:"json",
      	success:function(respuesta){

      	    $(".nuevoProducto").append(respuesta.contenido);

            // AGRUPAR PRODUCTOS EN FORMATO JSON
            listarProductos();
            sumarTotalPrecios();

	        localStorage.removeItem("quitarProducto");

      	}

     });

});


/*=============================================
QUITAR PRODUCTOS DE LA COMPRA Y RECUPERAR BOTÓN
=============================================*/

var idQuitarProducto = [];

localStorage.removeItem("quitarProducto");

$(".formularioCompra").on("click", "button.quitarProducto", function(){

	$(this).parent().parent().parent().parent().remove();

	var idProducto = $(this).attr("idProducto");

	/*=============================================
	ALMACENAR EN EL LOCALSTORAGE EL ID DEL PRODUCTO A QUITAR
	=============================================*/

	if(localStorage.getItem("quitarProducto") == null){

		idQuitarProducto = [];
	
	}else{

		idQuitarProducto.concat(localStorage.getItem("quitarProducto"));

	}

    idQuitarProducto.push({"idProducto":idProducto});

	localStorage.setItem("quitarProducto", JSON.stringify(idQuitarProducto));

    $("button.recuperarBoton[idProducto='"+idProducto+"']").removeClass('btn-default');

	$("button.recuperarBoton[idProducto='"+idProducto+"']").addClass('btn-primary agregarProducto');

	if($(".nuevoProducto").children().length == 0){

		$("#nuevoTotalCompra").val(0);
		$("#totalCompra").val(0);
		$("#nuevoTotalCompra").attr("total",0);

	}else{

		sumarTotalPrecios();
        listarProductos();

	}

});

//QUITAR TODOS LOS PRODUCTOS DE LA LISTA
$('.clearLista').on('click',function(){

	var lista = $('#listaId').val() != '' ? JSON.parse($('#listaId').val()): [];

	if(lista.length == 0){
		return;
	}

	swal({
      title: "Borrar lista",
      text: "¡Este accion borrara los productos de la lista!",
      type: "warning",
      showCancelButton: true,
      cancelButtonText: "¡Cerrar!",
      confirmButtonText: "¡Ok!"
    }).then(function(result){

    	if(result.value){

    		$('.nuevoProducto').html('');
    		sumarTotalPrecios();
        	listarProductos();
    		table.draw();    		

    	}

    });

    

});

/*=============================================
BUSCAR PRODUCTOS EN LA LISTA
=============================================*/

$('.search').keyup(function(e){

	var value = $(this).val()
	var lista = $("#listaProductos").val() != '' ? JSON.parse($("#listaProductos").val()): [] ;
	var listaFiltrada = [];

	if(lista.length > 0){

		listaFiltrada = value == '' ? lista : lista.filter((producto) => {
			
			var pro = producto.descripcion.toUpperCase();
			
			if(pro.includes(value.toUpperCase())){
				return pro;
			}else{
				$('#'+producto.producto_id).hide();
			}
			
		});

		if(value == ''){

			datos = 'accion=filterCompra&lista='+JSON.stringify(lista);

			$.ajax({
				cache: false,
				type:'POST',
				dataType: 'json',
				url: "ajax/productos.ajax.php",
				data: datos,
				success: function(response){

					$('.nuevoProducto').html('').append(response.data);

				},
				error: function(e){
					console.log(e);
		            alert(e.responseText);
				}
			});

		}

	}

});

/*==========================================================
BUSCAR PRODUCTOS CON CODIGO DE BARRA Y AGREGAR A LA LISTA
==========================================================*/

$('.codigoProducto').keypress(function(e){

	var codigo = $(this).val();
	var key = (e.which) ? e.which : e.keyCode
	var listaId = $('#listaId').val() != '' ? JSON.parse($('#listaId').val()) : [];
	
	if(key == 13 && codigo != ''){
		datos = 'accion=data&codigo='+codigo;

		$.ajax({
			cache: false,
			type:'POST',
			dataType: 'json',
			url: "ajax/productos.ajax.php",
			data: datos,
			success: function(response){

				if(listaId.includes(response.idProducto)){

					swal({
				      title: "Producto existente",
				      text: "¡Este producto ya se encuentra en la lista!",
				      type: "warning",
				      confirmButtonText: "¡Cerrar!"
				    });

				}else{

					$('.nuevoProducto').append(response.contenido);
					$('button.agregarProducto[idProducto="'+response.idProducto+'"]').removeClass('btn-primary');
					$('button.agregarProducto[idProducto="'+response.idProducto+'"]').addClass('btn-default recuperarBoton').removeClass('agregarProducto');
					$('.codigoProducto').val('');
					sumarTotalPrecios();
        			listarProductos();
				}

			},
			error: function(e){
				console.log(e);
	            alert(e.responseText);
			}
		});
	}
});

/*=============================================
AGREGANDO PRODUCTOS DESDE EL BOTÓN PARA DISPOSITIVOS
=============================================*/

var numProducto = 0;

$(".btnAgregarProducto").click(function(){

	numProducto ++;

	var datos = new FormData();
	datos.append("traerProductos", "ok");

	$.ajax({

		url:"ajax/productos.ajax.php",
      	method: "POST",
      	data: datos,
      	cache: false,
      	contentType: false,
      	processData: false,
      	dataType:"json",
      	success:function(respuesta){
      	    
      	    	$(".nuevoProducto").append(

          	'<div class="row" style="padding:5px 15px">'+

			  '<!-- Descripción del producto -->'+
	          
	          '<div class="col-xs-6" style="padding-right:0px">'+
	          
	            '<div class="input-group">'+
	              
	              '<span class="input-group-addon"><button type="button" class="btn btn-danger btn-xs quitarProducto" idProducto><i class="fa fa-times"></i></button></span>'+

	              '<select class="form-control nuevaDescripcionProducto" id="producto'+numProducto+'" idProducto name="nuevaDescripcionProducto" required>'+

	              '<option>Seleccione el producto</option>'+

	              '</select>'+  

	            '</div>'+

	          '</div>'+

	          '<!-- Cantidad del producto -->'+

	          '<div class="col-xs-3 ingresoCantidad">'+
	            
	             '<input type="number" class="form-control nuevaCantidadProducto" name="nuevaCantidadProducto" min="1" value="0" stock nuevoStock required>'+

	          '</div>' +

	          '<!-- Precio del producto -->'+

	          '<div class="col-xs-3 ingresoPrecio" style="padding-left:0px">'+

	            '<div class="input-group">'+

	              '<span class="input-group-addon"><i class="ion ion-social-usd"></i></span>'+
	                 
	              '<input type="text" class="form-control nuevoPrecioProducto" precioReal="" precioCompraReal="" name="nuevoPrecioProducto" readonly required>'+
	 
	            '</div>'+
	             
	          '</div>'+

	        '</div>');


	        // AGREGAR LOS PRODUCTOS AL SELECT 

	         respuesta.forEach(funcionForEach);

	         function funcionForEach(item, index){

	         	if(item.stock != 0){

		         	$("#producto"+numProducto).append(

						'<option idProducto="'+item.id+'" value="'+item.descripcion+'">'+item.descripcion+'</option>'
		         	)

		         
		         }

		         

	         }

        	 // SUMAR TOTAL DE PRECIOS

    		sumarTotalPrecios()

    		// AGREGAR IMPUESTO
	        
	        agregarImpuesto()

	        // PONER FORMATO AL PRECIO DE LOS PRODUCTOS

	        $(".nuevoPrecioProducto").number(true, 2);


      	}

	})

})

/*=============================================
SELECCIONAR PRODUCTO
=============================================*/

$(".formularioVenta").on("change", "select.nuevaDescripcionProducto", function(){

	var nombreProducto = $(this).val();

	var nuevaDescripcionProducto = $(this).parent().parent().parent().children().children().children(".nuevaDescripcionProducto");

	var nuevoPrecioProducto = $(this).parent().parent().parent().children(".ingresoPrecio").children().children(".nuevoPrecioProducto");

	var nuevaCantidadProducto = $(this).parent().parent().parent().children(".ingresoCantidad").children(".nuevaCantidadProducto");

	var datos = new FormData();
    datos.append("nombreProducto", nombreProducto);


	  $.ajax({

     	url:"ajax/productos.ajax.php",
      	method: "POST",
      	data: datos,
      	cache: false,
      	contentType: false,
      	processData: false,
      	dataType:"json",
      	success:function(respuesta){
      	    
            $(nuevaDescripcionProducto).attr("idProducto", respuesta["id"]);
            $(nuevaCantidadProducto).attr("stock", respuesta["stock"]);
            $(nuevaCantidadProducto).attr("nuevoStock", Number(respuesta["stock"])-1);
      	    $(nuevoPrecioProducto).val(respuesta["precio_venta"]);
      	    $(nuevoPrecioProducto).attr("precioReal", respuesta["precio_venta"]);
            $(nuevoPrecioProducto).attr("precioCompraReal", respuesta["precio_compra"]);

  	        listarProductos()

      	}

      })
})

/*=============================================
MODIFICAR LA CANTIDAD
=============================================*/

$(".formularioCompra").on("keyup", "input.nuevaCantidadProducto, input.nuevoPrecioProducto", function(){

    var id = $(this).attr('idProducto');
	// var precio = $(this).parent().parent().children(".ingresoPrecio").children().children(".nuevoPrecioProducto");
    var cantidad = $('.nuevaCantidadProducto[idProducto="'+id+'"]');
    var precio = $('.nuevoPrecioProducto[idProducto="'+id+'"]');
    var total = $('.nuevoTotalProducto[idProducto="'+id+'"]');
    
	var precioFinal = cantidad.val() * precio.val();
    
	total.val(precioFinal);

	if(Number(cantidad.val()) < 0){

		cantidad.val(0);

		var precioFinal = cantidad.val() * precio.val();

		total.val(precioFinal);

		sumarTotalPrecios();

		swal({
	      title: "Cantidad no permitida",
	      text: "¡Cantidad no puede ser inferior a 0!",
	      type: "error",
	      confirmButtonText: "¡Cerrar!"
	    });

	    return;

	}

	sumarTotalPrecios()
    listarProductos();

});

$(".formularioCompra").on("keyup", "input.nuevoTotalProducto", function(){

    var id = $(this).attr('idProducto');
    var cantidad = $('.nuevaCantidadProducto[idProducto="'+id+'"]');
    var precio = $('.nuevoPrecioProducto[idProducto="'+id+'"]');
    var total = $('.nuevoTotalProducto[idProducto="'+id+'"]');
    
    var precioFinal = 0;
    
    if(Number(cantidad.val()) == 0){

        cantidad.val(1);

        var precioFinal = cantidad.val() * precio.val();

        total.val(precioFinal);

        sumarTotalPrecios();

        swal({
          title: "Cantidad no permitida",
          text: "¡Cantidad no puede ser inferior a 0!",
          type: "error",
          confirmButtonText: "¡Cerrar!"
        });

        return;

    }

    precioFinal = total.val() / cantidad.val();
    precio.val(precioFinal.toFixed(2));

    sumarTotalPrecios()
    listarProductos();

});

/*=============================================
GUARDAR COMPRA
=============================================*/

$(".formularioCompra").on("submit", function(e){

    e.preventDefault();

    var str = $(this).serialize();

    $.ajax({
        cache: false,
        dataType: 'json',
        url: 'ajax/compras.ajax.php',
        type: "POST",
        data: str,
        success: function(response){

            if(response.respuesta == false){
            	swal({
		          title: "Error de operacion",
		          text: response.mensaje,
		          type: "error",
		          confirmButtonText: "¡Cerrar!"
		        });
            }else{
            	swal({
		          title: "Operacion Exitosa",
		          text: response.mensaje,
		          type: "success",
		          confirmButtonText: "¡Cerrar!"
		        }).then(function(result){
		        	if(result.value){
		        		//window.location = "compras";
		        	}
		        });
            }

        },
        error: function(e){
            console.log(e);
            alert(e.responseText);
        }
    });

    return false;

});



/*=============================================
SUMAR TODOS LOS PRECIOS
=============================================*/

function sumarTotalPrecios(){

	var precioItem = $(".nuevoTotalProducto");
	
	var arraySumaPrecio = [];
	var sumaTotalPrecio = 0;

    const sumaArrayPrecios = (total,numero) => total + numero; //funcion flecha

	for(var i = 0; i < precioItem.length; i++){

		arraySumaPrecio.push(parseFloat($(precioItem[i]).val()));
	
    }

	// function sumaArrayPrecios(total, numero){

	// 	return total + numero;

	// }
	if(arraySumaPrecio.length > 0){
		sumaTotalPrecio = arraySumaPrecio.reduce(sumaArrayPrecios);
	}
	
    	
	$("#nuevoTotalCompra").val(sumaTotalPrecio);
	$("#totalCompra").val(sumaTotalPrecio);
	$("#nuevoTotalCompra").attr("total",sumaTotalPrecio);
	$('.totalCompra').val(sumaTotalPrecio);


}


/*=============================================
FORMATO AL PRECIO FINAL
=============================================*/

$("#nuevoTotalCompra").number(true, 2);

/*=============================================
SELECCIONAR MÉTODO DE PAGO
=============================================*/

$("#nuevoMetodoPago").change(function(){

	var metodo = $(this).val();

	if(metodo == "Efectivo"){

		$(this).parent().parent().removeClass("col-xs-6");

		$(this).parent().parent().addClass("col-xs-4");

		$(this).parent().parent().parent().children(".cajasMetodoPago").html(

			 '<div class="col-xs-4">'+ 

			 	'<div class="input-group">'+ 

			 		'<span class="input-group-addon"><i class="ion ion-social-usd"></i></span>'+ 

			 		'<input type="text" class="form-control" id="nuevoValorEfectivo" placeholder="000000" required>'+

			 	'</div>'+

			 '</div>'+

			 '<div class="col-xs-4" id="capturarCambioEfectivo" style="padding-left:0px">'+

			 	'<div class="input-group">'+

			 		'<span class="input-group-addon"><i class="ion ion-social-usd"></i></span>'+

			 		'<input type="text" class="form-control" id="nuevoCambioEfectivo" placeholder="000000" readonly required>'+

			 	'</div>'+

			 '</div>'

		 )

		// Agregar formato al precio

		//$('#nuevoValorEfectivo').number( true, 2);
      	$('#nuevoCambioEfectivo').number( true, 2);


      	// Listar método en la entrada
      	listarMetodos()

	}else{

		$(this).parent().parent().removeClass('col-xs-4');

		$(this).parent().parent().addClass('col-xs-6');

		 $(this).parent().parent().parent().children('.cajasMetodoPago').html(

		 	'<div class="col-xs-6" style="padding-left:0px">'+
                        
                '<div class="input-group">'+
                     
                  '<input type="number" min="0" class="form-control" id="nuevoCodigoTransaccion" placeholder="Código transacción"  required>'+
                       
                  '<span class="input-group-addon"><i class="fa fa-lock"></i></span>'+
                  
                '</div>'+

              '</div>')

	}

	

});


/*=============================================
LISTAR TODOS LOS PRODUCTOS
=============================================*/

function listarProductos(){

	var listaProductos = [];
    var listaId = [];

	var descripcion = $(".nuevaDescripcionProducto");

	var cantidad = $(".nuevaCantidadProducto");

	var precio = $(".nuevoPrecioProducto");
    var total = $(".nuevoTotalProducto");
    var totalItems = 0;

	for(var i = 0; i < descripcion.length; i++){

		listaProductos.push({ "id" : $(descripcion[i]).attr("idDetalle"), 
                              "producto_id" : $(descripcion[i]).attr("idProducto"), 
							  "descripcion" : $(descripcion[i]).val(),
							  "cantidad" : $(cantidad[i]).val(),
							  "precio_compra" : $(precio[i]).val(),
                              "old_precio" : $(precio[i]).attr("oldPrecio"),
                              "sub_total" : $(total[i]).val()});
		

        listaId.push($(descripcion[i]).attr("idProducto"));
		
	}

	totalItems = listaId.length;

	$("#listaProductos").val(JSON.stringify(listaProductos));
    $("#listaId").val(JSON.stringify(listaId));
    $('.totalItems').html('<b>Nro Productos:</b> '+totalItems);
}

//COMPRAS

var tableLista = $('.tablaListaCompras').DataTable( {
    "ajax": {
        url:"ajax/datatable-compras.ajax.php",
        data: function(d){
            //d.productos = $('#listaId').val(); //enviar parametros personalizados
        },
        complete: function(res){
        	console.log(res);
        }
    },
    "deferRender": true,
	"retrieve": true,
	"processing": true,
    "serverSide":true,
    "order": [[ 4, "desc" ]],
    columns: [

        {data: 'DT_RowIndex', name: 'DT_RowIndex',className:'text-center'},
        {data: 'codigo', name: 'codigo',className:'text-center'},
        {data: 'proveedor_name', name: 'proveedor_name', className:'text-center'},
        {data: 'total', name: 'total', className:'text-center',orderable: false,searchable: false},
        {data: 'fecha_hora', name: 'fecha_hora', className:'text-center',searchable: false},
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
BOTON EDITAR COMPRA
=============================================*/
$(".tablaListaCompras").on("click", ".btnEditarCompra", function(){

	var idCompra = $(this).attr("idCompra");

	window.location = "index.php?ruta=editar-compra&term="+idCompra;


});



/*=============================================
BORRAR COMPRA
=============================================*/
$(".tablaListaCompras").on("click", ".btnEliminarCompra", function(){

  var idVenta = $(this).attr("idVenta");

  swal({
        title: '¿Está seguro de borrar la compra?',
        text: "¡Si no lo está puede cancelar la accíón!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, borrar compra!'
      }).then(function(result){
        if (result.value) {
          
            var data = "accion=delete&id="+idVenta;
            $.ajax({
            	cache: false,
            	type:'POST',
            	dataType: 'json',
            	url:'ajax/compras.ajax.php',
            	data: data,
            	success: function(response){

            	},
            	error: function(e){
            		console.log(e.responseText);
            		//alert(e.responseText)
            	}

            });
        }

  })

})

/*=============================================
IMPRIMIR FACTURA
=============================================*/

$(".tablas").on("click", ".btnImprimirFactura", function(){

	var codigoVenta = $(this).attr("codigoVenta");

	window.open("extensiones/tcpdf/pdf/factura.php?codigo="+codigoVenta, "_blank");

})

/*=============================================
RANGO DE FECHAS
=============================================*/

$('#daterange-btn').daterangepicker(
  {
    ranges   : {
      'Hoy'       : [moment(), moment()],
      'Ayer'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
      'Últimos 7 días' : [moment().subtract(6, 'days'), moment()],
      'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
      'Este mes'  : [moment().startOf('month'), moment().endOf('month')],
      'Último mes'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
    startDate: moment(),
    endDate  : moment()
  },
  function (start, end) {
    $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

    var fechaInicial = start.format('YYYY-MM-DD');

    var fechaFinal = end.format('YYYY-MM-DD');

    var capturarRango = $("#daterange-btn span").html();
   
   	localStorage.setItem("capturarRango", capturarRango);

   	//window.location = "index.php?ruta=ventas&fechaInicial="+fechaInicial+"&fechaFinal="+fechaFinal;

  }

)

/*=============================================
CANCELAR RANGO DE FECHAS
=============================================*/

$(".daterangepicker.opensleft .range_inputs .cancelBtn").on("click", function(){

	localStorage.removeItem("capturarRango");
	localStorage.clear();
	window.location = "compras";
});

/*=============================================
CAPTURAR HOY
=============================================*/

$(".daterangepicker.opensleft .ranges li").on("click", function(){

	var textoHoy = $(this).attr("data-range-key");

	if(textoHoy == "Hoy"){

		var d = new Date();
		
		var dia = d.getDate();
		var mes = d.getMonth()+1;
		var año = d.getFullYear();

		if(mes < 10){

			var fechaInicial = año+"-0"+mes+"-"+dia;
			var fechaFinal = año+"-0"+mes+"-"+dia;

		}else if(dia < 10){

			var fechaInicial = año+"-"+mes+"-0"+dia;
			var fechaFinal = año+"-"+mes+"-0"+dia;

		}else if(mes < 10 && dia < 10){

			var fechaInicial = año+"-0"+mes+"-0"+dia;
			var fechaFinal = año+"-0"+mes+"-0"+dia;

		}else{

			var fechaInicial = año+"-"+mes+"-"+dia;
	    	var fechaFinal = año+"-"+mes+"-"+dia;

		}	

    	localStorage.setItem("capturarRango", "Hoy");

    	window.location = "index.php?ruta=ventas&fechaInicial="+fechaInicial+"&fechaFinal="+fechaFinal;

	}

});




