$(document).ready(function(){
    retornarSaldosActuales();
    base_url('index.php/Reportes/pruebaExcel')
}) 
$(document).on("click", "#excel", function () {
    console.log('object');
    let excel = base_url("reportes/saldosExcel");
    location.href = (excel);
})

function retornarSaldosActuales() //*******************************
{   
    agregarcargando();
    $.ajax({
        type:"POST",
        url: base_url('index.php/Reportes/mostrarSaldos'), //******controlador
        dataType: "json",
    }).done(function(res){
    	quitarcargando();
        $("#tablaSaldosActuales").bootstrapTable('destroy');
        $("#tablaSaldosActuales").bootstrapTable({            ////********cambiar nombre tabla viata

                data:res,    
                    striped:true,
                    search:true,
                    filter:true,
                    //strictSearch: true,
                    stickyHeader: true,
                    stickyHeaderOffsetY: '50px',
                columns:
                [
                    {   
                        field: 'idArticulos',            
                        title: 'ID',
                        sortable:true,
                        align: 'center',
                        visible:false,
                    },
                   {   
                        field: 'CodigoArticulo',            
                        title: 'Codigo',
                        sortable:true,
                        align: 'center',
                    },
                    {   
                        field: 'Descripcion',            
                        title: 'Descripción',
                        sortable:true,
                        searchable: true,
                    },
                    {   
                        field: 'Sigla',            
                        title: 'Unidad',
                        sortable:true,
                        searchable: false,
                        align: 'center'
                    },
                    {   
                        field: 'laPaz',            
                        title: 'La Paz',
                        sortable:true,
                        align: 'right',
                        searchable: false,
                        formatter: operateFormatter3
                    },
                    {   
                        field: 'elAlto',            
                        title: 'El Alto',
                        align: 'right',
                        sortable:true,
                        searchable: false,
                        formatter: operateFormatter3
                    },
                    {   
                        field: 'potosi',            
                        title: 'Potosí',
                        align: 'right',
                        sortable:true,
                        searchable: false,
                        formatter: operateFormatter3
                    },
                    {   
                        field: 'santacruz',            
                        title: 'Santa Cruz',
                        align: 'right',
                        sortable:true,
                        searchable: false,
                        formatter: operateFormatter3
                    },
                    {   
                        field: '',            
                        title: 'Total',
                        sortable:true,
                        formatter: total,
                        searchable: false,
                        align: 'right',
                    }

                ]
            });
    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
    });
 }
function operateFormatter3(value, row, index) {
    if (value==='-') {
        return '-'
    } else {
    num=Math.round(value * 100) / 100
    num=num.toFixed(2);
    return (formatNumber.new(num));
    }
    
}
function total(value, row, index) {
    ea = row.elAlto == '-' ? 0 : parseFloat(row.elAlto) 
    lp = row.laPaz == '-' ? 0 : parseFloat(row.laPaz)
    sc = row.santacruz == '-' ? 0 : parseFloat(row.santacruz)
    pts = row.potosi == '-' ? 0 : parseFloat(row.potosi)
    $ret = ea+lp+sc+pts
    $ret = $ret.toFixed(2)
    return ($ret)
  }