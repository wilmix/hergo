let glob_factorIVA = 0.87;
let glob_factorRET = 0.087;
let loc_almacen;
let alm = 0
let hoy = moment().format('DD-MM-YYYY');
let articulos = []
let checkTipoCambio = false

$(document).ready(function () {
    alm = $("#almacen_ori").val();
    $('.fecha_traspaso').daterangepicker({
        locale: {
            format: 'DD-MM-YYYY'
        },
        singleDatePicker: true,
        startDate: hoy,
        showDropdowns: true,
    });
    loc_almacen = $("#almacen_imp").val();
})
$(document).on("change", "#almacen_imp", function () {
    clg
    let tablaaux = tablatoarray();
    if (tablaaux.length > 0) {
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
            function (isConfirm) {
                if (isConfirm) {
                    limpiarArticulo();
                    limpiarTabla();
                    loc_almacen = $("#almacen_imp").val();
                } else {
                    $("#almacen_imp").val(loc_almacen);
                }
            });
    }
});
$(document).on("change", "#almacen_ori", function () {
    alm = $("#almacen_ori").val();
    swal("Atencion!", "Usted esta cambiado de Almacen")
});
$(document).on("change", "#fechamov_ne", function () {
    let fecha = $('#fechamov_ne').val()
    if (hoy == fecha) {
        checkTipoCambio = true
    } else {
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
$(document).ready(function () {

    $(".tiponumerico").inputmask({
        alias: "decimal",
        digits: 3,
        groupSeparator: ',',
        autoGroup: true,
        autoUnmask: true
    });
    var glob_agregar = false;
    var glob_guardar = false;
    calcularTotal()
})
/*******************ARTICULO*****************/
$(function () {
   $("#articulo_impTest").autocomplete({
        minLength: 2,
        autoFocus: true,
        source: function (request, response) {
            $("#cargandocodigoTest").show(150)
            $("#codigocorrectoTest").html('<i class="fa fa-times" style="color:#bf0707" aria-hidden="true"></i>')
            glob_agregar = false;
            $.ajax({
                url: base_url("index.php/Ingresos/retornararticulosTest"),
                dataType: "json",
                data: {
                    b: request.term,
                    a: alm
                },
                success: function (data) {
                    response(data);
                    $("#cargandocodigoTest").hide(150)
                }
            });
        },
        select: function (event, ui) {
            $("#codigocorrectoTest").html('<i class="fa fa-check" style="color:#07bf52" aria-hidden="true"></i>');
            $("#articulo_impTest").val(ui.item.codigo);
            $("#idArticulo").val(ui.item.id);
            $("#descripcionArticulo").val(ui.item.descripcion);
            $("#saldo").val(ui.item.saldo);
            $("#costo").val(ui.item.cpp);
            $("#unidad_ne").val(ui.item.unidad);

            glob_agregar = true;
            return false;
        }
    }).autocomplete("instance")._renderItem = function (ul, item) {
        return $("<li>").append("<a><div>" + item.codigo + " </div><div style='color:#615f5f; font-size:10px'>" + item.descripcion + "</div></a>")
            .appendTo(ul);
    };
});
$(document).on("click", "#agregar_articulo", function () {
    if (glob_agregar) {
        //agregarArticulo(idcosto);//despues de generar el id de costo se agrega la fila con el id de costo
        agregarArticulo();
    }
})
$(document).on("click", ".eliminarArticulo", function () {
    $(this).parents("tr").remove()
    calcularTotal()
})

function limpiarArticulo() {
    inputarray = $(".filaarticulo").find("input").toArray();
    $.each(inputarray, function (index, value) {
        $(value).val("")
    })
    glob_agregar = false;
    $("#codigocorrecto").html('<i class="fa fa-times" style="color:#bf0707" aria-hidden="true"></i>')

}

function limpiarCabecera() {
    inputarray = $(".filacabecera").find("input").toArray();
    console.log(inputarray)
    $.each(inputarray, function (index, value) {
        $(value).val("")
    })
    glob_agregar = false;
    $("#clientecorrecto").html('<i class="fa fa-times" style="color:#bf0707" aria-hidden="true"></i>')
    $("#totalacostosus").val("");
    $("#totalacostobs").val("");
    $("#obs_ne").val("");
}

function limpiarTabla() {
    $("#tbodyarticulos").find("tr").remove();
}

function calcularTotal() {
    var moneda = $("#moneda_ne").val()
    var totalCosto = 0;
    var totales = $(".totalCosto").toArray();
    var total = 0;
    var dato = 0;
    $.each(totales, function (index, value) {
        dato = $(value).inputmask('unmaskedvalue');
        //   console.log(dato)
        total += (dato == "") ? 0 : parseFloat(dato)
    })
    //total=Math.round(total * 100) / 100
    if (moneda == 1) {
        var totalDolares = total / glob_tipoCambio;
    } else {
        var totalDolares = total;
        total = total * glob_tipoCambio;

    }
    // console.log(total)
    $("#totalacostobs").val(total)
    $("#totalacostosus").val(totalDolares)
}
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
//calculo de compras locales con y sin factura
function calculocompraslocales(cant, costo) {
    var ret;
    var pu //preciounitario
    pu = costo / cant; // calculamos el costo unitario      
    //if($("#nfact_imp").val()!="SF")  //si tiene el texto SF es sin factura         
    //   ret=pu*glob_factorIVA; //confactura
    //else                        
    //    ret=pu*glob_factorRET+pu; //sinfactura            
    // return ret;

}

function agregarArticulo() //faltaria el id costo; si se guarda en la base primero
{   
    let articulo_impTest = $("#articulo_imp").val();
    let cant = $("#cantidad_ne").inputmask('unmaskedvalue');
    let costo = $("#costo").inputmask('unmaskedvalue');
    let saldoAlmacen = $("#saldo").inputmask('unmaskedvalue');
    cant = (cant == "") ? 0 : cant;
    costo = (costo == "") ? 0 : costo;
    console.log(articulos)
    if (Number(cant) > 0 && Number(costo) >= 0) //valida cantidad mayor a cero
    {

        if (Number(cant) <= Number(saldoAlmacen) && Number(saldoAlmacen) > 0) // mensaje para  saldo de almacen 
        {
            addArticulo()
        } else {
            swal({
                title: 'Saldo Insuficiente',
                html: "No tiene suficiente <b>" + articulo_impTest + "</b> en su almacen.<br>" + "Desea generar <b>NEGATIVO</b>?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Agregar',
                cancelButtonText: 'No, Cancelar'
            }).then(
                function (result) {
                    
                    
                    swal({
                        type: 'error',
                        html: 'Usted generó un <b>NEGATIVO</b> en ' + articulo_impTest,
                    });
                    addArticulo()
                },
                function (dismiss) {
                    if (dismiss === 'cancel') {
                        swal(
                            'No agregado',
                            'Gracias por no generar negativos :)',
                            'error'
                        )
                    }
                });
        }

    } else {
        swal(
            'Oops...',
            'Ingrese cantidad valida!',
            'error'
        )
    }

}
function addArticulo() {
    let id = $("#idArticulo").val();
    let codigo = $("#articulo_impTest").val();
    let descripcion = $("#descripcionArticulo").val();
    let cant = $("#cantidad_ne").inputmask('unmaskedvalue');
    let costo = $("#costo").inputmask('unmaskedvalue');
    cant = (cant == "") ? 0 : cant;
    costo = (costo == "") ? 0 : costo;
    let total;
    total = cant * costo;
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
    let articulo = '<tr>' +
        '<td><input type="text" class="estilofila" disabled value="' + id + '""></input></td>' +
        '<td><input type="text" class="estilofila" disabled value="' + codigo + '""></input></td>' +
        '<td><input type="text" class="estilofila" disabled value="' + descripcion + '"></input</td>' +
        '<td class="text-right"><input type="text" class="estilofila tiponumerico" disabled value="' + cant + '""></input></td>' +
        '<td class="text-right"><input type="text" class="estilofila tiponumerico" disabled value="' + costo + '""></input></td>' +              
        '<td class="text-right"><input type="text" class="totalCosto estilofila tiponumerico" disabled value="' + total + '""></input></td>' +
        '<td><button type="button" class="btn btn-default eliminarArticulo" aria-label="Left Align"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></td>' + '</tr>'
    $("#tbodyarticulos").append(articulo)
    $(".tiponumerico").inputmask({
        alias: "decimal",
        digits: 4,
        groupSeparator: ',',
        autoGroup: true
    });
    calcularTotal()
    limpiarArticulo();
    document.getElementById("articulo_impTest").focus()
}
function agregarArticuloTraspasos() {
    var codigo = $("#articulo_imp").val();
    var descripcion = $("#Descripcion_ne").val();
    var cant = $("#cantidad_ne").inputmask('unmaskedvalue');
    var costo = $("#punitario_ne").inputmask('unmaskedvalue');
    var descuento = $("#descuento_ne").inputmask('unmaskedvalue');
    var totalfac = costo;
    var cant = (cant == "") ? 0 : cant;
    var costo = (costo == "") ? 0 : costo;
    var tipoingreso = $("#tipomov_imp2").val();
    var total;
    var saldoAlmacen = $("#saldo_ne").val();
    console.log(saldoAlmacen)
    total = cant * costo;
    var articulo = '<tr>' +
        '<td><input type="text" class="estilofila" disabled value="' + codigo + '""></input></td>' +
        '<td><input type="text" class="estilofila" disabled value="' + descripcion + '"></input</td>' +
        '<td class="text-right"><input type="text" class="estilofila tiponumerico" disabled value="' + cant + '""></input></td>' +
        '<td class="text-right"><input type="text" class="estilofila tiponumerico" disabled value="' + costo + '""></input></td>' + //nuevo P/U Factura                
        '<td class="text-right"><input type="text" class="totalCosto estilofila tiponumerico" disabled value="' + total + '""></input></td>' +
        '<td><button type="button" class="btn btn-default eliminarArticulo" aria-label="Left Align"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></td>' + '</tr>'
    $("#tbodyarticulos").append(articulo)
    $(".tiponumerico").inputmask({
        alias: "decimal",
        digits: 3,
        groupSeparator: ',',
        autoGroup: true
    });
    calcularTotal()
    limpiarArticulo();

}

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
    let tablaaux = tablatoarray();
    let almOrigen = $("#almacen_ori").val();
    let almDestino = $("#almacen_des").val();
    if (!checkTipoCambio) {
        swal("Error", "No se tiene tipo de cambio para esta Fecha", "error")
        return false;
    }
    if (almOrigen === almDestino) {
        swal("Error", "Almacen de destino es el mismo que el origen", "error")
    } else if (almDestino === "") {
        swal("Error", "Seleccione almacen de destino", "error")
    } else {
        console.log('ajax');

        if (tablaaux.length > 0 && !(almOrigen === almDestino)) {
            let tabla = JSON.stringify(tablaaux);

            valuesToSubmit += "&tabla=" + tabla;
            console.log(tabla);

            retornarajax(base_url("index.php/Traspasos/storeTraspaso"), valuesToSubmit, function (data) {
                estado = validarresultado_ajax(data);
                console.log(data);
                if (estado) {
                    if (data.respuesta) {
                        $("#modalIgresoDetalle").modal("hide");
                        limpiarArticulo();
                        limpiarCabecera();
                        limpiarTabla();
                        swal({
                            title: 'Traspaso realizado!',
                            text: "Traspaso guardada con éxito",
                            type: 'success',
                            showCancelButton: false
                        }).then(
                            function (result) {
                                let imprimir = base_url("pdf/Egresos/index/") + data.respuesta.egreso;
                                window.open(imprimir);
                            });
                    } else {
                        $(".mensaje_error").html("Error al almacenar los datos, intente nuevamente");
                        $("#modal_error").modal("show");
                    }

                }
            })
        } else {

            swal("Error", "No se tiene datos para guardar. ", "error")
        }
    }

}
function updateTraspaso() {
    let valuesToSubmit = $("#form_egreso").serialize();
    let tablaaux = tablatoarray();
    let almOrigen = $("#almacen_ori").val();
    let almDestino = $("#almacen_des").val();
    if (!checkTipoCambio) {
        swal("Error", "No se tiene tipo de cambio para esta Fecha", "error")
        return false;
    }
    if (almOrigen === almDestino) {
        swal("Error", "Almacen de destino es el mismo que el origen", "error")
    } else if (almDestino === "") {
        swal("Error", "Seleccione almacen de destino", "error")
    } else {
        console.log('ajax');

        if (tablaaux.length > 0 && !(almOrigen === almDestino)) {
            let tabla = JSON.stringify(tablaaux);

            valuesToSubmit += "&tabla=" + tabla;
            console.log(tabla);

            retornarajax(base_url("index.php/Traspasos/updateTraspaso"), valuesToSubmit, function (data) {
                estado = validarresultado_ajax(data);
                console.log(data);
                if (estado) {
                    if (data.respuesta) {

                        $("#modalIgresoDetalle").modal("hide");
                        limpiarArticulo();
                        limpiarCabecera();
                        limpiarTabla();
                        swal({
                            title: 'Traspaso modificado!',
                            text: "Traspaso modificado con éxito",
                            type: 'success',
                            showCancelButton: false
                        }).then(
                            function (result) {
                                window.location.href = base_url("Traspasos")
                            });
                    } else {
                        $(".mensaje_error").html("Error al almacenar los datos, intente nuevamente");
                        $("#modal_error").modal("show");
                    }

                }
            })
        } else {

            swal("Error", "No se tiene datos para guardar. ", "error")
        }
    }
    
}
function actualizarMovimiento() {
    let valuesToSubmit = $("#form_egreso").serialize();
    let tablaaux = tablatoarray();

    if (tablaaux.length > 0) {
        let tabla = JSON.stringify(tablaaux);

        valuesToSubmit += "&tabla=" + tabla;
        console.log(valuesToSubmit);
        /*retornarajax(base_url("index.php/Traspasos/actualizar"), valuesToSubmit, function (data) {
            estado = validarresultado_ajax(data);
            if (estado) {
                if (data.respuesta) {

                    //  $("#modalIgresoDetalle").modal("hide");
                    limpiarArticulo();
                    limpiarCabecera();
                    limpiarTabla();
                    $(".mensaje_ok").html("Datos actualizados correctamente");
                    $("#modal_ok").modal("show");
                    window.location.href = base_url("Egresos");
                } else {
                    $(".mensaje_error").html("Error al actualizar los datos, intente nuevamente");
                    $("#modal_error").modal("show");
                }

            }
        })*/
    } else {
        alert("no se tiene datos en la tabla para guardar")
    }
}

function anularTraspaso()
{
        let almDest = $('#almacen_des').find(":selected").text();
        let almOri = $('#almacen_ori').find(":selected").text();
        let valuesToSubmit = $("#form_egreso").serialize()
        console.log(valuesToSubmit);
        retornarajax(base_url("index.php/Traspasos/anularTraspaso"), valuesToSubmit, function (data) {
            estado = validarresultado_ajax(data);
            console.log(data);
            if (estado) {
                if (data.respuesta) {
                    swal({
                        title: 'Anulado!',
                        html: 'El traspaso de' + almOri +  ' a ' +  almDest + ' ha sido ANULADO',
                        type: 'warning',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok',
                    }).then((result) => {
                        //window.location.href = base_url("Egresos")
                    })
                } else {
                    $(".mensaje_error").html("Error al anular los datos, intente nuevamente");
                    $("#modal_error").modal("show");
                }

            }
        })
}

function recuperarTraspaso() // X
{
    var valuesToSubmit = $("#form_egreso").serialize();
    var tablaaux = tablatoarray();
    console.log(valuesToSubmit)
    console.log(tablaaux);
    if (tablaaux.length > 0) {
        var tabla = JSON.stringify(tablaaux);

        valuesToSubmit += "&tabla=" + tabla;
        retornarajax(base_url("index.php/Traspasos/recuperarTransferencia"), valuesToSubmit, function (data) {
            estado = validarresultado_ajax(data);
            if (estado) {
                if (data.respuesta) {

                    $("#modalIgresoDetalle").modal("hide");
                    limpiarArticulo();
                    limpiarCabecera();
                    limpiarTabla();
                    $(".mensaje_ok").html("Datos recuperados correctamente");
                    $("#modal_ok").modal("show");
                    window.location.href = base_url("Egresos");
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
function mostrarModal()
{
    $('#modalTipoCambio').modal('show');
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
                            $(datos[0]).val(), //id
                            $(datos[1]).val(), //codigo
                            $(datos[2]).val(),  //descrip
                            $(datos[3]).inputmask('unmaskedvalue'), //cantidad
                            $(datos[4]).inputmask('unmaskedvalue'), //costo
                            $(datos[5]).inputmask('unmaskedvalue'), //total
                        ))
    })
    return(tabla)
    console.log(filas)
}

$(document).on("click", "#guardarMovimiento", function () {
    almForm = $('#almacen_ori').val()
    almUser = $('#idAlmacenUsuario').val()
    isAdmin = $('#isAdmin').val()
    if (almForm != almUser && isAdmin == '') {
        swal("Error", "No se puede guardar movimiento", "error")
        return false
    }
    guardarmovimiento();
})
$(document).on("click", "#cancelarMovimiento", function () {
    limpiarArticulo();
    limpiarCabecera();
    limpiarTabla();
    window.location.href = base_url("Traspasos");
})
$(document).on("click", "#actualizarMovimiento", function () {
    updateTraspaso();
    console.log('update');
})
$(document).on("click", "#cancelarMovimientoActualizar", function () {
    window.location.href = base_url("Egresos");
})

$(document).on("click", "#anularTraspaso", function () {
    console.log('anularTraspaso');
    mensajeAnular("#obs_ne",
        function () {
            anularTraspaso();
        },
        function () {
            //window.location.href = base_url("Egresos");
        }
    );
})
$(document).on("click", "#recuperarTraspaso", function () {
    recuperarTraspaso();
    limpiarArticulo();
    limpiarCabecera();
    limpiarTabla();
})