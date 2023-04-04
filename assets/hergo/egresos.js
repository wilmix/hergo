var iniciofecha = moment().subtract(0, 'year').startOf('year')
var finfecha = moment().subtract(0, 'year').endOf('year')

$(document).ready(function () {


    var start = moment().subtract(0, 'year').startOf('year')
    var end = moment().subtract(0, 'year').endOf('year')
    var actual = moment().subtract(0, 'year').startOf('year')
    var unanterior = moment().subtract(1, 'year').startOf('year')
    var dosanterior = moment().subtract(2, 'year').startOf('year')
    var tresanterior = moment().subtract(3, 'year').startOf('year')

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
        retornarTablaEgresos();
    });
    retornarTablaEgresos();
})
$(document).on("change", "#almacen_filtro", function () {
    retornarTablaEgresos();
})
$(document).on("change", "#tipo_filtro", function () {
    retornarTablaEgresos();
})
$(document).on("click", "#refresh", function () {
    retornarTablaEgresos();
})
function mostrarTablaEgresosTraspasos(res) {
    $('#tegresos').bootstrapTable({

        data: res,
        striped: true,
        pagination: true,
        pageSize: "25",
        search: true,
        filter: true,
        showColumns: true,
        stickyHeader: true,
        stickyHeaderOffsetY: '50px',
        strictSearch: true,
        showToggle:true,

        columns: [{
                field: 'n',
                width: '3%',
                title: 'N',
                align: 'center',
                sortable: true,
                filter: {
                    type: "input"
                }
            },
            {
                field: 'fechamov',
                width: '7%',
                title: "Fecha",
                sortable: true,
                align: 'center',
                formatter: formato_fecha_corta,
            },
            {
                field: 'destino',
                title: "Destino",
                width: '17%',
                sortable: true,
                filter: {
                    type: "select",
                    data: datosselect[2]
                },

            },
            {
                field: 'monedasigla',
                title: "Mon",
                width: '1%',
                align: 'right',
                sortable: true,
                visible: false,
                filter: {
                    type: "select",
                    data: ["$US", "BS."],
                },
            },
            {
                field: 'totalsus',
                title: "Total Sus",
                width: '7%',
                align: 'right',
                sortable: true,
                visible: false,
                formatter: operateFormatter3,
                filter: {
                    type: "input"
                }
            },
            {
                field: 'total',
                title: "Total Bs",
                width: '7%',
                align: 'right',
                sortable: true,
                visible: false,
                formatter: operateFormatter3,
                filter: {
                    type: "input"
                }
            },
            
            {
                field: "clientePedido",
                width: '8%',
                title: "N° Pedido",
                sortable: true,
                visible: true,
                align: 'center',
            },
            {
                field: "plazopago",
                width: '8%',
                title: "PlazoPago",
                sortable: true,
                visible: false,
                align: 'center',
                formatter: formato_fecha_corta,
            },
            {
                field: "estado",
                title: "Estado",
                width: '7%',
                visible:true,
                sortable: true,
                align: 'center',
                cellStyle:cellStyle,
                //formatter: operateFormatter2,

            },
            {
                field: "autor",
                width: '8%',
                title: "Autor",
                sortable: true,
                visible: false,
                align: 'center',
                filter: {
                    type: "select",
                    data: datosselect[0]
                },
            },
            {
                field: "fecha",
                width: '8%',
                title: "Fecha",
                sortable: true,
                visible: false,
                align: 'center',
                formatter: formato_fecha_corta,
            },
            {
                title: 'Acciones',
                align: 'center',
                width: '11%',
                events: operateEvents,
                formatter: operateFormatter
            }
        ]

    });
}

