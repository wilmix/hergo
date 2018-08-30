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
    //let art = $("#articulos_filtro").val()
    let linea = ($("#linea_filtro").val()=='')?'':$("#linea_filtro").find(":selected").text();
    console.log(`ALM: ${alm} LINEA: ${linea}`);

    agregarcargando();
    $.ajax({
        type: "POST",
        url: base_url('index.php/Reportes/mostrarSaldosActualesItems'),
        dataType: "json",
        data: {
            alm: alm,
            //art: art,
            linea:linea,
        },
    }).done(function (res) {
        quitarcargando(); 

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
                    visible: true
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
                    footerFormatter: sumaColumna
                },
                {
                    field: 'remision',
                    title: 'Remisión',
                    align: 'right',
                    width:'90px',
                    searchable: false,
                    formatter: operateFormatter3,
                    footerFormatter: sumaColumna
                },
                {
                    field: 'saldoAlm',
                    title: 'Saldo Alm.',
                    align: 'right',
                    width:'90px',
                    formatter: operateFormatter3,
                    footerFormatter: sumaColumna
                },
                {
                    field: 'vTotal',
                    title: 'Total',
                    align: 'right',
                    width:'90px',
                    searchable: false,
                    formatter: operateFormatter3,
                    footerFormatter: sumaColumna
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

function sumaColumna(data) {
    field = this.field;
    let totalSum = data.reduce(function (sum, row) {
        return sum + (+row[field]);
    }, 0);
    return (formatNumber.new(totalSum.toFixed(2)));
}

function todosAlmacenes(res) {
    $("#tablaSaldos").bootstrapTable({ 
        data: res,
        //striped: true,
        //pagination: true,
        //pageSize: "100",
        search: true,
        //showColumns: true,
        filter: true,
        stickyHeader: true,
        stickyHeaderOffsetY: '50px',
        //showFooter: true,
        footerStyle: footerStyle,
        columns: [
            {
                field: 'linea',
                title: 'Linea',
                align: 'center',
                visible: true
            },
            {
                field: 'codigo',
                title: 'Código',
                align: 'center',
            },
            {
                field: 'descripcion',
                title: 'Descripcion',
                align: 'left'
            },
            {
                field: '',
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
                formatter: operateFormatter3,
                
            },
            {
                field: 'saldo',
                title: 'Saldo',
                align: 'right',
                width:'90px',
                formatter: operateFormatter3,
                footerFormatter: sumaColumna
            },
            {
                field: 'remision',
                title: 'Remisión',
                align: 'right',
                width:'90px',
                formatter: operateFormatter3,
                footerFormatter: sumaColumna
            },
            {
                field: 'saldoAlm',
                title: 'Saldo Alm.',
                align: 'right',
                width:'90px',
                formatter: operateFormatter3,
                footerFormatter: sumaColumna
            },
            {
                field: 'vTotal',
                title: 'Total',
                align: 'right',
                width:'90px',
                formatter: operateFormatter3,
                footerFormatter: sumaColumna
            },
        ]
      });
} 
function almacen(res) {
    console.log('object');
}