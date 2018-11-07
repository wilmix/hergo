let glob_factorIVA = 0.87;
let glob_factorRET = 0.087;
let loc_almacen;
let glob_guardar = false;
let glob_precio_egreso = 0;
let hoy
let idEgreso 
$(document).ready(function () {
    fechaModEgreso = $('#fechamov_ne').val()
    if (fechaModEgreso) {
        hoy = moment(fechaModEgreso).format('DD-MM-YYYY')
    } else{
        hoy = moment().format('DD-MM-YYYY')
    }

    $('.fecha_egreso').daterangepicker({
        singleDatePicker: true,
        startDate: hoy,
        autoApply: true,
        locale: {
            format: 'DD-MM-YYYY'
        },
        showDropdowns: true,
    });
    loc_almacen = $("#almacen_ne").val();
    idEgreso = $('#idegreso').val()
    if (idEgreso) {
        retornarTablaEgresoDetalle(idEgreso)
        calcularTotalEgresoMod()
    } else {
        console.log('crear egreso');
    }
    //cargarArticulos();
    
})
$(document).ready(function () {
    tablaEgresoDetalle()
    $(".tiponumerico").inputmask({
        alias: "decimal",
        digits: 4,
        groupSeparator: ',',
        autoGroup: true,
        autoUnmask: true
    });
    var glob_agregar = false;
    glob_guardar = false;
    calcularTotal()
})
$(document).on("change", "#almacen_ne", function () {
    swal("Atencion!", "Usted esta cambiado de Almacen")
    loc_almacen = $("#almacen_ne").val();
    console.log(loc_almacen);
});
$(document).on("click", "#anularMovimientoEgreso", function () {
    mensajeAnular("#obs_ne",
        function () {
            anularMovimientoEgreso();
        },
        function () {
            window.location.href = base_url("Egresos");
        }
    );

})
$(document).on("click", "#recuperarMovimientoEgreso", function () {
    mensajeRecuperar("#obs_ne",
        function () {
            recuperarMovimientoEgreso();
        },
        function () {
            window.location.href = base_url("Egresos");
        }
    );
})
$(document).on("click", "#agregar_articulo", function () {
    if (glob_guardar) {
        //console.log(glob_guardar);
                     
        
        agregarArticulo()
        
        
    }
})
$(document).on("click", ".eliminarArticulo", function () {
    $(this).parents("tr").remove()
    
    calcularTotal()
})
$(document).on("change", "#moneda_ne", function () {
    calcularTotal()
})
$(document).on("keyup", "#nfact_imp", function () {
    if ($(this).val() == "SF") {
        $("#consinfac").html("(sin Factura)")
        $("#consinfac").css("color", "#a60000")
    } else {
        $("#consinfac").html("(con Factura)")
        $("#consinfac").css("color", "#00a65a")
    }
})
$(document).on("keyup", "#cantidad_imp,#punitario_imp", function () {
    var cant = $("#cantidad_imp").inputmask('unmaskedvalue');
    var costo = $("#punitario_imp").inputmask('unmaskedvalue');
    var tipoingreso = $("#tipomov_imp2").val()
    cant = (cant == "") ? 0 : cant;
    costo = (costo == "") ? 0 : costo;
    if (tipoingreso == 2) //si es compra local idcompralocal=2
    {
        costo = calculocompraslocales(cant, costo)
    }
    //total=cant*costo;
    $("#constounitario").val(costo); //costo calculado
    /***para la alerta*******/
    var costobase = $("#costo_imp").inputmask('unmaskedvalue'); //costo de base de datos
    alertacosto(costo, costobase);
})
$(document).on("click", "#guardarMovimiento", function () {
    guardarmovimiento();
})
$(document).on("click", "#cancelarMovimiento", function () {
    limpiarArticulo();
    limpiarCabecera();
    limpiarTabla();
})
$(document).on("click", "#actualizarMovimiento", function () {
    actualizarMovimiento();
})
$(document).on("click", "#cancelarMovimientoActualizar", function () {
    window.location.href = base_url("Ingresos");
})
$(document).on("click", "#anularMovimiento", function () {
    anularMovimiento();
    limpiarArticulo();
    limpiarCabecera();
    limpiarTabla();
})
$(document).on("click", "#recuperarMovimiento", function () {
    recuperarMovimiento();
    limpiarArticulo();
    limpiarCabecera();
    limpiarTabla();
})
$(document).on("change", "#moneda_ne", function () {
    cambiarMoneda()
})
/*******************CLIENTE*****************/
$(function () {
    $("#cliente_egreso").autocomplete({
            minLength: 2,
            autoFocus: true,
            source: function (request, response) {
                $("#cargandocliente").show(150)
                $("#clientecorrecto").html('<i class="fa fa-times" style="color:#bf0707" aria-hidden="true"></i>')
                glob_guardar = false;
                $.ajax({
                    url: base_url("index.php/Egresos/retornarClientes"),
                    dataType: "json",
                    data: {
                        b: request.term
                    },
                    success: function (data) {
                        response(data);
                        $("#cargandocliente").hide(150)
                    }
                });

            },

            select: function (event, ui) {
                $("#clientecorrecto").html('<i class="fa fa-check" style="color:#07bf52" aria-hidden="true"></i>');
                $("#cliente_egreso").val(ui.item.nombreCliente + " - " + ui.item.documento);
                $("#idCliente").val(ui.item.idCliente);
                glob_guardar = true;
                return false;
            }
        })
        .autocomplete("instance")._renderItem = function (ul, item) {

            return $("<li>")
                .append("<a><div>" + item.nombreCliente + " </div><div style='color:#615f5f; font-size:10px'>" + item.documento + "</div></a>")
                .appendTo(ul);
        };
});

