/*=============================================
CARGAR LA TABLA DINÁMICA DE COMPRAS
=============================================*/

var arr = ['crear-venta-rapida'];

if(arr.includes($('#ruta').val())){
let u = sessionStorage.getItem('u') ? sessionStorage.getItem('u') : '1' ;

listarProductos();

$(".nuevaFecha").datepicker({ 
    dateFormat: 'yy-mm-dd',
    maxDate: "+0D"
});

//$("#seleccionarCliente").select2();

var table = $('.tablaProductos_v').DataTable( {
    "ajax": {
        url:"ajax/datatable-productos-v2.ajax.php",
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

$(".tablaProductos_v tbody").on("click", "button.agregarProducto", function(){

	let idProducto = $(this).attr("idProducto");

	let datos = `id=${idProducto}&accion=data_producto`;
    actionAddProducto(datos);

});


/*=============================================
QUITAR PRODUCTOS DE LA COMPRA Y RECUPERAR BOTÓN
=============================================*/


$(".formularioCompra").on("click", "button.quitarProducto", function(){

	let idProducto = $(this).attr("idProducto");
  
	swal({
      title: "Borrar producto",
      text: "¡Este accion borrara el producto de la lista!",
      type: "warning",
      showCancelButton: true,
      cancelButtonText: "¡Cerrar!",
      confirmButtonText: "¡Ok!"
    }).then(function(result){

    	if(result.value){
    		$("button.recuperarBoton[idProducto='"+idProducto+"']").removeClass('btn-default');

			$("button.recuperarBoton[idProducto='"+idProducto+"']").addClass('btn-primary agregarProducto');
    		$("#producto_"+idProducto).remove();

    		if(localStorage.getItem('data_temporal')){
		    	let temp = JSON.parse(localStorage.getItem('data_temporal'));
		    			    	
		    	let data = temp.filter((item) => {
		    		return item.id.toString() != idProducto.toString();
		    	});

		    	localStorage.setItem('data_temporal', JSON.stringify(data));
		    }
    		
        	listarProductos();
    		table.draw();    		

    	}

    });

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
    		localStorage.removeItem('data_temporal');
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
			}
			
		});

		showProductos(listaFiltrada);

	}

});

/*==========================================================
BUSCAR PRODUCTOS CON CODIGO DE BARRA Y AGREGAR A LA LISTA
==========================================================*/

$('.codigoProducto').keypress(function(e){

	var codigo = $(this).val();
	var key = (e.which) ? e.which : e.keyCode
	

	if(key == 13 && codigo != ''){
		datos = 'accion=data_producto&codigo='+codigo;
		console.log(key,codigo)
		actionAddProducto(datos);
	}
});


/*=============================================
MODIFICAR LA CANTIDAD
=============================================*/

$(".formularioCompra").on("focusout", "input.nuevaCantidadProducto", function(){

    let id = $(this).attr('idProducto');
	let cantidad = $('.nuevaCantidadProducto[idProducto="'+id+'"]');
    let precio = parseFloat($('.nuevoPrecioProducto[idProducto="'+id+'"]').attr('precioReal'));
    let stock = parseFloat($('.nuevaCantidadProducto[idProducto="'+id+'"]').attr('stock'));
    let oldPrecio = parseFloat($('.nuevoPrecioProducto[idProducto="'+id+'"]').val())
     
	let precioFinal = parseFloat(cantidad.val()) * precio;
	    
	if(Number(cantidad.val()) <= 0){

		cantidad.val(1);
		precioFinal = precio;

		swal({
	      title: "Cantidad no permitida",
	      text: "¡Cantidad no puede ser 0 o inferior!",
	      type: "error",
	      confirmButtonText: "¡Cerrar!"
	    });

	}

	if(parseFloat(cantidad.val()) > stock){
		let oldCantidad = oldPrecio/precio
		cantidad.val(oldCantidad);
		precioFinal = precio*oldCantidad;
		swal({
	      title: "Cantidad no permitida",
	      text: "¡Cantidad no puede ser superarior a "+stock+"!",
	      type: "error",
	      confirmButtonText: "¡Cerrar!"
	    });
	}

	$('.nuevoPrecioProducto[idProducto="'+id+'"]').val(precioFinal.toFixed(2));
	listarProductos(false);

});

