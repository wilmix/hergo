let iniciofecha = moment().subtract(10, 'year').startOf('year')
let finfecha = moment().subtract(0, 'year').endOf('year')
let hoy = moment().format('DD-MM-YYYY')
let $table
let scope = this
let clienteCorrecto = false
let checkTipoCambio = false
window.operateEvents = {
    'click .agregartabla': function (e, value, row, index) {
        AgregarTabla(row);
        mostrarDatosCliente(row);
    },
    'click .quitardetabla': function (e, value, row, index) {
        QuitardeTabla(row.idEgresos);
    },
    'click .enviartabla3': function (e, value, row, index) {

        AgregarRegistroTabla3Cliente(row, index, this);
    },
    'click .eliminarElemento': function (e, value, row, index) {
        quitarElementoTablaCliente(row);
    },

}
$( function() {
    $("#cliente_factura").autocomplete(
    {      
      minLength: 3,
      autoFocus: true,
      source: function (request, response) {        
        $("#cargandocliente").show(150) 
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
          $("#cliente_factura").val( ui.item.nombreCliente + " - " + ui.item.documento);
          $("#idCliente_factura").val( ui.item.idCliente);
          $("#nit_factura").val( ui.item.documento);
          $("#nombreFacturaPrevia").val( ui.item.nombreCliente);
          $("#errorCliente").removeClass("hidden")
          clienteCorrecto = true
          return false;
      }
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
      
      return $( "<li>" )
        .append( "<a><div>" + item.nombreCliente + " </div><div style='color:#615f5f; font-size:10px'>" + item.documento + "</div></a>" )
        .appendTo( ul );
    };
 });
$(document).ready(function () {
    $('#fechaFactura').daterangepicker({
        singleDatePicker: true,
        startDate: hoy,
        autoApply: true,
        locale: {
            format: 'DD-MM-YYYY'
        },
        showDropdowns: true,
    });
    let res = {
        detalle: 0
    }
    mostrarTablaDetalle(res);
    mostrarTablaFactura();
    $(".tiponumerico").inputmask({
        alias: "decimal",
        digits: 2,
        groupSeparator: ',',
        autoGroup: true,
        autoUnmask: true
    });
    let start = moment().subtract(10, 'year').startOf('year')
    let end = moment().subtract(0, 'year').endOf('year')
    $(function () {
        moment.locale('es');
        function cb(start, end) {
            $('#fechapersonalizada span').html(start.format('D MMMM, YYYY') + ' - ' + end.format('D MMMM, YYYY'));
            iniciofecha = start
            finfecha = end
        }
        $('#fechapersonalizada').daterangepicker({
            locale: {
                format: 'DD/MM/YYYY',
                applyLabel: 'Aplicar',
                cancelLabel: 'Cancelar',
                customRangeLabel: 'Personalizado',
            },
            startDate: start,
            endDate: end,
            ranges: {
                'Gestion Actual': [moment().subtract(0, 'year').startOf('year'), moment().subtract(0, 'year').endOf('year')],
                "Hace un Año": [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
                'Hace dos Años': [moment().subtract(2, 'year').startOf('year'), moment().subtract(2, 'year').endOf('year')],
                'Hace tres Años': [moment().subtract(3, 'year').startOf('year'), moment().subtract(3, 'year').endOf('year')],
            }
        }, cb);
        cb(start, end);
    });
    $('#fechapersonalizada').on('apply.daterangepicker', function (ev, picker) {
        retornarTablaFacturacion();
    });
    retornarTablaFacturacion();
})
$(document).on("change", "#fechaFactura", function () {
    let fecha = $('#fechaFactura').val()
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
    let fecha = $('#fechaFactura').val()
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
            checkTipoCambio = true
            glob_tipoCambio = data.TipoCambio
            console.log(glob_tipoCambio);
            $('#modalTipoCambio').modal('hide')
            swal("Atencion!", "Agrego un tipo de cambio para " + formato_fecha_corta(data.fecha))
            $('#tipocambio').val('')
        }
    });
})
$(document).on("change", "#almacen_filtro", function () {
    retornarTablaFacturacion();
})
$(document).on("change", "#tipo_filtro", function () {
    retornarTablaFacturacion();
})
$(document).on("click", "#refresh", function () {
    retornarTablaFacturacion();
})
$(document).on("change", "#tipoFacturacion", function () {
    if ($("#tipoFacturacion").val() == 1) {
        $(".facturaManual").removeClass("hidden")
        $("#fechaFactura").removeAttr("disabled")
        $(".facturaManual").val('')
    } else {
        $(".facturaManual").addClass("hidden")
        $("#fechaFactura").attr({
            disabled: "disabled"
        });
        $(".facturaManual").val('')
    }
})
$(document).on("click", ".quitarTodos", function () {
    quitarTodosElementosTabla3Cliente();
    $("#errorCliente").addClass("hidden")
})
$(document).on("click", "#crearFactura", function () {
    if ($("#tipoFacturacion").val() == 1 && $("#numFacManual").val() =='' ) {
        swal("Error", "Debe agregar número de Factura", "error")
        return false
    }
    if ($("#cliente_factura").val().trim() == '' && clienteCorrecto == false) {
        swal("Error", "Debe seleccionar cliente", "error")
        return false
    }
    if (!checkTipoCambio) {
        swal("Error", "No se tiene tipo de cambio para esta Fecha", "error")
        return false;
    }
    let tabla3factura = $("#tabla3Factura").bootstrapTable('getData');
    if (tabla3factura.length > 0) {
        let datos = {
            idAlmacen: $("#idAlm").val(),
            tipoFacturacion: $("#tipoFacturacion").val(),
            fechaFactura: $("#fechaFactura").val(),
            numFacManual: $("#numFacManual").val(),
            idCliente:$("#idCliente_factura").val(),
            montoBs:$("#totalFacturaBs").val()
        }
        $.ajax({
            type: "POST",
            url: base_url('index.php/Facturas/consultarDatosFactura'),
            dataType: "json",
            data: datos,
        }).done(function (res) {
            if (res.response) {
                vistaPreviaFactura();
                agregarDatosFactura(res);
            }
            else {
                let error = "";
                $.each(res.error, function (index, value) {
                    error += value + "\n";
                })
                swal("Error", error, "error")
            }
        }).fail(function (jqxhr, textStatus, error) {
            var err = textStatus + ", " + error;
            console.log("Request Failed: " + err);
        });
    }
})
$(document).on("click", ".agregarTodos", function () {
    let tabla2detalle = $("#tabla2detalle").bootstrapTable('getData');
    AgregarRegistroTabla3ArrayCliente(tabla2detalle);
})
$(document).on("click", ".editable-click", function () {
    $(".tiponumerico").inputmask({
        alias: "decimal",
        digits: 2,
        groupSeparator: ',',
        autoGroup: true,
        autoUnmask: true
    });
})
$(document).on("click", "#guardarFactura", function () {
    agregarcargando();
    $("#guardarFactura").css("disabled", "true");
    let idCliente = $("#idCliente_factura").val()
    let tabla3factura = $("#tabla3Factura").bootstrapTable('getData');
    tabla3factura = JSON.stringify(tabla3factura);
    let tabla3 = JSON.parse(tabla3factura)
    let moneda = $("#moneda").val()
    if (moneda == 2) {
        for (let i = 0; i < tabla3.length; i++) {
            tabla3[i].punitario = tabla3[i].punitario * glob_tipoCambio;
        }
    }
    tabla3factura = JSON.stringify(tabla3)
    let datos = {
        almacen: $("#almacen_filtro").val(),
        tipoFacturacion: $("#tipoFacturacion").val(),
        numFacManual: $("#numFacManual").val(),
        fechaFac: $("#fechaFactura").val(),
        moneda: $("#moneda").val(),
        total: $("#totalFacturaBs").val(),
        observaciones: $("#observacionesFactura").val(),
        codigoControl: $("#codigoControl").html(),
        tabla: tabla3factura,
        idCliente : idCliente,
    }
    $.ajax({
        type: "POST",
        url: base_url('index.php/Facturas/storeFactura'),
        dataType: "json",
        data: datos,
    }).done(function (res) {
        if (res) {
            console.log(res);
            quitarcargando();
            $("#tabla3Factura").bootstrapTable('removeAll');
            swal({
                title: 'Factura Grabada!',
                text: "La factura se guardó con éxito",
                type: 'success',
                showCancelButton: false,
                allowOutsideClick: false,
            }).then(
                function (result) {
                    agregarcargando();
                    location.reload();
                    let imprimir = base_url("pdf/Factura/index/") + res;
                    window.open(imprimir);
                });

        }
        else {
            swal("Error", res.mensaje, "error")
        }
    }).fail(function (jqxhr, textStatus, error) {
        let err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
    });

})
$(document).on("change", "#moneda", function () {
    cambiarMonedaTabla3();
})