function cargandoSaldoPrecioArticulo() {
    $(".cargandoPrecioSaldo").css("display", "");
}
function finCargaSaldoPrecioArticulo() {
    $(".cargandoPrecioSaldo").css("display", "none");
}
function limpiarArticulo() {
    inputarray = $(".filaarticulo").find("input").toArray();
    $.each(inputarray, function (index, value) {
        $(value).val("")
    })
    glob_agregar = false;
    $("#codigocorrecto").html('<i class="fa fa-times" style="color:#bf0707" aria-hidden="true"></i>')

}
function limpiarCabecera() {
    $("#cliente_egreso").val("");
    $("#pedido_ne").val("");
    $("#cliente_egreso").val("");

    glob_agregar = false;
    $("#clientecorrecto").html('<i class="fa fa-times" style="color:#bf0707" aria-hidden="true"></i>')
    $("#totalacostosus").val("");
    $("#totalacostobs").val("");
    $("#fechapago_ne").val("");
    $("#obs_ne").val("");
}
function limpiarTabla() {
    $("#tbodyarticulos").find("tr").remove();
}
function calcularTotal() {
    let moneda = $("#moneda_ne").val()
    let totales = $(".totalCosto").toArray();
    let total = 0;
    let dato = 0;
    $.each(totales, function (index, value) {
        dato = $(value).inputmask('unmaskedvalue');
        total += (dato == "") ? 0 : parseFloat(dato)
    })
    total = (Math.round(total * 100) / 100).toFixed(2);
    if (moneda == 1) {
        var totalDolares = total / glob_tipoCambio;
        totalDolares = (Math.round(totalDolares * 100) / 100).toFixed(2);
    } else {
        var totalDolares = total;
        total = total * glob_tipoCambio;
    }
    $("#totalacostobs").val(total)
    $("#totalacostosus").val(totalDolares)
}
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
function agregarArticulo() {
    let saldoAlmacen = $("#saldo_ne").inputmask('unmaskedvalue')
    let cant = $("#cantidad_ne").inputmask('unmaskedvalue')
    let costo = $("#punitario_ne").inputmask('unmaskedvalue')
    let dcto = $("#descuento_ne").inputmask('unmaskedvalue')
    let codigoArticulo = $("#articulo_impTest").val();


    cant = parseFloat((cant == '') ? 0 : cant)
    costo = parseFloat((costo == '') ? 0 : costo)
    dcto = parseFloat(dcto == '' ? 0 : dcto)
    saldoAlmacen = parseFloat((saldoAlmacen == '') ? 0 : saldoAlmacen)

    if ((cant) > 0 && (costo) >= 0 && dcto >= 0) {
        if ((cant) <= (saldoAlmacen) && parseFloat(saldoAlmacen) > 0) {
            addArticuloTable()
            agregarArticuloEgresos()
            
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
            }).then((result) => {
                addArticuloTable()
                agregarArticuloEgresos()
                swal({
                    type: 'error',
                    html: 'Usted generó un <b>NEGATIVO</b> en ' + codigoArticulo,
                });
            }, (dismiss) => {
                swal({
                    type: 'success',
                    title: 'Gracias por no generar negativos :)',
                    showConfirmButton: false,
                    timer: 1500
                })
            })
        }
    } else {
        swal(
            'Oops...',
            'Ingrese cantidad, precio o descuento  válido!',
            'error'
        )
    }
}
function agregarArticuloEgresos() {
    let codigo = $("#articulo_impTest").val()
    let descripcion = $("#Descripcion_ne").val()
    let cant = $("#cantidad_ne").inputmask('unmaskedvalue')
    let precioUnitario = $("#punitario_ne").inputmask('unmaskedvalue')
    let descuento = $("#descuento_ne").inputmask('unmaskedvalue')
    let id = $("#idArticulo").val();
    let total
    cant = parseFloat((cant == '') ? 0 : cant)
    precioUnitario = parseFloat((precioUnitario == '') ? 0 : precioUnitario)
    descuento = parseFloat((descuento == '' ? 0 : descuento))
    precioUnitario = precioUnitario - (precioUnitario * descuento / 100)
    total = precioUnitario * cant

    let articulo = '<tr>' +
        '<td><input type="text" class="estilofila" disabled value="' + id + '""></input></td>' +
        '<td><input type="text" class="estilofila" disabled value="' + codigo + '""></input></td>' +
        '<td><input type="text" class="estilofila" disabled value="' + descripcion + '"></input</td>' +
        '<td class="text-right"><input type="text" class="estilofila tiponumerico" disabled value="' + cant + '""></input></td>' +
        '<td class="text-right"><input type="text" class="estilofila tiponumerico" disabled value="' + precioUnitario + '""></input></td>' +
        '<td class="text-right"><input type="text" class="totalCosto estilofila currency" disabled value="' + total + '""></input></td>' +
        '<td class="text-right"><input type="text" class="estilofila currency" disabled value="' + descuento + '""></input></td>' +
        '<td><button type="button" class="btn btn-default eliminarArticulo" aria-label="Left Align"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></td>' +
        '</tr>'
    $("#tbodyarticulos").append(articulo)
    $(".tiponumerico").inputmask({
        alias: "decimal",
        digits: 4,
        groupSeparator: ',',
        autoGroup: true
    });
    $(".currency").inputmask({ 
        alias : "currency", 
        prefix: '',
        digits: 2,
    });
    calcularTotal()
    limpiarArticulo();
    document.getElementById("articulo_impTest").focus()
}
function alertacosto(costounitario, costobase) {
    var valormin = (parseFloat(costobase) - parseFloat(costobase * 0.15))
    var valormax = (parseFloat(costobase) + parseFloat(costobase * 0.15))
    if ((costounitario > valormin) && (costounitario < valormax)) {
        //se encuentra en el rango correco
        $("#constounitario").css("background-color", "#eee")
        $("#constounitario").css("color", "#555555")

    } else {
        //fuera de rango
        $("#constounitario").css("background-color", "red")
        $("#constounitario").css("color", "#fff")
    }
}
function guardarmovimiento() {
    let valuesToSubmit = $("#form_egreso").serialize();
    let tipoEgreso = $("#tipomov_ne2").text();
    let tablaaux = tablatoarray();
    if ($("#_tipomov_ne").val() == 9)
        var auxContinuar = true
    else
        var auxContinuar = false
    if (!glob_guardar && !auxContinuar) {
        swal("Error", "Seleccione el cliente", "error")
        return 0;
    }
    if (tablaaux.length > 0) {
        var tabla = JSON.stringify(tablaaux);
        valuesToSubmit += "&tabla=" + tabla;
        console.log(valuesToSubmit);
        retornarajax(base_url("index.php/Egresos/storeEgreso"), valuesToSubmit, function (data) {
            estado = validarresultado_ajax(data);
            if (estado) {
                if (data.respuesta) {

                    $("#modalIgresoDetalle").modal("hide");
                    swal({
                        title: 'Egreso realizado!',
                        text: tipoEgreso + " guardada con éxito",
                        type: 'success',
                        showCancelButton: false
                    }).then(
                        function (result) {
                            //location.reload();
                            let imprimir = base_url("pdf/Egresos/index/") + data.respuesta;
                            window.open(imprimir);
                            limpiarArticulo();
                            limpiarCabecera();
                            limpiarTabla();
                        });
                } else {
                    $(".mensaje_error").html("Error al almacenar los datos, intente nuevamente");
                    $("#modal_error").modal("show");
                }

            }
        })
    } else {

        swal("Error", "No se tiene datos en la tabla para guardar", "error")
    }
}
function actualizarMovimiento() {
    let valuesToSubmit = $("#form_egreso").serialize();
    let tablaaux = tablatoarray()
    if (tablaaux.length > 0) {
        let tabla = JSON.stringify(tablaaux);
        valuesToSubmit += "&tabla=" + tabla;
        retornarajax(base_url("index.php/Egresos/updateEgreso"), valuesToSubmit, function (data) {
            estado = validarresultado_ajax(data);
            if (estado) {
                if (data.respuesta) {
                    swal(
                        'Modificación realizada!',
                        'La modificación se realizó con éxito!',
                        'success'
                    )
                    window.location.href = base_url("Egresos");
                } else {
                    swal(
                        'Error',
                        'Error al actualizar los datos, intente nuevamente',
                        'error'
                    )
                }

            }
        })
    } else {
        alert("no se tiene datos en la tabla para guardar")
    }
}
function anularMovimiento() // X
{
    var valuesToSubmit = $("#form_ingresoImportaciones").serialize();
    var tablaaux = tablatoarray();
    if (tablaaux.length > 0) {
        var tabla = JSON.stringify(tablaaux);

        valuesToSubmit += "&tabla=" + tabla;
        retornarajax(base_url("index.php/Ingresos/anularmovimiento"), valuesToSubmit, function (data) {
            estado = validarresultado_ajax(data);
            if (estado) {
                if (data.respuesta) {

                    $("#modalIgresoDetalle").modal("hide");
                    limpiarArticulo();
                    limpiarCabecera();
                    limpiarTabla();
                    $(".mensaje_ok").html("Datos anulados correctamente");
                    $("#modal_ok").modal("show");
                    window.location.href = base_url("Ingresos");
                } else {
                    $(".mensaje_error").html("Error al anular los datos, intente nuevamente");
                    $("#modal_error").modal("show");
                }

            }
        })
    } else {
        alert("no se tiene datos en la tabla para guardar")
    }
}
function recuperarMovimiento() // X
{
    var valuesToSubmit = $("#form_ingresoImportaciones").serialize();
    var tablaaux = tablatoarray();
    if (tablaaux.length > 0) {
        var tabla = JSON.stringify(tablaaux);

        valuesToSubmit += "&tabla=" + tabla;
        retornarajax(base_url("index.php/Ingresos/recuperarmovimiento"), valuesToSubmit, function (data) {
            estado = validarresultado_ajax(data);
            if (estado) {
                if (data.respuesta) {

                    $("#modalIgresoDetalle").modal("hide");
                    limpiarArticulo();
                    limpiarCabecera();
                    limpiarTabla();
                    $(".mensaje_ok").html("Datos recuperados correctamente");
                    $("#modal_ok").modal("show");
                    window.location.href = base_url("Ingresos");
                } else {
                    $(".mensaje_error").html("Error al recuperar los datos, intente nuevamente");
                    $("#modal_error").modal("show");
                }

            }
        })
    } else {
        alert("no se tiene datos en la tabla para guardar")
    }
}
function tablatoarray() {
    var tabla = new Array()
    var filas = $("#tbodyarticulos").find("tr").toArray()
    var datos = ""
    $.each(filas, function (index, value) {
        datos = $(value).find("input").toArray()
        tabla.push(Array(
            $(datos[0]).val(), 
            $(datos[1]).val(), 
            $(datos[2]).val(), 
            $(datos[3]).inputmask('unmaskedvalue'), 
            $(datos[4]).inputmask('unmaskedvalue'), 
            $(datos[5]).inputmask('unmaskedvalue'), 
            $(datos[6]).inputmask('unmaskedvalue'), 
        ))
    })
    return (tabla)
}
function cambiarMoneda() {
    if ($("#moneda_ne").val() == 1) {
        $(".costo_ne_label").html("Precio Bs")
        //$(".punitario_ne_class").val(glob_precio_egreso)   

    } else {
        $(".costo_ne_label").html("Precio Dolares")
        //$(".punitario_ne_class").val(glob_precio_egreso/glob_tipoCambio)
    }
}
function anularMovimientoEgreso() {

    var valuesToSubmit = $("#form_egreso").serialize();
    var tablaaux = tablatoarray();
    if (tablaaux.length > 0) {
        var tabla = JSON.stringify(tablaaux);
        valuesToSubmit += "&tabla=" + tabla;
        retornarajax(base_url("index.php/Egresos/anularmovimiento"), valuesToSubmit, function (data) {
            estado = validarresultado_ajax(data);
            if (estado) {
                if (data.respuesta) {

                    /* swal(
                             'Anulado!',
                             'El movimiento ha sido anulado.',
                             'success'
                         )*/

                } else {
                    $(".mensaje_error").html("Error al anular los datos, intente nuevamente");
                    $("#modal_error").modal("show");
                }

            }
        })
    } else {
        alert("no se tiene datos en la tabla para guardar")
    }



}
function recuperarMovimientoEgreso() {
    var valuesToSubmit = $("#form_egreso").serialize();
    var tablaaux = tablatoarray();
    if (tablaaux.length > 0) {
        var tabla = JSON.stringify(tablaaux);
        valuesToSubmit += "&tabla=" + tabla;
        retornarajax(base_url("index.php/Egresos/recuperarmovimiento"), valuesToSubmit, function (data) {
            estado = validarresultado_ajax(data);
            if (estado) {
                if (data.respuesta) {
                    /* swal(
                             'Recuperado!',
                             'El movimiento ha sido recuperado.',
                             'success' 
                         )
                         window.location.href=base_url("egresos");*/
                } else {
                    $(".mensaje_error").html("Error al anular los datos, intente nuevamente");
                    $("#modal_error").modal("show");
                }

            }
        })
    } else {
        alert("no se tiene datos en la tabla para guardar")
    }
}