function mostrarTablaEgresos(res) {
    $('#tegresos').bootstrapTable({

        data: res,
        striped: true,
        pagination: true,
        pageSize: "100",
        search: true,
        filter: true,
        showColumns: true,
        stickyHeader: true,
        stickyHeaderOffsetY: '50px',
        //strictSearch: true,
        showToggle:true,
        columns: [
            {
                field: 'sigla',
                title: "Tipo",
                align: 'center',
                searchable: false,
                visible:false
            },
            {
                field: 'n',
                title: 'N',
                align: 'center',
                sortable: true,
                searchable: true,
            },
            {
                field: 'fechamov',
                title: "Fecha",
                sortable: true,
                align: 'center',
                formatter: formato_fecha,
                searchable: false,
            },
            {
                field: 'nombreCliente',
                title: "Cliente",
                sortable: true,
                filter: {
                    type: "select",
                    data: datosselect[1]
                },

            },
            {
                field: 'factura',
                title: "Factura",
                sortable: true,
                searchable: false,
                width:'80px',
                align:'center',
                filter: {
                    type: "input"
                }

            },
            {
                field: 'monedasigla',
                title: "Moneda",
                align: 'center',
                visible: true,
                width:'50px',
                sortable: true,
                searchable: false,
            },
            {
                field: 'totalsus',
                title: "Total Sus",
                align: 'right',
                sortable: true,
                width:'100px',
                formatter: operateFormatter3,
                searchable: false,
                filter: {
                    type: "input"
                }
            },
            {
                field: 'total',
                title: "Total Bs",
                width:'100px',
                align: 'right',
                sortable: true,
                formatter: operateFormatter3,
                searchable: false,
                filter: {
                    type: "input"
                }
            },
            {
                field: "tipoNota",
                title: "TipoNota",
                sortable: true,
                align: 'center',
                searchable: true,
                //visible: false,
                formatter: tipoNota,
            },
            {
                field: "clientePedido",
                title: "N° Pedido",
                sortable: true,
                align: 'center',
                searchable: true,
            },
            {
                field: "autor",
                title: "Autor",
                sortable: true,
                visible: false,
                align: 'center',
                /* filter: {
                    type: "select",
                    data: datosselect[0]
                }, */
            },
            {
                field: "vendedor",
                title: "Responsable",
                sortable: true,
                visible: true,
                align: 'center',
                filter: {
                    type: "select",
                    data: datosselect[0]
                },
            },
            {
                field: "estadoF",
                title: "Estado",
                width: '7%',
                sortable: true,
                align: 'center',
                cellStyle:cellStyle,
                filter: {
                    type: "select",
                    data: datosselect[3]
                },

            },
            {
                field: "plazopago",
                title: "PlazoPago",
                sortable: true,
                visible: false,
                align: 'center',
                searchable: false,
                formatter: formato_fecha_corta,
            },
            {
                field: "fecha",
                title: "Fecha",
                sortable: true,
                visible: false,
                align: 'center',
                searchable: false,
               // formatter: formato_fecha_corta,
            },
            {
                title: 'Acciones',
                align: 'center',
                width: '200px',
                searchable: false,
                events: operateEvents,
                formatter: operateFormatter
            }
        ]

    });
}

function retornarTablaEgresos() {
    ini = iniciofecha.format('YYYY-MM-DD')
    fin = finfecha.format('YYYY-MM-DD')
    alm = $("#almacen_filtro").val()
    tipoingreso = $("#tipo_filtro").val()
    agregarcargando();
    $.ajax({
        type: "POST",
        url: base_url('index.php/Egresos/mostrarEgresos'),
        dataType: "json",
        data: {
            i: ini,
            f: fin,
            a: alm,
            ti: tipoingreso
        },
    }).done(function (res) {
        //  console.log(res)
        datosselect = restornardatosSelect(res)
        quitarcargando();
        $("#tegresos").bootstrapTable('destroy');
        if (tipoingreso == 8)
            mostrarTablaEgresosTraspasos(res)
        else
            mostrarTablaEgresos(res)
        //$("#tegresos").bootstrapTable('showLoading');
        $("#tegresos").bootstrapTable('resetView');
        mensajeregistrostabla(res, "#tegresos");
    }).fail(function (jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
    });
    //$("body").css("padding-right","0px");

}

function cellStyle(value, row, index) {
    if (row.anulado ==1) {
        return { 
            css: {
                "color":"black",
                "text-decoration": "underline overline",
                "font-weight": "bold",
                "font-style": "italic",
                "padding-top": "15px",
            } 
        }
     }else if (row.estadoF =='*FACTURADO*'){
        return { 
            css: {
            "color":"green",
            "text-decoration": "underline overline",
            "font-weight": "bold",
            "font-style": "italic",
            "padding-top": "15px",
            } 
        }

     } else if (row.estadoF =='NO FACTURADO') {
        return { 
            css: {
            "color":"red",
            "font-size": "80%",
            "text-decoration": "underline overline",
            "font-weight": "bold",
            "font-style": "italic",
            "padding-top": "15px",
            } 
        }
     } else if (row.estadoF =='PARCIAL') {
        return { 
            css: {
            "color":"blue",
            "text-decoration": "underline overline",
            "font-weight": "bold",
            "font-style": "italic",
            "padding-top": "15px",
            } 
        }
     }
     return {};
     
}

