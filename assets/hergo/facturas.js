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
      retornarTablaEgresos();
    });
    retornarTablaEgresos();
})
$(document).on("change","#almacen_filtro",function(){
    retornarTablaEgresos();
})
$(document).on("change","#tipo_filtro",function(){
    retornarTablaEgresos();
})


function retornarTablaEgresos()
{


    ini=iniciofecha.format('YYYY-MM-DD')
    fin=finfecha.format('YYYY-MM-DD')
    alm=$("#almacen_filtro").val()
    tipoingreso=$("#tipo_filtro").val()
    console.log(tipoingreso)
    $.ajax({
        type:"POST",
        url: base_url('index.php/egresos/mostrarEgresos'),
        dataType: "json",
        data: {i:ini,f:fin,a:alm,ti:tipoingreso},
    }).done(function(res){
       //datosselect= restornardatosSelect(res)
        $("#tfacturas").bootstrapTable('destroy');
        $('#tfacturas').bootstrapTable({
                   
            data:res,
            striped:true,
            pagination:true,
            pageSize:"10",
            //height:"550", error con filtros
            //clickToSelect:true,
            search:true,
            //strictSearch:true,
            searchOnEnterKey:true,
            filter:true,
            showColumns:true,

            columns: [
            {
                field: '',
                width: '3%',
                title: 'Lote',
                align: 'center',
                visible:false,
                sortable:true,
            },
            {
                field: 'n',
                width: '3%',
                title: 'N',
                align: 'center',
                sortable:true,
            },
            {
                field:'fechamov',
                width: '7%',
                title:"Fecha",
                sortable:true,
                align: 'center',
                //formatter: formato_fecha_corta,
            },
            {
                field:'nombreCliente',
                title:"Cliente",
                width: '17%',
                sortable:true,
            },
            {
                field:'total',
                title:"Total",
                width: '7%',
                align: 'right',
                sortable:true,
                //formatter: operateFormatter3
            },
                        {
                field:'',
                title:"TipoMov",
                width: '7%',
                sortable:true,
                
            },
            {
                field:'',
                title:"N Mov",
                width: '7%',
                sortable:true,
                
            },
            {
                field:'',
                title:"Moneda",
                width: '7%',
                visible:false,
                sortable:true,
                
            },    
            {   //Estado poner Pagado - Pendiente - Anulado
                field:"estado",
                title:"Estado",
                width: '7%',
                sortable:true,
                align: 'center',
                //formatter: operateFormatter2
            },                  
            {
                field:"autor",
                width: '8%',
                title:"Autor",
                sortable:true,
                visible:false,
                align: 'center'
            },
            {
                field:"fecha",
                width: '8%',
                title:"Fecha",
                sortable:true,
                visible:false,
                align: 'center',
                //formatter: formato_fecha_corta,
            },
            {
                title: 'Acciones',
                align: 'center',
                width: '10%',
                //events: operateEvents,
                //formatter: operateFormatter
            }]
            
        });
        
        $("#tegresos").bootstrapTable('hideLoading');
        $("#tegresos").bootstrapTable('resetView');


        /*if(Object.keys(res).length<=0) $("tbody td","table#tegresos").html("No se encontraron registros")        
        else $("tbody","table#tegresos").show()            */

    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
    });
    //$("body").css("padding-right","0px");

}
function mostrarDetalle(res)
{
    $("#tFacturaDetalle").bootstrapTable('destroy');
        $("#tFacturaDetalle").bootstrapTable({

            data:res,
            striped:true,
            pagination:true,
            clickToSelect:true,
            search:false,
            columns:[
            {
                field: 'CodigoArticulo',
                title: 'Código',
                align: 'center',
                width: '10%',
                sortable:true,
            },
            {
                field: 'Descripcion',
                title: 'Descripcion',
                width: '50%',
                sortable:true,
            },
            {
                field:'cantidad',
                title:"Cantidad",
                align: 'right',
                width: '10%',
                sortable:true,
            },
            {
                field:'punitario',
                title:"P/U Bs",
                align: 'right',
                width: '10%',
                sortable:true,
            },
            {
                field:'total',
                title:"Total",
                align: 'right',
                width: '10%',
                sortable:true,
            },
            ]
        });
}

