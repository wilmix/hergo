var glob_factorIVA=0.87;
var glob_factorRET=0.087;
var loc_almacen;

$(document).ready(function(){    
    loc_almacen= $("#almacen_imp").val();    
})
$(document).on("change","#almacen_imp",function(){

    var tablaaux=tablatoarray();
    
    if(tablaaux.length>0)
    {
        swal("Atencion!", "Al cambiar el almacen se quitaran los articulos de la tabla")
        swal({
          title: "Atencion!",
          text: "Al cambiar el almacen se quitaran los articulos de la tabla",
          type: "warning",
          showCancelButton: true,
          cancelButtonText: "Cancelar",
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Continuar",
        
        },
        function(isConfirm){
          if (isConfirm) {
            limpiarArticulo();           
            limpiarTabla();
            loc_almacen= $("#almacen_imp").val();
          } else {
            $("#almacen_imp").val(loc_almacen);
          }
        });
    }
}); 


$(document).ready(function(){ 

    $(".tiponumerico").inputmask({
        alias:"decimal",
        digits:3,
        groupSeparator: ',',
        autoGroup: true,
        autoUnmask:true
    }); 
    var glob_agregar=false;
    var glob_guardar=false;
    calcularTotal()  
})
/*******************CLIENTE*****************/
$( function() {
    $("#cliente_egreso").autocomplete(
    {      
      minLength: 1,
      autoFocus: true,
      source: function (request, response) {        
        $("#cargandocliente").show(150)        
        $("#clientecorrecto").html('<i class="fa fa-times" style="color:#bf0707" aria-hidden="true"></i>')
        glob_guardar=false;
        $.ajax({
            url: base_url("index.php/egresos/retornararticulos"),
            dataType: "json",
            data: {
                b: request.term
            },
            success: function(data) {
               response(data);    
               $("#cargandocliente").hide(150)
              
            }
          });        
    }, 

      select: function( event, ui ) {       
         
          $("#clientecorrecto").html('<i class="fa fa-check" style="color:#07bf52" aria-hidden="true"></i>');
          $("#cliente_egreso").val( ui.item.nombreCliente + " - " + ui.item.documento);
          $("#idCliente").val( ui.item.idCliente);
          glob_guardar=true;
          return false;
      }
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
      
      return $( "<li>" )
        .append( "<a><div>" + item.nombreCliente + " </div><div style='color:#615f5f; font-size:10px'>" + item.documento + "</div></a>" )
        .appendTo( ul );
    };
 });
/******************FIN CLIENTE*************/
 $( function() {
    $("#articulo_imp").autocomplete(
    {      
      minLength: 1,
      autoFocus: true,
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

      select: function( event, ui ) {

        idAlmacen=$("#almacen_ori").val();
        console.log(idAlmacen)
         $.ajax({

            url: base_url("index.php/egresos/retornarpreciorticulo/"+ui.item.CodigoArticulo+"/"+idAlmacen),
            dataType: "json",
            data: {},
            success: function(data) {
                //response(data);                   
                console.log(data)
                $("#costo_ne").val(data.precio);
                $("#saldo_ne").val(data.ncantidad);              
                $("#punitario_ne").val(data.precio);
            }
          });    
         //fin agregar costo articulo
          $("#articulo_imp").val( ui.item.CodigoArticulo);
          $("#Descripcion_ne").val( ui.item.Descripcion);
          $("#unidad_ne").val(ui.item.Unidad);
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
        //agregarArticulo(idcosto);//despues de generar el id de costo se agrega la fila con el id de costo
        agregarArticulo();
    }
})
$(document).on("click",".eliminarArticulo",function(){    
    $(this).parents("tr").remove()
    calcularTotal()
})
function limpiarArticulo()
{
    inputarray=$(".filaarticulo").find("input").toArray();
    console.log(inputarray)
    $.each(inputarray,function(index,value)
    {
        $(value).val("")
    })        
    glob_agregar=false;
    $("#codigocorrecto").html('<i class="fa fa-times" style="color:#bf0707" aria-hidden="true"></i>')   
    
}
function limpiarCabecera()
{
    inputarray=$(".filacabecera").find("input").toArray();
    console.log(inputarray)
    $.each(inputarray,function(index,value)
    {
        $(value).val("")
    })        
    glob_agregar=false;
    $("#clientecorrecto").html('<i class="fa fa-times" style="color:#bf0707" aria-hidden="true"></i>')    
    $("#totalacostosus").val("");
    $("#totalacostobs").val("");
    $("#obs_ne").val("");
}
function limpiarTabla()
{
    $("#tbodyarticulos").find("tr").remove();
}
function calcularTotal()
{
    var moneda=$("#moneda_ne").val()
    var totalCosto=0;
    var totales=$(".totalCosto").toArray();
    var total=0;
    var dato=0;
    $.each(totales,function(index, value){
        dato=$(value).inputmask('unmaskedvalue');
     //   console.log(dato)
        total+=(dato=="")?0:parseFloat(dato)
    })
    //total=Math.round(total * 100) / 100
    if(moneda==1)
    {
        var totalDolares=total/glob_tipoCambio;
    }
    else
    {
        var totalDolares=total;
        total=total*glob_tipoCambio;

    }
   // console.log(total)
    $("#totalacostobs").val(total)
    $("#totalacostosus").val(totalDolares)
}
$(document).on("change","#moneda_ne",function(){
    calcularTotal()

})
$(document).on("keyup","#nfact_imp",function(){
    if($(this).val()=="SF")
    {
        $("#consinfac").html("(sin Factura)")
        $("#consinfac").css("color","#a60000")
    }
    else
    {
        $("#consinfac").html("(con Factura)")   
        $("#consinfac").css("color","#00a65a")
    }
})
//calculo de compras locales con y sin factura
function calculocompraslocales(cant,costo)
{
    var ret;    
    var pu//preciounitario
    pu=costo/cant;// calculamos el costo unitario      
    if($("#nfact_imp").val()!="SF")  //si tiene el texto SF es sin factura         
        ret=pu*glob_factorIVA; //confactura
    else                        
        ret=pu*glob_factorRET+pu; //sinfactura            
    return ret;

}
function agregarArticulo() //faltaria el id costo; si se guarda en la base primero
{
    //idcosto=12;
    var codigo=$("#articulo_imp").val()
    var descripcion=$("#Descripcion_ne").val()
    var cant=$("#cantidad_ne").inputmask('unmaskedvalue');
    var costo=$("#punitario_ne").inputmask('unmaskedvalue');
    var descuento=$("#descuento_ne").inputmask('unmaskedvalue');
    var totalfac=costo;
    var cant=(cant=="")?0:cant;
    var costo=(costo=="")?0:costo;
    var tipoingreso=$("#tipomov_imp2").val()
    var total;
    //console.log(tipoingreso)
   /* if(tipoingreso==2)//si es compra local idcompralocal=2
    {
 
        costo=calculocompraslocales(cant,costo)

    }*/
   // descuento=cant*costo*descuento/100;
   
    total=cant*costo;
    
    
    
    //console.log("cant",cant,"* costo",costo,"=",total)
    
    //var articulo='<tr caid="'+idcosto+'">'+ //costo articulo id
   // var punitfac=cant==0?0:(totalfac/cant);
    var articulo='<tr>'+ 
            '<td><input type="text" class="estilofila" disabled value="'+codigo+'""></input></td>'+
            '<td><input type="text" class="estilofila" disabled value="'+descripcion+'"></input</td>'+
            '<td class="text-right"><input type="text" class="estilofila tiponumerico" disabled value="'+cant+'""></input></td>'+
            '<td class="text-right"><input type="text" class="estilofila tiponumerico" disabled value="'+costo+'""></input></td>'+  //nuevo P/U Factura                
            '<td class="text-right"><input type="text" class="totalCosto estilofila tiponumerico" disabled value="'+total+'""></input></td>'+
            
            '<td><button type="button" class="btn btn-default eliminarArticulo" aria-label="Left Align"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></td>'+
        '</tr>'
    $("#tbodyarticulos").append(articulo)
    $(".tiponumerico").inputmask({
        alias:"decimal",
        digits:3,
        groupSeparator: ',',
        autoGroup: true
    });
    calcularTotal()
    limpiarArticulo();
}
$(document).on("keyup","#cantidad_imp,#punitario_imp",function(){
    var cant=$("#cantidad_imp").inputmask('unmaskedvalue');
    var costo=$("#punitario_imp").inputmask('unmaskedvalue'); 
    var tipoingreso=$("#tipomov_imp2").val()
    cant=(cant=="")?0:cant;
    costo=(costo=="")?0:costo;
    if(tipoingreso==2)//si es compra local idcompralocal=2
    {
        costo=calculocompraslocales(cant,costo)
    }
    //total=cant*costo;
    $("#constounitario").val(costo);//costo calculado
    /***para la alerta*******/
    var costobase=$("#costo_imp").inputmask('unmaskedvalue');//costo de base de datos
    alertacosto(costo,costobase);
})
function alertacosto(costounitario,costobase)
{
    var valormin=(parseFloat(costobase)-parseFloat(costobase*0.15))
    var valormax=(parseFloat(costobase)+parseFloat(costobase*0.15))
    if((costounitario>valormin)&&(costounitario<valormax))
    {
        //se encuentra en el rango correco
        $("#constounitario").css("background-color","#eee")
        $("#constounitario").css("color","#555555")

    }
    else
    {
        //fuera de rango
        $("#constounitario").css("background-color","red")
        $("#constounitario").css("color","#fff")
    }
}
function guardarmovimiento()
{     
    var valuesToSubmit = $("#form_egreso").serialize();
    var tablaaux=tablatoarray();
    
    if(tablaaux.length>0)
    {
        var tabla=JSON.stringify(tablaaux);
        
        valuesToSubmit+="&tabla="+tabla;

        retornarajax(base_url("index.php/traspasos/guardarmovimiento"),valuesToSubmit,function(data)
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
        
        swal("Error", "No se tiene datos en la tabla para guardar","error")
    }
}
function actualizarMovimiento()
{     
    var valuesToSubmit = $("#form_egreso").serialize();
    var tablaaux=tablatoarray();
    console.log(valuesToSubmit)
    console.log(tablaaux);
    if(tablaaux.length>0)
    {
        var tabla=JSON.stringify(tablaaux);

        valuesToSubmit+="&tabla="+tabla;    
        retornarajax(base_url("index.php/egresos/actualizarmovimiento"),valuesToSubmit,function(data)
        {
            estado=validarresultado_ajax(data);
            if(estado)
            {               
                if(data.respuesta)
                {
                    
                  //  $("#modalIgresoDetalle").modal("hide");
                    limpiarArticulo();
                    limpiarCabecera();
                    limpiarTabla();
                    $(".mensaje_ok").html("Datos actualizados correctamente");
                    $("#modal_ok").modal("show");
                    window.location.href=base_url("egresos");
                }
                else
                {
                    $(".mensaje_error").html("Error al actualizar los datos, intente nuevamente");
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
function anularMovimiento()// X
{     
    var valuesToSubmit = $("#form_ingresoImportaciones").serialize();
    var tablaaux=tablatoarray();
    console.log(valuesToSubmit)
    console.log(tablaaux);
    if(tablaaux.length>0)
    {
        var tabla=JSON.stringify(tablaaux);

        valuesToSubmit+="&tabla="+tabla;    
        retornarajax(base_url("index.php/ingresos/anularmovimiento"),valuesToSubmit,function(data)
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
                    $(".mensaje_ok").html("Datos anulados correctamente");
                    $("#modal_ok").modal("show");
                    window.location.href=base_url("ingresos");
                }
                else
                {
                    $(".mensaje_error").html("Error al anular los datos, intente nuevamente");
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
function recuperarMovimiento()// X
{     
    var valuesToSubmit = $("#form_ingresoImportaciones").serialize();
    var tablaaux=tablatoarray();
    console.log(valuesToSubmit)
    console.log(tablaaux);
    if(tablaaux.length>0)
    {
        var tabla=JSON.stringify(tablaaux);

        valuesToSubmit+="&tabla="+tabla;    
        retornarajax(base_url("index.php/ingresos/recuperarmovimiento"),valuesToSubmit,function(data)
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
                    $(".mensaje_ok").html("Datos recuperados correctamente");
                    $("#modal_ok").modal("show");
                    window.location.href=base_url("ingresos");
                }
                else
                {
                    $(".mensaje_error").html("Error al recuperar los datos, intente nuevamente");
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
        tabla.push(Array($(datos[0]).val(),$(datos[1]).val(),$(datos[2]).inputmask('unmaskedvalue'),$(datos[3]).inputmask('unmaskedvalue'),$(datos[4]).inputmask('unmaskedvalue'),$(datos[5]).inputmask('unmaskedvalue'),$(datos[6]).inputmask('unmaskedvalue')))
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
$(document).on("click","#actualizarMovimiento",function(){
    actualizarMovimiento();
})
$(document).on("click","#cancelarMovimientoActualizar",function(){
    window.location.href=base_url("ingresos");
})

$(document).on("click","#anularMovimiento",function(){
    anularMovimiento();
    limpiarArticulo();
    limpiarCabecera();
    limpiarTabla();
})
$(document).on("click","#recuperarMovimiento",function(){
    recuperarMovimiento();
    limpiarArticulo();
    limpiarCabecera();
    limpiarTabla();
})