function operateFormatter(value, row, index) {
    if (row.sigla == "ET")
        return [
            '<button type="button" class="btn btn-default verEgreso" aria-label="Right Align" data-toggle="tooltip" title="Ver">',
            '<span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>',
            '<button type="button" class="btn btn-default editarEgresoTraspaso" aria-label="Right Align" data-toggle="tooltip" title="Modificar">',
            '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>',
            '<button type="button" class="btn btn-default imprimirEgreso" aria-label="Right Align" data-toggle="tooltip" title="Imprimir">',
            '<span class="glyphicon glyphicon-print" aria-hidden="true"></span></button>'
        ].join('');
    else
        return [
            '<button type="button" class="btn btn-default verEgreso" aria-label="Right Align" data-toggle="tooltip" title="Ver">',
            '<span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>',
            '<button type="button" class="btn btn-default editarEgreso" aria-label="Right Align" data-toggle="tooltip" title="Modificar">',
            '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>',
            '<button type="button" class="btn btn-default imprimirEgreso" aria-label="Right Align" data-toggle="tooltip" title="Imprimir">',
            '<span class="glyphicon glyphicon-print" aria-hidden="true"></span></button>'
        ].join('');
}

function operateFormatter2(value, row, index) {
    $ret = ''
    if (row.sigla=='ET') {
        if (row.anulado == 1) {
            $ret = '<span class="label label-warning">Anulado</span>'
        } else {
            $ret = '<span class="label label-default">Traspaso</span>'
        }
        
    } else if(row.sigla=='EB'){
        $ret = '<span class="label label-default">BajaProducto</span>'
    } 
    else {
        if (row.anulado == 1) {
            $ret = '<span class="label label-warning">ANULADO</span>';
        } else {
            if (value == 0)
                $ret = '<span class="label label-danger">No facturado</span>';
            if (value == 1)
                $ret = '<span class="label label-success">T. Facturado</span>';
            if (value == 2)
                $ret = '<span class="label label-info">Facturado Parcial</span>';
        }
    }
    return ($ret);
}

function operateFormatter3(value, row, index) {
    num = Math.round(value * 100) / 100
    num = num.toFixed(2);
    return (formatNumber.new(num));
}
function tipoNota(value, row, index) {
    console.log(value);
    switch (value) {
        case '1':
            return ('Venta');
            break;
        case '2':
            return ('Prestamo');
            break;
        case '3':
            return ('Muestra');
            break;
        case '4':
            return ('Reserva');
            break;          
        default:
            return ("-");
            break;
    }
    
}

function totalEgreso (value, row, index) {
    console.log(row.cantidad);
    console.log(row.punitario);
    let cant = (Math.round(row.cantidad * 100) / 100).toFixed(2)
    let pu = (Math.round(row.punitario * 100) / 100).toFixed(2)
    num = cant * pu
    return (formatNumber.new(num.toFixed(2)));
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
    'click .verEgreso': function (e, value, row, index) {
        // fila=JSON.stringify(row);
        verdetalle(row)
    },
    'click .editarEgreso': function (e, value, row, index) {
        almForm = row.idAlmacen
        almUser = $('#idAlmacenUsuario').val()
        isAdmin = $('#isAdmin').val()
        if (almForm != almUser && isAdmin == '') {
            swal("Error", "No se puede Editar", "error")
            return false
        }
        let editar = base_url("Egresos/editarEgresos/") + row.idEgresos;
        if (row.estado == 1) {
            swal("Error", "El registro ya se encuentra Facturado.", "error")
        } else if (row.anulado == 1) {
            swal("Error", "El registro seleccionado esta anulado", "error")
        }
        else {
            window.location.href = editar;
        }
    },
    'click .editarEgresoTraspaso': function (e, value, row, index) {
        console.log(row);
        almForm = row.idAlmacen
        almUser = $('#idAlmacenUsuario').val()
        isAdmin = $('#isAdmin').val()
        if (almForm != almUser && isAdmin == '') {
            swal("Error", "No se puede Editar", "error")
            return false
        }
        var editar = base_url("Traspasos/modificarTraspaso/") + row.idEgresos;
        if (row.estado == 'TRASPASO') {
            window.location.href = editar;
        } else {
            swal("Error", "No se puede editar el registro esta ANULADO.", "error")
        }
    },
    'click .imprimirEgreso': function (e, value, row, index) {
        //alert(JSON.stringify(row));
        let imprimir = base_url("pdf/Egresos/index/") + row.idEgresos;
        window.open(imprimir);
    }
};

