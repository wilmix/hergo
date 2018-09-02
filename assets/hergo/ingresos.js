var iniciofecha = moment().subtract(0, 'year').startOf('year')
var finfecha = moment().subtract(0, 'year').endOf('year')


$(document).ready(function () {
    $(".tiponumerico").inputmask({
        alias: "decimal",
        digits: 2,
        groupSeparator: ',',
        autoGroup: true,
        autoUnmask: true
    });

    var start = moment().subtract(0, 'year').startOf('year')
    var end = moment().subtract(0, 'year').endOf('year')
    var actual = moment().subtract(0, 'year').startOf('year')
    var unanterior = moment().subtract(1, 'year').startOf('year')
    var dosanterior = moment().subtract(2, 'year').startOf('year')
    var tresanterior = moment().subtract(3, 'year').startOf('year')

    /* var ractual="Gestion "+actual.format('YYYY')
    var runo="Gestion "+unanterior.format('YYYY')
    var rdos="Gestion "+dosanterior.format('YYYY')
    var rtres="Gestion"+tresanterior.format('YYYY')
   
    var rango={};
    rango[ractual]=[actual,actual];
    rango[runo]=[unanterior,unanterior];
    rango[rdos]=[dosanterior,dosanterior];
    rango[rtres]=[tresanterior,tresanterior];

    jsonrango=JSON.stringify(rango)
    console.log(jsonrango)
*/

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
            //ranges:jsonrango
            ranges: {
                'Gestion Actual': [moment().subtract(0, 'year').startOf('year'), moment().subtract(0, 'year').endOf('year')],
                "Hace un Año": [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
                'Hace dos Años': [moment().subtract(2, 'year').startOf('year'), moment().subtract(2, 'year').endOf('year')],
                'Hace tres Años': [moment().subtract(3, 'year').startOf('year'), moment().subtract(3, 'year').endOf('year')],
                /*'Hoy': [moment(), moment()],
                'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],               
                'Este Mes': [moment().startOf('month'), moment().endOf('month')],*/

            }
        }, cb);

        cb(start, end);

    });
    $('#fechapersonalizada').on('apply.daterangepicker', function (ev, picker) {
        retornarTablaIngresos();
    });
    retornarTablaIngresos();
})
$(document).on("change", "#almacen_filtro", function () {
    retornarTablaIngresos();
})
$(document).on("change", "#tipo_filtro", function () {
    retornarTablaIngresos();

})
$(document).on("click", "#refresh", function () {
    retornarTablaIngresos();
})

function mostrarTablaIngresosTraspaso(res) {
    $("#tingresos").bootstrapTable({

        data: res,
        striped: true,
        pagination: true,
        pageSize: "100",
        search: true,
        filter: true,
        showColumns: true,
        stickyHeader: true,
        stickyHeaderOffsetY: '50px',
        strictSearch: true,



        columns: [{
                field: 'n',
                title: 'N',
                align: 'center',
                sortable: true,
                searchable: true,
                width:'20px',
            },
            {
                field: 'sigla',
                title: 'Tipo',
                align: 'center',
                visible: false,
                searchable: true,
                sortable: true,

                filter: {
                    type: "select",
                    data: datosselect[1]
                }
            },
            {
                field: 'fechamov',
                title: "Fecha",
                align: 'right',
                sortable: true,
                align: 'center',
                searchable: false,
                formatter: formato_fecha_corta,
            },
            {
                field: 'origen', //para traspasos
                title: "Origen",
                filter: {
                    type: "select",
                    data: datosselect[3]
                },
                sortable: true,
                searchable: true,
            },
            {
                field: 'monedasigla',
                title: "Moneda",
                align: 'right',
                width:'20px',
                sortable: true,
                searchable: false,

            },
            {
                field: 'totalsus',
                title: "Total Sus",
                align: 'right',
                width:'100px',
                sortable: true,
                formatter: operateFormatter3,
                searchable: false,
            },
            {
                field: 'total',
                title: "Total Bs",
                width:'100px',
                align: 'right',
                sortable: true,
                searchable: false,
                formatter: operateFormatter3,

            },
            {
                field: "autor",
                title: "Autor",
                sortable: true,
                filter: {
                    type: "select",
                    data: datosselect[2]
                },
                visible: true,
                align: 'center',
                searchable: true,

            },
            {
                field: "fecha",
                title: "Fecha",
                sortable: true,
                formatter: formato_fecha_corta,
                visible: false,
                align: 'center',
                searchable: false,

            },
            {
                field: "estado",
                title: "Estado",
                sortable: true,
                formatter: operateFormatter2,
                align: 'center',
                width:'100px',
                searchable: false,

            },
            {
                title: 'Acciones',
                align: 'center',
                width:'100px',
                events: operateEvents,
                searchable: false,
                formatter: operateFormatter
            }
        ]
    });
}

