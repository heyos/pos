/*=============================================
CARGAR LA TABLA DINÁMICA DE PRODUCTOS
=============================================*/

var perfilOculto = $("#perfilOculto").val();
var arr = ['productos'];

if(arr.includes($('#ruta').val())){

$('.tablaProductos').DataTable( {
    "ajax": "ajax/datatable-productos.ajax.php?perfilOculto="+perfilOculto,
    "deferRender": true,
	"retrieve": true,
	"processing": true,
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

} );

/*=============================================
CAPTURANDO LA CATEGORIA PARA ASIGNAR CÓDIGO
=============================================*/
$("#nuevaCategoria").change(function(){

	var idCategoria = $(this).val();

	var datos = new FormData();
  	datos.append("idCategoria", idCategoria);

  	$.ajax({

      url:"ajax/productos.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType:"json",
      success:function(respuesta){

      	if(!respuesta){

      		var nuevoCodigo = idCategoria+"01";
      		$("#nuevoCodigo").val(nuevoCodigo);

      	}else{

      		var nuevoCodigo = Number(respuesta["codigo"]) + 1;
          	$("#nuevoCodigo").val(nuevoCodigo);

      	}
                
      }

  	})

})

/*=============================================
AGREGANDO PRECIO DE VENTA
=============================================*/
$("#nuevoPrecioCompra, #editarPrecioCompra").change(function(){

	if($(".porcentaje").prop("checked")){

		var valorPorcentaje = $(".nuevoPorcentaje").val();
		
		var porcentaje = Number(($("#nuevoPrecioCompra").val()*valorPorcentaje/100))+Number($("#nuevoPrecioCompra").val());

		var editarPorcentaje = Number(($("#editarPrecioCompra").val()*valorPorcentaje/100))+Number($("#editarPrecioCompra").val());

		$("#nuevoPrecioVenta").val(porcentaje);
		$("#nuevoPrecioVenta").prop("readonly",true);

		$("#editarPrecioVenta").val(editarPorcentaje);
		$("#editarPrecioVenta").prop("readonly",true);

	}

})

/*=============================================
CAMBIO DE PORCENTAJE
=============================================*/
$(".nuevoPorcentaje").change(function(){

	if($(".porcentaje").prop("checked")){

		var valorPorcentaje = $(this).val();
		
		var porcentaje = Number(($("#nuevoPrecioCompra").val()*valorPorcentaje/100))+Number($("#nuevoPrecioCompra").val());

		var editarPorcentaje = Number(($("#editarPrecioCompra").val()*valorPorcentaje/100))+Number($("#editarPrecioCompra").val());

		$("#nuevoPrecioVenta").val(porcentaje);
		$("#nuevoPrecioVenta").prop("readonly",true);

		$("#editarPrecioVenta").val(editarPorcentaje);
		$("#editarPrecioVenta").prop("readonly",true);

	}

})

$(".porcentaje").on("ifUnchecked",function(){

	$("#nuevoPrecioVenta").prop("readonly",false);
	$("#editarPrecioVenta").prop("readonly",false);

})

$(".porcentaje").on("ifChecked",function(){

	$("#nuevoPrecioVenta").prop("readonly",true);
	$("#editarPrecioVenta").prop("readonly",true);

})

/*=============================================
SUBIENDO LA FOTO DEL PRODUCTO
=============================================*/

$(".nuevaImagen").change(function(){

	var imagen = this.files[0];
	
	/*=============================================
  	VALIDAMOS EL FORMATO DE LA IMAGEN SEA JPG O PNG
  	=============================================*/

  	if(imagen["type"] != "image/jpeg" && imagen["type"] != "image/png"){

  		$(".nuevaImagen").val("");

  		 swal({
		      title: "Error al subir la imagen",
		      text: "¡La imagen debe estar en formato JPG o PNG!",
		      type: "error",
		      confirmButtonText: "¡Cerrar!"
		    });

  	}else if(imagen["size"] > 2000000){

  		$(".nuevaImagen").val("");

  		 swal({
		      title: "Error al subir la imagen",
		      text: "¡La imagen no debe pesar más de 2MB!",
		      type: "error",
		      confirmButtonText: "¡Cerrar!"
		    });

  	}else{

  		var datosImagen = new FileReader;
  		datosImagen.readAsDataURL(imagen);

  		$(datosImagen).on("load", function(event){

  			var rutaImagen = event.target.result;

  			$(".previsualizar").attr("src", rutaImagen);

  		})

  	}
})

/*=============================================
EDITAR PRODUCTO
=============================================*/

$(".tablaProductos tbody").on("click", "button.btnEditarProducto", function(){

	var idProducto = $(this).attr("idProducto");
	
	var datos = new FormData();
    datos.append("idProducto", idProducto);

     $.ajax({

      url:"ajax/productos.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType:"json",
      success:function(respuesta){
          
          var datosCategoria = new FormData();
          datosCategoria.append("idCategoria",respuesta["id_categoria"]);

           $.ajax({

              url:"ajax/categorias.ajax.php",
              method: "POST",
              data: datosCategoria,
              cache: false,
              contentType: false,
              processData: false,
              dataType:"json",
              success:function(respuesta){
                  
                  $("#editarCategoria").val(respuesta["id"]);
                  $("#editarCategoria").html(respuesta["categoria"]);

              }

          })

           $("#editarCodigo").val(respuesta["codigo"]);

           $("#editarDescripcion").val(respuesta["descripcion"]);

           $("#editarStock").val(respuesta["stock"]);

           $("#editarPrecioCompra").val(respuesta["precio_compra"]);

           $("#editarPrecioVenta").val(respuesta["precio_venta"]);

           $("#editarId").val(respuesta["id"]);

           if(respuesta["imagen"] != ""){

           	$("#imagenActual").val(respuesta["imagen"]);

           	$(".previsualizar").attr("src",  respuesta["imagen"]);

           }

      }

  })

})

/*=============================================
ELIMINAR PRODUCTO
=============================================*/

$(".tablaProductos tbody").on("click", "button.btnEliminarProducto", function(){

	var idProducto = $(this).attr("idProducto");
	var codigo = $(this).attr("codigo");
	var imagen = $(this).attr("imagen");
	
	swal({

		title: '¿Está seguro de borrar el producto?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, borrar producto!'
        }).then(function(result) {
        if (result.value) {

        	window.location = "index.php?ruta=productos&idProducto="+idProducto+"&imagen="+imagen+"&codigo="+codigo;

        }


	})

});


document.querySelector('#barcodeList').addEventListener("click",async () => {

  blockPage();
  const response = await getListProductos();

  if(response.status){
    htmlProductos(response.data);
    $('#all_data').val(JSON.stringify(response.data));
  }

  unBlockPage();
});

document.querySelector('#filtro').addEventListener("keyup",()=>{

  let textoBuscado = document.querySelector('#filtro').value;
  let data = JSON.parse($('#all_data').val());
  
  const expresionRegular = new RegExp(textoBuscado, 'i');

  const filter = textoBuscado == "" ? data : data.filter(objeto => expresionRegular.test(objeto.descripcion));
  htmlProductos(filter);
});

$(".tablaProductos tbody").on("click", "button.btnBarcode", async function(){

  let idProducto = $(this).attr("idProducto");
  let descripcion = $(this).attr("descripcion");
  let precio = $(this).attr("precio");
  let codigo = $(this).attr("codigo");

  let arr = [
    {
      descripcion: descripcion,
      precio_venta: precio,
      codigo: codigo
    }
  ];
  
  let barcode = await getBarcode(arr);
  
  if(barcode.status){
    arr = barcode.data;
    getBarcodePdf(arr);
  }
  

});

async function getBarcode(arr){

  try{

    let formData = new FormData();

    formData.append('data',JSON.stringify(arr));

    let response = await fetch("api_barcode/",{
      method: "POST",
      body: formData
    }),
    json = await response.json();

    return json;

  }catch(e){
    console.log("getBarcode error:>>",e);
    return {status: false}
  }   

}
	
async function getBarcodePdf(arr){

  try{
    blockPage();
    let formData = new FormData();

    formData.append('data',JSON.stringify(arr));
    formData.append('accion','barcode');
    
    let response = await fetch("ajax/productos.ajax.php",{
      method: "POST",
      body: formData
    }),
    
    blob = await response.blob();

    if(blob.type =="application/pdf"){
      
      const url = URL.createObjectURL(blob);
      // const link = document.createElement('a');
      // link.href = url;
      // link.download = 'barcode.pdf';

      // document.body.appendChild(link);

      // link.click();

      // document.body.removeChild(link);
      $('#modalBarcode').modal('show')
      $('#iframe').attr('src',url);
    }
    unBlockPage();
  }catch(e){
    console.log("getBarcode error:>>",e);
    return {type: null}
  }   

}

async function getListProductos(){

  try{
    
    let formData = new FormData();

    formData.append('accion','data_productos');
    
    let response = await fetch("ajax/productos.ajax.php",{
      method: "POST",
      body: formData
    }),
    
    json = await response.json();

    return json;
    
  }catch(e){
    console.log("getListProductos error:>>",e);
    return {status: false}
  }   

}

function htmlProductos(arr){
    
  const body_productos = document.querySelector('#body_productos');

  let html = `
    <tr>
      <td colspan="4" class="text-center">
        No se encontraron registros
      </td>
    </tr>
  `;

  if(arr.length > 0){
    html = "";
    arr.forEach((item) => {
      let obj = JSON.stringify(item);
      html += `
        <tr>
          <td>${item.codigo}</td>
          <td>${item.descripcion}</td>
          <td>${item.precio_venta}</td>
          <td class="text-center">
            <input type="checkbox" class="check" codigo="${obj.codigo}" descripcion="${obj.descripcion}" precio="${item.precio_venta}">
          </td>
        </tr>
      `;
    });
  }
    
  body_productos.innerHTML = html;
  
}


}