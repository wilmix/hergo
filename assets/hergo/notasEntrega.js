var glob_factorIVA=0.87;
var glob_factorRET=0.087;
var loc_almacen;
var glob_guardar=false;
var glob_precio_egreso=0;
let hoy = moment().format('DD-MM-YYYY, hh:mm:ss a');
$(document).ready(function(){ 
    $('.fecha_egreso').daterangepicker({
        singleDatePicker: true,
        startDate:hoy,
        autoApply:true,
        locale: {
            format: 'DD-MM-YYYY'
        },
        showDropdowns: true,
      });
    loc_almacen= $("#almacen_imp").val();
    cargarArticulos();    
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
    glob_guardar=false;
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
            url: base_url("index.php/Egresos/retornarClientes"),
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

        $("#Descripcion_ne").val("");
        $("#cantidad_ne").val("");
        $("#punitario_ne").val("");
        $("#descuento_ne").val("");
        $("#unidad_ne").val("");
        $("#costo_ne").val("");
        $("#saldo_ne").val("");
        glob_agregar=false;
        /*$.ajax({
            url: base_url("index.php/Ingresos/retornararticulos"),
            dataType: "json",
            data: {
                b: request.term
            },
            success: function(data) {
               response(data);    
               $("#cargandocodigo").hide(150)
              
            }
          });        */
          /********************/    
        var busqueda=request.term.trim()

        if(busqueda.length > 1)
        {
            var ExpReg = new RegExp( busqueda ,"i");        
            response(glob_art.fuzzy(ExpReg));    
          
        }
        
        $("#cargandocodigo").hide(150);
              
              
        
        /********************/  
    }, 

      select: function( event, ui ) {

        idAlmacen=$("#almacen_ne").val();
        cargandoSaldoPrecioArticulo()
        //console.log(idAlmacen)
         $.ajax({

            url: base_url("index.php/Ingresos/retornarSaldoPrecioArticulo/"+ui.item.CodigoArticulo+"/"+idAlmacen),
            dataType: "json",
            data: {},
            success: function(data) {
                //response(data);                   
                console.log(data)
                finCargaSaldoPrecioArticulo()
                glob_precio_egreso=data.precio;
                $("#costo_ne").val(data.precio);
                $("#saldo_ne").val(data.ncantidad);              
                $("#punitario_ne").val("");
                cambiarMoneda()
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
function cargandoSaldoPrecioArticulo()
{
    $(".cargandoPrecioSaldo").css("display","");
}
function finCargaSaldoPrecioArticulo()
{
    $(".cargandoPrecioSaldo").css("display","none");
}
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
    //console.log(inputarray)
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
        //console.log(dato)
        total+=(dato=="")?0:parseFloat(dato)
    })
    total = (Math.round(total * 100) / 100).toFixed(2);
    if(moneda==1)
    {
        var totalDolares=total/glob_tipoCambio;
        totalDolares = (Math.round(totalDolares * 100) / 100).toFixed(2);
    }
    else
    {
        var totalDolares=total;
        total=total*glob_tipoCambio;
        

    }
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
function calculocompraslocales(cant, costo) {
    var ret;
    var pu //preciounitario
    pu = costo / cant; // calculamos el costo unitario      
    if ($("#nfact_imp").val() != "SF") //si tiene el texto SF es sin factura         
        ret = pu * glob_factorIVA; //confactura
    else
        ret = pu * glob_factorRET + pu; //sinfactura            
    return ret;

}

function agregarArticulo() //faltaria el id costo; si se guarda en la base primero
{
    //idcosto=12;
    var codigo = $("#articulo_imp").val()
    var descripcion = $("#Descripcion_ne").val()
    var cant = $("#cantidad_ne").inputmask('unmaskedvalue');
    var costo = $("#punitario_ne").inputmask('unmaskedvalue');
    var descuento = $("#descuento_ne").inputmask('unmaskedvalue');
    var totalfac = costo;
    var cant = (cant == "") ? 0 : cant;
    var costo = (costo == "") ? 0 : costo;
    var tipoingreso = $("#tipomov_imp2").val();
    let saldoAlmacen = $("#saldo_ne").val();
    var codigoArticulo = $("#articulo_imp").val();
    var total;

    if (Number(cant) > 0 && Number(costo)>=0) 
    {
        if (Number(cant)<=Number(saldoAlmacen) && Number(saldoAlmacen) > 0 ) // mensaje para  saldo de almacen 
        {
            console.log(Number(cant)<=Number(saldoAlmacen) && Number(saldoAlmacen) > 0 )
            agregarArticuloEgresos();
          
        } else {
            swal({
                title: 'Saldo Insuficiente',
                html: "No tiene suficiente <b>" + codigoArticulo + "</b> en su almacen.<br>" + "Desea generar <b>NEGATIVO</b>?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Agregar',
                cancelButtonText: 'No, Cancelar'
            }).then(
                result=>
                {
                    agregarArticuloEgresos();
                    swal({
                        html: 'Usted generó un <b>NEGATIVO</b> en ' + codigoArticulo,
                        //timer: 4000
                    });
                    
                },
                dismiss => {
                    swal(
                        'No agregado',
                        'Gracias por no generar negativos :)',
                        'error'
                    )
                }
                );
                
        }

    } else {
        swal(
            'Oops...',
            'Ingrese cantidad o precio  válido!',
            'error'
        )
    }
}

function agregarArticuloEgresos()
{
    var codigo=$("#articulo_imp").val()
    var descripcion=$("#Descripcion_ne").val()
    var cant=$("#cantidad_ne").inputmask('unmaskedvalue');
    var costo=$("#punitario_ne").inputmask('unmaskedvalue');
    var descuento=$("#descuento_ne").inputmask('unmaskedvalue');
    var totalfac=costo;
    var cant=(cant=="")?0:cant;
    var costo=(costo=="")?0:costo;
    var tipoingreso=$("#tipomov_imp2").val();
    var total;

    descuento=cant*costo*descuento/100;
    costo=costo;
    total=(cant*costo)-descuento;   

    var articulo='<tr>'+ 
            '<td><input type="text" class="estilofila" disabled value="'+codigo+'""></input></td>'+
            '<td><input type="text" class="estilofila" disabled value="'+descripcion+'"></input</td>'+
            '<td class="text-right"><input type="text" class="estilofila tiponumerico" disabled value="'+cant+'""></input></td>'+
            '<td class="text-right"><input type="text" class="estilofila tiponumerico" disabled value="'+costo+'""></input></td>'+  //nuevo P/U Factura
            '<td class="text-right"><input type="text" class="estilofila tiponumerico" disabled value="'+descuento+'""></input></td>'+ //nuevo Total Factura            
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
    document.getElementById("articulo_imp").focus()
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
    console.log($("#_tipomov_ne").val())
    var valuesToSubmit = $("#form_egreso").serialize();
    console.log(valuesToSubmit);
    var tipoEgreso=$("#tipomov_ne2").text();
    var tablaaux=tablatoarray();
    if($("#_tipomov_ne").val()==9) //continuar en el caso de que el tipo de movimiento es baja de producto
        var auxContinuar=true
    else
        var auxContinuar=false

    console.log(!glob_guardar,!auxContinuar);
    if(!glob_guardar && !auxContinuar)
    {
        swal("Error", "Seleccione el cliente","error")
        console.log("este tipo "+tipoEgreso);
        return 0;
    }    
    if(tablaaux.length>0)
    {
        var tabla=JSON.stringify(tablaaux);
        console.log(valuesToSubmit)
        valuesToSubmit+="&tabla="+tabla;

        retornarajax(base_url("index.php/Egresos/guardarmovimiento"),valuesToSubmit,function(data)
        {
            estado=validarresultado_ajax(data);
            if(estado)
            {               
                if(data.respuesta)
                {
                    
                    $("#modalIgresoDetalle").modal("hide");
                    swal({
                        title: 'Egreso realizado!',
                        text: tipoEgreso+" guardada con éxito",
                        type: 'success', 
                        showCancelButton: false
                    }).then(
                          function(result) {
                            location.reload();
                          });
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
    {        var tabla=JSON.stringify(tablaaux);

        valuesToSubmit+="&tabla="+tabla;    
        retornarajax(base_url("index.php/Egresos/actualizarmovimiento"),valuesToSubmit,function(data)
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
                    //$(".mensaje_ok").html("Datos actualizados correctamente");
                    //$("#modal_ok").modal("show");
                    swal(
                          'Modificación realizada!',
                          'La modificación se realizó con éxito!',
                          'success'
                        )
                    window.location.href=base_url("Egresos");
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
        retornarajax(base_url("index.php/Ingresos/anularmovimiento"),valuesToSubmit,function(data)
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
                    window.location.href=base_url("Ingresos");
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
        retornarajax(base_url("index.php/Ingresos/recuperarmovimiento"),valuesToSubmit,function(data)
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
                    window.location.href=base_url("Ingresos");
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
    window.location.href=base_url("Ingresos");
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

$(document).on("change","#moneda_ne",function(){
    cambiarMoneda()
    
})

function cambiarMoneda()
{
    if ($("#moneda_ne").val()==1) 
    {
        $(".costo_ne_label").html("Precio Bs")
        //$(".punitario_ne_class").val(glob_precio_egreso)   

    }
    else
    {
        $(".costo_ne_label").html("Precio Dolares")
        //$(".punitario_ne_class").val(glob_precio_egreso/glob_tipoCambio)
    }
}




function anularMovimientoEgreso()
{     
   
        var valuesToSubmit = $("#form_egreso").serialize();
        var tablaaux=tablatoarray();
        console.log(valuesToSubmit)
        console.log(tablaaux);
        if(tablaaux.length>0)
        {
            var tabla=JSON.stringify(tablaaux);
            valuesToSubmit+="&tabla="+tabla;    
            retornarajax(base_url("index.php/Egresos/anularmovimiento"),valuesToSubmit,function(data)
            {
                estado=validarresultado_ajax(data);
                if(estado)
                {               
                    if(data.respuesta)
                    {
                        
                       /* swal(
                                'Anulado!',
                                'El movimiento ha sido anulado.',
                                'success'
                            )*/
                       
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
function recuperarMovimientoEgreso()
{
    var valuesToSubmit = $("#form_egreso").serialize();
    var tablaaux=tablatoarray();
    console.log(valuesToSubmit)
    console.log(tablaaux);
    if(tablaaux.length>0)
    {
        var tabla=JSON.stringify(tablaaux);
        valuesToSubmit+="&tabla="+tabla;    
        retornarajax(base_url("index.php/Egresos/recuperarmovimiento"),valuesToSubmit,function(data)
        {
            estado=validarresultado_ajax(data);
            if(estado)
            {               
                if(data.respuesta)
                {
               /* swal(
                        'Recuperado!',
                        'El movimiento ha sido recuperado.',
                        'success' 
                    )
                    window.location.href=base_url("egresos");*/
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

$(document).on("click","#anularMovimientoEgreso",function(){
    mensajeAnular("#obs_ne",
        function(){
            anularMovimientoEgreso();
        },
        function(){
            window.location.href=base_url("Egresos");
        }
    );
    
})

$(document).on("click","#recuperarMovimientoEgreso",function(){
     mensajeRecuperar("#obs_ne",
        function(){
            recuperarMovimientoEgreso();
        },
        function(){
             window.location.href=base_url("Egresos");
        }
    );
})