function mostrarTablaIngresos(res) {
    $("#tingresos").bootstrapTable({

        data: res,
        striped: true,
        pagination: true,
        pageSize: "100",
        search: true,
        //searchOnEnterKey: true,
        filter: true,
        showColumns: true,
        stickyHeader: true,
        stickyHeaderOffsetY: '50px',
        strictSearch: true,

        columns: [{
                field: 'n',
                title: 'N',
                align: 'center',
                sortable: true
            },
            {
                field: 'sigla',
                title: 'Tipo',
                align: 'center',
                visible: false,
                sortable: true,
                searchable: true,
                filter: {
                    type: "select",
                    data: datosselect[1]
                }
            },
            {
                field: 'fechamov',
                title: "Fecha",
                align: 'right',
                sortable: true,
                align: 'center',
                searchable: false,
                formatter: formato_fecha_corta,
            },
            {
                field: 'nombreproveedor',
                title: "Proveedor",
                filter: {
                    type: "select",
                    data: datosselect[0]
                },
                sortable: true,

            },
            {
                field: 'nfact',
                title: "Factura",
                align: 'center',
                sortable: true,
                width:'80px',
                searchable:false,
            },
            {
                field: 'monedasigla',
                title: "Moneda",
                align: 'center',
                searchable:false,
                width:'20px'
     
            },
            {
                field: 'totalsus',
                title: "Total Sus",
                width:'100px',
                align: 'right',
                sortable: true,
                searchable:false,
                formatter: operateFormatter3,

            },
            {
                field: 'total',
                title: "Total Bs",
                align: 'right',
                sortable: true,
                searchable:false,
                width:'100px',
                formatter: operateFormatter3,
                filter: {
                    type: "input"
                },

            },
            {
                field: "autor",
                title: "Autor",
                searchable:true,
                filter: {
                    type: "select",
                    data: datosselect[2]
                },
                visible: true,
                align: 'center',

            },
            {
                field: "fecha",
                title: "Fecha",
                searchable:false,
                sortable: true,
                //formatter: formato_fecha_corta,
                visible: false,
                align: 'center',

            },
            {
                field: "estado",
                title: "Estado",
                sortable: true,
                width:'100px',
                searchable:false,
                formatter: operateFormatter2,
                align: 'center',

            },
            {
                title: 'Acciones',
                align: 'center',
                searchable:false,
                width: '150px',
                events: operateEvents,
                formatter: operateFormatter
            }
        ]
    });
}

function retornarTablaIngresos() {
    ini = iniciofecha.format('YYYY-MM-DD')
    fin = finfecha.format('YYYY-MM-DD')
    alm = $("#almacen_filtro").val()
    tipoingreso = $("#tipo_filtro").val()
    //console.log(tipoingreso)
    agregarcargando();
    $.ajax({
        type: "POST",
        url: base_url('index.php/Ingresos/mostrarIngresos'),
        dataType: "json",
        data: {
            i: ini,
            f: fin,
            a: alm,
            ti: tipoingreso
        },
    }).done(function (res) {
        quitarcargando();
        datosselect = restornardatosSelect(res)

        $("#tingresos").bootstrapTable('destroy');
        if (tipoingreso == 3)
            mostrarTablaIngresosTraspaso(res);
        else
            mostrarTablaIngresos(res);

        $("#tarticulo").bootstrapTable('hideLoading');
        $("#tarticulo").bootstrapTable('resetView');
        mensajeregistrostabla(res, "#tarticulo");
    }).fail(function (jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
    });
    //$("body").css("padding-right","0px");

}

