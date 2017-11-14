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
                    search:true,
                    //searchOnEnterKey:true,
                    showColumns:true,
                    filter:true,
                    //showExport:true,
                    stickyHeader: true,
                    stickyHeaderOffsetY: '50px',
                columns:
                [
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
                        field: '',            
                        title: 'La Paz',
                        sortable:true
                    },
                    {   
                        field: '',            
                        title: 'El Alto',
                        sortable:true
                    },
                    {   
                        field: '',            
                        title: 'Potosí',
                        sortable:true
                    },
                    {   
                        field: '',            
                        title: 'Santa Cruz',
                        sortable:true
                    },
                    {   
                        field: '',            
                        title: 'Cochabamba',
                        sortable:true
                    },
                    {   
                        field: '',            
                        title: 'Total',
                        sortable:true
                    }

                ]
            });
    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
    });
 }
function operateFormatter3(value, row, index)
{       
    num=Math.round(value * 100) / 100
    num=num.toFixed(2);
    return (formatNumber.new(num));
}
