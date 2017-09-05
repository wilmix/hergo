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
      retornarTablaFacturacion();
    });
})
$(document).on("change","#almacen_filtro",function(){
    retornarTablaFacturacion();
})
$(document).on("change","#tipo_filtro",function(){
    retornarTablaFacturacion();
})


function retornarTablaFacturacion()
{
    ini=iniciofecha.format('YYYY-MM-DD')
    fin=finfecha.format('YYYY-MM-DD')
    alm=$("#almacen_filtro").val()
    tipo=$("#tipo_filtro").val()
    console.log({ini:ini,fin:fin,alm:alm,tipo:tipo})
    $.ajax({
        type:"POST",
        url: base_url('index.php/facturas/MostrarTablaConsultaFacturacion'),
        dataType: "json",
        data: {ini:ini,fin:fin,alm:alm,tipo:tipo},
    }).done(function(res){
       //datosselect= restornardatosSelect(res)
        $("#facturasConsulta").bootstrapTable('destroy');
        $('#facturasConsulta').bootstrapTable({
                   
            data:res,
            striped:true,
            pagination:true,
            pageSize:"10",    
            search:true,        
            searchOnEnterKey:true,
            filter:true,
            showColumns:true,

            columns: [            
            {
                field: 'lote',                
                title: 'lote',                            
                visible:false,
            },
       
            {
                field:'nmovimiento',
                title:"Moviemiento",
                sortable:true,
                class:"col-sm-1",
                align: 'center',
                
            },
            {
                field:'fecha',
                title:"Fecha",
                class:"col-sm-1",
                sortable:true,
            },
            {
                field:'Ncliente',
                title:"N° Cliente",                
                class:"col-sm-1",                                
                sortable:true,
                visible:false
            },
            {
                field:'cliente',
                title:"Cliente",                
                class:"col-sm-4",         
                sortable:true,
            },
            {
                field:'total',
                title:"Total",                
                sortable:true,
                
            },
            {
                field:'estado',
                title:"Estado",
                width: '7%',
                sortable:true,                
            },           
            {
                title: 'Acciones',
                align: 'center',
                width: '10%',
              //  events: operateEvents,
              //  formatter: operateFormatter
            }]
            
        });
        
    

    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
    });
    

}