/*******************ARTICULO*****************/
$( function() {
    console.log(loc_almacen);
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
            $("#Descripcion_ne").val( ui.item.descripcion);
            $("#unidad_imp").val( ui.item.unidad);
            $("#saldo_ne").val( ui.item.saldo);
            $("#precio").val( ui.item.precio);
            //console.log(ui)
            glob_guardar=true;
            return false;
        }
        }).autocomplete("instance")._renderItem = function( ul, item ) {
        return $( "<li>" ).append( "<a><div>" + item.codigo + " </div><div style='color:#615f5f; font-size:10px'>" + item.descripcion + "</div></a>" )
        .appendTo( ul );
    };
 });

let articulos = []
let $table
function addArticuloTable() {
    $("#egresoDetalle").bootstrapTable('append', addArticulo());  
    //limpiarArticulo();
    //document.getElementById("articulo_impTest").focus()
    calcularTotalEgreso()
}
function addArticulo() {
    id = $('#idArticulo').val()
    let codigo = $("#articulo_impTest").val()
    let descripcion = $("#Descripcion_ne").val()
    let cant = $("#cantidad_ne").inputmask('unmaskedvalue')
    let precioUnitario = $("#punitario_ne").inputmask('unmaskedvalue')
    let descuento = $("#descuento_ne").inputmask('unmaskedvalue')
    let total
    cant = parseFloat((cant == '') ? 0 : cant)
    precioUnitario = parseFloat((precioUnitario == '') ? 0 : precioUnitario)
    descuento = parseFloat((descuento == '' ? 0 : descuento))
    precioUnitario = precioUnitario - (precioUnitario * descuento / 100)
    total = precioUnitario * cant

    rows = [];
        rows.push({
            id : id,
            codigo : codigo,
            descripcion : descripcion,
            cantidad: cant,
            pu: precioUnitario,
            total: total,
            dcto: descuento,
        });
    return rows
}
 function tablaEgresoDetalle() {
     console.log(articulos)
    $table = $("#egresoDetalle").bootstrapTable('destroy');
    $("#egresoDetalle").bootstrapTable({
        clickToSelect: true,
        uniqueId: 'id',
        search: false,
        data: articulos,
        columns: [
            {
                field: 'id',
                title: 'id',
            },
            {
                field: 'codigo',
                title: 'Código',
                align: 'center',
                class: "col-sm-1",
            },
            {
                field: 'descripcion',
                title: 'Descripcion',
                class: "col-sm-7",
                editable: {
                    container: 'body',
                    type: 'text',
                },
            },
            {
                field: 'cantidad',
                title: "Cantidad",
                align: 'right',
                class: "col-sm-1",
                formatter: formatoMoneda,
                editable: {
                    container: 'body',
                    type: 'text',
                    params: { a: 1, b: 2 },
                    inputclass: "tiponumerico",
                    
                    validate: function (value) {
                        if ($.trim(value) == '') {
                            return 'El campo es requerido';
                        }
                        if (!$.isNumeric(value)) {
                            return 'El campo es numerico';
                        }
                        if (value < 0 || value == 0) {
                            return 'no puede ser igual o menor a 0';
                        }
                    },
                },
            },
            {
                field: 'pu',
                title: "P/U",
                align: 'right',
                class: "col-sm-1",
                formatter: formatoMoneda,
                editable: {
                    container: 'body',
                    type: 'text',
                    inputclass: "tiponumerico",
                    validate: validateNum,
                },
            },
            {
                field: 'total',
                title: "total",
                align: 'right',
                class: "col-sm-1",
                formatter: formatoMoneda,
            },
            {
                field: 'dcto',
                title: "dcto",
                align: 'right',
                class: "col-sm-1",
                formatter: formatoMoneda,
            },
        ]
    });
    $table.on('editable-save.bs.table', function (e, field, row, old, $el) {
        var total = parseFloat(row.pu) * parseFloat(row.cantidad);
        $("#egresoDetalle").bootstrapTable('updateByUniqueId', {
            id: row.id,
            row: {
                total: total
            }
        });
    });
}

