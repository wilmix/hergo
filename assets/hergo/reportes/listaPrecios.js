$(document).ready(function(){
    retornarListaPrecios();
}) 

function retornarListaPrecios() //*******************************
{   
    agregarcargando();
    $.ajax({
        type:"POST",
        url: base_url('index.php/Reportes/mostrarListaPrecios'), //******controlador
        dataType: "json",
    }).done(function(res){
    	quitarcargando();
        $("#tablaListaPrecios").bootstrapTable('destroy');
        $("#tablaListaPrecios").bootstrapTable({            ////********cambiar nombre tabla viata

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
                        field: 'Dolares',            
                        title: 'Dólares',
                        align: 'right',
                        sortable:true,
                        formatter: operateFormatter3
                    },
                    {   
                        field: 'Bolivianos',            
                        title: 'Bolivianos',
                        align: 'right',
                        sortable:true,
                        formatter: operateFormatter3
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