function calcularTotalDetalle(detalle) {
    var total = 0;
    $.each(detalle, function (index, value) {

        total += parseFloat(value.total)
        //console.log(total);
    })
    return total;
}

function verdetalle(fila) {
    tipomov = fila.tipomov
    id = fila.idEgresos
    console.log(fila);
    datos = {
        id: id,
        moneda: fila.moneda,
        tipocambio: fila.tipocambiovalor
    }
    console.log(datos);
    retornarajax(base_url("index.php/Egresos/mostrarDetalle"), datos, function (data) {
        estado = validarresultado_ajax(data);
        console.log(data);
        if (estado) {
            if (tipomov == 'Traspaso A Almacen') {
                mostrarDetalleTraspaso(data.respuesta.resultado);
            } else {
                mostrarDetalle(data.respuesta.resultado);
            }
            
            var totalnn = calcularTotalDetalle(data.respuesta.resultado)
            var tipocambioEgreso = data.respuesta.tipocambio;
            var totalsus = totalnn;
            var totalbs = totalnn;
            if (fila.moneda == 1) {
                totalsus = totalsus / tipocambioEgreso;
                //console.log(tipocambioEgreso)
            }
            if (fila.moneda == 2)
                totalbs = totalbs * tipocambioEgreso;
            $("#facturadonofacturado").html(operateFormatter2(fila.estado, fila))
            $("#almacen_egr").val(fila.almacen)
            $("#tipomov_egr").val(fila.tipomov)
            $("#fechamov_egr").val(formato_fecha_corta(fila.fechamov));
            $("#moneda_egr").val(fila.monedasigla)
            $("#tipoCambio").val(fila.tipocambiovalor)
            $("#nmov_egr").val(fila.n)
            $("#cliente_egr").val(fila.nombreCliente)
            $("#pedido_egr").val(fila.clientePedido)
            $("#fechaPago").val(formato_fecha_corta(fila.plazopago));
            $("#obs_egr").val(fila.obs);
            $("#numeromovimiento").html(fila.n);
            $("#nombreModal").html(fila.tipomov);
            /***pendienteaprobado***/
            var boton = "";
            var csFact = "Sin factura";
            if (fila.nfact != "SF")
                csFact = "Con factura";


            $("#pendienteaprobado").html(boton);
            $("#totalsusdetalle").val(totalsus);
            $("#totalbsdetalle").val(totalbs);
            $("#titulo_modalIgresoDetalle").html(" - " + fila.tipomov + " - " + csFact);
            $("#modalEgresoDetalle").modal("show");
            let arrayFactura = ""
            if (fila.factura === null) {
                return arrayFactura
            } else {
                arrayFactura = fila.factura.split("-");
            }
            
            var cadena = ""
            $.each(arrayFactura, function (index, val) {

                cadena += "<option>" + val + "</option>"
            })
            $("#facturasnum").html(cadena);

        }
    })
}