$(".formularioCompra").on("focusout", "input.nuevoPrecioProducto", function(){

    let id = $(this).attr('idProducto');
    let cantidad = $('.nuevaCantidadProducto[idProducto="'+id+'"]');
    let precio = $(this).attr('precioReal')
    let precioCompra = $(this).attr('precioCompraReal');
    let precioFinal = $(this);
    
    if(Number(cantidad.val()) <= 0){

        cantidad.val(1);

        precioFinal.val(precio);

        swal({
          title: "Cantidad no permitida",
          text: "¡Cantidad no puede ser 0 o inferior!",
          type: "error",
          confirmButtonText: "¡Cerrar!"
        });

    }

    let precioFinalTemp = parseFloat(precioCompra)*parseFloat(cantidad.val());

    if(parseFloat(precioFinal.val()) < precioFinalTemp){
    	let total = parseFloat(precio)*parseFloat(cantidad.val());
    	precioFinal.val(total.toFixed(2));
    	swal({
          title: "Precio total no permitido",
          text: `¡Precio total no puede ser inferior a ${precioFinalTemp}!`,
          type: "error",
          confirmButtonText: "¡Cerrar!"
        });
    }


    listarProductos(false);

});

/*=============================================
GUARDAR COMPRA
=============================================*/

$(".formularioCompra").on("submit", async function(e){

    e.preventDefault();
    
    let idVendedor = sessionStorage.getItem('u') ? sessionStorage.getItem('u') : 0;
    
    if(idVendedor == 0){
    	swal({
          title: "Error de operacion",
          text: "Inicie session para continuar",
          type: "error",
          confirmButtonText: "¡Cerrar!"
        });
    	return;
    }
    
    var str = $(this).serialize()+"&idVendedor="+idVendedor;

    let opt = await swal({
      title: "Guardar venta",
      text: "Este accion guardara la venta, esta seguro de continuar?",
      type: "warning",
      showCancelButton: true,
      cancelButtonText: "¡Cerrar!",
      confirmButtonText: "¡Ok!"
    }).then(function(result){

    	return result.value ? true : false;

    });
    
    if(!opt){
    	return;
    }
    
    $.ajax({
        cache: false,
        dataType: 'json',
        url: 'ajax/ventas.ajax.php',
        type: "POST",
        data: str,
        success: function(response){

            if(response.status == false){
            	swal({
		          title: "Error de operacion",
		          text: response.message,
		          type: "error",
		          confirmButtonText: "¡Cerrar!"
		        });
            }else{
            	swal({
		          title: "Operacion Exitosa",
		          text: response.message,
		          type: "success",
		          confirmButtonText: "¡Cerrar!"
		        }).then(function(result){
		        	if(result.value){
		        		window.location = "ventas";
		        		localStorage.removeItem('data_temporal')
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

	var precioItem = $(".nuevoPrecioProducto");
	
	var sumaTotalPrecio = 0;

	for(var i = 0; i < precioItem.length; i++){

		sumaTotalPrecio += parseFloat($(precioItem[i]).val());
	
    }

	$("#nuevoTotalVenta").val(sumaTotalPrecio);
	$("#totalVenta").val(sumaTotalPrecio);
	$("#nuevoTotalVenta").attr("total",sumaTotalPrecio);
	$('.totalVenta').val(sumaTotalPrecio);

}

/*=============================================
FORMATO AL PRECIO FINAL
=============================================*/

$("#nuevoTotalVenta").number(true, 2);

/*=============================================
LISTAR TODOS LOS PRODUCTOS
=============================================*/

function listarProductos(load = true){

	let listaProductos = [];
    var listaId = [];

	var descripcion = $(".nuevaDescripcionProducto");

	var cantidad = $(".nuevaCantidadProducto");

	var precio = $(".nuevoPrecioProducto");
   
    var totalItems = 0;
    
    if(localStorage.getItem('data_temporal')){
    	let temp = JSON.parse(localStorage.getItem('data_temporal'));
    	listaProductos = temp;
    	
    	temp.forEach((item) => {
    		listaId.push(item.id);
    	});
    }

	for(var i = 0; i < descripcion.length; i++){

		let find = listaProductos.find(item => item.id.toString() == $(descripcion[i]).attr("idProducto").toString());

		let producto = { 
						"id" : $(descripcion[i]).attr("idProducto"), 
						"descripcion" : $(descripcion[i]).val(),
					  	"cantidad" : $(cantidad[i]).val(),
					  	"stock" : $(cantidad[i]).attr("nuevoStock"),
					  	"precio" : $(precio[i]).attr("precioReal"),
		              	"precioCompra" : $(precio[i]).attr("precioCompraReal"),
					  	"total" : $(precio[i]).val()
					};
		
		if(!find){
			listaProductos.push(producto);
			listaId.push(producto.id);
		}else{
			let index = listaProductos.indexOf(find);
			
			listaProductos[index] = producto;
		}
		
	}

	totalItems = listaId.length;
	
	localStorage.setItem('data_temporal',JSON.stringify(listaProductos));
	$("#listaProductos").val(JSON.stringify(listaProductos));
    $("#listaId").val(JSON.stringify(listaId));
    $('.totalItems').html('<b>Nro Productos:</b> '+totalItems);
    
    if(load){
    	showProductos(listaProductos);
    }
    
    sumarTotalPrecios();
}


function showProductos(arr){

	let contenedor = document.querySelector(".nuevoProducto");
	contenedor.innerHTML = "";
	arr.forEach((item) => {
		contenedor.innerHTML += showProducto(item);
	});
	
}

function showProducto(obj){

	return `
		<div class="row" style="padding:5px 15px" id="producto_${obj.id}" >
			<div class="col-xs-6" style="padding-right:0px">
	          	<div class="input-group">
	              	<span class="input-group-addon">
		              	<button type="button" class="btn btn-danger btn-xs quitarProducto" 
		              	idProducto="${obj.id}"><i class="fa fa-times"></i></button>
	              	</span>
	              	<input type="text" class="form-control nuevaDescripcionProducto" idProducto="${obj.id}" 
	              	name="agregarProducto" value="${obj.descripcion}" readonly required>
	            </div>
	        </div>
	        <div class="col-xs-3">
	            <input type="number" class="form-control nuevaCantidadProducto"
	            idProducto="${obj.id}"
	            name="nuevaCantidadProducto" step="any" value="${obj.cantidad}" stock="${obj.stock}" nuevoStock="${obj.stock}" required>
	        </div>

	        <div class="col-xs-3 ingresoPrecio" style="padding-left:0px">
	        	<div class="input-group">
	            	<span class="input-group-addon"><i class="ion ion-social-usd"></i></span>
	                <input type="text" data-num="${obj.id}" id="nuevoPrecio${obj.id}"
	                idProducto="${obj.id}"
	                class="form-control nuevoPrecioProducto" 
	                precioReal="${obj.precio}" 
	                precioCompraReal="${obj.precioCompra}" 
	                name="nuevoPrecioProducto" 
	                value="${obj.total}"  required>
	 			</div>
	        </div>
	    </div>
	`;

}

function updateProductoLista(obj){
	let input_cantidad = $(`.nuevaCantidadProducto[idProducto="${obj.id}"]`);
	let input_precio = $(`.nuevoPrecioProducto[idProducto="${obj.id}"]`);
	let precio = parseFloat(input_precio.attr('precioReal'));
	let cantidad = parseFloat(input_cantidad.val()) + 1;
	let stock = parseFloat(obj.stock);

	if(cantidad > stock){
		swal("Advertencia.!",`Stock insuficiente, no puede superar a ${stock}`,'warning');
		return;
	}

	let total = precio*cantidad;
	input_precio.val(total.toFixed(2));
	input_cantidad.val(cantidad);

	listarProductos(false);
}

function actionAddProducto(formData){
	
	$.ajax({
        url:"ajax/productos.ajax.php",
      	method: "POST",
      	data: formData,
      	cache: false,
      	dataType:"json",
      	success:function(response){
      		
      		$('.codigoProducto').val('');

      		let listaId = $('#listaId').val() != '' ? JSON.parse($('#listaId').val()) : [];

      	    if(response.status){

      	    	if(listaId.includes(response.idProducto)){

					updateProductoLista(response.data);
					
				    return;

				}
      	    	
      	    	let p = showProducto(response.data);
      	    	$('.nuevoProducto').append(p);
	            listarProductos();
	            
      	    }else{
      	    	swal({
			      title: "Error.!",
			      text: response.message,
			      type: "warning",
			      confirmButtonText: "¡Cerrar!"
			    });
      	    }     

      	}

     });
}



}
