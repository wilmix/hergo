$(document).ready(function() {
    tituloReporte()
    retornarSaldos()
    $('#articulos_filtro').select2({
        /*maximumSelectionLength:2,
        multiple:true,
        placeholder:'Seleccione rango Articulos',*/
        allowClear: true,
    })});
$(document).on("click", "#saldos", function () {
    tituloReporte();
    retornarSaldos();
    
})
$(document).on("click", "#refresh", function () {
    tituloReporte();
    retornarSaldos();
})
$(document).on("change", "#articulos_filtro", function () {
    tituloReporte();
    retornarSaldos();
})
$(document).on("change", "#almacen_filtro", function () {
    tituloReporte();
    retornarSaldos();
})

function retornarSaldos() {
    let alm = $("#almacen_filtro").val()
    if (alm>0) {
        retornarSaldosAlmacen()
    } else {
        retornarSaldosGeneral()
    }
    
}

function retornarSaldosAlmacen() {
    let alm = $("#almacen_filtro").val()
    console.log(`ALM: ${alm}`);

    agregarcargando();
    $.ajax({
        type: "POST",
        url: base_url('index.php/Reportes/mostrarSaldosActualesItems'),
        dataType: "json",
        data: {
            alm: alm,
        },
    }).done(function (res) {
        quitarcargando(); 
        datosselect = restornardatosSelect(res)
        console.log(res);
        $("#tablaSaldos").bootstrapTable('destroy');    
        $("#tablaSaldos").bootstrapTable({ 
            data: res,
            striped: true,
            //pagination: true,
            //pageSize: "100",
            search: true,
            //showColumns: true,
            filter: true,
            stickyHeader: true,
            stickyHeaderOffsetY: '50px',
            rowStyle:rowStyle,
            footerStyle: footerStyle,
            columns: [
                {
                    field: 'linea',
                    title: 'Linea',
                    align: 'center',
                    visible: true,
                    filter: {
                        type: "select",
                        data: datosselect[0]
                    },
                },
                {
                    field: 'codigo',
                    title: 'Código',
                    align: 'center',
                },
                {
                    field: 'descripcion',
                    title: 'Descripcion',
                    searchable: false,
                    align: 'left'
                },
                {
                    field: 'unidad',
                    title: 'Unidad',
                    align: 'center'
                },
                {
                    field: 'almacen',
                    title: 'Almacen',
                    align: 'center',
                    width:'150px',
                },
                {
                    field: 'costo',
                    title: 'Costo.Uni.',
                    align: 'right',
                    width:'80px',
                    searchable: false,
                    formatter: operateFormatter3,
                    
                },
                {
                    field: 'saldo',
                    title: 'Saldo',
                    align: 'right',
                    width:'90px',
                    searchable: false,
                    formatter: operateFormatter3,
                },
                {
                    field: 'remision',
                    title: 'Remisión',
                    align: 'right',
                    width:'90px',
                    searchable: false,
                    formatter: operateFormatter3,
                },
                {
                    field: 'saldoAlm',
                    title: 'Saldo Alm.',
                    align: 'right',
                    width:'90px',
                    formatter: operateFormatter3,
                },
                {
                    field: 'vTotal',
                    title: 'Total',
                    align: 'right',
                    width:'90px',
                    searchable: false,
                    formatter: operateFormatter3,
                },
            ]
          });
    }).fail(function (jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
    });
}
function retornarSaldosGeneral() {
    agregarcargando();
    $.ajax({
        type: "POST",
        url: base_url('index.php/Reportes/mostrarSaldosActualesItems'),
        dataType: "json",
        data: {
            alm: '',
        },
    }).done(function (res) {
        console.log(res);
        datosselect = restornardatosSelect(res)
        quitarcargando(); 
        $("#tablaSaldos").bootstrapTable('destroy');    
        $("#tablaSaldos").bootstrapTable({ 
            data: res,
            striped: true,
            //pagination: true,
            //pageSize: "100",
            search: true,
            //showColumns: true,
            filter: true,
            stickyHeader: true,
            stickyHeaderOffsetY: '50px',
            rowStyle:rowStyle,
            footerStyle: footerStyle,
            columns: [
                {
                    field: 'linea',
                    title: 'Linea',
                    align: 'center',
                    visible: true,
                    filter: {
                        type: "select",
                        data: datosselect[0]
                    },
                },
                {
                    field: 'codigo',
                    title: 'Código',
                    align: 'center',
                },
                {
                    field: 'descripcion',
                    title: 'Descripcion',
                    searchable: false,
                    align: 'left'
                },
                {
                    field: 'unidad',
                    title: 'Unidad',
                    align: 'center'
                },
                {
                    field: 'almacen',
                    title: 'Almacen',
                    align: 'center',
                    width:'150px',
                },
                {
                    field: 'costo',
                    title: 'Costo.Uni.',
                    align: 'right',
                    width:'80px',
                    searchable: false,
                    formatter: operateFormatter3,
                    
                },
                {
                    field: 'saldo',
                    title: 'Saldo',
                    align: 'right',
                    width:'90px',
                    searchable: false,
                    formatter: operateFormatter3,
                },
                {
                    field: 'remision',
                    title: 'Remisión',
                    align: 'right',
                    width:'90px',
                    searchable: false,
                    formatter: operateFormatter3,
                },
                {
                    field: 'saldoAlm',
                    title: 'Saldo Alm.',
                    align: 'right',
                    width:'90px',
                    formatter: operateFormatter3,
                },
                {
                    field: 'vTotal',
                    title: 'Total',
                    align: 'right',
                    width:'90px',
                    searchable: false,
                    formatter: operateFormatter3,
                },
            ]
          });
    }).fail(function (jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
    });
}
function rowStyle(row, index) {
    if (row.codigo=='') {
        return {
            css: {
                //"font-weight": "bold",
                //"border-top": "3px solid white",
                //"border-bottom": "3px solid white",
                "text-align": "right",
                //"padding": "15px",
                "background-color": "#3c8dbc",
                "color": "white",
               // "font-size":"120%",
            }
        };
    }
    return {};
}
function operateFormatter3(value, row, index) {

        num = Math.round(value * 100) / 100
        num = num.toFixed(2);
        return (formatNumber.new(num));

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
    $('#nombreArticulo').text(nomArticulo);
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
function restornardatosSelect(res) {


    let linea = new Array()
    var datos = new Array()
    $.each(res, function (index, value) {
        linea.push(value.linea)
    })

    linea.sort();
    datos.push(linea.unique());
    return (datos);
}
Array.prototype.unique = function (a) {
    return function () {
        return this.filter(a)
    }
}(function (a, b, c) {
    return c.indexOf(a, b + 1) < 0
});
