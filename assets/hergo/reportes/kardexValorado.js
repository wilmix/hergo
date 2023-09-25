$(document).ready(function() {

    tituloReporte()
    $('#export').click(function () {
        $('.table').tableExport({
        type:'excel',
        fileName: 'KardexValorado',
        numbers: {output : false}
        })
    });
    $('#articulos_filtro').select2({
        theme: "classic",
       // maximumSelectionLength: 2
    })
})
$(document).on("click", "#refresh", function () {
    tituloReporte();
    $('#tablas').empty();
    retornarKardex();
})
$(document).on("click", "#pdfGeneral", function () {
    let alm = $("#almacen_filtro").val()
    let imprimir = base_url("pdf/KardexAll/index/") + alm;
    window.open(imprimir);
})
$(document).on("click", "#pdfGeneralSN", function () {
    let alm = $("#almacen_filtro").val()
    let imprimir = base_url("pdf/KardexAllSN/index/") + alm;
    window.open(imprimir);
})
$(document).on("change", "#articulos_filtro", function () {
    tituloReporte();
})
$(document).on("change", "#almacen_filtro", function () {
    tituloReporte();
    $('#tablas').empty();
    retornarKardex();
})


function retornarKardex() {
    let alm = $("#almacen_filtro").val()
    let art = $("#articulos_filtro").val()
    if (!art) {
        swal("Atencion!", "Seleccióne al menos un artículo")
        return false
    }
    agregarcargando();
    $.ajax({
        type: "POST",
        url: base_url('index.php/Reportes/showKardexIndividual'),
        dataType: "json",
        data: {
            alm: alm,
            art: art,
        },
    }).done(function (res) {
        for (let i = 0; i < res.length; i++) {
            let codigo = res[i][0]
            let descrip = res[i][1]
            let unidad = res[i][2]
            let element = res[i][4]
            $("#tablas").append(`   
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">${codigo} - ${descrip} - ${unidad}<h3></h3>
                </div>
                <div class="box-body no-padding">
                    <table id="tablaKardex${i}" data-toggle="table"> 
                    </table>
                </div>
            </div>`);
            let nombreTabla = `#tablaKardex${i}`
            agregarTabla(element, nombreTabla)
    }
        quitarcargando(); 
    }).fail(function (jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
    });
}
function agregarTabla(res , nombre) {
    $(nombre).bootstrapTable('destroy');    
    $(nombre).bootstrapTable({
        data: res,
        striped: true,
        //pagination: true,
        //pageSize: "100",
        //search: true,
        showColumns: true,
        filter: true,
        stickyHeader: true,
        stickyHeaderOffsetY: '50px',
        showFooter: true,
        footerStyle: footerStyle,
        columns: [
            {
                field: 'almacen',
                title: 'Alm',
                align: 'center',
                visible: true
            },
            {
                field: 'fecha',
                title: 'Fecha Documento',
                align: 'center',
                formatter: formato_fecha

            },
            {
                field: 'fechaKardex',
                title: 'Fecha Kardex',
                align: 'center',
                visible: false,
                formatter: formato_fecha

            },
            {
                field: 'tipo',
                title: 'Tipo',
                align: 'center'
            },
            {
                field: 'numMov',
                title: 'N° Mov',
                align: 'center'
            },
            {
                field: 'nombreproveedor',
                title: 'Cliente | Proveedor',
                align: 'left',
                formatter: clienteNotaInfo
            },
            {
                field: 'p',
                title: 'Pedido | OC',
                align: 'left',
                visible:false,
                
            },
            
            {
                field: 'punitario',
                title: 'P/U',
                align: 'right',
                formatter: operateFormatter3,
            },
            
            /* {
                field: 'cantidad',
                title: 'Cantidad',
                align: 'right',
                visible: false,
                formatter: operateFormatter3,
                //footerFormatter: sumaColumna
            }, */
            {
                field: 'cantidad',
                title: 'Ingresos',
                align: 'right',
                formatter: ingresos,
                footerFormatter: sumaIngresos
            },
            {
                field: 'cantidad',
                title: 'Factura',
                align: 'right',
                formatter: factura,
                footerFormatter: sumaFactura

            },
            {
                field: 'cantidad',
                title: 'N.E.',
                align: 'right',
                formatter: notaEntrega,
               footerFormatter: sumaNE
            },
            {
                field: 'cantidad',
                title: 'Traspaso',
                align: 'right',
                formatter: traspaso,
                footerFormatter: sumaOtros
            },
            {
                field: '_cantidad',
                title: 'Saldo',
                align: 'right',
                //visible: false,
                formatter: operateFormatter3,
                //footerFormatter: saldo
            },
            {
                field: '_cpp',
                title: 'CPP',
                align: 'right',
                formatter: operateFormatter3,
                //visible:false,
                //footerFormatter: cpp
            },
            {
                field: '_total',
                title: 'Total',
                align: 'right',
                formatter: operateFormatter3,
                //footerFormatter: total
            },
            
            
        ]
      });
}
function operateFormatter3(value, row, index) {
    num = Math.round(value * 100) / 100
    num = num.toFixed(2);
    return (formatNumber.new(num));
}
function clienteNotaInfo(value, row, index) {
    if (row.notaInfo == '') {
        return (`${row.nombreproveedor}`);
    }
    return (`${row.nombreproveedor} (${row.notaInfo})`);
}
function costoPromedio4(value, row, index) {
    num = Number(value)
    num = num.toFixed(4);
    return (formatNumber.new(num));
}
function tituloReporte() {
    almText = $('#almacen_filtro').find(":selected").text();
    nomArticulo = $('#articulos_filtro').find(':selected').text();
    $('#tituloReporte').text(almText);
    //$('#nombreArticulo').text(nomArticulo);
}
function ingresos(value, row, index) {
    $ret = ''
    let suma =[]
    if (row.tipo=='II'||row.tipo=='IT'||row.tipo=='CL'||row.tipo=='ID'||row.tipo=='IMP'||row.tipo=='RI') {
        $ret = row.cantidad
        $ret = Math.round( $ret * 100) / 100
        $ret = $ret.toFixed(2);
    } 
   // return ($ret);
    return (formatNumber.new($ret));
}

