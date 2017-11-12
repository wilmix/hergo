
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
      tablaListaPrecios();
    });
    tablaListaPrecios();
})
$(document).on("change","#almacen_filtro",function(){
    tablaListaPrecios();
}) //para cambio filtro segun cada uno

function tablaListaPrecios() //*******************************
{   
    ini=iniciofecha.format('YYYY-MM-DD')
    fin=finfecha.format('YYYY-MM-DD')
    alm=$("#almacen_filtro").val();
    $.ajax({
        type:"POST",
        url: base_url('index.php/Reportes/mostrarListaPrecios'), //******controlador
        dataType: "json",
        data: {i:ini,f:fin,a:alm}, //**** variables para filtro
    }).done(function(res){
        console.log(res);
        console.log(alm);
        //datosselect= restornardatosSelect(res)
        $("#tablaListaPrecios").bootstrapTable('destroy');
        $("#tablaListaPrecios").bootstrapTable({            ////********cambiar nombre tabla viata
                data:res,           
                    striped:true,
                    pagination:true,
                    pageSize:"100",
                    search:true,
                    searchOnEnterKey:true,
                    showColumns:true,
                    filter:true,
                columns:
                [
                    {   
                        field: '',            
                        title: 'Código',
                        visible:false,
                        sortable:true
                    },
                     {   
                        field: '',            
                        title: 'Descripción',
                        visible:false,
                        sortable:true
                    },
                     {   
                        field: '',            
                        title: 'Unidad',
                        visible:false,
                        sortable:true
                    },
                    {   
                        field: '',            
                        title: 'Codigo',
                        visible:false,
                        sortable:true
                    },
                     {   
                        field: '',            
                        title: 'C/U',
                        visible:false,
                        sortable:true
                    },
                    {   
                        field: '',            
                        title: 'Saldo LP',
                        visible:false,
                        sortable:true
                    },
                    {   
                        field: '',            
                        title: 'Saldo ALTO',
                        visible:false,
                        sortable:true
                    },
                    {   
                        field: '',            
                        title: 'Saldo PTS',
                        visible:false,
                        sortable:true
                    },
                     {   
                        field: '',            
                        title: '',
                        visible:false,
                        sortable:true
                    },
                     {   
                        field: '',            
                        title: 'Saldo CBBA',
                        visible:false,
                        sortable:true
                    },
                    {   
                        field: '',            
                        title: 'Saldo Total',
                        visible:false,
                        sortable:true
                    }]
            });
    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
    });
}

