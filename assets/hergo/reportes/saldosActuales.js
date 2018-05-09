$(document).ready(function(){
    retornarSaldosActuales();
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
                    //pagination:true,
                    //pageSize:"100",
                    //search:true,
                    //searchOnEnterKey:true,
                    //showColumns:true,
                    filter:true,
                    //showExport:true,
                    stickyHeader: true,
                    stickyHeaderOffsetY: '50px',
                columns:
                [
                    {   
                        field: 'idArticulos',            
                        title: 'ID',
                        sortable:true,
                        align: 'center',
                        filter: { type: "input" }
                    },
                   {   
                        field: 'CodigoArticulo',            
                        title: 'Codigo',
                        sortable:true,
                        align: 'center',
                        filter: { type: "input" }
                    },
                    {   
                        field: 'Descripcion',            
                        title: 'Descripción',
                        sortable:true,
                        filter: { type: "input" }
                    },
                    {   
                        field: 'Sigla',            
                        title: 'Unidad',
                        sortable:true,
                        align: 'center'
                    },
                    {   
                        field: 'laPaz',            
                        title: 'La Paz',
                        sortable:true,
                        align: 'right',
                        formatter: operateFormatter3
                    },
                    {   
                        field: 'elAlto',            
                        title: 'El Alto',
                        align: 'right',
                        sortable:true,
                        formatter: operateFormatter3
                    },
                    {   
                        field: 'potosi',            
                        title: 'Potosí',
                        align: 'right',
                        sortable:true,
                        formatter: operateFormatter3
                    },
                    {   
                        field: 'santacruz',            
                        title: 'Santa Cruz',
                        align: 'right',
                        sortable:true,
                        formatter: operateFormatter3
                    },
                    {   
                        field: '',            
                        title: 'Total',
                        sortable:true,
                        formatter: tipoFactura,
                        align: 'right',
                        //formatter:operateFormatter3
                    }

                ]
            });
    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
    });
 }
function operateFormatter3(value, row, index) {     
    num=Math.round(value * 100) / 100
    num=num.toFixed(2);
    return (formatNumber.new(num));
}
function tipoFactura(value, row, index) {
    
    $ret =  parseFloat(row.laPaz) + 
            parseFloat(row.elAlto) +
            parseFloat(row.potosi) +
            parseFloat(row.santacruz);
    $ret = $ret.toFixed(2);
    return ($ret);
  }