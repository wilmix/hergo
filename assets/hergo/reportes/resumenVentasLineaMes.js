
var iniciofecha=moment().subtract(0, 'year').startOf('year')
var finfecha=moment().subtract(0, 'year').endOf('year')
$(document).ready(function(){
     $(".tiponumerico").inputmask({
        alias:"decimal",
        digits:2,
        groupSeparator: ',',
        autoGroup: true,
        autoUnmask:true
    });

    var start = moment().subtract(0, 'year').startOf('year')
    var end = moment().subtract(0, 'year').endOf('year')
    var actual=moment().subtract(0, 'year').startOf('year')
    var unanterior=moment().subtract(1, 'year').startOf('year')
    var dosanterior=moment().subtract(2, 'year').startOf('year')
    var tresanterior=moment().subtract(3, 'year').startOf('year')
 
    $(function() {
        moment.locale('es');
        function cb(start, end) {
            $('#fechapersonalizada span').html(start.format('D MMMM, YYYY') + ' - ' + end.format('D MMMM, YYYY'));
            iniciofecha=start
            finfecha=end
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
               "Hace un Año": [moment().subtract(1, 'year').startOf('year'),moment().subtract(1, 'year').endOf('year')],
               'Hace dos Años': [moment().subtract(2, 'year').startOf('year'),moment().subtract(2, 'year').endOf('year')],
               'Hace tres Años': [moment().subtract(3, 'year').startOf('year'),moment().subtract(3, 'year').endOf('year')],               
            }
        }, cb);

        cb(start, end);

    });
    $('#fechapersonalizada').on('apply.daterangepicker', function(ev, picker) {
      retornarVentasLineaMes();
    });
    retornarVentasLineaMes();
})
$(document).on("change","#almacen_filtro",function(){
    retornarVentasLineaMes();
}) //para cambio filtro segun cada uno



function retornarVentasLineaMes() //*******************************
{   
    ini=iniciofecha.format('YYYY-MM-DD')
    fin=finfecha.format('YYYY-MM-DD')
    alm=$("#almacen_filtro").val();
    agregarcargando();
    $.ajax({
        type:"POST",
        url: base_url('index.php/Reportes/mostrarVentasLineaMes'), //******controlador
        dataType: "json",
        data: {i:ini,f:fin,a:alm}, //**** variables para filtro
    }).done(function(res){
    	quitarcargando();
        console.log(res);
        //console.log(alm);
        //datosselect= restornardatosSelect(res);
        $("#tablaResumenVentasLineaMes").bootstrapTable('destroy');
        $("#tablaResumenVentasLineaMes").bootstrapTable({            ////********cambiar nombre tabla viata

                data:res,    
                    striped:true,
                    pagination:true,
                    pageSize:"100",
                    search:true,
                    searchOnEnterKey:true,
                    showColumns:true,
                    filter:true,
                    showExport:true,
                    exportTypes:['xlsx'],
                    exportDataType:'basic',
                    /*exportOptions:{
                                    bookType:"xlsx",
                                    type:'excel',
                                    fileName: 'Notas',
                                    worksheetName: "Notas"
                                  },*/
                    //groupBy:true,
                    //groupByField:'nombreCliente',
                    stickyHeader: true,
                    stickyHeaderOffsetY: '50px',
                columns:
                [
                   {   
                        field: 'Sigla',            
                        title: 'Sigla',
                        sortable:true,

                    },
                    {
                    	field: 'Linea',
                        title: 'Linea',
                        align: 'center',
                    },
                    {
                        field: 'total',
                        title: 'Total',
                        align: 'right',
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