function validateNum(value) {
    value = $.trim(value);
    if ($.trim(value) == '') {
        return 'El dato es requerido';
    }
    if (!$.isNumeric(value)) {
        return 'Debe ingresar un numero';
    }
    if (value < 0 || value == 0) {
        return 'no puede ser igual o menor a 0';
    }
    let data = $("#egresoDetalle").bootstrapTable('getData');
    let index = $(this).parents('tr').data('index');
    let row = (data[index]);

    if (row.dcto > 0) {
        return 'No puede editar error' 
    }
}
$(document).on("click", "#getTabla", function () {
    let tabla = $("#egresoDetalle").bootstrapTable('getData');
    tabla = JSON.stringify(tabla);
     tabla = JSON.parse(tabla)
    console.log(tabla);

})
$(document).on("click", "#getTablaMod", function () {
    let tabla = $("#tablaEditarEgreso").bootstrapTable('getData');
    tabla = JSON.stringify(tabla);
     tabla = JSON.parse(tabla)
    console.log(tabla);
    calcularTotalEgresoMod()

})
function formatoMoneda(value, row, index) {
    num = Math.round(value * 100) / 100
    return (formatNumber.new(num))
}
function calcularTotalEgreso() {
    let moneda = $("#moneda_ne").val()
    let tablaEgreso = $("#egresoDetalle").bootstrapTable('getData');
    let total = 0;
    $.each(tablaEgreso, function (index, value) {
        total = total + parseFloat(value.total);
    })
    /****************Bs**************/
    let totalBs = moneda == 2 ? (parseFloat(total) * parseFloat(glob_tipoCambio)) : total;
    $("#totalBolivianos").val(totalBs);
    /*************SUS***************/
    let totalSus = moneda == 2 ? total : parseFloat(total) / parseFloat(glob_tipoCambio);
    $("#totalDolares").val(totalSus);
}
function retornarTablaEgresoDetalle(idEgreso) {
    //agregarcargando();
    $.ajax({
        type: "POST",
        url: base_url('index.php/Egresos/mostrarDetalleEditarPost'),
        dataType: "json",
        data: { id: idEgreso },
    }).done(function (res) {
        //quitarcargando();
        $("#tablaEditarEgreso").bootstrapTable('destroy');
        $('#tablaEditarEgreso').bootstrapTable({
            data: res,
            clickToSelect: true,
            uniqueId: 'id',
            columns: 
                [
                    {
                        field: 'idArticulos',
                        title: 'id',
                    },
                    {
                        field: 'CodigoArticulo',
                        title: 'Código',
                        align: 'center',
                        class: "col-sm-1",
                    },
                    {
                        field: 'Descripcion',
                        title: 'Descripcion',
                        class: "col-sm-7",
                        editable: {
                            container: 'body',
                            type: 'text',
                        },
                    },

                    {
                        field: 'cantidad',
                        title: "Cantidad",
                        align: 'right',
                        class: "col-sm-1",
                        formatter: formatoMoneda,
                        editable: {
                            container: 'body',
                            type: 'text',
                            params: { a: 1, b: 2 },
                            inputclass: "tiponumerico",
                            validate: validarCantidadFactura,
                        },
                    },
                    {
                        field: 'punitario',
                        title: "P/U",
                        align: 'right',
                        class: "col-sm-1",
                        formatter: formatoMoneda,
                        editable: {
                            container: 'body',
                            type: 'text',
                            inputclass: "tiponumerico",
                            validate: validateNum,
                        },
                    },
                    {
                        field: 'total',
                        title: "total",
                        align: 'right',
                        class: "col-sm-1",
                        formatter: formatoMoneda,
                    },
                    {
                        field: 'cantFact',
                        title: 'CantFact',
                        align: 'right',
                        class: "col-sm-1",
                        formatter: formatoMoneda,
                    },
                    {

                        title: '<button type="button" class="btn btn-default quitarTodos"><span class="fa fa-times-circle" aria-hidden="true"></span></button>',
                        align: 'center',
                        class: "col-sm-1",
                        //events: operateEvents,
                        formatter: retornarBoton2
                    },
                ]
        });
        $table.on('editable-save.bs.table', function (e, field, row, old, $el) {
            var total = parseFloat(row.cantidad) * parseFloat(row.punitario);
            $("#egresoDetalle").bootstrapTable('updateByUniqueId', {
                id: row.idArticulos,
                row: {
                    total: total
                }
            });
        });
    }).fail(function (jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
    });
}
function validarCantidadFactura(value) {
    value = $.trim(value);
    if ($.trim(value) == '') {
        return 'El dato es requerido..';
    }
    if (!$.isNumeric(value)) {
        return 'Debe ingresar un numero';
    }
    if (value < 0 || value == 0) {
        return 'no puede ser igual o menor a 0';
    }
    let data = $("#tablaEditarEgreso").bootstrapTable('getData');
    let index = $(this).parents('tr').data('index');
    let row = (data[index]);

    if (parseFloat(value) < parseFloat(row.cantFact)) {
        return 'La cantidad debe ser mayor o igual a la cantidad facturada' 
    }
    if (parseFloat(row.cantidad) == parseFloat(row.cantFact)) {
        return 'El artículo fue facturado totalmente' 
    }
}
function calcularTotalEgresoMod() {
    let moneda = $("#moneda_ne").val()
    let tablaEgreso = $("#tablaEditarEgreso").bootstrapTable('getData');
    let total = 0;
    $.each(tablaEgreso, function (index, value) {
        console.log(value);
        total = total + parseFloat(value.total);
    })
    /****************Bs**************/
    let totalBs = moneda == 2 ? (parseFloat(total) * parseFloat(glob_tipoCambio)) : total;
    $("#totalBolivianosMod").val(totalBs);
    /*************SUS***************/
    let totalSus = moneda == 2 ? total : parseFloat(total) / parseFloat(glob_tipoCambio);
    $("#totalDolaresMod").val(totalSus);
}
 
function operateFormatter(value, row, index) {
    return [
        '<button type="button" class="btn btn-default agregartabla" data-view="' + row.idEgresos + '" aria-label="Right Align">',
        '<span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>',
        '<button type="button" class="btn btn-default quitardetabla hidden" data-remove="' + row.idEgresos + '" aria-label="Right Align">',
        '<span class="fa fa-minus-square-o " aria-hidden="true"></span></button>',
    ].join('');
}

function retornarBoton2(value, row, index) {
    return [
        '<button type="button" class="btn btn-default eliminarElemento" data-view="' + row.idingdetalle + '"><span class="fa fa-times" aria-hidden="true"></span></button>',
    ].join('');
}