function factura(value, row, index) {
    $ret = ''
    if (row.tipo=='FAC') {
        $ret = Number(row.cantidad)
        $ret = Math.round( $ret * 100) / 100
        $ret = $ret.toFixed(2);
    } 
    return (formatNumber.new($ret));
}
function notaEntrega(value, row, index) {
    $ret = ''
    if (row.tipo=='NE') {
        $ret = Number(row.cantidad)
        $ret = Math.round( $ret * 100) / 100
        $ret = $ret.toFixed(2);
    } 
    return (formatNumber.new($ret));
}
function traspaso(value, row, index) {
    $ret = ''
    if (row.tipo=='VC'||row.tipo=='ET'||row.tipo=='EB') {
        $ret = Number(row.cantidad)
        $ret = Math.round( $ret * 100) / 100
        $ret = $ret.toFixed(2);
    } 
    return (formatNumber.new($ret));
}
function footerStyle(value, row, index) {
    return {
        css: {
            "font-weight": "normal",
            "border-top": "3px solid white",
            "border-bottom": "3px solid white",
            "text-align": "right",
            "padding": "15px",
            "background-color": "#3c8dbc",
            "color": "white"
        }
    };
}
function sumaFactura(data) {
    let facturas = data.filter(dato=>dato.tipo=='FAC')
    field = this.field;
    let totalSum = facturas.reduce(function (sum, row) {
        return sum + (+row[field]);
    }, 0);
    return (formatNumber.new(totalSum.toFixed(2)));
}

function sumaNE(data) {
    let notasEnt = data.filter(dato=>dato.tipo=='NE')
    field = this.field;
    let totalSum = notasEnt.reduce(function (sum, row) {
        return sum + (+row[field]);
    }, 0);
    return (formatNumber.new(totalSum.toFixed(2)));
}

function sumaIngresos(data) {
    let ingresos = data.filter(dato=>dato.tipo=='II'||dato.tipo=='RI'||dato.tipo=='IT'||dato.tipo=='CL'||dato.tipo=='ID'||dato.tipo=='IMP')
    field = this.field;
    let totalSum = ingresos.reduce(function (sum, row) {
        return sum + (+row[field]);
    }, 0);
    return (formatNumber.new(totalSum.toFixed(2)));
}
function sumaOtros(data) {
    let otros = data.filter(dato=>dato.tipo=='ET'||dato.tipo=='EB')
    field = this.field;
    let totalSum = otros.reduce(function (sum, row) {
        return sum + (+row[field]);
    }, 0);
    return (formatNumber.new(totalSum.toFixed(2)));
}