function mostrarDetalle(res) {
    console.log(res);
    $("#tegresosdetalle").bootstrapTable('destroy');
    $("#tegresosdetalle").bootstrapTable({

        data: res,
        striped: true,
        pagination: true,
        clickToSelect: true,
        search: false,
        showFooter: true,
        footerStyle: footerStyle,
        columns: [{
                field: 'CodigoArticulo',
                title: 'Código',
                align: 'center',
                width: '10%',
                sortable: true,
            },
            {
                field: 'Descripcion',
                title: 'Descripcion',
                width: '50%',
                sortable: true,
            },
            {
                field: 'Sigla',
                title: 'Uni.',
                align: 'center',
                sortable: true,
            },
            {
                field: 'cantidad',
                title: "Cantidad",
                align: 'right',
                width: '10%',
                formatter: operateFormatter3,
                sortable: true,
            },
            //PARA COMPARAR CON FACTURA           
            {
                field: 'punitario',
                title: "P/U Bs",
                align: 'right',
                width: '10%',
                formatter: operateFormatter3,
                sortable: true,
            },
            {
                field: 'total',
                title: "Total",
                align: 'right',
                width: '10%',
                formatter: totalEgreso,
                sortable: true,
                footerFormatter: sumaColumnaEgreso
            },
            {
                field: 'descuento',
                title: "% Dscnt",
                align: 'right',
                width: '5%',
                formatter: operateFormatter3,
                sortable: true,
            },
            
            {
                field: 'cantFact',
                title: "CantFact",
                align: 'right',
                width: '5%',
                sortable: true,
            },
        ]
    });
}
function mostrarDetalleTraspaso(res) {
    console.log(res);
    $("#tegresosdetalle").bootstrapTable('destroy');
    $("#tegresosdetalle").bootstrapTable({

        data: res,
        striped: true,
        pagination: true,
        clickToSelect: true,
        search: false,
        showFooter: true,
        footerStyle: footerStyle,
        columns: [{
                field: 'CodigoArticulo',
                title: 'Código',
                align: 'center',
                width: '10%',
                sortable: true,
            },
            {
                field: 'Descripcion',
                title: 'Descripcion',
                width: '50%',
                sortable: true,
            },
            {
                field: 'cantidad',
                title: "Cantidad",
                align: 'right',
                width: '10%',
                formatter: operateFormatter3,
                sortable: true,
            },
            //PARA COMPARAR CON FACTURA           
            {
                field: 'punitario',
                title: "P/U Bs",
                align: 'right',
                visible:false,
                width: '10%',
                formatter: operateFormatter3,
                sortable: true,
            },
            {
                field: 'total',
                title: "Total",
                visible:false,
                align: 'right',
                width: '10%',
                formatter: operateFormatter3,
                sortable: true,
                footerFormatter: sumaColumna
            },
            {
                field: 'descuento',
                title: "% Dscnt",
                visible:false,
                align: 'right',
                width: '5%',
                formatter: operateFormatter3,
                sortable: true,
            },
            
            {
                field: 'cantFact',
                title: "CantFact",
                visible:false,
                align: 'right',
                width: '5%',
                sortable: true,
            },
        ]
    });
}
function footerStyle(value, row, index) {
    return {
        css: {
            "font-weight": "bold",
            "border-top": "3px solid white",
            "border-bottom": "3px solid white",
            "text-align": "right",
            "padding": "15px",
            "background-color": "#3c8dbc",
            "color": "white"
        }
    };
}
function sumaColumnaEgreso(data) {
    let totalSum = data.reduce(function (sum, row) {
      return sum + ( + (row['punitario'] * row['cantidad']) );
    }, 0);
    return (formatNumber.new(totalSum.toFixed(2)));
}
function sumaColumna(data) {
    field = this.field;
    let totalSum = data.reduce(function (sum, row) {
      return sum + (+row[field]);
    }, 0);
    return (formatNumber.new(totalSum.toFixed(2)));
}

function punitariofac(value, row, index) {

    //console.log(row);
    var punit = row.cantidad == "" ? 0 : row.cantidad;
    punit = row.totaldoc / punit;
    punit = redondeo(punit, 3)

    return (formatNumber.new(punit));
    //return(num)
}

function restornardatosSelect(res) {

    //var proveedor = new Array()
    //var tipo = new Array()
    var autor = new Array()
    var cliente = new Array()
    var datos = new Array()
    var destino = new Array()
    let estado = new Array()

    $.each(res, function (index, value) {

        autor.push(value.autor)
        cliente.push(value.nombreCliente)
        destino.push(value.destino)
        estado.push(value.estadoF)
    })
    autor.sort()
    cliente.sort()
    estado.sort()
    datos.push(autor.unique())
    datos.push(cliente.unique())
    datos.push(destino.unique())
    datos.push(estado.unique())
    return (datos);
}
Array.prototype.unique = function (a) {
    return function () {
        return this.filter(a)
    }
}(function (a, b, c) {
    return c.indexOf(a, b + 1) < 0
});