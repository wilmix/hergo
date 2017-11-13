
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
      retornarNEporFac();
    });
    retornarNEporFac();
})
$(document).on("change","#almacen_filtro",function(){
    retornarNEporFac();
}) //para cambio filtro segun cada uno



function retornarNEporFac() //*******************************
{   
    ini=iniciofecha.format('YYYY-MM-DD')
    fin=finfecha.format('YYYY-MM-DD')
    alm=$("#almacen_filtro").val();
    agregarcargando();
    $.ajax({
        type:"POST",
        url: base_url('index.php/Reportes/mostrarNEporFac'), //******controlador
        dataType: "json",
        data: {i:ini,f:fin,a:alm}, //**** variables para filtro
    }).done(function(res){
    	quitarcargando();
        //console.log(res);
        //console.log(alm);
        datosselect= restornardatosSelect(res);
        $("#tablaNotasEntregaFacturar").bootstrapTable('destroy');
        $("#tablaNotasEntregaFacturar").bootstrapTable({            ////********cambiar nombre tabla viata

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
                        field: 'fechamov',            
                        title: 'Fecha',
                        sortable:true,
                        formatter: formato_fecha_corta
                    },
                    {   
                        field: 'almacen',            
                        title: 'Almacen',
                        visible:true,
                        sortable:true,
                        filter: 
                        {
                            type: "select",
                            data: datosselect[0]
                        }
                    },
                    {   
                        field: 'nombreCliente',            
                        title: 'Cliente',
                        visible:true,
                        sortable:true,
                        filter: 
                        {
                            type: "select",
                            data: datosselect[1]
                        }
                    },
                    {   
                        field: 'n',            
                        title: 'Número',
                        visible:true,
                        sortable:true,
                        align: 'center',
                        filter: { type: "input" },
                    },
                    {   
                        field: 'total',            
                        title: 'Importe',
                        visible:true,
                        sortable:true,
                        align: 'right',
                        formatter: operateFormatter3
                    },
                    {   
                        field: 'autor',            
                        title: 'Responsable',
                        visible:true,
                        sortable:true,
                        align: 'center',
                        filter: 
                        {
                            type: "select",
                            data: datosselect[2]
                        }
                    },
                    {
                    	field: 'monedasigla',
                        title: 'Moneda',
                        align: 'Moneda',
                        visible:false
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
function restornardatosSelect(res)
{

    var alm = new Array()
    var cliente = new Array()
    var responsable = new Array()
    var datos =new Array()
    $.each(res, function(index, value){

        alm.push(value.almacen)
        cliente.push(value.nombreCliente)
        responsable.push(value.autor)
    })

    alm.sort();
    cliente.sort();
    responsable.sort();
    //console.log(nPago);
    datos.push(alm.unique());
    datos.push(cliente.unique());
    datos.push(responsable.unique());
    return(datos);
}
Array.prototype.unique=function(a){
  return function(){return this.filter(a)}}(function(a,b,c){return c.indexOf(a,b+1)<0
});