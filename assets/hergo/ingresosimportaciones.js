var glob_factorIVA=0.87;
var glob_factorRET=0.087;
let glob_guardar = false
var loc_almacen;
let hoy
let checkTipoCambio = false
let articulos = []

$(document).ready(function(){
    fechaMod = $('#fechamov_imp').val()
    if (fechaMod) {
        hoy = moment(fechaMod).format('DD-MM-YYYY')
    } else{
        hoy = moment().format('DD-MM-YYYY, hh:mm:ss a')
    }
    $('.fecha_ingreso').daterangepicker({
        startDate:hoy,
        locale: {
            format: 'DD-MM-YYYY'
        },
        singleDatePicker: true,
        showDropdowns: true
      });    
    loc_almacen= $("#almacen_imp").val();   
    if ($("#tipoDoc").val() == 1 || $("#tipoDoc").val() == 3) {
        $(".tipoDocumento").removeClass("hidden")
    } 
})
$(document).on("change", "#fechamov_imp", function () {
    let fecha = $('#fechamov_imp').val()
    if (hoy == fecha) {
        checkTipoCambio = true
    } else {
        console.log('buscar');
        $.ajax({
            type: "POST",
            async: false,
            url: base_url("index.php/Egresos/consultarTipoCambio"),
            dataType: "json",
            data: {
                fecha: fecha
            },
            success: function(data) {

                data ? checkTipoCambio = data : checkTipoCambio = false
               
            }
        });
        if (checkTipoCambio == false) {
            mostrarModal()
        } else {
            glob_tipoCambio = checkTipoCambio.tipocambio
            console.log(checkTipoCambio.fecha+ ' - ' +glob_tipoCambio)
        }
    }
})
$(document).on("click", "#setTipoCambio", function () {
    let fecha = $('#fechamov_imp').val()
    let tc = $('#tipocambio').val()
    if (!tc || tc == 0) {
        swal("Atencion!", "Ingrese tipo cambio valido")
        return false
    }
    $.ajax({
        type: "POST",
        url: base_url("index.php/Configuracion/updateTipoCambio"),
        dataType: "json",
        data: {
            id: 'egreso',
            fechaCambio: fecha,
            tipocambio: tc
        },
        success: function(data) {
            console.log(data);
            checkTipoCambio = true
            glob_tipoCambio = data.TipoCambio
            console.log(glob_tipoCambio);
            $('#modalTipoCambio').modal('hide')
            swal("Atencion!", "Agrego un tipo de cambio para" + formato_fecha_corta(data.fecha))
            $('#tipocambio').val('')
        }
    });
})
$(document).on("click","#agregar_articulo",function(){
    if(glob_guardar)
    {
        agregarArticulo();
    }
})
$(document).on("click",".eliminarArticulo",function(){   
   let id=$("#idArticulo").val()
    articulos.forEach(function(index, element) {
        if (index.id == id) {
            delete articulos[element]
        }
    })
    $(this).parents("tr").remove()
    calcularTotal()
})
$(document).on("change","#almacen_imp",function(){
    loc_almacen = $("#almacen_imp").val()
    swal("Atencion!", "Usted esta cambiado de Almacen")
}); 
$(document).ready(function(){ 

    $(".tiponumerico").inputmask({
        alias:"decimal",
        digits:4,
        groupSeparator: ',',
        autoGroup: true,
        autoUnmask:true
    }); 
    calcularTotal()  
})
$(document).on("change","#moneda_imp",function(){
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
$(document).on("click","#guardarMovimiento",function(){
    almForm = $('#almacen_imp').val()
    almUser = $('#idAlmacenUsuario').val()
    isAdmin = $('#isAdmin').val()
    fechaIngreso = $('#fechamov_imp').val()
    ingresoYear = moment(fechaIngreso).format('YYYY')
    var actualDate = new Date();
    var actualYear = actualDate.getFullYear();
    /*if (actualYear != ingresoYear) {
        swal("Error", "Fecha no se encuentra en la gestiòn actual", "error")
        return false
    }*/
    if (almForm != almUser && isAdmin == '') {
        swal("Error", "No se puede guardar movimiento", "error")
        return false
    }
    storeIngreso();
})
$(document).on("click","#cancelarMovimiento",function(){
    limpiarArticulo();
    limpiarCabecera();
    limpiarTabla();
})
$(document).on("click","#actualizarMovimiento",function(){
    updateIngreso();
})
$(document).on("click","#cancelarMovimientoActualizar",function(){
    window.location.href=base_url("Ingresos");
})
$(document).on("click","#anularMovimiento",function(){
    almForm = $('#almacen_imp').val()
    almUser = $('#idAlmacenUsuario').val()
    isAdmin = $('#isAdmin').val()
    if (almForm != almUser && isAdmin == '') {
        swal("Error", "No se puede Anular", "error")
        return false
    }
    mensajeAnular("#obs_imp",
        function(){
            anularMovimiento();
        },
        function(){
            window.location.href=base_url("Ingresos");  
        }
    );
    
  
})
$(document).on("click","#recuperarMovimiento",function(){   
    mensajeRecuperar("#obs_imp",
        function(){
             recuperarMovimiento();
        },
        function(){
            window.location.href=base_url("Ingresos");  
        }
    );
})
$(document).on("change","#tipoDoc",function(){
    if ($("#tipoDoc").val() == 1 || $("#tipoDoc").val() == 3) {
        $(".tipoDocumento").removeClass("hidden")
        $(".tipoDocumento").val('')
    } else {
        $(".tipoDocumento").addClass("hidden")
        swal({
            title: "Se eliminaran articulos",
            text: "Se quitaran articulos de tabla",
            type: "warning",        
            allowOutsideClick: false,                                                                        
            }).then(function(){
                limpiarTabla()
            })
        $(".tipoDocumento").val('')
    }
})
function cargandoSaldoCosto()
{
    $(".cargandoCostoSaldo").css("display","");
}
function finCargaSaldoCosto()
{
    $(".cargandoCostoSaldo").css("display","none");
}
function limpiarArticulo()
{
    $("#articulo_impTest").val("");
    $("#Descripcion_imp").val("");
    $("#cantidad_imp").val("");
    $("#punitario_imp").val("");
    $("#constounitario").val("");
    $("#unidad_imp").val("");
    $("#costo_imp").val("");
    $("#saldo_imp").val("");
    $("#constounitario").css("background-color","#eee");
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
    var moneda=$("#moneda_imp").val()
    
    var totales=$(".totalCosto").toArray();
    var totalDoc=$(".totalDoc").toArray();

    var total=0;
    var dato=0;
    $.each(totales,function(index, value){
        dato=$(value).inputmask('unmaskedvalue');        
        total+=(dato=="")?0:parseFloat(dato)
    })
    var totald=0;
    dato=0;
    console.log(moneda)
    $.each(totalDoc,function(index, value){
        dato=$(value).inputmask('unmaskedvalue');        
        totald+=(dato=="")?0:parseFloat(dato)
    })    
    if(moneda=="2")
    {
        $("#nombretotaldoc").html("Sus Doc");
        $("#nombretotalsis").html("Sus Sis");
    }
    else
    {
        $("#nombretotaldoc").html("Bs Doc");
        $("#nombretotalsis").html("Bs Sis");        
    }
    totald = (Math.round(totald * 100) / 100).toFixed(2);
    total = (Math.round(total * 100) / 100).toFixed(2);
    $("#totalacostodoc").val(totald)
    $("#totalacostobs").val(total)
}
//calculo de compras locales con y sin factura
function calculocompraslocales(cant,costo)
{
    var ret;    
    var pu//preciounitario
    pu=costo/cant;// calculamos el costo unitario      
    if($("#tipoDoc").val() == 2)  //sin factura      
        ret=pu*glob_factorRET+pu; //sinfactura  
    else                        
        ret=pu*glob_factorIVA; //confactura   
    //console.log(ret);       
    return ret;


}
function agregarArticulo() //faltaria el id costo; si se guarda en la base primero
{
    //idcosto=12;
    let id=$("#idArticulo").val()
    let codigo=$("#articulo_impTest").val()
    let descripcion=$("#Descripcion_imp").val()
    let cant=$("#cantidad_imp").inputmask('unmaskedvalue');
    let costo=$("#punitario_imp").inputmask('unmaskedvalue');    
    let totalfac=costo;
    cant=(cant=="")?0:cant;
    costo=(costo=="")?0:costo;
    let tipoingreso=$("#tipomov_imp2").val()
    let total;
    if (articulos.length>0) {
        if(articulos.map((el) => el.id).indexOf(id)>=0)
        {
            swal("Atencion", "Ya se tiene un registro con este codigo","info");
            return false;
        }
        articulos.push({id})

    } else {
        articulos.push({id})
    }


    if(tipoingreso==2)//si es compra local idcompralocal=2
    {
        totalfac = costo
        costo=calculocompraslocales(cant,costo)
    } 
    total=cant*costo; 
    let punitfac=cant==0?0:(totalfac/cant);
    let articulo
    if (tipoingreso==2) {
        articulo='<tr>'+ 
        '<td><input type="text" class="estilofila hidden" disabled value="'+id+'""></td>'+
        '<td><input type="text" class="estilofila" disabled value="'+codigo+'""></td>'+
        '<td><input type="text" class="estilofila" disabled value="'+descripcion+'"></input</td>'+
        '<td class="text-right"><input type="text" class="estilofila tiponumerico" disabled value="'+cant+'""></td>'+
        '<td class="text-right"><input type="text" class="estilofila tiponumerico" disabled value="'+punitfac+'""></td>'+  //nuevo P/U Factura
        '<td class="text-right"><input type="text" class="totalDoc estilofila tiponumerico" disabled value="'+totalfac+'""></td>'+ //nuevo Total Factura
        '<td class="text-right"><input type="text" class="estilofila tiponumerico" disabled value="'+costo+'""></td>'+
        '<td class="text-right"><input type="text" class="totalCosto estilofila tiponumerico" disabled value="'+total+'""></td>'+
        '<td><button type="button" class="btn btn-default eliminarArticulo" aria-label="Left Align"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></td>'+
    '</tr>'
    } else {
        punitfac = costo
        totalfac = total
        console.log('pu'+punitfac);
        console.log('total'+totalfac);
        articulo='<tr>'+ 
        '<td><input type="text" class="estilofila hidden" disabled value="'+id+'""></td>'+
        '<td><input type="text" class="estilofila" disabled value="'+codigo+'""></input></td>'+
        '<td><input type="text" class="estilofila" disabled value="'+descripcion+'"></input</td>'+
        '<td class="text-right"><input type="text" class="estilofila tiponumerico" disabled value="'+cant+'""></input></td>'+
        '<td class="text-right"><input type="text" class="estilofila tiponumerico" disabled value="'+punitfac+'""></input></td>'+  //nuevo P/U Factura
        '<td class="text-right"><input type="text" class="totalDoc estilofila tiponumerico" disabled value="'+totalfac+'""></input></td>'+ //nuevo Total Factura
        '<td class="text-right"><input type="text" class="estilofila tiponumerico" disabled value="'+costo+'""></input></td>'+
        '<td class="text-right"><input type="text" class="totalCosto estilofila tiponumerico" disabled value="'+total+'""></input></td>'+
        '<td><button type="button" class="btn btn-default eliminarArticulo" aria-label="Left Align"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></td>'+
    '</tr>'

    }
   
    $("#tbodyarticulos").append(articulo)
    $(".tiponumerico").inputmask({
        alias:"decimal",
        digits:4,
        groupSeparator: ',',
        autoGroup: true
    });
    calcularTotal()
    limpiarArticulo();
    document.getElementById("articulo_impTest").focus()
}
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
    var valuesToSubmit = $("#form_ingresoImportaciones").serialize();
    var tablaaux=tablatoarray();
    //console.log(tablaaux);
    if (!checkTipoCambio) {
        swal("Error", "No se tiene tipo de cambio para esta Fecha", "error")
        return false;
    }
    if(tablaaux.length>0)
    {
        var tabla=JSON.stringify(tablaaux);
        console.log(valuesToSubmit)
        valuesToSubmit+="&tabla="+tabla;

        retornarajax(base_url("index.php/Ingresos/guardarmovimiento"),valuesToSubmit,function(data)
        {
            estado=validarresultado_ajax(data);
            if(estado)
            {               
                if(data.respuesta) {
                    swal({
                        title: "Ingreso realizado!",
                        text: "El ingreso se guardo con éxito",
                        type: "success",        
                        allowOutsideClick: false,                                                                        
                        }).then(function(){
                            location.reload();
                            let imprimir = base_url("pdf/Ingresos/index/") + data.respuesta;
                            window.open(imprimir);
                        })
                }
            }
            else 
            {
                swal({
                    title: "Ingreso error!",
                    text: "El ingreso NO se guardo",
                    type: "error",        
                    allowOutsideClick: false,                                                                        
                    }).then(function(){
                        console.log(error);
                    })
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
    var valuesToSubmit = $("#form_ingresoImportaciones").serialize();
    var tablaaux=tablatoarray();
    console.log(valuesToSubmit)
    console.log(tablaaux);
    if(tablaaux.length>0)
    {
        var tabla=JSON.stringify(tablaaux);

        valuesToSubmit+="&tabla="+tabla;    
        retornarajax(base_url("index.php/Ingresos/actualizarmovimiento"),valuesToSubmit,function(data)
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
                    $(".mensaje_ok").html("Datos actualizados correctamente");
                    $("#modal_ok").modal("show");
                    window.location.href=base_url("Ingresos");
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
function updateIngreso()
{     
    let formData = new FormData($('#form_ingresoImportaciones')[0]); 
    let tableIngresos=tablatoarray();
    let tabla=JSON.stringify(tableIngresos);
    formData.append('tabla',tabla)

    if (tableIngresos.length>0) {
        $.ajax({
            url: base_url("index.php/Ingresos/updateIngreso"),
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (returndata) {
                if (returndata) {
                    swal({
                        title: "Ingreso modificado!",
                        text: "El ingreso se modificó con éxito",
                        type: "success",        
                        allowOutsideClick: false,                                                                        
                        }).then(function(){
                            console.log(returndata)
                            window.location.href=base_url("Ingresos");
                            let imprimir = base_url("pdf/Ingresos/index/") + returndata;
                            window.open(imprimir);
                        })
                    return false
                } else {
                    swal(
                        'Error',
                        'Error al actualizar , revise los datos e intente nuevamente',
                        'error'
                    )
                    return false
                }
            },
            error : function (returndata) {
                swal(
                    'Error',
                    'Error',
                    'error'
                )
                console.log(returndata);
            },
        });
    } else {
        swal("Error", "No se tiene datos en la tabla para guardar","error")
    }
}
function anularMovimiento()
{     
    var valuesToSubmit = $("#form_ingresoImportaciones").serialize();
    var tablaaux=tablatoarray();
   // console.log(valuesToSubmit)
   // console.log(tablaaux);
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
                   // window.location.href=base_url("ingresos");
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
function recuperarMovimiento()
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
        tabla.push(
                    Array(
                            $(datos[0]).val(),
                            $(datos[1]).val(),
                            $(datos[2]).val(),
                            $(datos[3]).inputmask('unmaskedvalue'),
                            $(datos[4]).inputmask('unmaskedvalue'),
                            $(datos[5]).inputmask('unmaskedvalue'),
                            $(datos[6]).inputmask('unmaskedvalue'),
                            $(datos[7]).inputmask('unmaskedvalue'),
                        ))
        //console.log(datos);
    })
    return(tabla)
    console.log(filas)
}
function mostrarModal()
{
    $('#modalTipoCambio').modal('show');
}


function storeIngreso()
{     
    let formData = new FormData($('#form_ingresoImportaciones')[0]); 
    let tableIngresos=tablatoarray();
    let tabla=JSON.stringify(tableIngresos);
    formData.append('tabla',tabla)
    if (!checkTipoCambio) {
        swal("Error", "No se tiene tipo de cambio para esta Fecha", "error")
        return false;
    }
    if (tableIngresos.length>0) {
        $.ajax({
            url: base_url("index.php/Ingresos/storeIngreso"),
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (returndata) {
                swal({
                    title: "Ingreso realizado!",
                    text: "El ingreso se guardo con éxito",
                    type: "success",        
                    allowOutsideClick: false,                                                                        
                    }).then(function(){
                        location.reload();
                        let imprimir = base_url("pdf/Ingresos/index/") + returndata;
                        window.open(imprimir);
                    })
            },
            error : function (returndata) {
                swal(
                    'Error',
                    'Error',
                    'error'
                )
            },
        });
    } else {
        swal("Error", "No se tiene datos en la tabla para guardar","error")
    }

}

let alm = $("#almacen_imp").val();
$(document).on("change","#almacen_imp",function(){
    alm = $("#almacen_imp").val();
    console.log(alm);
}); 

/*******************CLIENTE*****************/
$( function() {
    console.log(alm);
    $("#articulo_impTest").autocomplete(
    {      
        minLength: 2,
        autoFocus: true,
        source: function (request, response) {        
            $("#cargandocodigoTest").show(150)        
            $("#codigocorrectoTest").html('<i class="fa fa-times" style="color:#bf0707" aria-hidden="true"></i>')
            glob_guardar=false;
            $.ajax({
                url: base_url("index.php/Ingresos/retornararticulosTest"),
                dataType: "json",
                data: {
                    b: request.term,
                    a: loc_almacen
                },
                success: function(data) {
                response(data);    
                $("#cargandocodigoTest").hide(150)
                }
            });        
        }, 
        select: function( event, ui ) {       
            $("#codigocorrectoTest").html('<i class="fa fa-check" style="color:#07bf52" aria-hidden="true"></i>');
            $("#articulo_impTest").val( ui.item.codigo);
            $("#idArticulo").val( ui.item.id);
            $("#Descripcion_imp").val( ui.item.descripcion);
            $("#unidad_imp").val( ui.item.unidad);
            $("#saldo_imp").val( ui.item.saldo);
            $("#costo_imp").val( ui.item.cpp);
            console.log(ui)
            glob_guardar=true;
            return false;
        }
        }).autocomplete("instance")._renderItem = function( ul, item ) {
        return $( "<li>" ).append( "<a><div>" + item.codigo + " </div><div style='color:#615f5f; font-size:10px'>" + item.descripcion + "</div></a>" )
        .appendTo( ul );
    };
 });
/******************FIN CLIENTE*************/