$(document).ready(function(){    
    $(".tiponumerico").inputmask({
        alias:"decimal",
        digits:2,
        groupSeparator: ',',
        autoGroup: true,
        autoUnmask:true
    }); 
    var glob_agregar=false;
})

 $( function() {
    $("#articulo_imp").autocomplete(
    {      
      minLength: 2,
      source: function (request, response) {        
        $("#cargandocodigo").show(150)
        $("#Descripcion_imp").val('');
        $("#codigocorrecto").html('<i class="fa fa-times" style="color:#bf0707" aria-hidden="true"></i>')
        glob_agregar=false;
        $.ajax({
            url: base_url("index.php/ingresos/retornararticulos"),
            dataType: "json",
            data: {
                b: request.term
            },
            success: function(data) {
               response(data);    
               $("#cargandocodigo").hide(150)
              
            }
          });        
    }, 
     /* focus: function( event, ui ) {
          //$(".solicitanuevo").val( ui.item.nombre );//si se instancio como indice un valor en este caso array("nombre"=> "valor")
          $(".solicitanuevo").val( ui.item[0] );
         // carregarDados();
          return false;
      },*/
      select: function( event, ui ) {
      
          $("#articulo_imp").val( ui.item.CodigoArticulo);
          $("#Descripcion_imp").val( ui.item.Descripcion);
          $("#unidad_imp").val(ui.item.Unidad);
          $("#codigocorrecto").html('<i class="fa fa-check" style="color:#07bf52" aria-hidden="true"></i>');
          glob_agregar=true;
          return false;
      }
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
      
      return $( "<li>" )
        .append( "<a><div>" + item.CodigoArticulo + " </div><div style='color:#615f5f; font-size:10px'>" + item.Descripcion + "</div></a>" )
        .appendTo( ul );
    };
 });
$(document).on("click","#agregar_articulo",function(){
    if(glob_agregar)
    {
        agregarArticulo();
    }
})
$(document).on("click",".eliminarArticulo",function(){    
    $(this).parents("tr").remove()
    calcularTotal()
})
function limpiarArticulo()
{
    $("#articulo_imp").val("")
    $("#Descripcion_imp").val("")
    $("#cantidad_imp").val("")
    $("#punitario_imp").val("")
    glob_agregar=false;
    $("#codigocorrecto").html('<i class="fa fa-times" style="color:#bf0707" aria-hidden="true"></i>')
}
function limpiarCabecera()
{
    $("#ordcomp_imp").val("")
    $("#nfact_imp").val("")
    $("#ningalm_imp").val("")
    $("#obs_imp").val("")
    $("#totalacostosus").val("")
    $("#totalacostobs").val("")
   
}
function limpiarTabla()
{
    $("#tbodyarticulos").find("tr").remove();
}
function calcularTotal()
{
    var totalCosto=0;
    var totales=$(".totalCosto").toArray();
    var total=0;
    var dato=0;
    $.each(totales,function(index, value){
        dato=$(value).inputmask('unmaskedvalue');
        //console.log(dato)
        total+=(dato=="")?0:parseFloat(dato)
    })
    //total=Math.round(total * 100) / 100
    
    var totalDolares=total/glob_tipoCambio;
    console.log(totalDolares)
    //totalDolares=Math.round(totalDolares * 100) / 100
    //total=total.toLocaleString()
    $("#totalacostobs").val(total)

    //totalDolares=totalDolares.toLocaleString()
    $("#totalacostosus").val(totalDolares)


}
function agregarArticulo()
{
    var codigo=$("#articulo_imp").val()
    var descripcion=$("#Descripcion_imp").val()
    var cant=$("#cantidad_imp").inputmask('unmaskedvalue');
    var costo=$("#punitario_imp").inputmask('unmaskedvalue');
    
    var cant=(cant=="")?0:cant;
    var costo=(costo=="")?0:costo;
    //costo=costo.toFixed(2);
    var total=cant*costo;
    console.log("cant",cant,"* costo",costo,"=",total)
    
    var articulo='<tr>'+
            '<td><input type="text" class="estilofila" disabled value='+codigo+'></input></td>'+
            '<td><input type="text" class="estilofila" disabled value='+descripcion+'></input</td>'+
            '<td class="text-right"><input type="text" class="estilofila tiponumerico" disabled value='+cant+'></input></td>'+
            '<td class="text-right"><input type="text" class="estilofila tiponumerico" disabled value='+costo+'></input></td>'+
            '<td class="text-right"><input type="text" class="totalCosto estilofila tiponumerico" disabled value='+total+'></input></td>'+
            '<td><button type="button" class="btn btn-default eliminarArticulo" aria-label="Left Align"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></td>'+
        '</tr>'
    $("#tbodyarticulos").append(articulo)
    $(".tiponumerico").inputmask({
        alias:"decimal",
        digits:2,
        groupSeparator: ',',
        autoGroup: true
    });
    calcularTotal()
    limpiarArticulo();
}
function guardarmovimiento()
{     
    var valuesToSubmit = $("#form_ingresoImportaciones").serialize();
    var tablaaux=tablatoarray();
    console.log(tablaaux);
    if(tablaaux.length>0)
    {
        var tabla=JSON.stringify(tablaaux);

        valuesToSubmit+="&tabla="+tabla;    
        retornarajax(base_url("index.php/ingresos/guardarmovimiento"),valuesToSubmit,function(data)
        {
            estado=validarresultado_ajax(data);
            if(estado)
            {               
                if(data.respuesta)
                {
                    
                    $("#modalIgresoDetalle").modal("hide");
                    limpiarArticulo();
                    limpiarCabecera();
                    limpiarTabla();
                    $(".mensaje_ok").html("Datos almacenados correctamente");
                    $("#modal_ok").modal("show");
                }
                else
                {
                    $(".mensaje_error").html("Error al almacenar los datos, intente nuevamente");
                    $("#modal_error").modal("show");
                }
                
            }
        })      
    }
    else
    {
        alert("no se tiene datos en la tabla para guardar")
    }
}
function tablatoarray()
{
    var tabla=new Array()
    var filas=$("#tbodyarticulos").find("tr").toArray()
    var datos=""
    $.each(filas,function(index,value){
        datos=$(value).find("input").toArray()
        tabla.push(Array($(datos[0]).val(),$(datos[1]).val(),$(datos[2]).inputmask('unmaskedvalue'),$(datos[3]).inputmask('unmaskedvalue'),$(datos[4]).inputmask('unmaskedvalue')))
        //console.log(datos);
    })
    return(tabla)
    //console.log(filas)
}
$(document).on("click","#guardarMovimiento",function(){
    guardarmovimiento();
})
$(document).on("click","#cancelarMovimiento",function(){
    limpiarArticulo();
    limpiarCabecera();
    limpiarTabla();
})