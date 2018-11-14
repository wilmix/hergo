let iniciofecha = moment().subtract(0, 'year').startOf('year')
let finfecha = moment().subtract(0, 'year').endOf('year')

$(document).ready(function () {
    $(".tiponumerico").inputmask({
        alias: "decimal",
        digits: 2,
        groupSeparator: ',',
        autoGroup: true,
        autoUnmask: true
    })
    let start = moment().subtract(0, 'year').startOf('year')
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
        retornarTablaTraspasos();
    });
    retornarTablaTraspasos();
})
$(document).on("change", "#almacen_filtro", function () {
    retornarTablaTraspasos();
})
$(document).on("change", "#tipo_filtro", function () {
    retornarTablaTraspasos();
})
$(document).on("click", "#refresh", function () {
    retornarTablaTraspasos();
})


function retornarTablaTraspasos() {
    ini = iniciofecha.format('YYYY-MM-DD')
    fin = finfecha.format('YYYY-MM-DD')
    agregarcargando();
    $.ajax({
        type: "POST",
        url: base_url('index.php/Traspasos/motrarTraspasos'),
        dataType: "json",
        data: {
            i: ini,
            f: fin
        },
    }).done(function (res) {
        datosselect = restornardatosSelect(res)
        quitarcargando();
        $("#tTraspasos").bootstrapTable('destroy');
        $('#tTraspasos').bootstrapTable({
            data: res,
            striped: true,
            pagination: true,
            pageSize: "100",
            search: true,
            strictSearch: true,
            filter: true,
            columns: [{
                    field: 'fecha',
                    title: "Fecha",
                    sortable: true,
                    align: 'center',
                    formatter: formato_fecha_corta,
                    searchable: false,
                },
                {
                    field: 'numEgreso',
                    title: "Nº Egreso",
                    align: 'center',
                    sortable: true,
                    width: "20px",
                },
                {
                    field: 'origen',
                    title: "Almacen Origen",
                    sortable: true,
                    filter: {
                        type: "select",
                        data: datosselect[0]
                    }
                },
                {
                    field: 'numIngreso',
                    title: "Nº Ingreso",
                    align: 'center',
                    sortable: true,
                    width: "20px",
                },
                {
                    field: 'destino',
                    title: "Almacen Destino",
                    sortable: true,
                    filter: {
                        type: "select",
                        data: datosselect[1]
                    }
                },
                {
                    field: "estadoT",
                    title: "Estado",
                    sortable: true,
                    align: 'center',
                    cellStyle: cellStyle,
                    filter: {
                        type: "select",
                        data: datosselect[2]
                    },
                },

                {
                    title: 'Acciones',
                    align: 'center',
                    width: '100px',
                    events: operateEvents,
                    searchable: false,
                    formatter: operateFormatter
                }
            ]

        });
        $("#tegresos").bootstrapTable('resetView');
        mensajeregistrostabla(res, "#tegresos");
    }).fail(function (jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
    });
}

function operateFormatter(value, row, index) {
    return [
        '<button type="button" class="btn btn-default verTraspaso" aria-label="Right Align" data-toggle="tooltip" title="Ver">',
        '<span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>',
    ].join('');
}

function cellStyle(value, row, index) {
    if (row.estadoT == 'PENDIENTE') {
        return {
            css: {
                "color": "red",
                "text-decoration": "underline overline",
                "font-weight": "bold",
                "font-style": "italic",
                "padding-top": "15px",
            }
        }
    } else if (row.estadoT == 'APROBADO') {
        return {
            css: {
                "color": "green",
                "text-decoration": "underline overline",
                "font-weight": "bold",
                "font-style": "italic",
                "padding-top": "15px",
            }
        }

    } else {
        return {
            css: {
                "color": "black",
                "text-decoration": "underline overline",
                "font-weight": "bold",
                "font-style": "italic",
                "padding-top": "15px",
            }
        }
    }
    return {};

}

function operateFormatter2(value, row, index) {
    $ret = ''

    if (row.anulado == 1) {
        $ret = '<span class="label label-warning">ANULADO</span>';
    } else {
        if (value == 0)
            $ret = '<span class="label label-danger">PENDIENTE</span>';
        if (value == 1)
            $ret = '<span class="label label-success">APROBADO</span>';
    }

    return ($ret);
}

function operateFormatter3(value, row, index) {
    num = Math.round(value * 100) / 100
    num = num.toFixed(2);
    return (formatNumber.new(num));
}

function mostrarFactura(value, row, index) {
    var cadena = ""
    $.each(value, function (index, val) {
        cadena += val.nFactura
        // cadena+=" - ";
        if (index < value.length - 1)
            cadena += " - ";
    })

    return (cadena);
}
/***********Eventos*************/
window.operateEvents = {
    'click .verTraspaso': function (e, value, row, index) {
        verdetalle(row)
    },
    'click .imprimirIngreso': function (e, value, row, index) {
        //alert(JSON.stringify(row));
    }
};

function verdetalle(fila) {
    id = fila.idEgreso
    datos = {
        id: id
    }
    retornarajax(base_url("index.php/Traspasos/mostrarDetalle"), datos, function (data) {
        estado = validarresultado_ajax(data);
        if (estado) {
            mostrarDetalle(data.respuesta);

            $("#almacen_ori").val(fila.origen + ' # ' + fila.numEgreso)
            $("#almacen_des").val(fila.destino + ' # ' + fila.numIngreso)
            $("#fechamov_egr").val(formato_fecha_corta(fila.fecha));
            $("#pedido").val(fila.clientePedido)
            $("#obs_egr").val(fila.obs);
            $("#modalEgresoDetalle").modal("show");
        }
    })
}

function mostrarDetalle(res) {
    //console.log(res)
    $("#tTraspasodetalle").bootstrapTable('destroy');
    $("#tTraspasodetalle").bootstrapTable({

        data: res,
        striped: true,
        pagination: true,
        clickToSelect: true,
        search: false,
        columns: [{
                field: 'CodigoArticulo',
                title: 'Código',
                align: 'center',
                width: '100px',
                sortable: true,
            },
            {
                field: 'Descripcion',
                title: 'Descripcion',
                sortable: true,
            },
            {
                field: 'cantidad',
                title: "Cantidad",
                align: 'right',
                width: '100px',
                sortable: true,
            },
            {
                field: 'punitario',
                title: "P/U Bs",
                align: 'right',
                width: '100px',
                visible: false,
                sortable: true,
            },
            {
                field: 'total',
                title: "Total",
                align: 'right',
                width: '100px',
                visible: false,
                sortable: true,
            },

        ]
    });
}

function restornardatosSelect(res) {

    let origen = new Array()
    let destino = new Array()
    let estado = new Array()
    let datos = new Array()
    $.each(res, function (index, value) {

        origen.push(value.origen)
        destino.push(value.destino)
        estado.push(value.estadoT)
    })
    origen.sort()
    destino.sort()
    estado.sort()
    datos.push(origen.unique())
    datos.push(destino.unique())
    datos.push(estado.unique())
    console.log(datos);
    return (datos);
}
Array.prototype.unique = function (a) {
    return function () {
        return this.filter(a)
    }
}(function (a, b, c) {
    return c.indexOf(a, b + 1) < 0
});