function mostrarModal()
{
    $('#modalTipoCambio').modal('show');
}
function retornarTablaFacturacion() {
    agregarcargando();
    ini = iniciofecha.format('YYYY-MM-DD')
    fin = finfecha.format('YYYY-MM-DD')
    alm = $("#almacen_filtro").val()
    tipo = $("#tipo_filtro").val()
    $.ajax({
        type: "POST",
        url: base_url('index.php/Facturas/MostrarTablaFacturacion'),
        dataType: "json",
        data: { ini: ini, fin: fin, alm: alm, tipo: tipo },
    }).done(function (res) {
        quitarcargando();
        datosselect = restornardatosSelect(res)
        $("#tfacturas").bootstrapTable('destroy');
        $('#tfacturas').bootstrapTable({
            data: res,
            striped: true,
            //pagination: true,
            //pageSize: "1000",
            search: true,
            filter: true,
            showColumns: true,
            strictSearch: true,
            columns: [
                {
                    field: 'almacen',
                    title: 'Almacen',
                    searchable: false,
                    visible: (alm == '' ? true : false),
                    sortable: true,
                },
                {
                    field: 'n',
                    title: 'N',
                    align: 'center',
                    sortable: true,
                },
                {
                    field: 'fechamov',
                    title: "Fecha",
                    sortable: true,
                    align: 'center',
                    searchable: false,
                    formatter: formato_fecha_corta,
                },
                {
                    field: 'nombreCliente',
                    title: "Cliente",
                    sortable: true,
                    filter: {
                        type: "select",
                        data: datosselect[1]
                    }
                },
                {
                    field: 'monedasigla',
                    title: "Moneda",
                    searchable: false,
                    visible: true,
                    sortable: true,
                },
                {
                    field: 'total',
                    title: "Total Bs",
                    align: 'right',
                    sortable: true,
                    formatter: operateFormatter3,
                },
                {
                    field: 'totalsus',
                    title: "Total Sus",
                    width: '7%',
                    align: 'right',
                    sortable: true,
                    formatter: operateFormatter3,
                },
                {
                    field: 'sigla',
                    title: "TipoMov",
                    sortable: true,
                    filter: {
                        type: "select",
                        data: ["NE", "VC"]
                    }
                },
                {
                    field: 'n',
                    title: "N Mov",
                    searchable: false,
                    sortable: true,
                },
                {
                    field: "plazopago",
                    title: "Plazo Pago",
                    sortable: true,
                    visible: false,
                    searchable: false,
                    align: 'center',
                    formatter: formato_fecha_corta,
                },
                {
                    field: "clientePedido",
                    title: "Pedido",
                    visible: false,
                    searchable: false,
                    align: 'left',
                },
                {
                    field: "autor",
                    title: "Vendedor",
                    align: 'center',
                    filter: {
                        type: "select",
                        data: datosselect[0]
                    }
                },
                {   
                    field: "estado",
                    title: "Estado",
                    sortable: true,
                    align: 'center',
                    searchable: false,
                    formatter: operateFormatter2,
                },
                {
                    title: 'Acciones',
                    align: 'center',
                    searchable: false,
                    visible: true,
                    events: operateEvents,
                    formatter: operateFormatter
                }]
        });
    }).fail(function (jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
    });
}
function mostrarTablaDetalle(res) {
    $("#tabla2detalle").bootstrapTable('destroy');
    $("#tabla2detalle").bootstrapTable({
        data: res.detalle,
        clickToSelect: true,
        search: false,
        rowStyle: rowStyle,
        columns: [
            {
                field: 'idEgreDetalle',
                title: 'idEgreDetalle',
                visible: false,
            },
            {
                field: 'idegreso',
                title: 'idegreso',
                visible: false,
            },
            {
                field: 'Sigla',
                title: 'unidad',
                visible: false,
            },
            {
                field: 'id',
                title: 'id',
                visible: true,
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
            },
            {
                field: 'cantidad',
                title: "Cantidad",
                formatter: operateFormatter3,
                visible: false
            },
            {
                field: 'cantidadReal',
                title: "Cantidad",
                align: 'right',
                formatter: operateFormatter3,
                class: "col-sm-1",
            },
            {
                field: 'punitario',
                title: res.moneda == 1 ? "P/U Bs" : "P/U Sus",
                align: 'right',
                class: "col-sm-1",
                formatter: operateFormatter3,
            },
            {

                title: "Total",
                align: 'right',
                class: "col-sm-1",
                formatter: totalTabla2
            },
            {

                title: '<button type="button" class="btn btn-default agregarTodos"><span class="fa fa-arrow-circle-right" aria-hidden="true"></span></button>',
                align: 'center',
                class: "col-sm-1",
                events: operateEvents,
                formatter: retornarBoton
            },
        ]
    });
}
function existeId(id) {
    let existe = false;
    let tabla3factura = $("#tabla3Factura").bootstrapTable('getData');
    $.each(tabla3factura, function (index, value) {
        if (value.idEgreDetalle == id) {
            existe = true
        }
    })
    return existe;
}
function registrosTabla3() {
    let tabla3factura = $("#tabla3Factura").bootstrapTable('getData');
    return tabla3factura.length;
}
function rowStyle(row, index) {
    let existe = false;
    let tabla3factura = $("#tabla3Factura").bootstrapTable('getData');
    $.each(tabla3factura, function (index, value) {
        if (value.idEgreDetalle == row.idingdetalle) {
            existe = true
        }
    })
    if (existe) {
        return {
            classes: "danger",
        };
    }
    else {
        return {};
    }
}
function mostrarTablaFactura() {
    $table = $("#tabla3Factura").bootstrapTable('destroy');
    $("#tabla3Factura").bootstrapTable({
        clickToSelect: true,
        uniqueId: 'idEgreDetalle',
        search: false,
        columns: [
            {
                field: 'idEgreDetalle',
                title: 'idEgreDetalle',
                visible: false,
            },
            {
                field: 'idegreso',
                title: 'idegreso',
                visible: false,
            },
            {
                field: 'Sigla',
                title: 'unidad',
                visible: false,
            },
            {
                field: 'id',
                title: 'id',
                visible: true,
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
            },
            {
                field: 'cantidadRealAux',
                title: "cantidadRealAux",
                formatter: operateFormatter3,
                visible: false
            },
            {
                field: 'cantidadReal',
                title: "Cantidad",
                align: 'right',
                class: "col-sm-1",
                formatter: operateFormatter3,
                editable: {
                    container: 'body',
                    type: 'text',
                    params: { a: 1, b: 2 },
                    inputclass: "tiponumerico",
                    validate: validateNum,
                },
            },
            {
                field: 'punitario',
                title: "P/U",
                align: 'right',
                class: "col-sm-1",
                editable: {
                    container: 'body',
                    type: 'text',
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
                formatter: operateFormatter3,
            },
            {
                title: "Total",
                align: 'right',
                class: "col-sm-1",
                formatter: totalTabla2,
            },
            {

                title: '<button type="button" class="btn btn-default quitarTodos"><span class="fa fa-times-circle" aria-hidden="true"></span></button>',
                align: 'center',
                class: "col-sm-1",
                events: operateEvents,
                formatter: retornarBoton2
            },
        ]
    });
    $table.on('editable-save.bs.table', function (e, field, row, old, $el) {
        let total = parseFloat(row.punitario) * parseFloat(row.cantidadReal);
        $("#tabla3Factura").bootstrapTable('updateByUniqueId', {
            id: row.idEgreDetalle,
            row: {
                total: total.toFixed(2)
            }
        });
        calcularTotalFactura();
    });
}
scope.formatoMoneda = function (value) {
    $(this).html(operateFormatter3(value));
}
function operateFormatter2(value, row, index) {
    $ret = ''
    if (row.anulado == 1) {
        $ret = '<span class="label label-warning">ANULADO</span>';
    }
    else {
        if (value == 0)
            $ret = '<span class="label label-danger">No facturado</span>';
        if (value == 1)
            $ret = '<span class="label label-success">T. Facturado</span>';
        if (value == 2)
            $ret = '<span class="label label-info">Facturado Parcial</span>';
    }
    return ($ret);
}
function operateFormatter3(value, row, index) {
    num = Math.round(value * 100) / 100
    num = num.toFixed(2)
    return (formatNumber.new(num))
}
function totalTabla2(value, row, index) {
    let pu = (Math.round(row.punitario * 100) / 100).toFixed(2)
    let cant = (Math.round(row.cantidadReal * 100) / 100).toFixed(2)
    return (operateFormatter3(pu * cant));
}
function mostrarDatosCliente(row) {
    $("#nombreClienteTabla1").val(row.nombreCliente);
    $("#tipoNumEgreso").val(row.sigla + "-" + row.n);
    $("#pedidoClienteT2").val(row.clientePedido);
    $("#monedaT2").val(row.monedasigla);
    $("#idMonedaT2").val(row.moneda);
    $("#idclienteHidden").val(row.idcliente);
}
function operateFormatter(value, row, index) {
    return [
        '<button type="button" class="btn btn-default agregartabla" data-view="' + row.idEgresos + '" aria-label="Right Align">',
        '<span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>',
        '<button type="button" class="btn btn-default quitardetabla hidden" data-remove="' + row.idEgresos + '" aria-label="Right Align">',
        '<span class="fa fa-minus-square-o " aria-hidden="true"></span></button>',
    ].join('');
}
function retornarBoton(value, row, index) {
    return [
        '<button type="button" class="btn btn-default enviartabla3" data-view="' + row.idingdetalle + '"><span class="fa fa-arrow-right" aria-hidden="true"></span></button>',
    ].join('');
}
function retornarBoton2(value, row, index) {
    return [
        '<button type="button" class="btn btn-default eliminarElemento" data-view="' + row.idingdetalle + '"><span class="fa fa-times" aria-hidden="true"></span></button>',
    ].join('');
}
function quitarElementoTabla(row) {
    ids = new Array(row.idEgreDetalle)
    $("#tabla3Factura").bootstrapTable('remove', {
        field: 'idEgreDetalle',
        values: ids
    });
    $.ajax({
        type: "POST",
        url: base_url('index.php/Facturas/eliminarElementoTabla3'),
        dataType: "json",
        data: { idegresoDetalle: row.idEgreDetalle },
    }).done(function (res) {

        $('[data-view="' + row.idEgreDetalle + '"]').parents("tr").removeClass("danger");
        calcularTotalFactura();
    }).fail(function (jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
    });
}
function quitarElementoTablaCliente(row) {
    ids = new Array(row.idEgreDetalle)
    $("#tabla3Factura").bootstrapTable('remove', {
        field: 'idEgreDetalle',
        values: ids
    });
    if (registrosTabla3() == 0) {
        $("#valuecliente").val("");
        $("#valueidcliente").val("");
        $("#nitCliente").val("");
        $("#clienteFactura").html("");
        $("#clienteFacturaNit").html("")
        $("#clientePedido").html("")
    }
    $('[data-view="' + row.idEgreDetalle + '"]').parents("tr").removeClass("danger");
    calcularTotalFactura();
}
function quitarTodosElementosTabla3() {
    $("#tabla3Factura").bootstrapTable('removeAll');
    $.ajax({
        type: "POST",
        url: base_url('index.php/Facturas/eliminarTodosElementoTabla3'),
        dataType: "json",
    }).done(function (res) {
        $.each($("#tabla2detalle").find("tbody tr"), function (index, value) {
            $(value).removeClass("danger");
        })
        $("#cliente_factura").val("");
        $("#idCliente_factura").val("");
        $("#valuecliente").val("");
        $("#valueidcliente").val("");
        calcularTotalFactura();
    }).fail(function (jqxhr, textStatus, error) {
        let err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
    });


}
function quitarTodosElementosTabla3Cliente() {
    $("#tabla3Factura").bootstrapTable('removeAll');
        $.each($("#tabla2detalle").find("tbody tr"), function (index, value) {
            $(value).removeClass("danger");
        })
        $("#cliente_factura").val("");
        $("#idCliente_factura").val("");
        $("#valuecliente").val("");
        $("#valueidcliente").val("");
        $("#nitCliente").val("");
        $("#clienteFactura").html("");
        $("#clienteFacturaNit").html("")
        $("#clientePedido").html("")
        calcularTotalFactura();
}
function AgregarRegistroTabla3(row, index, btn) {
    $("#tabla3Factura").bootstrapTable('showLoading');
    //Comprobamos si el semaforo esta en verde (1)
    let $f = $(this);
    console.log($f.data('locked'))
    if ($f.data('locked') == undefined || !$f.data('locked')) {
        $.ajax({
            type: "POST",
            url: base_url('index.php/Facturas/retornarTabla3'),
            dataType: "json",
            data: { idegreso: row.idegreso, idegresoDetalle: row.idingdetalle },
            beforeSend: function () { $f.data('locked', true); },// (2)
            complete: function () { $f.data('locked', false); }// (3)
        }).done(function (res) {
            if (res.detalle) {
                $("#nombreCliente").val(res.cliente);
                $("#valuecliente").val(res.cliente);
                $("#valueidcliente").val(res.idCliente);
                $("#nitCliente").val(res.clienteNit);
                agregarRegistrosTabla3(res.detalle);
                let tr = $('[data-index="' + index + '"]', "#tabla2detalle").addClass("danger")
                $("#clienteFactura").html(res.cliente);
                $("#clienteFacturaNit").html(res.clienteNit)
                $("#clientePedido").html(res.clientePedido)
                calcularTotalFactura();
            }
            else {
                swal("Error", res.mensaje, "error")
            }
            $("#tabla3Factura").bootstrapTable('hideLoading');
            $(btn).removeClass("disabled");
        }).fail(function (jqxhr, textStatus, error) {
            var err = textStatus + ", " + error;
            console.log("Request Failed: " + err);
        });
    }
}
function AgregarRegistroTabla3Cliente(row, index, btn) {
    let monedaE = $("#idMonedaT2").val();
    if ($("#cliente_factura").val() == "" || $("#cliente_factura").val() == $("#nombreClienteTabla1").val()) {
        if (!existeId(row.idingdetalle)) {
            $.ajax({
                type: "POST",
                url: base_url('index.php/Facturas/retornarTabla3Cliente'),
                dataType: "json",
                data: { idegreso: row.idegreso, idegresoDetalle: row.idingdetalle },
            }).done(function (res) {
                $("#cliente_factura").val(res.cliente);
                $("#idCliente_factura").val(res.idCliente);
                $("#nit_factura").val(res.clienteNit);
                $("#valuecliente").val(res.cliente);
                $("#valueidcliente").val(res.idCliente);
                $("#nitCliente").val(res.clienteNit);
                $("#clienteFactura").html(res.cliente);
                $("#clienteFacturaNit").html(res.clienteNit)
                $("#clientePedido").html(res.clientePedido)
                $("#moneda").val(monedaE)
            }).fail(function (jqxhr, textStatus, error) {
                let err = textStatus + ", " + error;
                console.log("Request Failed: " + err);
            });
            agregarRegistrosTabla3Cliente(row);
            let tr = $('[data-index="' + index + '"]', "#tabla2detalle").addClass("danger")
            calcularTotalFactura();
            $("#tabla3Factura").bootstrapTable('hideLoading');
            $(btn).removeClass("disabled");
        }
        else {
            swal("Error", "Ya se agrego este registro", "error")
        }
    }
    else {
        swal("Error", "No se pueden agregar registros de otro cliente", "error")
    }
}
function AgregarTabla(datos) {
    $("#tabla2detalle").bootstrapTable('showLoading');
    $.ajax({
        type: "POST",
        url: base_url('index.php/Facturas/retornarTabla2'),
        dataType: "json",
        data: { idegreso: datos.idEgresos },
    }).done(function (res) {
        if (res.detalle) {
            agregarRegistrosTabla2(res);
            $("#idAlm").val(res.alm);
            $("#valueidcliente").val(res.idcliente);
        }
        else {
            swal("Error", res.mensaje, "error")
        }
        $("#tabla2detalle").bootstrapTable('hideLoading');
    }).fail(function (jqxhr, textStatus, error) {
        let err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
    });
}
function agregarRegistrosTabla2(detalle) {
    mostrarTablaDetalle(detalle);
    $("#tabla2detalle").bootstrapTable('resetView');
}
function agregarRegistrosTabla3(detalle) {
    let moneda = $("#moneda").val();
    detalle = detalle[0];
    let rows = [];
    rows.push({
        idEgreDetalle: detalle.idingdetalle,
        idegreso: detalle.idegreso,
        Sigla: detalle.Sigla,
        CodigoArticulo: detalle.CodigoArticulo,
        Descripcion: detalle.Descripcion,
        cantidadRealAux: parseFloat(detalle.cantidadReal),
        cantidadReal: parseFloat(detalle.cantidadReal),
        punitario: parseFloat(detalle.punitario),//moneda == 2 ? detalle.punitario / glob_tipoCambio : detalle.punitario,
        total: parseFloat(detalle.total).toFixed(2) //moneda == 2 ? detalle.total.toFixed(2) / glob_tipoCambio : detalle.total.toFixed(2),
    })
    $("#tabla3Factura").bootstrapTable('append', rows);
}
function agregarRegistrosTabla3Cliente(detalle) {
    if (!existeId(detalle.idingdetalle)) {
            let rows = [];
            rows.push({
                id:detalle.id,
                idEgreDetalle: detalle.idingdetalle,
                idegreso: detalle.idegreso,
                Sigla: detalle.Sigla,
                CodigoArticulo: detalle.CodigoArticulo,
                Descripcion: detalle.Descripcion,
                cantidadRealAux: parseFloat(detalle.cantidadReal),
                cantidadReal: parseFloat(detalle.cantidadReal),
                punitario: parseFloat(detalle.punitario),// moneda == 2 ? detalle.punitario / glob_tipoCambio : detalle.punitario,
                total: parseFloat(detalle.total).toFixed(2) //moneda == 2 ? detalle.total.toFixed(2) / glob_tipoCambio : detalle.total.toFixed(2),
            })
            $("#tabla3Factura").bootstrapTable('append', rows);
    }
   
}
function calcularTotalFactura() {
    let moneda = $("#moneda").val();
    let tabla3factura = $("#tabla3Factura").bootstrapTable('getData');
    let total = 0;
    $.each(tabla3factura, function (index, value) {
        aux = (value.punitario * value.cantidadReal)
        total = total + parseFloat(aux.toFixed(2))
    })
    /****************Bs**************/
    let totalBs = moneda == 2 ? (parseFloat(total) * parseFloat(glob_tipoCambio)) : total;
    $("#totalFacturaBs").val(totalBs);
    /*************SUS***************/
    let totalSus = moneda == 2 ? total : parseFloat(total) / parseFloat(glob_tipoCambio);
    $("#totalFacturaSus").val(totalSus);
}
function agregarDatosFactura(res) {
    vmVistaPrevia.guardar = true;
    vmVistaPrevia.nit = res.nit;
    vmVistaPrevia.numero = res.nfac
    vmVistaPrevia.glosa01 = res.glosa01
    vmVistaPrevia.glosa02 = res.glosa02
    vmVistaPrevia.glosa03 = res.glosa03
    vmVistaPrevia.autorizacion = res.autorizacion;
    vmVistaPrevia.fechaLimiteEmision = res.fechaLimite
    vmVistaPrevia.codigoControl = res.codigoControl

    //vmVistaPrevia.llave = res.detalle.llaveDosificacion;
    vmVistaPrevia.manual = $("#tipoFacturacion").val();
    vmVistaPrevia.ClienteFactura = $("#cliente_factura").val();
    vmVistaPrevia.ClienteNit = $("#nit_factura").val();
    vmVistaPrevia.tipocambio = glob_tipoCambio;
    vmVistaPrevia.moneda = $("#moneda").val();
    //vmVistaPrevia.generarCodigoControl() //este dato se extrae de la base de datos, solo se usa para generar el codigo
    vmVistaPrevia.generarCodigoQr();
    $("#facPrev").modal("show");
}
function vistaPreviaFactura() {
    let tabla3factura = $("#tabla3Factura").bootstrapTable('getData');
    if (tabla3factura.length > 0) {
        let arreglo = tabla3factura.map(item => {
            return {
                facturaCantidad: item.cantidadReal,
                Sigla: item.Sigla,
                ArticuloCodigo: item.CodigoArticulo,
                ArticuloNombre: item.Descripcion,
                facturaPUnitario: item.punitario
            }
        })
        vmVistaPrevia.datosFactura = arreglo;
        vmVistaPrevia.fecha = $("#fechaFactura").val();
        vmVistaPrevia.glosa = $("#observacionesFactura").val()
    }
}
function AgregarRegistroTabla3Array(row) {
    $("#tabla3Factura").bootstrapTable('showLoading');
    let datos = JSON.stringify(row);
    $.ajax({
        type: "POST",
        url: base_url('index.php/Facturas/retornarTabla3Array'),
        dataType: "json",
        data: { rows: datos },
    }).done(function (res) {
        $("#tabla3Factura").bootstrapTable('hideLoading');
        if (res.detalle && res.detalle.length != 0) {
            $("#valuecliente").val(res.cliente);
            $("#nombreCliente").val(res.cliente);
            $("#valueidcliente").val(res.idCliente);
            $("#nitCliente").val(res.clienteNit);
            $("#clienteFactura").html(res.cliente);
            $("#clienteFacturaNit").html(res.clienteNit)
            $("#clientePedido").html(res.clientePedido)
            $.each(res.detalle, function (index, value) {
                agregarRegistrosTabla3(value);
                $('[data-index="' + index + '"]', "#tabla2detalle").addClass("danger")
            })
            calcularTotalFactura();
            $.each($("#tabla2detalle").find("tbody tr"), function (index, value) {
                $(value).addClass("danger");
            })
        }
        else {
            swal("Error", res.mensaje, "error")
        }
    }).fail(function (jqxhr, textStatus, error) {
        let err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
    });
}
function AgregarRegistroTabla3ArrayCliente(row) {
    let monedaE = $("#idMonedaT2").val();
    if (row.length > 0) {
        if ($("#cliente_factura").val() == "" || $("#cliente_factura").val() == $("#nombreClienteTabla1").val()) {
            $.ajax({
                type: "POST",
                url: base_url('index.php/Facturas/retornarTabla3Cliente'),
                dataType: "json",
                data: { idegreso: row[0].idegreso, idegresoDetalle: row[0].idingdetalle },
            }).done(function (res) {
            
                $("#cliente_factura").val(res.cliente)
                $("#idCliente_factura").val(res.idCliente)
                $("#nit_factura").val(res.clienteNit)
                $("#valuecliente").val(res.cliente);
                $("#valueidcliente").val(res.idCliente)
                $("#nitCliente").val(res.clienteNit)
                $("#clienteFactura").html(res.cliente)
                $("#clienteFacturaNit").html(res.clienteNit)
                $("#clientePedido").html(res.clientePedido)
                $("#moneda").val(monedaE)
            }).fail(function (jqxhr, textStatus, error) {
                let err = textStatus + ", " + error;
                console.log("Request Failed: " + err);
            });
            $.each(row, function (index, value) {
                agregarRegistrosTabla3Cliente(value);
                $('[data-index="' + index + '"]', "#tabla2detalle").addClass("danger")
            })
            calcularTotalFactura();
            $.each($("#tabla2detalle").find("tbody tr"), function (index, value) {
                $(value).addClass("danger");
            })
        }
        else {
            swal("Error", "No se pueden agregar registros de otro cliente", "error")
        }
    }
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
    let data = $("#tabla3Factura").bootstrapTable('getData');
    console.log(data);
    let index = $(this).parents('tr').data('index');
    console.log(index);
    let row = (data[index]);
    console.log(row);
    if (parseFloat(value) > parseFloat(row.cantidadRealAux)) {
        return 'No puede ser mayor a ' + row.cantidadRealAux;
    }
}
function cambiarMonedaTabla3() {
    //console.log('cambiarMoneda');
    //return false
    let moneda = $("#moneda").val();
    let totalRows = ($('#tabla3Factura').bootstrapTable('getOptions').data.length)
    for (let index = 0; index < totalRows; index++) {
        let punitario = $('#tabla3Factura').bootstrapTable('getOptions').data[index].punitario;
        let cantidad = $('#tabla3Factura').bootstrapTable('getOptions').data[index].cantidadReal;
        let nuevopunitario = moneda == 2 ? punitario / glob_tipoCambio : punitario * glob_tipoCambio;
        $('#tabla3Factura').bootstrapTable('updateRow', {
            index: index,
            row: {
                //punitario: nuevopunitario,
                total: cantidad * punitario
            }
        });
    }
    calcularTotalFactura();
}
function restornardatosSelect(res) {
    let cliente = new Array()
    let vendedor = new Array()
    let datos = new Array()
    $.each(res, function (index, value) {
        cliente.push(value.nombreCliente)
        vendedor.push(value.autor)
    })
    vendedor.sort()
    cliente.sort()
    datos.push(vendedor.unique())
    datos.push(cliente.unique())
    return (datos)
}
Array.prototype.unique = function (a) {
    return function () { return this.filter(a) }
}(function (a, b, c) {
    return c.indexOf(a, b + 1) < 0
});