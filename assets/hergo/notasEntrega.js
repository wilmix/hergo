let glob_factorIVA = 0.87
let glob_factorRET = 0.087
let loc_almacen
let glob_guardar = false
let glob_guardar_cliente = false
let glob_precio_egreso = 0
let hoy
let idEgreso 
let $table
let checkTipoCambio = false
let moneda
let tipoMovEgre 
agregarcargando()
$(document).ready(function () {
    validarCliente()
    tipoMovEgre = $("#_tipomov_ne").val()
    if (tipoMovEgre == 9) {
        $(".hiddenBaja").addClass("hidden");
    }
    console.log(tipoMovEgre);
    moneda = $("#moneda_ne").val()
    glob_guardar = false;
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
    } else {
        retornarTablaEgresoDetalle(idEgreso)
    }
})
$(document).on("change", "#fechamov_ne", function () {
    let fecha = $('#fechamov_ne').val()
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
    let fecha = $('#fechamov_ne').val()
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
$(document).on("change", "#almacen_ne", function () {
    swal("Atencion!", "Usted esta cambiado de Almacen")
    loc_almacen = $("#almacen_ne").val();
})
$(document).on("click", "#anularMovimientoEgreso", function () {
    almForm = $('#almacen_ne').val()
    almUser = $('#idAlmacenUsuario').val()
    isAdmin = $('#isAdmin').val()
    if (almForm != almUser && isAdmin == '') {
        swal("Error", "No se puede Anular", "error")
        console.log('error');
        return false
    }
    mensajeAnular("#obs_ne",
        function () {
            anularMovimientoEgreso()
        },
        function () {
            window.location.href = base_url("Egresos")
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
        agregarArticulo()
    }
})
$(document).on("change", "#moneda_ne", function () {
    moneda = $("#moneda_ne").val()
    limpiarArticulo()
    calcularTotalEgresoMod()
    cambiarMoneda()
})
$(document).on("click", "#guardarMovimiento", function () {
    almForm = $('#almacen_ne').val()
    almUser = $('#idAlmacenUsuario').val()
    isAdmin = $('#isAdmin').val()
    if (almForm != almUser && isAdmin == '') {
        swal("Error", "No se puede guardar movimiento", "error")
        console.log('error');
        return false
    }
    guardarmovimiento();
})
$(document).on("click", "#cancelarMovimiento", function () {
    window.location.href = base_url("Egresos")

})
$(document).on("click", "#actualizarMovimiento", function () {
    actualizarMovimiento();
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
window.operateEvents = {
    'click .eliminarElemento': function (e, value, row, index) {
        quitarArticulo(row)
    },
}
/*******************CLIENTE*****************/
$(function () {
    $("#cliente_egreso").autocomplete({
            minLength: 2,
            autoFocus: true,
            source: function (request, response) {
                $("#cargandocliente").show(150)
                $("#clientecorrecto").html('<i class="fa fa-times" style="color:#bf0707" aria-hidden="true"></i>')
                glob_guardar_cliente = false;
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
                validarClienteCorrecto(ui.item.idCliente,ui.item.nombreCliente,ui.item.documento)
                return false;
            }
        })
        .autocomplete("instance")._renderItem = function (ul, item) {

            return $("<li>")
                .append("<a><div>" + item.nombreCliente + " </div><div style='color:#615f5f; font-size:10px'>" + item.documento + "</div></a>")
                .appendTo(ul);
        };

});
/*******************ARTICULO*****************/
$( function() {
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
            if (moneda == 2) {
                $("#precio").val(($("#tipomov_ne2").val()=='9') ? formatoCosto((ui.item.cpp/glob_tipoCambio).toFixed(2)) : (ui.item.precio/glob_tipoCambio).toFixed(2));
                $("#punitario_ne").val(($("#tipomov_ne2").val()=='9') ? formatoCosto((ui.item.cpp/glob_tipoCambio).toFixed(2)) : '');
            } else {
                $("#precio").val(($("#tipomov_ne2").val()=='9') ? formatoCosto(ui.item.cpp) : ui.item.precio);
                $("#punitario_ne").val(($("#tipomov_ne2").val()=='9') ? formatoCosto(ui.item.cpp) : '');
            }
            glob_guardar=true;
            return false;
        }
        }).autocomplete("instance")._renderItem = function( ul, item ) {
        return $( "<li>" ).append( "<a><div>" + item.codigo + " </div><div style='color:#615f5f; font-size:10px'>" + item.descripcion + "</div></a>" )
        .appendTo( ul );
    };
 });

function agregarArticulo() {
    let saldoAlmacen = $("#saldo_ne").inputmask('unmaskedvalue')
    let cant = $("#cantidad_ne").inputmask('unmaskedvalue')
    let costo = $("#punitario_ne").inputmask('unmaskedvalue')
    let dcto = $("#descuento_ne").inputmask('unmaskedvalue')
    let codigoArticulo = $("#articulo_impTest").val()
    let iniCod = codigoArticulo.substr(0,2)


    cant = parseFloat((cant == '') ? 0 : cant)
    cant = cant.toFixed(2)
    costo = (costo == '') ? 0 : costo
    costo = costo.replace(",","")
    costo = parseFloat(costo)
    costo = costo.toFixed(2)
    dcto = parseFloat(dcto == '' ? 0 : dcto)
    saldoAlmacen = parseFloat((saldoAlmacen == '') ? 0 : saldoAlmacen)

    if ((cant) > 0 && (costo) >= 0 && dcto >= 0) {
        if ((cant) <= (saldoAlmacen) && parseFloat(saldoAlmacen) > 0 || iniCod == 'SR' ) {
            addArticuloTable()
        } else if (iniCod == 'CS') {
            swal(
                'Oops...',
                'No puede generar <b>Negativo</b> en la línea de <b>Señalética</b>, realice el ingreso correspondiente por favor.',
                'error'
            )
        }
         else {
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
                swal({
                    type: 'error',
                    html: 'Usted generó un <b>NEGATIVO</b> en ' + codigoArticulo,
                });
                addArticuloTable()
            }, (dismiss) => {
                swal({
                    type: 'success',
                    title: ' no generar negativos :)',
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
function guardarmovimiento() {
    let valuesToSubmit = $("#form_egreso").serialize()
    let articulos = $("#tablaEditarEgreso").bootstrapTable('getData')
    articulos.forEach(element => {
        delete element.Descripcion;
    });
    let tipoEgreso = $("#tipomov_ne2").text()
    if ($("#_tipomov_ne").val() == 9){
        glob_guardar_cliente = true
    } else {
        if (!glob_guardar_cliente) {
        swal("Error", "Seleccione el cliente", "error")
        return false;
        }
    }
    if (!checkTipoCambio) {
        swal("Error", "No se tiene tipo de cambio para esta Fecha", "error")
        return false;
    }
    if (articulos.length > 0) {
        let tabla = JSON.stringify(articulos);
        valuesToSubmit += "&tabla=" + tabla;
        retornarajax(base_url("index.php/Egresos/storeEgreso"), valuesToSubmit, function (data) {
            estado = validarresultado_ajax(data);
            if (estado) {
                $("#modalIgresoDetalle").modal("hide");
                swal({
                    title: 'Egreso realizado!',
                    text: tipoEgreso + " guardada con éxito",
                    type: 'success',
                    showCancelButton: false
                }).then(
                    function (result) {
                        let imprimir = base_url("pdf/Egresos/index/") + data.respuesta;
                        window.open(imprimir)
                        limpiarArticulo()
                        limpiarCabecera()
                        limpiarTabla()
                        limpiarTotales()
                    });
            } else {
                swal({
                    title: 'Error!',
                    text: 'No se puede guardar el egreso',
                    type: 'error',
                    showCancelButton: false
                })
            }
        })
    } else {

        swal("Error", "No se tiene datos en la tabla para guardar", "error")
    }
}
function actualizarMovimiento() {
    let valuesToSubmit = $("#form_egreso").serialize();
    let articulos = $("#tablaEditarEgreso").bootstrapTable('getData');
    if (articulos.length > 0) {
        let tabla = JSON.stringify(articulos);
        valuesToSubmit += "&tabla=" + tabla;
        retornarajax(base_url("index.php/Egresos/updateEgreso"), valuesToSubmit, function (data) {
            estado = validarresultado_ajax(data);
            if (estado) {
                if (data.respuesta) {
                    console.log(data.respuesta.id);
                    swal({
                        title: 'Modificación realizada!',
                        html: 'La modificación se realizó con éxito!',
                        type: 'success',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok',
                    }).then((result) => {
                        window.location.href = base_url("Egresos")
                        let imprimir = base_url("pdf/Egresos/index/") + data.respuesta.id;
                        window.open(imprimir)
                    })
                } else {
                    swal(
                        'Error',
                        'Error al modificar, revise los datos e intente nuevamente',
                        'error'
                    )
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
function cambiarMoneda() {
    if ($("#tipomov_ne2").val()=='9') {
        if (moneda == 1) {
            $(".costo_ne_label").html("Costo Bs")
        } else {
            $(".costo_ne_label").html("Costo Dolares")
        }
    } else {
        if (moneda == 1) {
            $(".costo_ne_label").html("Precio Bs")
        } else {
            $(".costo_ne_label").html("Precio $U$")
        }
    }
    
}
function anularMovimientoEgreso() {
    let clienteNombre = $('#cliente_egreso').val()
    let nmov = $('#nmov').val()
    let valuesToSubmit = $("#form_egreso").serialize();
        retornarajax(base_url("index.php/Egresos/anularmovimiento"), valuesToSubmit, function (data) {
            estado = validarresultado_ajax(data);
            console.log(estado);
            if (estado) {
                    swal({
                        title: 'Anulado!',
                        html: 'El movimiento # ' + nmov +  ' de ' +  clienteNombre + ' ha sido ANULADO',
                        type: 'warning',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok',
                    }).then((result) => {
                        window.location.href = base_url("Egresos")
                    })
            } else {
                swal({
                    title: 'Error!',
                    text: 'No se puede anular el egreso',
                    type: 'error',
                    showCancelButton: false
                })
            }
        })

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
    //$("#fechapago_ne").val("");
    $("#obs_ne").val("");
}
function limpiarTabla() {
    $("#tablaEditarEgreso").bootstrapTable('removeAll');
}
function limpiarTotales() {
    $("#totalDolaresMod").val("0")
    $("#totalBolivianosMod").val("0")
}
function addArticuloTable() {
    let dataTabla = $("#tablaEditarEgreso").bootstrapTable('getData');
    let id = $('#idArticulo').val()
    if(dataTabla.length>0)
    {                
        if(dataTabla.map((el) => el.idArticulos).indexOf(id)>=0)
        {
            swal("Atencion", "Ya se tiene un registro con este codigo","info");
            return false;
        }
        $("#tablaEditarEgreso").bootstrapTable('append', addArticulo());  
    }
    else
    {
        $("#tablaEditarEgreso").bootstrapTable('append', addArticulo());  
    }
    limpiarArticulo();
    document.getElementById("articulo_impTest").focus()
    calcularTotalEgresoMod()
}
function addArticulo() {
    
    let id = $('#idArticulo').val()
    let codigo = $("#articulo_impTest").val()
    let descripcion = $("#Descripcion_ne").val()
    let cant = $("#cantidad_ne").inputmask('unmaskedvalue')
    let precioUnitario = $("#punitario_ne").inputmask('unmaskedvalue')
    let descuento = $("#descuento_ne").inputmask('unmaskedvalue')
    let total
    precioUnitario = precioUnitario.replace(",","")
    cant = parseFloat((cant == '') ? 0 : cant).toFixed(2)
    cant = parseFloat(cant)
    precioUnitario = parseFloat((precioUnitario == '') ? 0 : precioUnitario).toFixed(2)
    precioUnitario = parseFloat(precioUnitario)
    descuento = parseFloat((descuento == '' ? 0 : descuento))
    precioUnitario = precioUnitario - (precioUnitario * descuento / 100)
    total = precioUnitario * cant

    rows = [];
        rows.push({
            idArticulos : id,
            CodigoArticulo : codigo,
            Descripcion : descripcion,
            cantidad: cant,
            punitario: precioUnitario,
            total: total,
            descuento: descuento,
            cantFact:0

        });
    return rows
}
function formatoMoneda(value, row, index) {
    num = Math.round(value * 100) / 100
    num = num.toFixed(2);
    return (formatNumber.new(num));
}
function formatoCosto(value, row, index) {
    num = Math.round(value * 100) / 100
    num = num.toFixed(2);
    return (formatNumber.new(num));
}
function retornarTablaEgresoDetalle(idEgreso=null) {
    agregarcargando();
    $.ajax({
        type: "POST",
        url: base_url('index.php/Egresos/mostrarDetalleEditarPost'),
        dataType: "json",
        async: false,
        data: { id: idEgreso },
    }).done(function (res) {
        let idMoneda = $("#idMoneda").val();
        /*if (idMoneda == 2) {
            res.forEach(art => {
                art.punitario = art.punitario / art.tipocambio
                art.total = art.total / art.tipocambio
            });
        }*/
        quitarcargando();
        $table = $("#tablaEditarEgreso").bootstrapTable('destroy');
        $('#tablaEditarEgreso').bootstrapTable({
            data: res,
            clickToSelect: true,
            uniqueId: 'idArticulos',
            columns: 
                [
                    {
                        field: 'idingdetalle',
                        title: 'unique',
                        visible: false
                    },
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
                        /*editable: {
                            container: 'body',
                            type: 'text',
                        },*/
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
                        title: ($("#tipomov_ne2").val()=='9') ? 'Costo': 'Precio',
                        align: 'right',
                        class: "col-sm-1",
                        formatter: formatoMoneda,
                        editable: {
                            container: 'body',
                            type: 'text',
                            inputclass: "tiponumerico",
                            validate: validatePUedit,
                        },
                    },
                    {
                        field: 'total',
                        title: "Total",
                        align: 'right',
                        class: "col-sm-1",
                        formatter: formatoMoneda,
                    },
                    {
                        field: 'descuento',
                        title: "Dcto",
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

                        title: '',
                        align: 'center',
                        class: "col-sm-1",
                        events: operateEvents,
                        formatter: botonQuitar
                    },
                ]
        })
        $table.on('editable-save.bs.table', function (e, field, row, old, $el) {
            var total = parseFloat(row.cantidad) * parseFloat(row.punitario);
            $("#tablaEditarEgreso").bootstrapTable('updateByUniqueId', {
                id: row.idArticulos,
                row: {
                    total: total
                }
            })
        calcularTotalEgresoMod()
        })
    }).fail(function (jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
    })
    calcularTotalEgresoMod() 
}
$(document).on("click", ".editable-click", function () {
    $(".tiponumerico").inputmask({
        alias: "decimal",
        digits: 2,
        groupSeparator: ',',
        autoGroup: true,
        autoUnmask: true
    });
})
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
function validatePUedit(value) {
    value = $.trim(value);
    if ($.trim(value) == '') {
        return 'El dato es requerido';
    }
    if (!$.isNumeric(value)) {
        return 'Debe ingresar un numero';
    }
    if (value < 0 ) {
        return 'no puede ser igual a 0';
    }
    let data = $("#tablaEditarEgreso").bootstrapTable('getData');
    let index = $(this).parents('tr').data('index');
    let row = (data[index]);

    if (parseFloat(row.cantidad) == parseFloat(row.cantFact)) {
        return 'El artículo fue facturado totalmente' 
    }
}
function calcularTotalEgresoMod() {
    console.log('totales')
    let moneda = $("#moneda_ne").val()
    let tablaEgreso = $("#tablaEditarEgreso").bootstrapTable('getData');
    let total = 0;
    $.each(tablaEgreso, function (index, value) {
        total = total + parseFloat(value.total);
    })
    /****************Bs**************/
    let totalBs = moneda == 2 ? (parseFloat(total) * parseFloat(glob_tipoCambio)) : total;
    $("#totalBolivianosMod").val(formatoMoneda(totalBs ? totalBs : 0))
    /*************SUS***************/
    let totalSus = moneda == 2 ? total : parseFloat(total) / parseFloat(glob_tipoCambio);
    $("#totalDolaresMod").val(formatoMoneda(totalSus ? totalSus : 0))
}
function botonQuitar(value, row, index) {
    return [
        '<button type="button" class="btn btn-sm eliminarElemento" data-view="' + row.idingdetalle + '"><span class="fa fa-times" aria-hidden="true"></span></button>',
    ].join('');
}
function quitarArticulo(row) {
    ids = new Array(row.idArticulos)
    $("#tablaEditarEgreso").bootstrapTable('remove', {
        field: 'idArticulos',
        values: ids
    })
    calcularTotalEgresoMod() 
}
function mostrarModal()
{
    $('#modalTipoCambio').modal('show');
}