function operateFormatter(value, row, index) {
    if (row.sigla == "IT")
        return [
            '<button type="button" class="btn btn-default verIngreso" aria-label="Right Align">',
            '<span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>',
            '<button type="button" class="btn btn-default imprimirIngreso" aria-label="Right Align">',
            '<span class="glyphicon glyphicon-print" aria-hidden="true"></span></button>'
        ].join('');
    else
        return [
            '<button type="button" class="btn btn-default verIngreso" aria-label="Right Align">',
            '<span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>',
            '<button type="button" class="btn btn-default editarIngreso" aria-label="Right Align">',
            '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>',
            '<button type="button" class="btn btn-default imprimirIngreso" aria-label="Right Align">',
            '<span class="glyphicon glyphicon-print" aria-hidden="true"></span></button>'
        ].join('');
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
/***********Eventos*************/
window.operateEvents = {
    'click .verIngreso': function (e, value, row, index) {
        // fila=JSON.stringify(row);
        verdetalle(row)
    },
    'click .editarIngreso': function (e, value, row, index) {
        //console.log(row.idIngresos);

        var editar = base_url("Ingresos/editarimportaciones/") + row.idIngresos;

        window.location.href = editar;
    },
    'click .imprimirIngreso': function (e, value, row, index) {
        //alert(JSON.stringify(row));
        let imprimir = base_url("pdf/Ingresos/index/") + row.idIngresos;
        window.open(imprimir);
    }
};

function verdetalle(fila) {
    console.log(fila)
    id = fila.idIngresos

    datos = {
        id: id,
        mon: fila.moneda
    }
    retornarajax(base_url("index.php/Ingresos/mostrarDetalle"), datos, function (data) {
        estado = validarresultado_ajax(data);
        if (estado) {
            var totaldoc = 0;
            var totalsis = 0;
            mostrarDetalle(data.respuesta);
            $.each(data.respuesta, function (index, value) {
                totaldoc += value.totaldoc
                totalsis += value.total
            })
            var totalnn = fila.total

            if (fila.moneda == 2) {

                $("#nombretotaldoc").html("$us Doc")
                $("#nombretotalsis").html("$us Sis")
            } else {
                $("#nombretotaldoc").html("Bs Doc")
                $("#nombretotalsis").html("Bs Sis")
            }
            $("#almacen_imp").val(fila.almacen)
            $("#tipomov_imp").val(fila.tipomov)
            $("#fechamov_imp").val(fila.fechamov)
            $("#moneda_imp").val(fila.monedasigla)
            $("#nmov_imp").val(fila.n)
            $("#proveedor_imp").val(fila.nombreproveedor)
            $("#ordcomp_imp").val(fila.ordcomp)
            $("#nfact_imp").val(fila.nfact)
            $("#ningalm_imp").val(fila.ningalm)
            $("#obs_imp").val(fila.obs)
            $("#nmovingre").html(fila.n)
            console.log(fila.tipomov);
            console.log(fila.estado);

            /***pendienteaprobado***/
            var boton = "";

            if (fila.estado == "0"){
                boton = '<button type="button" class="btn btn-success" datastd="' + fila.idIngresos + '" id="btnaprobado">Aprobado</button>';
            } else {
                boton = '<button type="button" class="btn btn-danger" datastd="' + fila.idIngresos + '" id="btnpendiente">Pendiente</button>';
            }    
            let csFact = "";
            if (fila.nfact == "SF") {
                csFact = "Sin factura"
            } else if (fila.tipomov == "Traspaso de Almacen") {
                if (fila.estado==1) {
                    csFact = "Aprobado"
                } else {
                    csFact = "Pendiente"
                }
            } else {
                csFact = "Con factura"
            }


            totaldoc = totaldoc * 100 / 100;
            totaldoc = totaldoc.toFixed(2);
            totalsis = totalsis * 100 / 100;
            totalsis = totalsis.toFixed(2);
            $("#pendienteaprobado").html(boton);
            $("#totaldocdetalle").val(totaldoc);
            $("#totalsisdetalle").val(totalsis);
            $("#titulo_modalIgresoDetalle").html(fila.tipomov);
            $("#tituloDetalleFac").html(csFact);
            $("#modalIgresoDetalle").modal("show");
        }
    })
}
$(document).on("click", "#btnaprobado", function () {
    id = $(this).attr("datastd");
    datos = {
        d: 1,
        id: id
    }
    retornarajax(base_url("index.php/Ingresos/revisarStd"), datos, function (data) {
        console.log(data);
        if(data.estado=="ok")
            {
                retornarTablaIngresos()
                $("#modalIgresoDetalle").modal("hide");
            }
            else
            {
              quitarcargando();
              swal("Atencion!", "Usted no tiene permiso de aprobar ingresos")
              console.log(data.respuesta);
            }

    })
})
$(document).on("click", "#btnpendiente", function () {
    id = $(this).attr("datastd");
    datos = {
        d: 0,
        id: id
    }
    retornarajax(base_url("index.php/Ingresos/revisarStd"), datos, function (data) {
        estado = validarresultado_ajax(data);
        if (estado) {
            retornarTablaIngresos()
            $("#modalIgresoDetalle").modal("hide");
        }
    })
})

function mostrarDetalle(res) {
    $("#tingresosdetalle").bootstrapTable('destroy');
    $("#tingresosdetalle").bootstrapTable({
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
                width: '40%',
                sortable: true,
            },
            {
                field: 'cantidad',
                title: "Cantidad",
                align: 'right',
                width: '10%',
                sortable: true,
            },


            //PARA COMPARAR CON FACTURA
            {
                field: '',
                title: "P/U Documento",
                align: 'right',
                width: '10%',
                sortable: true,
                formatter: punitariofac,
                //formatter: operateFormatter3,
            },
            {
                field: 'totaldoc',
                title: "Total Documento",
                align: 'right',
                width: '10%',
                sortable: true,
                formatter: operateFormatter3,
                footerFormatter: sumaColumna
            },




            {
                field: 'punitario',
                title: "C/U Sistema",
                align: 'right',
                width: '10%',
                sortable: true,
                formatter: operateFormatter3,
            },
            {
                field: 'total',
                title: "Total",
                align: 'right',
                width: '10%',
                sortable: true,
                formatter: operateFormatter3,
                footerFormatter: sumaColumna
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

function sumaColumna(data) {
    field = this.field;
    let totalSum = data.reduce(function (sum, row) {
        return sum + (+row[field]);
    }, 0);
    return (formatNumber.new(totalSum.toFixed(2)));
}

function punitariofac(value, row, index) {

    console.log(row);
    var punit = row.cantidad == "" ? 0 : row.cantidad;
    punit = row.totaldoc / punit;
    punit = redondeo(punit, 3);
    punit = punit.toFixed(2);

    return (formatNumber.new(punit));
    //return(num)
}

function restornardatosSelect(res) {
    var proveedor = new Array()
    var tipo = new Array()
    var autor = new Array()
    var origen = new Array()
    var datos = new Array()
    $.each(res, function (index, value) {

        proveedor.push(value.nombreproveedor)
        tipo.push(value.sigla)
        autor.push(value.autor)
        origen.push(value.origen)
    })
    proveedor.sort();
    tipo.sort();
    autor.sort();
    datos.push(proveedor.unique());
    datos.push(tipo.unique());
    datos.push(autor.unique());
    datos.push(origen.unique());
    return (datos);
}
Array.prototype.unique = function (a) {
    return function () {
        return this.filter(a)
    }
}(function (a, b, c) {
    return c.indexOf(a, b + 1) < 0
});